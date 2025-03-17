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
use GuzzleHttp\Client;

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
        // Get authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find employee by email
        $employee = Employee::where('email_address', $user->email)->first();
        
        if (!$employee) {
            return response()->json([
                'name' => $user->first_name . ' ' . $user->last_name,
                'id' => $user->id,
                'position' => null,
                'department' => null
            ]);
        }
        
        // Get position and department information
        $position = $employee->position ? $employee->position->name : null;
        $department = $employee->department ? $employee->department->name : null;

        return response()->json([
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'id' => $employee->id,
            'position' => $position,
            'department' => $department
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
            Artisan::call('attendance:store');
            return response()->json(['message' => 'Attendance records stored successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function capturePreview()
    {
        try {
            $user = Auth::user();
            
            // Check if user has Employee or Supervisor role
            if (!$user->hasRole(['Employee', 'Supervisor'])) {
                return redirect()->route('home')->with('error', 'Unauthorized access.');
            }
            
            // Find employee by email
            $employee = Employee::where('email_address', $user->email)->first();
            
            if (!$employee) {
                Log::warning('Employee not found for user email: ' . $user->email);
            }
            
            return view('attendances.capture-preview', compact('employee'));
        } catch (\Exception $e) {
            Log::error('Error in capture preview: ' . $e->getMessage());
            return redirect()->route('attendances.attendance')->with('error', 'An error occurred while loading the preview page.');
        }
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
                Artisan::call('storage:link');
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
            Log::error('Error storing timestamp image: ' . $e->getMessage());
            return false;
        }
    }

    public function storeAttendanceCapture(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'type' => 'required|in:in,out',
                'image' => 'required|string', // Base64 encoded image
                'location' => 'required|string',
                'timestamp' => 'required|string',
            ]);

            // Get the authenticated user
            $user = Auth::user();
            
            // Find the employee by email
            $employee = Employee::where('email_address', $user->email)->first();
            
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee record not found for the current user.'
                ], 404);
            }

            // Parse the timestamp
            $timestamp = Carbon::parse($request->timestamp);
            $currentDate = $timestamp->format('Y-m-d');
            $currentTime = $timestamp->format('H:i:s');

            // Find existing attendance for today
            $attendance = Attendance::where('employee_id', $employee->id)
                                 ->where('date_attended', $currentDate)
                                 ->first();

            // Store the image and get the path
            $imagePath = $this->storeTimestampImage($request->image, $request->timestamp);
            
            if (!$imagePath) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to store timestamp image.'
                ], 500);
            }

            // Handle Clock In
            if ($request->type === 'in') {
                // Check if already clocked in
                if ($attendance && $attendance->time_in) {
                    // Delete the newly stored image since we won't use it
                    Storage::disk('public')->delete($imagePath);
                    
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Already clocked in for today.'
                    ], 400);
                }
                
                if (!$attendance) {
                    // Create new attendance record
                    $attendance = Attendance::create([
                        'employee_id' => $employee->id,
                        'date_attended' => $currentDate,
                        'time_in' => $currentTime,
                        'time_stamp1' => $imagePath,
                        'time_in_address' => $request->location,
                    ]);
                } else {
                    // Update existing attendance with clock in
                    $attendance->time_in = $currentTime;
                    $attendance->time_stamp1 = $imagePath;
                    $attendance->time_in_address = $request->location;
                    $attendance->save();
                }

                // Send Telegram notification for clock in
                $this->sendTelegramNotification($employee, 'in', $currentTime, $request->location);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Clock in recorded successfully.'
                ]);
            } 
            // Handle Clock Out
            else {
                // Check if attendance record exists and has time_in
                if (!$attendance || !$attendance->time_in) {
                    // Delete the newly stored image since we won't use it
                    Storage::disk('public')->delete($imagePath);
                    
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Must clock in first before clocking out.'
                    ], 400);
                }

                // Check if already clocked out
                if ($attendance->time_out) {
                    // Delete the newly stored image since we won't use it
                    Storage::disk('public')->delete($imagePath);
                    
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Already clocked out for today.'
                    ], 400);
                }

                // Update attendance with clock out
                $attendance->time_out = $currentTime;
                $attendance->time_stamp2 = $imagePath;
                $attendance->time_out_address = $request->location;
                $attendance->save();

                // Send Telegram notification for clock out
                $this->sendTelegramNotification($employee, 'out', $currentTime, $request->location);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Clock out recorded successfully.'
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Attendance capture error: ' . $e->getMessage());
            
            // Delete any stored image if there was an error
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Send attendance notification to Telegram group
     */
    private function sendTelegramNotification($employee, $type, $time, $location)
    {
        try {
            $botToken = config('services.telegram.bot_token');
            $chatId = config('services.telegram.chat_id');

            if (!$botToken || !$chatId) {
                Log::warning('Telegram credentials not configured');
                return;
            }

            // Get department name
            $departmentName = $employee->department ? $employee->department->name : 'N/A';

            // Determine company name based on department
            $companyName = match(strtoupper($departmentName)) {
                'MHRHCI' => 'Medical & Resources Health Care, Inc.',
                'BGPDI' => 'Bay Gas and Petroleum Distribution, Inc.',
                'VHI' => 'Verbena Hotel Inc.',
                default => 'MHR Property Conglomerates, Inc.'
            };

            // Format time for display
            $formattedTime = Carbon::parse($time)->format('h:i A');
            $formattedDate = Carbon::parse($time)->format('F d, Y');

            // Create message
            $message = "🏢 *{$companyName}*\n\n";
            $message .= "📍 *Attendance Update*\n";
            $message .= "Type: " . ($type === 'in' ? '🟢 Clock In' : '🔴 Clock Out') . "\n";
            $message .= "Date: {$formattedDate}\n";
            $message .= "Time: {$formattedTime}\n";
            $message .= "Employee: {$employee->first_name} {$employee->last_name}\n";
            $message .= "Department: {$departmentName}\n";
            $message .= "Position: " . ($employee->position ? $employee->position->name : 'N/A') . "\n";
            $message .= "Location: {$location}";

            $client = new Client();

            // Get the latest attendance record for the employee
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date_attended', Carbon::parse($time)->format('Y-m-d'))
                ->first();

            // Determine which timestamp to use
            $timestampField = $type === 'in' ? 'time_stamp1' : 'time_stamp2';
            
            if ($attendance && $attendance->$timestampField) {
                try {
                    // First send the message
                    $messageUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
                    $messageData = [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'Markdown'
                    ];

                    $messageResponse = $client->post($messageUrl, [
                        'json' => $messageData
                    ]);

                    if ($messageResponse->getStatusCode() !== 200) {
                        throw new \Exception('Failed to send Telegram message');
                    }

                    // Then send the image
                    $imageUrl = "https://api.telegram.org/bot{$botToken}/sendPhoto";
                    
                    // Get the full URL for the image
                    $imagePath = Storage::disk('public')->get($attendance->$timestampField);
                    $fullImageUrl = asset('storage/' . $attendance->$timestampField);

                    // Prepare image data
                    $imageCaption = "Timestamp image for {$employee->first_name} {$employee->last_name}'s " . 
                                   ($type === 'in' ? 'Clock In' : 'Clock Out') . 
                                   " at {$formattedTime}";

                    // Send image using URL
                    $imageResponse = $client->post($imageUrl, [
                        'form_params' => [
                            'chat_id' => $chatId,
                            'photo' => $fullImageUrl,
                            'caption' => $imageCaption,
                            'parse_mode' => 'Markdown'
                        ]
                    ]);

                    if ($imageResponse->getStatusCode() !== 200) {
                        throw new \Exception('Failed to send Telegram image');
                    }

                } catch (\Exception $e) {
                    // If sending image fails, try sending as file
                    try {
                        $documentUrl = "https://api.telegram.org/bot{$botToken}/sendDocument";
                        $filePath = storage_path('app/public/' . $attendance->$timestampField);
                        
                        if (file_exists($filePath)) {
                            $imageResponse = $client->post($documentUrl, [
                                'multipart' => [
                                    [
                                        'name' => 'chat_id',
                                        'contents' => $chatId
                                    ],
                                    [
                                        'name' => 'document',
                                        'contents' => fopen($filePath, 'r'),
                                        'filename' => basename($filePath)
                                    ],
                                    [
                                        'name' => 'caption',
                                        'contents' => "Timestamp image for {$employee->first_name} {$employee->last_name}'s " . 
                                                    ($type === 'in' ? 'Clock In' : 'Clock Out') . 
                                                    " at {$formattedTime}"
                                    ]
                                ]
                            ]);

                            if ($imageResponse->getStatusCode() !== 200) {
                                throw new \Exception('Failed to send Telegram document');
                            }
                        } else {
                            Log::warning("Timestamp image file not found: {$filePath}");
                        }
                    } catch (\Exception $docError) {
                        Log::error('Failed to send image as document: ' . $docError->getMessage());
                    }
                }
            } else {
                // If no image is available, just send the message
                $messageUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
                $messageData = [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown'
                ];

                $response = $client->post($messageUrl, [
                    'json' => $messageData
                ]);

                if ($response->getStatusCode() !== 200) {
                    throw new \Exception('Failed to send Telegram message');
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification: ' . $e->getMessage());
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
            Log::error('Error checking attendance status: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking attendance status'
            ], 500);
        }
    }
}