@extends('layouts.app')

@section('title', 'Capture Attendance')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-camera me-2"></i> Attendance Capture
                    </h5>
                    <div id="current-time" class="text-primary fw-bold"></div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            @if(isset($employee))
                            <div class="employee-info">
                                <h6>Employee Information:</h6>
                                <p><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p><strong>Position:</strong> {{ $employee->position ? $employee->position->name : 'N/A' }}</p>
                                <p><strong>Department:</strong> {{ $employee->department ? $employee->department->name : 'N/A' }}</p>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-circle me-2"></i> Employee record not found
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div id="attendance-status" class="alert alert-info">
                                <i class="fas fa-spinner fa-spin me-2"></i> Checking attendance status...
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="camera-container mb-3">
                                <div class="camera-wrapper border rounded overflow-hidden">
                                    <video id="camera-feed" width="100%" height="auto" autoplay playsinline></video>
                                </div>
                                <div class="d-grid mt-2">
                                    <button id="capture-btn" class="btn btn-primary">
                                        <i class="fas fa-camera me-2"></i> Capture Image
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="preview-container mb-3">
                                <div class="preview-wrapper border rounded overflow-hidden">
                                    <canvas id="preview-canvas" width="100%" height="auto" class="d-none"></canvas>
                                    <div id="no-preview" class="text-center p-5 bg-light">
                                        <i class="fas fa-image fa-3x mb-3 text-muted"></i>
                                        <p class="text-muted">Preview will appear here after capture</p>
                                    </div>
                                </div>
                                <div class="d-grid mt-2">
                                    <button id="submit-btn" class="btn btn-success d-none">
                                        <i class="fas fa-clock me-2"></i> <span id="submit-btn-text">Submit</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div id="location-info" class="alert alert-secondary">
                            <i class="fas fa-map-marker-alt me-2"></i> Detecting your location...
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('attendances.attendance') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Attendance
                        </a>
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
        // DOM Elements
        const cameraFeed = document.getElementById('camera-feed');
        const captureBtn = document.getElementById('capture-btn');
        const previewCanvas = document.getElementById('preview-canvas');
        const noPreview = document.getElementById('no-preview');
        const submitBtn = document.getElementById('submit-btn');
        const submitBtnText = document.getElementById('submit-btn-text');
        const attendanceStatus = document.getElementById('attendance-status');
        const locationInfo = document.getElementById('location-info');
        const currentTimeEl = document.getElementById('current-time');
        
        // Variables
        let stream = null;
        let capturedImage = null;
        let attendanceAction = null;
        let userLocation = null;
        
        // Initialize current time display and update every second
        function updateCurrentTime() {
            const now = new Date();
            currentTimeEl.textContent = now.toLocaleString();
        }
        
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
        
        // Initialize camera
        async function initCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 640 },
                        height: { ideal: 480 },
                        facingMode: 'user'
                    },
                    audio: false
                });
                
                cameraFeed.srcObject = stream;
                captureBtn.disabled = false;
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Failed to access camera. Please ensure camera permissions are granted and try again.');
            }
        }
        
        // Get user location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const { latitude, longitude } = position.coords;
                        userLocation = `${latitude},${longitude}`;
                        
                        // Attempt to get human-readable address via reverse geocoding
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                            .then(response => response.json())
                            .then(data => {
                                const address = data.display_name || userLocation;
                                locationInfo.innerHTML = `<i class="fas fa-map-marker-alt me-2"></i> Location: ${address}`;
                            })
                            .catch(() => {
                                locationInfo.innerHTML = `<i class="fas fa-map-marker-alt me-2"></i> Location: ${userLocation}`;
                            });
                    },
                    (error) => {
                        console.error('Error getting location:', error);
                        locationInfo.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> Could not determine location. Please enable location services.`;
                        locationInfo.classList.remove('alert-secondary');
                        locationInfo.classList.add('alert-warning');
                    }
                );
            } else {
                locationInfo.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> Geolocation is not supported by this browser.`;
                locationInfo.classList.remove('alert-secondary');
                locationInfo.classList.add('alert-warning');
            }
        }
        
        // Check attendance status
        function checkAttendanceStatus() {
            fetch('{{ route("attendances.getStatus") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    attendanceAction = data.action;
                    
                    if (data.action === 'clock_in') {
                        attendanceStatus.innerHTML = `<i class="fas fa-sign-in-alt me-2"></i> ${data.message}`;
                        attendanceStatus.classList.remove('alert-info');
                        attendanceStatus.classList.add('alert-primary');
                        submitBtnText.textContent = 'Clock In';
                    } else if (data.action === 'clock_out') {
                        attendanceStatus.innerHTML = `<i class="fas fa-sign-out-alt me-2"></i> ${data.message}`;
                        attendanceStatus.classList.remove('alert-info');
                        attendanceStatus.classList.add('alert-warning');
                        submitBtnText.textContent = 'Clock Out';
                    } else if (data.action === 'completed') {
                        attendanceStatus.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${data.message}`;
                        attendanceStatus.classList.remove('alert-info');
                        attendanceStatus.classList.add('alert-success');
                        captureBtn.disabled = true;
                    }
                } else {
                    attendanceStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${data.message}`;
                    attendanceStatus.classList.remove('alert-info');
                    attendanceStatus.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error checking attendance status:', error);
                attendanceStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> Error checking attendance status`;
                attendanceStatus.classList.remove('alert-info');
                attendanceStatus.classList.add('alert-danger');
            });
        }
        
        // Capture image from camera feed
        captureBtn.addEventListener('click', function() {
            if (!cameraFeed.srcObject) {
                alert('Camera not initialized. Please refresh and try again.');
                return;
            }
            
            // Draw camera feed to canvas
            const context = previewCanvas.getContext('2d');
            previewCanvas.width = cameraFeed.videoWidth;
            previewCanvas.height = cameraFeed.videoHeight;
            context.drawImage(cameraFeed, 0, 0, previewCanvas.width, previewCanvas.height);
            
            // Show preview and capture button
            previewCanvas.classList.remove('d-none');
            noPreview.classList.add('d-none');
            submitBtn.classList.remove('d-none');
            
            // Store captured image as base64 data URL
            capturedImage = previewCanvas.toDataURL('image/jpeg');
        });
        
        // Submit attendance
        submitBtn.addEventListener('click', function() {
            if (!capturedImage) {
                alert('Please capture an image first.');
                return;
            }
            
            if (!userLocation) {
                alert('Location information is not available. Please ensure location services are enabled.');
                return;
            }
            
            if (!attendanceAction || attendanceAction === 'completed') {
                alert('Cannot determine appropriate action. Please refresh and try again.');
                return;
            }
            
            // Disable button to prevent multiple submissions
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
            
            // Prepare data for submission
            const now = new Date();
            const data = {
                type: attendanceAction === 'clock_in' ? 'in' : 'out',
                image: capturedImage,
                location: userLocation,
                timestamp: now.toISOString()
            };
            
            // Submit attendance data
            fetch('{{ route("attendances.storeCapture") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show success message
                    attendanceStatus.innerHTML = `<i class="fas fa-check-circle me-2"></i> ${data.message}`;
                    attendanceStatus.classList.remove('alert-primary', 'alert-warning', 'alert-info');
                    attendanceStatus.classList.add('alert-success');
                    
                    // Disable capture functionality after successful submission
                    captureBtn.disabled = true;
                    submitBtn.disabled = true;
                    
                    // Redirect back to main attendance page after delay
                    setTimeout(() => {
                        window.location.href = '{{ route("attendances.attendance") }}';
                    }, 3000);
                } else {
                    // Show error message
                    attendanceStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${data.message}`;
                    attendanceStatus.classList.remove('alert-primary', 'alert-warning', 'alert-info');
                    attendanceStatus.classList.add('alert-danger');
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i class="fas fa-clock me-2"></i> ${submitBtnText.textContent}`;
                }
            })
            .catch(error => {
                console.error('Error submitting attendance:', error);
                attendanceStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> Error submitting attendance`;
                attendanceStatus.classList.remove('alert-primary', 'alert-warning', 'alert-info');
                attendanceStatus.classList.add('alert-danger');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fas fa-clock me-2"></i> ${submitBtnText.textContent}`;
            });
        });
        
        // Initialize
        initCamera();
        getLocation();
        checkAttendanceStatus();
    });
</script>
@endsection
