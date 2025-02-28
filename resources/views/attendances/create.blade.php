@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
<!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('attendances.index') }}" class="contribution-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Attendance</span>
                <small class="description">Attendance List</small>
            </div>
        </a>
        @can('attendance-create')
        <a href="{{ route('attendances.create') }}" class="contribution-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Time In/Time Out</span>
                <small class="description">Attendance Create</small>
            </div>
        </a>
        @endcan
        @canany(['hrcomben', 'admin', 'super-admin'])
        <a href="{{ url('/timesheets') }}" class="contribution-link {{ request()->routeIs('attendances.timesheets') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Timesheets</span>
                <small class="description">Employee attendance records</small>
            </div>
        </a>
        @endcanany
    </div>
</div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        {{ Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HR Compliance') || Auth::user()->hasRole('HR ComBen') ? 'Create New Attendance' : 'Attendance' }}
                    </h3>
                </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('successMessage'))
                            <div class="alert alert-success">
                                {{ session('successMessage') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('attendances.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen'))
                                    <!-- Admin Interface -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_id">Employee</label>
                                            <select id="employee_id" name="employee_id" class="form-control" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_attended">Date:</label>
                                            <input type="date" id="date_attended" name="date_attended" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="time_in_group" class="form-group" style="display: none;">
                                            <label for="time_in">Time In:</label>
                                            <input type="time" id="time_in" name="time_in" class="form-control">
                                        </div>
                                        <div id="time_out_group" class="form-group" style="display: none;">
                                            <label for="time_out">Time Out:</label>
                                            <input type="time" id="time_out" name="time_out" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="time_stamp1_group" class="form-group" style="display: none;">
                                            <label for="time_stamp1">Time Stamp (In):</label>
                                            <input type="file" id="time_stamp1" name="time_stamp1" class="form-control" accept="image/*">
                                        </div>
                                        <div id="time_stamp2_group" class="form-group" style="display: none;">
                                            <label for="time_stamp2">Time Stamp (Out):</label>
                                            <input type="file" id="time_stamp2" name="time_stamp2" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HR ComBen'))
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group" aria-label="Button group">
                                            <button type="submit" class="btn btn-primary">Submit Attendance</button>&nbsp;&nbsp;
                                            <a href="{{ route('attendances.index') }}" class="btn btn-info">Back</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen'))
            const employeeSelect = document.getElementById('employee_id');
            const dateInput = document.getElementById('date_attended');
            const timeInGroup = document.getElementById('time_in_group');
            const timeOutGroup = document.getElementById('time_out_group');
            const timeStamp1Group = document.getElementById('time_stamp1_group');
            const timeStamp2Group = document.getElementById('time_stamp2_group');

            async function checkAttendance() {
                const employeeId = employeeSelect.value;
                const dateInputValue = dateInput.value;
                const dateAttended = new Date(dateInputValue).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                if (employeeId && dateInputValue) {
                    const response = await fetch(`/check-attendance?employee_id=${employeeId}&date_attended=${dateInputValue}`);
                    const data = await response.json();

                    // New logic to check if attendance is already submitted
                    if (data.hasTimeIn && data.hasTimeOut) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Attendance Already Submitted',
                            text: `This employee has already created attendance for ${dateAttended}.`,
                            confirmButtonText: 'OK'
                        });
                        return; // Exit the function if attendance is already submitted
                    }

                    // Existing logic for showing/hiding time in/out based on attendance status
                    if (data.hasTimeIn) {
                        timeInGroup.style.display = 'none';
                        timeStamp1Group.style.display = 'none';
                        if (data.hasTimeOut) {
                            timeOutGroup.style.display = 'none'; // Time Out already submitted
                        } else {
                            timeOutGroup.style.display = 'block'; // Time Out button available
                        }
                        timeStamp2Group.style.display = 'block';
                    } else {
                        timeInGroup.style.display = 'block';
                        timeStamp1Group.style.display = 'block';
                        timeOutGroup.style.display = 'none';
                        timeStamp2Group.style.display = 'none';
                    }
                } else {
                    timeInGroup.style.display = 'none';
                    timeOutGroup.style.display = 'none';
                    timeStamp1Group.style.display = 'none';
                    timeStamp2Group.style.display = 'none';
                }
            }

            employeeSelect.addEventListener('change', checkAttendance);
            dateInput.addEventListener('change', checkAttendance);
        @else
            // Mobile App Version Logic
            function updateClock() {
                fetch('/server-time')
                    .then(response => response.json())
                    .then(data => {
                        const serverTime = new Date(data.server_time);
                        const timeString = serverTime.toLocaleTimeString();
                        document.getElementById('clock').textContent = timeString;
                    });
            }

            // Call the function every second to update the clock
            setInterval(updateClock, 1000);
            updateClock();

            async function checkAttendance() {
                const employeeId = document.getElementById('employee_id').value;
                const dateAttended = document.getElementById('date_attended').value;

                const response = await fetch(`/check-attendance?employee_id=${employeeId}&date_attended=${dateAttended}`);
                const data = await response.json();

                // Logic for showing/hiding time in/out based on attendance status
                if (data.hasTimeIn && data.hasTimeOut) {
                    document.getElementById('attendance_actions').style.display = 'none';
                    document.getElementById('attendance_submitted').style.display = 'block';
                } else if (data.hasTimeIn) {
                    document.getElementById('time_in_group').style.display = 'none';
                    document.getElementById('time_out_group').style.display = 'block'; // Show Time Out
                } else {
                    document.getElementById('time_in_group').style.display = 'block';
                    document.getElementById('time_out_group').style.display = 'none';
                }
            }

            checkAttendance();

            document.getElementById('captureTimeIn').addEventListener('click', function() {
                document.getElementById('time_stamp1').click();
            });

            document.getElementById('captureTimeOut').addEventListener('click', function() {
                document.getElementById('time_stamp2').click();
            });

            function handleImageCapture(event, timeInputId, actionType) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('capturedImage').src = e.target.result;
                        document.getElementById('capturedImage').style.display = 'block';
                    }
                    reader.readAsDataURL(file);

                    fetch('/server-time')
                        .then(response => response.json())
                        .then(data => {
                            const serverTime = new Date(data.server_time);
                            const hours = String(serverTime.getHours()).padStart(2, '0');
                            const minutes = String(serverTime.getMinutes()).padStart(2, '0');
                            document.getElementById(timeInputId).value = `${hours}:${minutes}`;
                            document.getElementById('submitAttendance').style.display = 'block';
                        });
                }
            }

            document.getElementById('time_stamp1').addEventListener('change', function(event) {
                handleImageCapture(event, 'time_in', 'Time In');
                document.getElementById('time_in_group').style.display = 'none';
                document.getElementById('time_out_group').style.display = 'none';
            });

            document.getElementById('time_stamp2').addEventListener('change', function(event) {
                handleImageCapture(event, 'time_out', 'Time Out');
                document.getElementById('time_out_group').style.display = 'none';
            });

            // Handle form submission
            document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to the same page
                        window.location.href = window.location.href;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: data.message || 'An error occurred while submitting attendance.',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: 'An unexpected error occurred. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            });

            // Check for flash message and display SweetAlert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            @endif
        @endif
    });
</script>
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
