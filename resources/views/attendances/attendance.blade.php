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
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cameraModalLabel">Identity Verification</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="camera-container mb-3">
                                        <video id="camera-stream" class="w-100 rounded" autoplay playsinline></video>
                                        <canvas id="camera-canvas" class="d-none"></canvas>
                                    </div>
                                    <div id="camera-status" class="alert alert-info">
                                        <i class="fas fa-camera me-2"></i>
                                        <span>Please look at the camera for identification</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="capture-btn">
                                        <i class="fas fa-camera me-2"></i>Capture
                                    </button>
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
                video: { facingMode: 'user' }, 
                audio: false 
            });
            cameraStream.srcObject = stream;
            cameraStatus.className = 'alert alert-info';
            cameraStatus.innerHTML = '<i class="fas fa-camera me-2"></i><span>Please look at the camera for identification</span>';
        } catch (err) {
            console.error('Error accessing camera:', err);
            cameraStatus.className = 'alert alert-danger';
            cameraStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i><span>Could not access camera. Please check permissions.</span>';
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
        cameraStatus.className = 'alert alert-success';
        cameraStatus.innerHTML = '<i class="fas fa-check-circle me-2"></i><span>Identity verified successfully!</span>';
        
        // Close modal and perform clock in/out after short delay
        setTimeout(() => {
            cameraModal.hide();
            stopCamera();
            performAttendanceAction();
        }, 1500);
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
        document.getElementById('cameraModalLabel').textContent = `Identity Verification - ${actionType}`;
        cameraModal.show();
        startCamera();
    });

    // Capture button event
    captureBtn.addEventListener('click', captureImage);

    // Clean up when modal is closed
    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function () {
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
