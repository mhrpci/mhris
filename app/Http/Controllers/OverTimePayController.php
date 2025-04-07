<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\OvertimePay;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OverTimePayController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:overtime-list|overtime-create|overtime-edit|overtime-delete', ['only' => ['index']]);
        $this->middleware('permission:overtime-create', ['only' => ['create','store']]);
        $this->middleware('permission:overtime-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:overtime-delete', ['only' => ['destroy']]);
        $this->middleware('role_or_permission:Employee|overtime-list|overtime-create|overtime-edit|overtime-delete', ['only' => ['show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all OvertimePay records
        $overtime = OvertimePay::all();

        // Calculate overtime pay for each record
        foreach ($overtime as $overtimePay) {
            $overtimePay->calculateOvertimePay();
        }

        // Pass the data to the view
        return view('overtime.index', compact('overtime'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('overtime.create',compact('employees'));
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
        'overtime_rate' => 'required|numeric|min:1',
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
                ->withErrors(['time_in' => 'Time in date must match the overtime date']);
        }
        
        // Calculate the time difference in hours (before saving)
        $timeDiff = strtotime($timeOut) - strtotime($timeIn);
        $hours = $timeDiff / 3600; // Convert seconds to hours
        $hours = round($hours, 2); // Round to 2 decimal places
        
        // Create the overtime record
        $overtime = new OvertimePay();
        $overtime->employee_id = $request->employee_id;
        $overtime->date = $date;
        $overtime->time_in = $timeIn;
        $overtime->time_out = $timeOut;
        $overtime->overtime_rate = $request->overtime_rate;
        $overtime->approval_status = 'pending';
        $overtime->overtime_hours = $hours > 0 ? $hours : 0; // Set overtime_hours before saving
        $overtime->save();
        
        // Calculate overtime pay
        try {
            $overtime->calculateOvertimePay();
        } catch (\Exception $e) {
            // If calculation fails, log the error but continue
            Log::error('Overtime calculation error: ' . $e->getMessage());
        }
        
        return redirect()->route('overtime.index')
            ->with('success', 'Overtime created successfully.');
    } catch (\Exception $e) {
        Log::error('Overtime creation error: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(OvertimePay $overtime)
    {
        return view('overtime.show', compact('overtime'));
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(OvertimePay $overtime)
    // {
    //     $employees =  Employee::all();
    //     return view('overtime.edit', compact('overtime', 'employees'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, OvertimePay $overtime)
    // {
    //     $overtime->update($request->all());
    //     return redirect()->route('overtime.index');
    // }

     /**
     * Approve the night premium pay by supervisor
     */
    public function approvedBySupervisor(OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This night premium record has already been processed.');
        }

        $overtime->approval_status = 'approvedBySupervisor';
        $overtime->approveBySupervisor(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved by supervisor successfully.');
    }

    /**
     * Reject the night premium pay by supervisor
     */
    public function rejectedBySupervisor(Request $request, OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This overtime record has already been processed.');
        }

        $overtime->approval_status = 'rejectedBySupervisor';
        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->approveBySupervisor(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected by supervisor.');
    }

    /**
     * Approve the night premium pay by finance head
     */
    public function approvedByFinance(OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'approvedBySupervisor') {
            return redirect()->back()->with('error', 'This overtime must be approved by supervisor first.');
        }

        $overtime->approval_status = 'approvedByFinance';
        $overtime->approveByFinanceHead(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved by finance head successfully.');
    }

    /**
     * Reject the night premium pay by finance head
     */
    public function rejectedByFinance(Request $request, OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'approvedBySupervisor') {
            return redirect()->back()->with('error', 'This overtime must be approved by supervisor first.');
        }

        $overtime->approval_status = 'rejectedByFinance';
        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->approveByFinanceHead(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected by finance head.');
    }

    /**
     * Approve the night premium pay by VP finance
     */
    public function approvedByVPFinance(OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This overtime must be approved by finance head first.');
        }

        $overtime->approval_status = 'approvedByVPFinance';
        $overtime->approveByVpFinance(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved by VP finance successfully.');
    }

    /**
     * Reject the night premium pay by VP finance
     */
    public function rejectedByVPFinance(Request $request, OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This overtime must be approved by finance head first.');
        }

        $overtime->approval_status = 'rejectedByVPFinance';
        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->approveByVpFinance(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected by VP finance.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimePay $overtime)
    {
        $overtime->delete();
        return redirect()->route('overtime.index');
    }
}
