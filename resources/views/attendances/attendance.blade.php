@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2 px-3 mb-4 rounded shadow-sm">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
    </ol>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <!-- Time and Attendance Card -->
        <div class="col-lg-8 col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Attendance System
                    </h5>
                    <span class="badge bg-light text-primary" id="current-date"></span>
                </div>
                <div class="card-body">
                    <!-- Real-time Clock -->
                    <div class="text-center mb-4">
                        <div class="display-1 fw-bold text-primary mb-2" id="current-time">00:00:00</div>
                        <div class="text-muted small">Local Time</div>
                    </div>

                    <!-- Clock In/Out Button -->
                    <div class="text-center mb-4">
                        <div class="d-grid gap-2 col-lg-6 col-md-8 mx-auto">
                            <button id="attendance-btn" class="btn btn-lg btn-primary px-5 py-3 shadow-sm">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Clock In
                            </button>
                            <div class="text-muted small mt-2" id="last-action">
                                Last action: Not available
                            </div>
                        </div>
                    </div>

                    <!-- Camera Capture Modal -->
                    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-fullscreen p-0">
                            <div class="modal-content bg-black border-0">
                                <!-- Camera Header Controls -->
                                <div class="camera-controls-top d-flex justify-content-between align-items-center px-3 py-2">
                                    <button type="button" class="btn btn-camera-control" id="flash-btn">
                                        <i class="fas fa-bolt"></i>
                                    </button>
                                    <button type="button" class="btn btn-camera-control" id="hdr-btn">
                                        HDR
                                    </button>
                                    <button type="button" class="btn btn-camera-control" id="timer-btn">
                                        <i class="fas fa-stopwatch"></i>
                                    </button>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                
                                <!-- Camera Viewport -->
                                <div class="camera-viewport position-relative d-flex align-items-center justify-content-center">
                                    <video id="camera-stream" autoplay playsinline class="camera-video"></video>
                                    <canvas id="camera-canvas" class="d-none"></canvas>
                                    
                                    <!-- Frame Guide -->
                                    <div class="frame-guide"></div>
                                    
                                    <!-- Zoom Indicator -->
                                    <div class="zoom-indicator">
                                        <span class="zoom-text">1×</span>
                                    </div>
                                    
                                    <!-- Camera Status -->
                                    <div id="camera-status" class="camera-status-overlay">
                                        <span>Please look at the camera</span>
                                    </div>
                                </div>
                                
                                <!-- Camera Bottom Controls -->
                                <div class="camera-controls-bottom">
                                    <!-- Camera Modes -->
                                    <div class="camera-modes d-flex justify-content-around mb-3">
                                        <div class="camera-mode">SLO-MO</div>
                                        <div class="camera-mode">VIDEO</div>
                                        <div class="camera-mode active">PHOTO</div>
                                        <div class="camera-mode">PORTRAIT</div>
                                    </div>
                                    
                                    <!-- Capture Controls -->
                                    <div class="capture-controls d-flex justify-content-around align-items-center mb-4">
                                        <button type="button" class="btn btn-gallery">
                                            <div class="gallery-preview"></div>
                                        </button>
                                        <button type="button" class="btn-capture" id="capture-btn"></button>
                                        <button type="button" class="btn btn-flip-camera">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Card -->
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Location Details
                    </h5>
                </div>
                <div class="card-body">
                    <div id="location-status" class="alert alert-info d-none">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span class="status-message">Waiting for location access...</span>
                    </div>
                    
                    <div class="location-info">
                        <div class="mb-3">
                            <label class="text-muted small">Current Address</label>
                            <p id="current-location" class="mb-0 fw-bold">Waiting for location...</p>
                        </div>
                        <div id="coordinates-info" class="d-none">
                            <label class="text-muted small">Coordinates</label>
                            <p id="coordinates" class="mb-0 font-monospace"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Today's Activity
                    </h5>
                    <span class="badge bg-primary" id="activity-date"></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width: 15%">Clock In Time</th>
                                    <th style="width: 25%">Clock In Location</th>
                                    <th style="width: 15%">Clock Out Time</th>
                                    <th style="width: 25%">Clock Out Location</th>
                                    <th style="width: 20%">Status</th>
                                </tr>
                            </thead>
                            <tbody id="activity-log">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No activity recorded today</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .breadcrumb {
        background: transparent;
    }
    .location-info {
        border-radius: 0.375rem;
    }
    #attendance-btn {
        transition: all 0.3s ease;
        border-radius: 50px;
    }
    #attendance-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    #current-time {
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
    }
    .card {
        border: none;
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    @media (max-width: 768px) {
        .display-1 {
            font-size: 3.5rem;
        }
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    .table td {
        vertical-align: middle;
    }
    .activity-time {
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }
    .location-text {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.9rem;
    }
    .status-badge {
        min-width: 90px;
    }
    
    /* iPhone-style Camera UI */
    .camera-viewport {
        position: relative;
        width: 100%;
        height: calc(100vh - 160px);
        background-color: #000;
        overflow: hidden;
    }
    
    .camera-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .camera-controls-top {
        background-color: #000;
        color: #fff;
        padding: 15px;
        z-index: 10;
    }
    
    .btn-camera-control {
        color: #ffffff;
        font-size: 1rem;
        font-weight: 500;
        background: transparent;
        border: none;
        padding: 8px 12px;
        border-radius: 50px;
    }
    
    .camera-controls-bottom {
        background-color: #000;
        color: #fff;
        padding: 10px 0;
        position: relative;
        z-index: 10;
    }
    
    .camera-modes {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #ffffff;
        font-weight: 500;
    }
    
    .camera-mode {
        padding: 5px 10px;
        opacity: 0.7;
    }
    
    .camera-mode.active {
        color: #f9ca24;
        opacity: 1;
        font-weight: 600;
    }
    
    .btn-capture {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #fff;
        border: 4px solid #ffffff;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }
    
    .btn-capture:active {
        transform: scale(0.95);
    }
    
    .btn-gallery, .btn-flip-camera {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.3);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .gallery-preview {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        background-color: #555;
    }
    
    .frame-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 150px;
        height: 150px;
        border: 2px solid #f9ca24;
        border-radius: 0;
        box-sizing: border-box;
        pointer-events: none;
    }
    
    .frame-guide::before, .frame-guide::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        border-color: #f9ca24;
        border-style: solid;
    }
    
    .frame-guide::before {
        top: -6px;
        left: -6px;
        border-width: 2px 0 0 2px;
    }
    
    .frame-guide::after {
        bottom: -6px;
        right: -6px;
        border-width: 0 2px 2px 0;
    }
    
    .zoom-indicator {
        position: absolute;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0,0,0,0.5);
        color: #fff;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .camera-status-overlay {
        position: absolute;
        bottom: 140px;
        left: 0;
        right: 0;
        text-align: center;
        color: #fff;
        background-color: rgba(0,0,0,0.5);
        padding: 10px;
        margin: 0 auto;
        width: 80%;
        max-width: 300px;
        border-radius: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .camera-status-overlay.visible {
        opacity: 1;
    }
    
    /* Adjust previous fullscreen styles */
    .camera-fullscreen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 2000;
        background-color: #000;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update time every second
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('activity-date').textContent = now.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Camera access variables
    let stream = null;
    let actionType = '';
    const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
    const captureBtn = document.getElementById('capture-btn');
    const cameraStream = document.getElementById('camera-stream');
    const cameraCanvas = document.getElementById('camera-canvas');
    const cameraStatus = document.getElementById('camera-status');

    // Start camera function
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                }, 
                audio: false 
            });
            cameraStream.srcObject = stream;
            
            // Show camera status message temporarily
            cameraStatus.textContent = 'Please look at the camera for identification';
            cameraStatus.classList.add('visible');
            
            setTimeout(() => {
                cameraStatus.classList.remove('visible');
            }, 3000);
            
        } catch (err) {
            console.error('Error accessing camera:', err);
            cameraStatus.textContent = 'Could not access camera. Please check permissions.';
            cameraStatus.classList.add('visible');
        }
    }

    // Stop camera function
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    // Capture image function
    function captureImage() {
        const context = cameraCanvas.getContext('2d');
        cameraCanvas.width = cameraStream.videoWidth;
        cameraCanvas.height = cameraStream.videoHeight;
        context.drawImage(cameraStream, 0, 0, cameraCanvas.width, cameraCanvas.height);
        
        // Get image data as base64 (here you can send to server for verification)
        const imageData = cameraCanvas.toDataURL('image/jpeg');
        
        // Show processing notification
        cameraStatus.textContent = 'Identity verified successfully!';
        cameraStatus.classList.add('visible');
        
        // Add subtle "flash" effect on capture
        const flashEffect = document.createElement('div');
        flashEffect.style.position = 'absolute';
        flashEffect.style.top = '0';
        flashEffect.style.left = '0';
        flashEffect.style.right = '0';
        flashEffect.style.bottom = '0';
        flashEffect.style.backgroundColor = 'white';
        flashEffect.style.opacity = '0.8';
        flashEffect.style.transition = 'opacity 0.5s';
        flashEffect.style.zIndex = '9';
        
        document.querySelector('.camera-viewport').appendChild(flashEffect);
        
        setTimeout(() => {
            flashEffect.style.opacity = '0';
            setTimeout(() => {
                flashEffect.remove();
                
                // Close modal and perform clock in/out after short delay
                setTimeout(() => {
                    cameraModal.hide();
                    stopCamera();
                    performAttendanceAction();
                }, 500);
                
            }, 500);
        }, 100);
    }

    // Attendance button functionality
    const attendanceBtn = document.getElementById('attendance-btn');
    const lastAction = document.getElementById('last-action');
    let isClockIn = true;

    // Process actions after camera verification
    function performAttendanceAction() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        
        if (actionType === 'Clock In') {
            attendanceBtn.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Clock Out';
            attendanceBtn.classList.remove('btn-primary');
            attendanceBtn.classList.add('btn-danger');
            lastAction.textContent = `Last action: Clocked in at ${timeString}`;
            isClockIn = false;
        } else if (actionType === 'Clock Out') {
            attendanceBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Clock In';
            attendanceBtn.classList.remove('btn-danger');
            attendanceBtn.classList.add('btn-primary');
            lastAction.textContent = `Last action: Clocked out at ${timeString}`;
            isClockIn = true;
        }
        
        // Here you can add AJAX call to your backend to record the attendance
        updateActivityLog(actionType);
    }

    attendanceBtn.addEventListener('click', function() {
        actionType = isClockIn ? 'Clock In' : 'Clock Out';
        cameraModal.show();
        startCamera();
    });

    // Capture button event
    captureBtn.addEventListener('click', captureImage);
    
    // Add dummy button functionality
    document.getElementById('flash-btn').addEventListener('click', function() {
        this.classList.toggle('active');
    });
    
    document.getElementById('hdr-btn').addEventListener('click', function() {
        this.classList.toggle('active');
    });
    
    document.getElementById('timer-btn').addEventListener('click', function() {
        this.classList.toggle('active');
    });
    
    // Clean up when modal is closed
    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function() {
        stopCamera();
    });

    // Activity Log Update
    let currentActivityRow = null;
    
    function updateActivityLog(action) {
        const tbody = document.getElementById('activity-log');
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        const location = document.getElementById('current-location').textContent;
        
        if (action === 'Clock In') {
            // Create new row for new clock in
            if (tbody.firstElementChild.getElementsByTagName('td')[0].colSpan) {
                tbody.innerHTML = '';
            }
            
            const row = document.createElement('tr');
            row.className = 'text-center';
            row.innerHTML = `
                <td class="activity-time text-success">${timeString}</td>
                <td class="location-text" title="${location}">${location}</td>
                <td class="activity-time text-muted">--:--:-- --</td>
                <td class="location-text text-muted">--</td>
                <td><span class="badge bg-warning status-badge">In Progress</span></td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
            currentActivityRow = row;
        } else if (action === 'Clock Out' && currentActivityRow) {
            // Update existing row with clock out time
            currentActivityRow.children[2].textContent = timeString;
            currentActivityRow.children[2].className = 'activity-time text-danger';
            currentActivityRow.children[3].textContent = location;
            currentActivityRow.children[3].className = 'location-text';
            currentActivityRow.children[3].title = location;
            currentActivityRow.children[4].innerHTML = '<span class="badge bg-success status-badge">Completed</span>';
            currentActivityRow = null;
        }
    }

    // Location tracking
    const locationStatus = document.getElementById('location-status');
    const currentLocation = document.getElementById('current-location');
    const coordinatesInfo = document.getElementById('coordinates-info');
    const coordinates = document.getElementById('coordinates');

    if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition(
            function(position) {
                // Get address from coordinates using reverse geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        currentLocation.textContent = data.display_name;
                        coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                        coordinatesInfo.classList.remove('d-none');
                        locationStatus.classList.add('d-none');
                    })
                    .catch(error => {
                        currentLocation.textContent = 'Unable to fetch address';
                        coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                        coordinatesInfo.classList.remove('d-none');
                        locationStatus.classList.remove('d-none');
                        locationStatus.className = 'alert alert-warning';
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Unable to fetch address details';
                    });
            },
            function(error) {
                locationStatus.classList.remove('d-none');
                locationStatus.className = 'alert alert-danger';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location services.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location request timed out.';
                        break;
                }
                currentLocation.textContent = 'Location access required';
                coordinatesInfo.classList.add('d-none');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    } else {
        locationStatus.classList.remove('d-none');
        locationStatus.className = 'alert alert-danger';
        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Geolocation is not supported by your browser.';
        currentLocation.textContent = 'Location services not supported';
        coordinatesInfo.classList.add('d-none');
    }
});
</script>
@endpush
@endsection

