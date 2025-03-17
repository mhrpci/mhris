@extends('layouts.app')

@section('content')
<style>
    /* Base styles */
    body.preview-active {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    
    .preview-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #000;
        z-index: 9990;
    }
    
    /* Image preview section */
    .image-preview-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background-color: #000;
    }
    
    .preview-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Camera interface elements */
    .camera-interface {
        display: none;
    }

    .camera-frame {
        display: none;
    }

    .camera-corners {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 3px solid #4285f4;
    }

    .corner-top-left {
        top: -3px;
        left: -3px;
        border-right: none;
        border-bottom: none;
        border-top-left-radius: 8px;
    }

    .corner-top-right {
        top: -3px;
        right: -3px;
        border-left: none;
        border-bottom: none;
        border-top-right-radius: 8px;
    }

    .corner-bottom-left {
        bottom: -3px;
        left: -3px;
        border-right: none;
        border-top: none;
        border-bottom-left-radius: 8px;
    }

    .corner-bottom-right {
        bottom: -3px;
        right: -3px;
        border-left: none;
        border-top: none;
        border-bottom-right-radius: 8px;
    }
    
    /* Logo overlay */
    .preview-logo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 100px;
        height: auto;
        z-index: 9992;
        background: rgba(255, 255, 255, 0.9);
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Status badge */
    .preview-status-badge {
        position: absolute;
        bottom: 160px; /* Position above the datetime */
        left: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 12px 24px;
        border-radius: 30px;
        color: white;
        font-weight: bold;
        z-index: 9992;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 1.2rem;
    }
    
    .preview-status-badge.in {
        background: rgba(16, 185, 129, 0.2);
        border-color: rgba(16, 185, 129, 0.4);
    }
    
    .preview-status-badge.out {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    /* Large status indicator */
    .preview-status-large {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 8rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.15);
        text-transform: uppercase;
        pointer-events: none;
        z-index: 9991;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        letter-spacing: 4px;
    }

    .preview-status-large.in {
        color: rgba(40, 167, 69, 0.15);
    }

    .preview-status-large.out {
        color: rgba(220, 53, 69, 0.15);
    }
    
    /* Info overlay */
    .preview-info-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        color: white;
        z-index: 9992;
        padding: 25px;
        background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.8) 50%, rgba(0,0,0,0.4) 85%, transparent 100%);
    }
    
    .preview-overlay-content {
        max-width: 100%;
        display: grid;
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .preview-company-name {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 5px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
    }
    
    .preview-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .preview-status-badge {
        position: relative;
        bottom: auto;
        left: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 8px 16px;
        border-radius: 30px;
        color: white;
        font-weight: bold;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 1rem;
    }
    
    .preview-datetime {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .preview-time {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        letter-spacing: 0.5px;
    }
    
    .preview-date {
        font-size: 1.1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.9);
        margin: 0;
    }
    
    .preview-details {
        display: grid;
        gap: 12px;
        margin-top: 5px;
    }
    
    .preview-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: rgba(255,255,255,0.95);
        margin: 0;
        letter-spacing: 0.3px;
    }
    
    .preview-info-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .preview-info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .preview-info-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .preview-info-value {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255,255,255,0.95);
    }
    
    .preview-location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.9);
        font-size: 0.95rem;
        background: rgba(255,255,255,0.1);
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 10px;
        border: 1px solid rgba(255,255,255,0.15);
    }
    
    /* Controls */
    .preview-controls {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 9993;
    }
    
    .btn-capture, .btn-cancel {
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-capture {
        background-color: #0d6efd;
        color: white;
    }
    
    .btn-capture:hover {
        background-color: #0b5ed7;
    }
    
    .btn-cancel {
        background-color: rgba(255,255,255,0.15);
        color: white;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .btn-cancel:hover {
        background-color: rgba(255,255,255,0.25);
    }
    
    /* Loading overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 20px;
        color: white;
    }
    
    .loading-spinner {
        width: 70px;
        height: 70px;
        border: 6px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #ffffff;
        animation: spin 1s linear infinite;
    }
    
    .loading-text {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Media queries */
    @media (max-width: 768px) {
        .preview-status-large {
            font-size: 6rem;
        }
        
        .preview-time {
            font-size: 1.7rem;
        }
        
        .preview-date {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .preview-status-large {
            font-size: 4rem;
        }
        
        .preview-company-name {
            font-size: 0.9rem;
        }
        
        .preview-info-overlay {
            padding: 20px;
        }
        
        .preview-time {
            font-size: 1.5rem;
        }
        
        .preview-date {
            font-size: 0.9rem;
        }
        
        .preview-name {
            font-size: 1.1rem;
        }
        
        .preview-info-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .preview-logo {
            width: 80px;
            top: 15px;
            right: 15px;
        }
    }
</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Attendance - Capture Preview</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        Please allow access to your camera and location to record your attendance.
                    </div>
                </div>
            </div>
            
            <!-- Camera preview and controls -->
            <div class="row">
                <div class="col-md-6">
                    <div class="camera-container">
                        <video id="camera-preview" width="100%" height="auto" autoplay playsinline style="border-radius: 10px;"></video>
                        
                        <div class="mt-3 d-flex justify-content-between">
                            <button id="captureButton" class="btn btn-primary">
                                <i class="fas fa-camera mr-2"></i> Capture Attendance
                            </button>
                            <button id="switchCameraButton" class="btn btn-secondary">
                                <i class="fas fa-sync-alt mr-2"></i> Switch Camera
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Attendance Status</h5>
                        </div>
                        <div class="card-body">
                            <div id="attendanceStatus" class="alert alert-info">
                                Checking attendance status...
                            </div>
                            
                            <div id="employeeInfo" class="mb-3">
                                <p><strong>Name:</strong> <span id="employeeName">Loading...</span></p>
                                <p><strong>Position:</strong> <span id="employeePosition">Loading...</span></p>
                                <p><strong>Department:</strong> <span id="employeeDepartment">Loading...</span></p>
                            </div>
                            
                            <div id="locationInfo" class="mb-3">
                                <p><strong>Location:</strong> <span id="currentLocation">Detecting...</span></p>
                            </div>
                            
                            <div id="dateTimeInfo">
                                <p><strong>Date:</strong> <span id="currentDate"></span></p>
                                <p><strong>Time:</strong> <span id="currentTime"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Container for Captured Image -->
<div class="preview-container" style="display: none;">
    <div class="image-preview-container">
        <img src="" alt="Captured image" class="preview-image" id="captured-image">
        
        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
        
        <div class="preview-status-large" id="preview-status-large">IN</div>
        
        <div class="preview-info-overlay">
            <div class="preview-overlay-content">
                <div class="preview-company-name" id="preview-company-name"></div>
                <div class="preview-header">
                    <div id="preview-status-badge" class="preview-status-badge">
                        <i class="fas fa-clock"></i>
                        <span id="status-text">Clock In</span>
                    </div>
                    
                    <div class="preview-datetime">
                        <div class="preview-time" id="preview-time"></div>
                        <div class="preview-date" id="preview-date"></div>
                    </div>
                </div>
                
                <div class="preview-details">
                    <div class="preview-name" id="preview-name"></div>
                    
                    <div class="preview-info-row">
                        <div class="preview-info-item">
                            <span class="preview-info-label">Position</span>
                            <span class="preview-info-value" id="preview-position"></span>
                        </div>
                        <div class="preview-info-item">
                            <span class="preview-info-label">Department</span>
                            <span class="preview-info-value" id="preview-department"></span>
                        </div>
                    </div>
                    
                    <div class="preview-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="preview-location-text" id="preview-location"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="preview-controls">
            <button class="btn-capture" id="submit-attendance">
                <i class="fas fa-check"></i> Submit
            </button>
            <button class="btn-cancel" id="back-to-camera">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Processing attendance...</div>
</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const cameraPreview = document.getElementById('camera-preview');
        const captureButton = document.getElementById('captureButton');
        const switchCameraButton = document.getElementById('switchCameraButton');
        const previewContainer = document.querySelector('.preview-container');
        const capturedImage = document.getElementById('captured-image');
        const submitAttendanceButton = document.getElementById('submit-attendance');
        const backToCameraButton = document.getElementById('back-to-camera');
        const loadingOverlay = document.getElementById('loading-overlay');
        
        // Status elements
        const attendanceStatus = document.getElementById('attendanceStatus');
        const employeeName = document.getElementById('employeeName');
        const employeePosition = document.getElementById('employeePosition');
        const employeeDepartment = document.getElementById('employeeDepartment');
        const currentLocation = document.getElementById('currentLocation');
        const currentDate = document.getElementById('currentDate');
        const currentTime = document.getElementById('currentTime');
        
        // Preview elements
        const previewCompanyName = document.getElementById('preview-company-name');
        const previewStatusBadge = document.getElementById('preview-status-badge');
        const statusText = document.getElementById('status-text');
        const previewStatusLarge = document.getElementById('preview-status-large');
        const previewTime = document.getElementById('preview-time');
        const previewDate = document.getElementById('preview-date');
        const previewName = document.getElementById('preview-name');
        const previewPosition = document.getElementById('preview-position');
        const previewDepartment = document.getElementById('preview-department');
        const previewLocation = document.getElementById('preview-location');
        
        // Variables
        let stream = null;
        let facingMode = 'user'; // Default to front camera
        let capturedImageData = null;
        let currentAttendanceAction = 'clock_in'; // Default action
        let locationString = 'Unknown location';
        let position = null;
        let employeeData = null;
        
        // Initialize date/time display
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Initialize
        initCamera();
        getEmployeeInfo();
        getAttendanceStatus();
        getLocation();
        
        // Update date and time
        function updateDateTime() {
            const now = moment();
            currentDate.textContent = now.format('MMMM D, YYYY');
            currentTime.textContent = now.format('h:mm:ss A');
            
            // Also update preview date/time
            previewTime.textContent = now.format('h:mm A');
            previewDate.textContent = now.format('MMMM D, YYYY');
        }
        
        // Initialize camera
        function initCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode }
                }).then(function(mediaStream) {
                    stream = mediaStream;
                    cameraPreview.srcObject = stream;
                    cameraPreview.play();
                }).catch(function(error) {
                    console.error('Camera access error:', error);
                    attendanceStatus.className = 'alert alert-danger';
                    attendanceStatus.textContent = 'Camera access denied. Please allow camera access to record attendance.';
                });
            } else {
                console.error('getUserMedia not supported');
                attendanceStatus.className = 'alert alert-danger';
                attendanceStatus.textContent = 'Your browser does not support camera access.';
            }
        }
        
        // Switch camera
        switchCameraButton.addEventListener('click', function() {
            if (stream) {
                // Stop all tracks
                stream.getTracks().forEach(track => track.stop());
                
                // Toggle facing mode
                facingMode = facingMode === 'user' ? 'environment' : 'user';
                
                // Reinitialize camera with new facing mode
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode }
                }).then(function(mediaStream) {
                    stream = mediaStream;
                    cameraPreview.srcObject = stream;
                    cameraPreview.play();
                }).catch(function(error) {
                    console.error('Camera switch error:', error);
                    // If switching fails, try to revert to previous mode
                    facingMode = facingMode === 'user' ? 'environment' : 'user';
                    initCamera();
                });
            }
        });
        
        // Get employee info
        function getEmployeeInfo() {
            fetch('/attendance/get-employee-info')
                .then(response => response.json())
                .then(data => {
                    employeeData = data;
                    employeeName.textContent = data.name;
                    employeePosition.textContent = data.position || 'Not assigned';
                    employeeDepartment.textContent = data.department || 'Not assigned';
                    
                    // Set company name based on department
                    let companyName = 'MHR Property Conglomerates, Inc.';
                    if (data.department) {
                        const deptUpper = data.department.toUpperCase();
                        if (deptUpper === 'MHRHCI') {
                            companyName = 'Medical & Resources Health Care, Inc.';
                        } else if (deptUpper === 'BGPDI') {
                            companyName = 'Bay Gas and Petroleum Distribution, Inc.';
                        } else if (deptUpper === 'VHI') {
                            companyName = 'Verbena Hotel Inc.';
                        }
                    }
                    previewCompanyName.textContent = companyName;
                    
                    // Also update preview info
                    previewName.textContent = data.name;
                    previewPosition.textContent = data.position || 'Not assigned';
                    previewDepartment.textContent = data.department || 'Not assigned';
                })
                .catch(error => {
                    console.error('Error fetching employee info:', error);
                    employeeName.textContent = 'Error loading data';
                    employeePosition.textContent = 'Error loading data';
                    employeeDepartment.textContent = 'Error loading data';
                });
        }
        
        // Get attendance status
        function getAttendanceStatus() {
            fetch('/attendance/get-status')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        currentAttendanceAction = data.action;
                        
                        if (data.action === 'clock_in') {
                            attendanceStatus.className = 'alert alert-primary';
                            attendanceStatus.innerHTML = '<i class="fas fa-clock mr-2"></i> ' + data.message;
                            captureButton.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> Clock In';
                            // Update preview elements
                            statusText.textContent = 'Clock In';
                            previewStatusLarge.textContent = 'IN';
                            previewStatusLarge.className = 'preview-status-large in';
                            previewStatusBadge.className = 'preview-status-badge in';
                        } else if (data.action === 'clock_out') {
                            attendanceStatus.className = 'alert alert-success';
                            attendanceStatus.innerHTML = '<i class="fas fa-check-circle mr-2"></i> ' + data.message;
                            captureButton.innerHTML = '<i class="fas fa-sign-out-alt mr-2"></i> Clock Out';
                            // Update preview elements
                            statusText.textContent = 'Clock Out';
                            previewStatusLarge.textContent = 'OUT';
                            previewStatusLarge.className = 'preview-status-large out';
                            previewStatusBadge.className = 'preview-status-badge out';
                        } else if (data.action === 'completed') {
                            attendanceStatus.className = 'alert alert-warning';
                            attendanceStatus.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> ' + data.message;
                            captureButton.disabled = true;
                            captureButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Completed';
                        }
                    } else {
                        attendanceStatus.className = 'alert alert-danger';
                        attendanceStatus.textContent = data.message || 'Error checking attendance status';
                    }
                })
                .catch(error => {
                    console.error('Error fetching attendance status:', error);
                    attendanceStatus.className = 'alert alert-danger';
                    attendanceStatus.textContent = 'Failed to check attendance status';
                });
        }
        
        // Get location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        position = pos;
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        
                        // Try to get address from coordinates
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    locationString = data.display_name;
                                } else {
                                    locationString = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                                }
                                currentLocation.textContent = locationString;
                                previewLocation.textContent = locationString;
                            })
                            .catch(error => {
                                console.error('Error fetching location name:', error);
                                locationString = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                                currentLocation.textContent = locationString;
                                previewLocation.textContent = locationString;
                            });
                    },
                    function(error) {
                        console.error('Geolocation error:', error);
                        currentLocation.textContent = 'Location access denied';
                        previewLocation.textContent = 'Location unavailable';
                    }
                );
            } else {
                currentLocation.textContent = 'Geolocation not supported';
                previewLocation.textContent = 'Location unavailable';
            }
        }
        
        // Capture photo
        captureButton.addEventListener('click', function() {
            if (stream) {
                const canvas = document.createElement('canvas');
                const video = cameraPreview;
                const width = video.videoWidth;
                const height = video.videoHeight;
                
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                
                // If using front camera, flip the image horizontally
                if (facingMode === 'user') {
                    ctx.translate(width, 0);
                    ctx.scale(-1, 1);
                }
                
                ctx.drawImage(video, 0, 0, width, height);
                
                // Get image data as base64 string
                capturedImageData = canvas.toDataURL('image/jpeg');
                
                // Display the captured image
                capturedImage.src = capturedImageData;
                
                // Show the preview container
                previewContainer.style.display = 'block';
                document.body.classList.add('preview-active');
            }
        });
        
        // Go back to camera
        backToCameraButton.addEventListener('click', function() {
            previewContainer.style.display = 'none';
            document.body.classList.remove('preview-active');
            capturedImageData = null;
        });
        
        // Submit attendance
        submitAttendanceButton.addEventListener('click', function() {
            if (!capturedImageData || !employeeData) {
                alert('Missing data. Please try again.');
                return;
            }
            
            loadingOverlay.style.display = 'flex';
            
            // Prepare data for submission
            const timestamp = moment().format('YYYY-MM-DD HH:mm:ss');
            const attendanceType = currentAttendanceAction === 'clock_in' ? 'in' : 'out';
            
            const postData = {
                type: attendanceType,
                image: capturedImageData,
                location: locationString,
                timestamp: timestamp
            };
            
            // Send the request
            fetch('/attendance/store-capture', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(postData)
            })
            .then(response => response.json())
            .then(data => {
                loadingOverlay.style.display = 'none';
                
                if (data.status === 'success') {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect or refresh the page
                        window.location.href = '/attendance';
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred while recording attendance',
                        confirmButtonText: 'Try Again'
                    }).then(() => {
                        // Hide preview container and go back to camera
                        previewContainer.style.display = 'none';
                        document.body.classList.remove('preview-active');
                    });
                }
            })
            .catch(error => {
                console.error('Error submitting attendance:', error);
                loadingOverlay.style.display = 'none';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network error. Please try again.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Hide preview container and go back to camera
                    previewContainer.style.display = 'none';
                    document.body.classList.remove('preview-active');
                });
            });
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });
    });
</script>
@endsection
