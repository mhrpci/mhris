@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Attendance Image Preview</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <h4>Review Your Attendance Image</h4>
                        <p class="text-muted">Please verify the image before saving your attendance record</p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="preview-container text-center mb-4">
                                @if(isset($employee))
                                <div class="employee-info mb-3">
                                    <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                                    <p class="mb-0">{{ $employee->position->name }} - {{ $employee->department->name }}</p>
                                </div>
                                @endif
                                
                                <div class="image-container border p-2 mb-3">
                                    <img id="capturedImage" class="img-fluid" style="max-height: 400px;" alt="Captured image preview">
                                </div>
                                
                                <div class="timestamp-info mb-3">
                                    <h6>Timestamp: <span id="timestampDisplay"></span></h6>
                                    <h6>Location: <span id="locationDisplay">Loading location...</span></h6>
                                </div>
                            </div>

                            <form id="captureForm" action="{{ route('attendance.capture') }}" method="POST" class="text-center">
                                @csrf
                                <input type="hidden" name="image_data" id="imageData">
                                <input type="hidden" name="timestamp" id="timestamp">
                                <input type="hidden" name="location" id="location">
                                <input type="hidden" name="attendance_type" id="attendanceType">

                                <div class="btn-group btn-group-lg" role="group">
                                    <button type="button" id="retakeButton" class="btn btn-secondary mr-2">
                                        <i class="fas fa-camera"></i> Retake Photo
                                    </button>
                                    <button type="submit" id="saveButton" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save Attendance
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="text-center text-muted">
                        <small>This attendance record includes your photo timestamp and location details</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the image data from session storage
    const imageData = sessionStorage.getItem('capturedImageData');
    const timestamp = sessionStorage.getItem('timestamp');
    const latitude = sessionStorage.getItem('latitude');
    const longitude = sessionStorage.getItem('longitude');
    const attendanceType = sessionStorage.getItem('attendanceType');
    
    // Display the image
    if (imageData) {
        document.getElementById('capturedImage').src = imageData;
        document.getElementById('imageData').value = imageData;
    } else {
        // No image data found, redirect back to the capture page
        window.location.href = "{{ route('attendances.attendance') }}";
    }
    
    // Display and store the timestamp
    if (timestamp) {
        const formattedTime = new Date(timestamp).toLocaleString();
        document.getElementById('timestampDisplay').textContent = formattedTime;
        document.getElementById('timestamp').value = timestamp;
    }
    
    // Display and store location
    if (latitude && longitude) {
        const locationText = `${latitude}, ${longitude}`;
        document.getElementById('locationDisplay').textContent = locationText;
        document.getElementById('location').value = locationText;
    }
    
    // Store attendance type
    if (attendanceType) {
        document.getElementById('attendanceType').value = attendanceType;
    }
    
    // Handle retake button
    document.getElementById('retakeButton').addEventListener('click', function() {
        // Keep the attendance type but clear the image
        sessionStorage.removeItem('capturedImageData');
        window.location.href = "{{ route('attendances.attendance') }}";
    });
    
    // Form submission confirmation
    document.getElementById('captureForm').addEventListener('submit', function(e) {
        const saveBtn = document.getElementById('saveButton');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });
});
</script>
@endsection
