<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\OvertimePay;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

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
    $validatedData = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'date' => 'required|date',
        'overtime_hours' => 'required|numeric',
        'overtime_rate' => 'nullable|numeric',
        'overtime_pay' => 'nullable|numeric',
    ]);

    // Check if the employee already has an overtime record on the same date
    $existingOvertime = OvertimePay::where('employee_id', $request->employee_id)
                                   ->where('date', $request->date)
                                   ->first();

    if ($existingOvertime) {
        return redirect()->back()->withErrors(['error' => 'Overtime for this employee on the selected date already exists.']);
    }

    // Fetch the attendance record for the employee on the given date
    $attendance = Attendance::where('employee_id', $request->employee_id)
                            ->where('date_attended', $request->date)
                            ->first();

    // Check if the attendance remark is 'Overtime'
    if (!$attendance || $attendance->remarks !== 'Overtime') {
        return redirect()->back()->withErrors(['error' => 'The employee is not marked for overtime on the selected date.']);
    }

    // Convert the overtime difference to decimal and compare
    $overtimeDifferenceDecimal = $attendance->getOvertimeDifferenceInDecimal();

    if (abs($overtimeDifferenceDecimal - $request->overtime_hours) > 0.01) {
        return redirect()->back()->withErrors(['error' => 'The overtime hours do not match the recorded overtime difference.']);
    }

    // If all checks pass, create the overtime record
    OvertimePay::create($validatedData);

    return redirect()->route('overtime.index')->with('success', 'Overtime created successfully.');
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
     * Approve the overtime pay
     */
    public function approve(OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This overtime record has already been processed.');
        }

        $overtime->approve(Auth::id());
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved successfully.');
    }

    /**
     * Reject the overtime pay
     */
    public function reject(Request $request, OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This overtime record has already been processed.');
        }

        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->reject(Auth::id());
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected successfully.');
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
