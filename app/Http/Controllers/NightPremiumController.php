<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\NightPremium;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class NightPremiumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || !($user->roles->pluck('name')->intersect(['Supervisor', 'Finance', 'VP Finance', 'Admin', 'Super Admin'])->count() > 0)) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all NightPremium records
        $nightPremiums = NightPremium::all();

        // Calculate night premium pay for each record
        foreach ($nightPremiums as $nightPremium) {
            $nightPremium->calculateNightPremiumPay();
        }

        // Pass the data to the view
        return view('night-premium.index', compact('nightPremiums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('night-premium.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:Y-m-d\TH:i',
            'time_out' => 'required|date_format:Y-m-d\TH:i|after:time_in',
            'night_rate' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Format the datetimes properly
            $date = date('Y-m-d', strtotime($request->date));
            $timeIn = date('Y-m-d H:i:s', strtotime($request->time_in));
            $timeOut = date('Y-m-d H:i:s', strtotime($request->time_out));
            
            // Verify time_in date matches the date field
            $timeInDate = date('Y-m-d', strtotime($timeIn));
            if ($timeInDate !== $date) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time_in' => 'Time in date must match the night premium date']);
            }
            
            // Calculate the time difference in hours (before saving)
            $timeDiff = strtotime($timeOut) - strtotime($timeIn);
            $hours = $timeDiff / 3600; // Convert seconds to hours
            $hours = round($hours, 2); // Round to 2 decimal places
            
            // Create the night premium record
            $nightPremium = new NightPremium();
            $nightPremium->employee_id = $request->employee_id;
            $nightPremium->date = $date;
            $nightPremium->time_in = $timeIn;
            $nightPremium->time_out = $timeOut;
            $nightPremium->night_rate = $request->night_rate;
            $nightPremium->approval_status = 'pending';
            $nightPremium->night_hours = $hours > 0 ? $hours : 0; // Set night_hours before saving
            $nightPremium->save();
            
            // Calculate night premium pay
            try {
                $nightPremium->calculateNightPremiumPay();
            } catch (\Exception $e) {
                // If calculation fails, log the error but continue
                Log::error('Night premium calculation error: ' . $e->getMessage());
            }
            
            return redirect()->route('night-premium.index')
                ->with('success', 'Night premium created successfully.');
        } catch (\Exception $e) {
            Log::error('Night premium creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NightPremium $nightPremium)
    {
        $user = Auth::user();
        
        // Check user's role and mark as read accordingly
        if ($user) {
            try {
                // Handle read status based on user role
                $this->markAsReadBasedOnRole($user, $nightPremium);
            } catch (\Exception $e) {
                Log::error('Error marking night premium as read: ' . $e->getMessage());
            }
        }
        
        return view('night-premium.show', compact('nightPremium'));
    }
    
    /**
     * Mark overtime as read based on user role
     * 
     * @param User $user
     * @param OvertimePay $overtime
     * @return void
     */
    protected function markAsReadBasedOnRole(User $user, NightPremium $nightPremium): void
    {
        // VP Finance role check
        if ($user->roles->pluck('name')->contains('VP Finance')) {
            $nightPremium->is_read_by_vpfinance = true;
            $nightPremium->is_read_at_vpfinance = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by VP Finance user #' . $user->id);
        } 
        // Finance Head role check
        elseif ($user->roles->pluck('name')->contains('Finance')) {
            $nightPremium->is_read_by_finance = true;
            $nightPremium->is_read_at_finance = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by Finance Head user #' . $user->id);
        } 
        // Supervisor role check
        elseif ($user->roles->pluck('name')->contains('Supervisor')) {
            $nightPremium->is_read_by_supervisor = true;
            $nightPremium->is_read_at_supervisor = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by Supervisor user #' . $user->id);
        } 
        // Employee role check - only mark as read if it belongs to the employee
        elseif ($user->roles->pluck('name')->contains('Employee') && 
                $user->employee && 
                $nightPremium->employee_id == $user->employee->id) {
            $nightPremium->is_read_by_employee = true;
            $nightPremium->is_read_at_employee = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by Employee user #' . $user->id);
        }
    }

 

       /**
     * Approve the night premium pay by supervisor
     */
    public function approvedBySupervisor(NightPremium $nightPremium)
    {
        if($nightPremium->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This night premium record has already been processed.');
        }

        // Check if employee has rank "Rank File"
        if($nightPremium->employee->rank !== 'Rank File') {
            return redirect()->back()->with('error', 'Supervisor approval is only applicable for Rank File employees.');
        }

        $nightPremium->approval_status = 'approvedBySupervisor';
        $nightPremium->approveBySupervisor(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium approved by supervisor successfully.');
    }

    /**
     * Reject the night premium pay by supervisor
     */
    public function rejectedBySupervisor(Request $request, NightPremium $nightPremium)
    {
        if($nightPremium->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This night premium record has already been processed.');
        }

        // Check if employee has rank "Rank File"
        if($nightPremium->employee->rank !== 'Rank File') {
            return redirect()->back()->with('error', 'Supervisor rejection is only applicable for Rank File employees.');
        }

        $nightPremium->approval_status = 'rejectedBySupervisor';
        $nightPremium->rejection_reason = $request->rejection_reason;
        $nightPremium->approveBySupervisor(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by supervisor.');
    }

    /**
     * Approve the night premium pay by finance head
     */
    public function approvedByFinance(NightPremium $nightPremium)
    {
        // Check if employee has rank "Managerial"
        if($nightPremium->employee->rank === 'Managerial') {
            // For Managerial rank, this is the first approval step
            if($nightPremium->approval_status !== 'pending') {
                return redirect()->back()->with('error', 'This night premium record has already been processed.');
            }
        } else {
            // For other ranks, supervisor must approve first
            if($nightPremium->approval_status !== 'approvedBySupervisor') {
                return redirect()->back()->with('error', 'This night premium must be approved by supervisor first.');
            }
        }

        $nightPremium->approval_status = 'approvedByFinance';
        $nightPremium->approveByFinanceHead(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium approved by finance head successfully.');
    }

    /**
     * Reject the night premium pay by finance head
     */
    public function rejectedByFinance(Request $request, NightPremium $nightPremium)
    {
        // Check if employee has rank "Managerial"
        if($nightPremium->employee->rank === 'Managerial') {
            // For Managerial rank, this is the first approval step
            if($nightPremium->approval_status !== 'pending') {
                return redirect()->back()->with('error', 'This night premium record has already been processed.');
            }
        } else {
            // For other ranks, supervisor must approve first
            if($nightPremium->approval_status !== 'approvedBySupervisor') {
                return redirect()->back()->with('error', 'This night premium must be approved by supervisor first.');
            }
        }

        $nightPremium->approval_status = 'rejectedByFinance';
        $nightPremium->rejection_reason = $request->rejection_reason;
        $nightPremium->approveByFinanceHead(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by finance head.');
    }

    /**
     * Approve the night premium pay by VP finance
     */
    public function approvedByVPFinance(NightPremium $nightPremium)
    {
        if($nightPremium->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This night premium must be approved by finance head first.');
        }

        $nightPremium->approval_status = 'approvedByVPFinance';
        $nightPremium->approveByVpFinance(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium approved by VP finance successfully.');
    }

    /**
     * Reject the night premium pay by VP finance
     */
    public function rejectedByVPFinance(Request $request, NightPremium $nightPremium)
    {
        if($nightPremium->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This night premium must be approved by finance head first.');
        }

        $nightPremium->approval_status = 'rejectedByVPFinance';
        $nightPremium->rejection_reason = $request->rejection_reason;
        $nightPremium->approveByVpFinance(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by VP finance.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NightPremium $nightPremium)
    {
        $nightPremium->delete();
        return redirect()->route('night-premium.index');
    }
}
