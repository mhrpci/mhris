<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Events\AttendanceStored;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Imports\AttendanceImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;

class AttendanceController extends Controller
{
    use HasRoles;

    // Middleware for permissions
    function __construct()
    {
        $this->middleware(['permission:attendance-list|attendance-create|attendance-edit|attendance-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:attendance-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:attendance-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:attendance-delete'], ['only' => ['destroy']]);
    }

    // Display all attendance records
    public function index()
    {
        if (auth()->user()->hasRole('Supervisor')) {
            $attendances = Attendance::whereHas('employee', function($query) {
                $query->where('department_id', auth()->user()->department_id);
            })->get();
        } else {
            $attendances = Attendance::all();
        }
        
        return view('attendances.index', compact('attendances'));
    }

    // Show form for creating a new attendance record
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('attendances.create', compact('employees'));
    }

    // Store a new attendance record
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
            'time_stamp1' => 'nullable',
            'time_stamp2' => 'nullable',
            'time_in' => 'required_without:time_out|nullable|date_format:H:i',
            'time_out' => 'required_without:time_in|nullable|date_format:H:i',
            'remarks' => 'nullable',
            'hours_worked' => 'nullable',
        ]);

        // Check if attendance already exists for the given date_attended and employee_id
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
                                        ->where('date_attended', $request->date_attended)
                                        ->first();

        // Get the authenticated user
        $user = Auth::user();

        $successMessage = '';

        if ($existingAttendance) {
            if ($existingAttendance->time_out && $existingAttendance->time_stamp2) {
                if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
                    $successMessage = 'Attendance for this employee on this date already has time out and time stamp.';
                } else {
                    $successMessage = 'Your attendance on this date already has time out and time stamp.';
                }
            } else {
                // Update existing attendance with time_out if time_in exists and time_out doesn't
                $existingAttendance->time_out = $request->time_out;
                if ($request->hasFile('time_stamp2')) {
                    $existingAttendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
                }
                $existingAttendance->remarks = $request->remarks;
                $existingAttendance->hours_worked = $request->hours_worked;
                $existingAttendance->save();

                $successMessage = 'You have successfully timed out.';
            }
        } else {
            // Create new attendance record with time_in
            $newAttendance = new Attendance;
            $newAttendance->employee_id = $request->employee_id;
            $newAttendance->date_attended = $request->date_attended;
            $newAttendance->time_in = $request->time_in;
            if ($request->hasFile('time_stamp1')) {
                $newAttendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
            }
            $newAttendance->remarks = $request->remarks;
            $newAttendance->hours_worked = $request->hours_worked;
            $newAttendance->save();

            $successMessage = 'You have successfully timed in.';
        }

        // Check user role and return appropriate view
        if ($user->hasRole('Employee')) {
            $employees = Employee::all(); // Fetch employees to pass to the view
            return view('attendances.create', compact('employees'))->with('successMessage', $successMessage);
        } else {
            return redirect()->route('attendances.index')->with('success', $successMessage);
        }
    }

    // Method to check if attendance exists (for AJAX call)
    public function checkAttendance(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $dateAttended = $request->query('date_attended');

        // Replace this with your logic to check attendance in the database
        $attendance = Attendance::where('employee_id', $employeeId)
                                ->where('date_attended', $dateAttended)
                                ->first();

        return response()->json([
            'hasTimeIn' => $attendance && $attendance->time_in != null,
            'hasTimeOut' => $attendance && $attendance->time_out != null,
        ]);
    }

    // Display a specific attendance record
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    // Show form for editing an attendance record
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        $remarks = Attendance::getRemarks();
        return view('attendances.edit', compact('attendance', 'employees', 'remarks'));
    }

    // Update a specific attendance record
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
            'time_stamp1' => 'nullable|file',
            'time_stamp2' => 'nullable|file',
            'time_in' => 'nullable',
            'time_out' => 'nullable',
            'remarks' => 'nullable',
            'hours_worked' => 'nullable|numeric',
        ]);

        // Update attendance with provided data
        $attendance->employee_id = $request->employee_id;
        $attendance->date_attended = $request->date_attended;
        $attendance->time_in = $request->time_in;
        $attendance->time_out = $request->time_out;
        $attendance->remarks = $request->remarks;
        $attendance->hours_worked = $request->hours_worked;

        if ($request->hasFile('time_stamp1')) {
            $attendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
        }

        if ($request->hasFile('time_stamp2')) {
            $attendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
        }

        $attendance->save();

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance updated successfully.');
    }

    // Delete a specific attendance record
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance deleted successfully.');
    }

    // Generate timesheets or attendance records for all employees
    public function generateTimesheets()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Get employees based on user role
        $employees = $user->hasRole('Super Admin') 
            ? Employee::all()
            : Employee::where('employee_status', 'Active')->get();
            
        $departments = Department::all();
        $timesheets = [];

        foreach ($employees as $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)->get();
            $timesheets[$employee->id] = $attendances;
        }

        return view('attendances.timesheets', compact('employees', 'timesheets', 'departments'));
    }

    // Show attendance for a specific employee
    public function showEmployeeAttendance($employee_id)
    {
        $employee = Employee::find($employee_id);
        $attendances = Attendance::where('employee_id', $employee_id)->get();

        return view('attendances.employee_attendance', compact('employee', 'attendances'));
    }

    // Show the logged-in employee's timesheet
    public function myTimesheet()
    {
        $user = Auth::user();

        // Find the employee record corresponding to the logged-in user
        $employee = Employee::where('first_name', $user->first_name)->first();

        if (!$employee) {
            return redirect()->route('attendances.index')
                             ->withErrors(['error' => 'No corresponding employee record found for the user.']);
        }

        // Fetch the attendance records for the logged-in employee
        $attendances = Attendance::where('employee_id', $employee->id)->get();

        return view('attendances.employee_attendance', compact('employee', 'attendances'));
    }

    // Check user role and execute appropriate function
    public function checkUserAndShowTimesheet()
    {
        $user = Auth::user();

        // Assuming roles are defined and you have a method to check user roles
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return redirect()->route('attendances.index');
        } else {
            return $this->myTimesheet();
        }
    }

    public function checkAttendanceStatus(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
        ]);

        $employeeId = $request->input('employee_id');
        $dateAttended = $request->input('date_attended');

        // Retrieve the attendance record for the employee and date
        $attendance = Attendance::where('employee_id', $employeeId)
                                ->where('date_attended', $dateAttended)
                                ->first();

        // Check if attendance exists and has a time_in value
        if ($attendance) {
            return response()->json([
                'status' => 'exists',
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out, // You may also include time_out if needed
            ], 200);
        }

        return response()->json(['status' => 'not_found'], 200);
    }

    public function getEmployeeInfo(Request $request)
    {
        // Replace this with actual logic to get authenticated employee info
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
        ]);
    }

    public function printAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
        ]);

        $employeeId = $request->input('employee_id');
        $dateAttended = $request->input('date_attended');

        $attendance = Attendance::where('employee_id', $employeeId)
                                ->where('date_attended', $dateAttended)
                                ->first();

        if (!$attendance) {
            return redirect()->back()->withErrors(['error' => 'Attendance record not found.']);
        }

        // Pass the attendance data to the print view
        return view('attendances.print', [
            'attendance' => $attendance
        ]);
    }

    /**
     * Show the form for importing attendance records.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('attendances.import');
    }

    /**
     * Handle the import of attendance records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new AttendanceImport, $request->file('file'));

            return redirect()->route('attendances.index')->with('success', 'Attendance records imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to import records: ' . $e->getMessage()]);
        }
    }

    /**
     * Export attendance records to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new AttendanceExport, 'attendances.xlsx');
    }

    public function attendance()
    {
        $user = Auth::user();
        
        if (!$user->hasRole(['Employee', 'Supervisor'])) {
            abort(403, 'Unauthorized access.');
        }

        $employee = Employee::where('email_address', $user->email)->first();

        return view('attendances.attendance', compact('employee'));
    }

    /**
     * Execute the attendance:store artisan command
     */
    public function executeStoreCommand()
    {
        try {
            \Artisan::call('attendance:store');
            return response()->json(['message' => 'Attendance records stored successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function capturePreview()
    {
        $user = Auth::user();
        $employee = Employee::where('email_address', $user->email)->first();
        
        if (!$employee) {
            \Log::warning('Employee not found for user email: ' . $user->email);
        }
        
        return view('attendances.capture-preview', compact('employee'));
    }

    /**
     * Helper method to store timestamp images
     * 
     * @param string $imageData Base64 encoded image
     * @param string $timestamp Timestamp for filename
     * @return string|false Returns the stored image path or false on failure
     */
    private function storeTimestampImage($imageData, $timestamp)
    {
        try {
            // Check if storage link exists
            if (!file_exists(public_path('storage'))) {
                \Artisan::call('storage:link');
            }

            // Create time_stamps directory if it doesn't exist
            $directory = 'time_stamps';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Clean the base64 string
            $cleanImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
            
            // Generate unique filename with timestamp
            $filename = uniqid() . '_' . Carbon::parse($timestamp)->format('Ymd_His') . '.jpg';
            $fullPath = $directory . '/' . $filename;

            // Store the image
            if (Storage::disk('public')->put($fullPath, $cleanImageData)) {
                return $fullPath;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Error storing timestamp image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Store attendance capture with image and location data
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAttendanceCapture(Request $request)
    {
        // Initialize variables
        $imagePath = null;
        $attendance = null;

        try {
            // 1. Request Validation with detailed messages
            $request->validate([
                'type' => 'required|in:in,out',
                'image' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/^data:image\/[a-zA-Z]+;base64,/', $value)) {
                            $fail('The image must be a valid base64 encoded image.');
                        }
                    },
                ],
                'location' => 'required|string|max:500',
                'timestamp' => [
                    'required',
                    'string',
                    'date',
                    function ($attribute, $value, $fail) {
                        $timestamp = Carbon::parse($value);
                        $now = Carbon::now();
                        
                        if ($timestamp->diffInMinutes($now) > 5) {
                            $fail('The timestamp is too far from the current time.');
                        }
                    },
                ],
            ], [
                'type.required' => 'Please specify whether you are clocking in or out.',
                'type.in' => 'Invalid attendance type specified.',
                'image.required' => 'The captured image is required.',
                'image.string' => 'Invalid image format provided.',
                'location.required' => 'Location information is required.',
                'location.max' => 'Location information is too long.',
                'timestamp.required' => 'Timestamp is required.',
                'timestamp.date' => 'Invalid timestamp format.',
            ]);

            // 2. User Authentication Check
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated.'
                ], 401);
            }

            // 3. Employee Record Check
            $employee = Employee::where('email_address', $user->email)
                              ->where('employee_status', 'Active')
                              ->first();
            
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No active employee record found for the current user.'
                ], 404);
            }

            // 4. Parse and Validate Timestamp
            try {
                $timestamp = Carbon::parse($request->timestamp);
                $currentDate = $timestamp->format('Y-m-d');
                $currentTime = $timestamp->format('H:i:s');
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid timestamp format provided.'
                ], 400);
            }

            // 5. Check for Existing Attendance
            $attendance = Attendance::where('employee_id', $employee->id)
                                  ->where('date_attended', $currentDate)
                                  ->first();

            // 6. Store Image with Error Handling
            $imagePath = $this->storeTimestampImage($request->image, $request->timestamp);
            if (!$imagePath) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to store the captured image. Please try again.'
                ], 500);
            }

            // 7. Process Clock In/Out Based on Type
            if ($request->type === 'in') {
                return $this->handleClockIn($employee, $attendance, $currentDate, $currentTime, $imagePath, $request->location);
            } else {
                return $this->handleClockOut($employee, $attendance, $currentTime, $imagePath, $request->location);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Clean up any stored image if validation fails
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Attendance capture error: ' . $e->getMessage());
            
            // Clean up any stored image if there's an error
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred while processing your request.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle clock in process
     * 
     * @param Employee $employee
     * @param Attendance|null $attendance
     * @param string $currentDate
     * @param string $currentTime
     * @param string $imagePath
     * @param string $location
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleClockIn($employee, $attendance, $currentDate, $currentTime, $imagePath, $location)
    {
        // Check if already clocked in
        if ($attendance && $attendance->time_in) {
            Storage::disk('public')->delete($imagePath);
            return response()->json([
                'status' => 'error',
                'message' => 'You have already clocked in for today.'
            ], 400);
        }

        try {
            if (!$attendance) {
                // Create new attendance record
                $attendance = Attendance::create([
                    'employee_id' => $employee->id,
                    'date_attended' => $currentDate,
                    'time_in' => $currentTime,
                    'time_stamp1' => $imagePath,
                    'time_in_address' => $location,
                ]);
            } else {
                // Update existing attendance with clock in
                $attendance->time_in = $currentTime;
                $attendance->time_stamp1 = $imagePath;
                $attendance->time_in_address = $location;
                $attendance->save();
            }

            // Calculate hours if both time_in and time_out exist
            if ($attendance->time_in && $attendance->time_out) {
                $this->calculateAndUpdateHours($attendance);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Clock in recorded successfully.',
                'data' => [
                    'attendance_id' => $attendance->id,
                    'time_in' => $attendance->time_in,
                    'date' => $attendance->date_attended
                ]
            ]);

        } catch (\Exception $e) {
            Storage::disk('public')->delete($imagePath);
            throw $e;
        }
    }

    /**
     * Handle clock out process
     * 
     * @param Employee $employee
     * @param Attendance|null $attendance
     * @param string $currentTime
     * @param string $imagePath
     * @param string $location
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleClockOut($employee, $attendance, $currentTime, $imagePath, $location)
    {
        // Verify attendance record exists and has time_in
        if (!$attendance || !$attendance->time_in) {
            Storage::disk('public')->delete($imagePath);
            return response()->json([
                'status' => 'error',
                'message' => 'You must clock in first before clocking out.'
            ], 400);
        }

        // Check if already clocked out
        if ($attendance->time_out) {
            Storage::disk('public')->delete($imagePath);
            return response()->json([
                'status' => 'error',
                'message' => 'You have already clocked out for today.'
            ], 400);
        }

        try {
            // Update attendance with clock out
            $attendance->time_out = $currentTime;
            $attendance->time_stamp2 = $imagePath;
            $attendance->time_out_address = $location;
            
            // Calculate hours worked
            $this->calculateAndUpdateHours($attendance);
            
            $attendance->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Clock out recorded successfully.',
                'data' => [
                    'attendance_id' => $attendance->id,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'hours_worked' => $attendance->hours_worked
                ]
            ]);

        } catch (\Exception $e) {
            Storage::disk('public')->delete($imagePath);
            throw $e;
        }
    }

    /**
     * Calculate and update hours worked for an attendance record
     * 
     * @param Attendance $attendance
     * @return void
     */
    private function calculateAndUpdateHours($attendance)
    {
        if ($attendance->time_in && $attendance->time_out) {
            $timeIn = Carbon::parse($attendance->time_in);
            $timeOut = Carbon::parse($attendance->time_out);
            
            // Calculate hours worked
            $hoursWorked = $timeOut->diffInMinutes($timeIn) / 60;
            
            // Round to 2 decimal places
            $attendance->hours_worked = round($hoursWorked, 2);
        }
    }

    public function getAttendanceStatus()
    {
        try {
            $user = Auth::user();
            
            // Check if user has Employee or Supervisor role
            if (!$user->hasRole(['Employee', 'Supervisor'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Find employee by email
            $employee = Employee::where('email_address', $user->email)->first();
            
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee record not found'
                ], 404);
            }

            // Get current date in Y-m-d format
            $currentDate = Carbon::now()->format('Y-m-d');

            // First, check if there's any attendance record for today
            $attendance = Attendance::where('employee_id', $employee->id)
                                 ->where('date_attended', $currentDate)
                                 ->first();

            // If no attendance record exists for today
            if (!$attendance) {
                return response()->json([
                    'status' => 'success',
                    'action' => 'clock_in',
                    'message' => 'Ready to clock in'
                ]);
            }

            // Check if attendance record exists and has time_in
            if ($attendance && $attendance->time_in) {
                // If both time_in and time_out exist
                if ($attendance->time_out) {
                    return response()->json([
                        'status' => 'success',
                        'action' => 'completed',
                        'message' => "You've already submitted your attendance today"
                    ]);
                }

                // If has time_in but no time_out
                return response()->json([
                    'status' => 'success',
                    'action' => 'clock_out',
                    'message' => 'Ready to clock out'
                ]);
            }

            // Fallback case (should not normally happen)
            return response()->json([
                'status' => 'success',
                'action' => 'clock_in',
                'message' => 'Ready to clock in'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error checking attendance status: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking attendance status'
            ], 500);
        }
    }
}