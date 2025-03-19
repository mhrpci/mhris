@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Current Time and Attendance Actions in one card -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <!-- Date and Time -->
                    <div class="text-center mb-4">
                        <h4 id="currentDate" class="mb-2 text-muted">{{ date('l, F j, Y') }}</h4>
                        <div class="time-display py-3 rounded">
                            <h1 id="currentTime" class="display-4 font-weight-bold text-primary mb-0">00:00:00</h1>
                        </div>
                    </div>
                    
                    <!-- Location Display -->
                    <div class="location-display mb-4 rounded p-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-wrapper text-primary mr-2">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="text-muted small">Current Location</div>
                            <div class="ml-auto">
                                <button id="refreshLocation" class="btn btn-sm btn-link p-0">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div id="locationAddress" class="font-weight-medium">
                            <div class="location-placeholder d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span>Requesting location permission...</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Info -->
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center p-3 rounded bg-light">
                                <div class="icon-wrapper text-primary mr-3">
                                    <i class="fas fa-sign-in-alt fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Clock In</div>
                                    <div class="font-weight-bold" id="clockInTime">--:--:--</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded bg-light">
                                <div class="icon-wrapper text-danger mr-3">
                                    <i class="fas fa-sign-out-alt fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Clock Out</div>
                                    <div class="font-weight-bold" id="clockOutTime">--:--:--</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Status Alert - Simplified -->
                    <div class="alert alert-light border text-center mb-4" id="attendanceStatus">
                        <span class="status-indicator bg-info mr-2"></span>
                        You have not clocked in today
                    </div>

                    <!-- Clock In/Out Buttons - Cleaner design -->
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <button id="clockInBtn" class="btn btn-outline-primary btn-block py-3">
                                <i class="fas fa-sign-in-alt mr-2"></i>Clock In
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button id="clockOutBtn" class="btn btn-outline-danger btn-block py-3" disabled>
                                <i class="fas fa-sign-out-alt mr-2"></i>Clock Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Summary - Simplified -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0">
                    <h6 class="mb-0 text-muted">
                        <i class="fas fa-history mr-2"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Clock In</th>
                                    <th class="border-0">Clock Out</th>
                                    <th class="border-0 d-none d-md-table-cell">Hours</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('M d', strtotime('-1 day')) }}</td>
                                    <td>08:02</td>
                                    <td>17:01</td>
                                    <td class="d-none d-md-table-cell">8.98</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-2 day')) }}</td>
                                    <td>08:05</td>
                                    <td>17:15</td>
                                    <td class="d-none d-md-table-cell">9.17</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-3 day')) }}</td>
                                    <td>--:--</td>
                                    <td>--:--</td>
                                    <td class="d-none d-md-table-cell">0.00</td>
                                    <td><span class="badge badge-soft-danger">Absent</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-4 day')) }}</td>
                                    <td>08:10</td>
                                    <td>17:05</td>
                                    <td class="d-none d-md-table-cell">8.92</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-5 day')) }}</td>
                                    <td>08:00</td>
                                    <td>17:30</td>
                                    <td class="d-none d-md-table-cell">9.49</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div class="modal fade fullscreen-modal" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered m-0 h-100 w-100" role="document">
        <div class="modal-content border-0 h-100 rounded-0">
            <div class="modal-body p-0 h-100">
                <div class="camera-container h-100 d-flex flex-column">
                    <!-- Camera Header -->
                    <div class="camera-header d-flex align-items-center justify-content-between p-3">
                        <div class="camera-title">
                            <h5 class="mb-0" id="cameraActionType">Take Photo</h5>
                        </div>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <!-- Camera Viewfinder & Overlay -->
                    <div class="camera-viewfinder-container flex-grow-1">
                        <video id="cameraFeed" autoplay playsinline></video>
                        <canvas id="cameraCanvas" class="d-none"></canvas>
                        <div id="capturedPhoto" class="captured-photo d-none"></div>
                        
                        <!-- Facial Detection Overlay -->
                        <div class="face-detection-overlay">
                            <div class="face-outline"></div>
                        </div>
                        
                        <!-- Status Message -->
                        <div id="cameraStatus" class="camera-status">
                            <div class="status-message">
                                <i class="fas fa-user-circle mr-2"></i>
                                <span id="statusText">Position your face in the center</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Camera Controls -->
                    <div class="camera-controls d-flex align-items-center justify-content-center py-4">
                        <button id="switchCameraBtn" class="btn btn-light mr-4">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button id="captureBtn" class="capture-btn">
                            <div class="inner-circle"></div>
                        </button>
                        <button id="retakeBtn" class="btn btn-light ml-4 d-none">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="action-button py-3 px-4 text-center d-none" id="actionButtonContainer">
                        <button id="confirmAttendanceBtn" class="btn btn-primary btn-block py-2">
                            <span id="actionButtonText">Confirm Clock In</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Minimalist styling */
    .time-display {
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    
    #currentTime {
        font-size: 3rem;
        color: #333;
    }
    
    .icon-wrapper {
        width: 40px;
        text-align: center;
    }
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-outline-primary:hover, .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .badge-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        font-weight: 500;
    }
    
    .badge-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        font-weight: 500;
    }
    
    .table th {
        font-weight: 500;
        color: #6c757d;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }

    .location-display {
        border-radius: 8px;
    }

    .font-weight-medium {
        font-weight: 500;
    }

    #refreshLocation {
        color: #6c757d;
        transition: all 0.2s;
    }

    #refreshLocation:hover {
        color: #3b82f6;
        transform: rotate(90deg);
    }
    
    @media (max-width: 576px) {
        #currentTime {
            font-size: 2.5rem;
        }
    }

    /* Camera Modal Styles */
    .fullscreen-modal {
        padding: 0 !important;
    }
    
    .fullscreen-modal .modal-dialog {
        max-width: 100% !important;
        margin: 0;
    }
    
    .modal-content {
        border-radius: 0;
        overflow: hidden;
    }

    .camera-container {
        background-color: #000;
        position: relative;
    }

    .camera-header {
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
    }

    .camera-viewfinder-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: #000;
    }

    #cameraFeed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Mirror effect for front camera */
    #cameraFeed.mirror {
        transform: scaleX(-1);
    }

    .captured-photo {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
    }

    .face-detection-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .face-outline {
        width: 220px;
        height: 220px;
        border: 2px dashed rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        position: relative;
    }

    .face-outline::before {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
    }

    .camera-status {
        position: absolute;
        bottom: 100px;
        left: 0;
        right: 0;
        text-align: center;
        z-index: 5;
    }

    .status-message {
        display: inline-block;
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 14px;
    }

    .camera-controls {
        background-color: rgba(0, 0, 0, 0.8);
        position: relative;
        z-index: 5;
    }

    .capture-btn {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .capture-btn .inner-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: white;
    }

    .capture-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .btn-light {
        background-color: rgba(255, 255, 255, 0.15);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .btn-light:hover {
        background-color: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .action-button {
        background-color: rgba(0, 0, 0, 0.8);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 5;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .face-outline {
            width: 180px;
            height: 180px;
        }
        
        .camera-status {
            bottom: 90px;
        }
        
        .status-message {
            font-size: 12px;
        }
        
        .capture-btn {
            width: 70px;
            height: 70px;
        }
        
        .capture-btn .inner-circle {
            width: 56px;
            height: 56px;
        }
    }
    
    @media (orientation: landscape) {
        .camera-controls {
            padding: 12px 0 !important;
        }
        
        .face-outline {
            width: 140px;
            height: 140px;
        }
        
        .camera-status {
            bottom: 70px;
        }
        
        .capture-btn {
            width: 60px;
            height: 60px;
        }
        
        .capture-btn .inner-circle {
            width: 48px;
            height: 48px;
        }
        
        .btn-light {
            width: 40px;
            height: 40px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Update current time every second
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            $('#currentTime').text(`${hours}:${minutes}:${seconds}`);
        }
        
        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
        
        // Location variables
        let userCoordinates = null;
        const locationIqApiKey = 'pk.e5dff6366eb119dd6b5fc023775923c9'; // Replace with your LocationIQ API key
        
        // Get user location
        function getUserLocation() {
            $('#locationAddress').html(`
                <div class="location-placeholder d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span>Fetching your location...</span>
                </div>
            `);
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success callback
                    function(position) {
                        userCoordinates = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        getAddressFromCoordinates(userCoordinates);
                    },
                    // Error callback
                    function(error) {
                        let errorMessage = 'Unable to retrieve your location';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location permission denied. Please enable location services.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information is unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Location request timed out.';
                                break;
                        }
                        
                        $('#locationAddress').html(`
                            <div class="text-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i> ${errorMessage}
                            </div>
                        `);
                    },
                    // Options
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                $('#locationAddress').html(`
                    <div class="text-danger">
                        <i class="fas fa-exclamation-circle mr-1"></i> Geolocation is not supported by this browser.
                    </div>
                `);
            }
        }
        
        // Get address from coordinates using LocationIQ API
        function getAddressFromCoordinates(coordinates) {
            // Using reverse geocoding endpoint instead of the matching/driving endpoint
            const apiUrl = `https://us1.locationiq.com/v1/reverse.php?key=${locationIqApiKey}&lat=${coordinates.lat}&lon=${coordinates.lng}&format=json`;
            
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.display_name) {
                        const address = response.display_name;
                        $('#locationAddress').html(`
                            <div>
                                <i class="fas fa-map-marker-alt text-primary mr-1"></i> ${address}
                            </div>
                        `);
                        
                        // Store location for clock in/out
                        window.currentLocationAddress = address;
                    } else {
                        $('#locationAddress').html(`
                            <div class="text-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Could not determine exact address.
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    $('#locationAddress').html(`
                        <div class="text-danger">
                            <i class="fas fa-exclamation-circle mr-1"></i> Error fetching location: ${error}
                        </div>
                    `);
                    console.error('LocationIQ API Error:', error);
                }
            });
        }
        
        // Initialize location detection
        getUserLocation();
        
        // Allow manual refresh of location
        $('#refreshLocation').click(function() {
            getUserLocation();
            $(this).addClass('fa-spin');
            setTimeout(() => {
                $(this).removeClass('fa-spin');
            }, 1000);
        });
        
        // =====================
        // Camera functionality
        // =====================
        let currentAction = ''; // 'clockIn' or 'clockOut'
        let stream = null;
        let facingMode = 'user'; // 'user' for front camera, 'environment' for back camera
        let photoTaken = false;
        let photoDataUrl = null;
        
        // Camera elements
        const cameraModal = $('#cameraModal');
        const cameraFeed = document.getElementById('cameraFeed');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const capturedPhoto = document.getElementById('capturedPhoto');
        const captureBtn = document.getElementById('captureBtn');
        const retakeBtn = document.getElementById('retakeBtn');
        const switchCameraBtn = document.getElementById('switchCameraBtn');
        const confirmAttendanceBtn = document.getElementById('confirmAttendanceBtn');
        const actionButtonContainer = document.getElementById('actionButtonContainer');
        const cameraActionType = document.getElementById('cameraActionType');
        const statusText = document.getElementById('statusText');
        
        // Start the camera stream
        async function startCamera() {
            try {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                
                const constraints = {
                    video: {
                        facingMode: facingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                };
                
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                cameraFeed.srcObject = stream;
                
                // Mirror the front camera display but not back camera
                if (facingMode === 'user') {
                    $(cameraFeed).addClass('mirror');
                } else {
                    $(cameraFeed).removeClass('mirror');
                }
                
                // Update UI for camera start
                $(cameraFeed).removeClass('d-none');
                $(capturedPhoto).addClass('d-none');
                $('#retakeBtn').addClass('d-none');
                $('#captureBtn').removeClass('d-none');
                $('#actionButtonContainer').addClass('d-none');
                photoTaken = false;
                
                // Show "face positioning" message
                $('#statusText').text('Position your face in the center');
                
                // Simulate face detection with setTimeout
                setTimeout(() => {
                    $('#statusText').html('<i class="fas fa-check-circle mr-1"></i> Face detected');
                    $('.face-outline').css('border-color', 'rgba(40, 167, 69, 0.7)');
                }, 1500);
                
            } catch (error) {
                console.error('Error accessing camera:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Camera Error',
                    text: 'Unable to access camera. Please ensure you have granted camera permissions.',
                    customClass: {
                        popup: 'swal-minimalist'
                    }
                });
            }
        }
        
        // Stop camera stream
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }
        
        // Take photo
        function takePhoto() {
            const context = cameraCanvas.getContext('2d');
            
            // Set canvas dimensions to match video
            cameraCanvas.width = cameraFeed.videoWidth;
            cameraCanvas.height = cameraFeed.videoHeight;
            
            // Draw video frame to canvas
            if (facingMode === 'user') {
                // For front camera, we need to un-mirror the image when saving
                // First flip the context horizontally
                context.translate(cameraCanvas.width, 0);
                context.scale(-1, 1);
                // Then draw the flipped video
                context.drawImage(cameraFeed, 0, 0, cameraCanvas.width, cameraCanvas.height);
                // Reset transform
                context.setTransform(1, 0, 0, 1, 0, 0);
            } else {
                // For back camera, just draw normally
                context.drawImage(cameraFeed, 0, 0, cameraCanvas.width, cameraCanvas.height);
            }
            
            // Get data URL from canvas
            photoDataUrl = cameraCanvas.toDataURL('image/jpeg');
            
            // Display the captured photo (we don't want the display to be mirrored)
            capturedPhoto.style.backgroundImage = `url(${photoDataUrl})`;
            $(capturedPhoto).removeClass('d-none').removeClass('mirror');
            $(cameraFeed).addClass('d-none');
            
            // Update UI for photo taken
            $('#captureBtn').addClass('d-none');
            $('#retakeBtn').removeClass('d-none');
            $('#actionButtonContainer').removeClass('d-none');
            
            // Update status
            $('#statusText').html('<i class="fas fa-check-circle mr-1"></i> Photo captured');
            
            photoTaken = true;
        }
        
        // Handle modal open and setup camera
        function openCameraModal(action) {
            currentAction = action;
            
            // Set the modal title based on the action
            if (action === 'clockIn') {
                $('#cameraActionType').text('Clock In Verification');
                $('#actionButtonText').text('Confirm Clock In');
            } else {
                $('#cameraActionType').text('Clock Out Verification');
                $('#actionButtonText').text('Confirm Clock Out');
            }
            
            // Reset UI elements
            $('.face-outline').css('border-color', 'rgba(255, 255, 255, 0.7)');
            $('#statusText').text('Initializing camera...');
            
            // Show the camera modal
            cameraModal.modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
            
            // Handle fullscreen on mobile
            if (window.innerWidth < 768) {
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
            }
            
            // Start the camera after modal is shown
            cameraModal.on('shown.bs.modal', function() {
                startCamera();
                
                // Try to go fullscreen if supported
                try {
                    const elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) { /* Safari */
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) { /* IE11 */
                        elem.msRequestFullscreen();
                    }
                } catch (err) {
                    console.log('Fullscreen not supported');
                }
            });
            
            // Stop the camera when modal is hidden
            cameraModal.on('hidden.bs.modal', function() {
                stopCamera();
                // Reset the modal state for next use
                photoTaken = false;
                
                // Exit fullscreen if needed
                try {
                    if (document.fullscreenElement) {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                    }
                } catch (err) {
                    console.log('Exit fullscreen error');
                }
                
                // Restore body scrolling
                document.documentElement.style.overflow = '';
                document.body.style.overflow = '';
            });
        }
        
        // Switch between front and back cameras
        $('#switchCameraBtn').click(function() {
            facingMode = facingMode === 'user' ? 'environment' : 'user';
            startCamera();
        });
        
        // Capture photo button
        $('#captureBtn').click(function() {
            takePhoto();
        });
        
        // Retake photo button
        $('#retakeBtn').click(function() {
            startCamera();
        });
        
        // Confirm attendance button (after photo capture)
        $('#confirmAttendanceBtn').click(function() {
            if (!photoTaken) {
                $('#statusText').text('Please take a photo first');
                return;
            }
            
            // Close the camera modal
            cameraModal.modal('hide');
            
            // Get current time
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour12: false });
            
            // Process the action (clock in or clock out)
            if (currentAction === 'clockIn') {
                processClockIn(timeString, photoDataUrl);
            } else {
                processClockOut(timeString, photoDataUrl);
            }
        });
        
        // Process Clock In
        function processClockIn(timeString, photoData) {
            $('#clockInTime').text(timeString);
            $('#attendanceStatus').removeClass('alert-light').addClass('alert-success');
            $('#attendanceStatus').html('<span class="status-indicator bg-success mr-2"></span>You are currently clocked in');
            
            // Enable clock out button and disable clock in
            $('#clockInBtn').prop('disabled', true);
            $('#clockOutBtn').prop('disabled', false);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Clocked In Successfully',
                html: `
                    <div class="text-left">
                        <p class="mb-1"><strong>Time:</strong> ${timeString}</p>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-map-marker-alt mr-1"></i> 
                            ${window.currentLocationAddress || 'Location recorded'}
                        </p>
                    </div>
                    <div class="mt-3">
                        <img src="${photoData}" class="img-fluid rounded" style="max-height: 150px;">
                    </div>
                `,
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'swal-minimalist'
                }
            });
            
            // Here you would typically send the data to your server
            // sendAttendanceData('clockIn', timeString, photoData, userCoordinates);
        }
        
        // Process Clock Out
        function processClockOut(timeString, photoData) {
            $('#clockOutTime').text(timeString);
            $('#attendanceStatus').removeClass('alert-success').addClass('alert-light');
            $('#attendanceStatus').html('<span class="status-indicator bg-secondary mr-2"></span>You have completed your shift today');
            
            // Disable both buttons
            $('#clockOutBtn').prop('disabled', true);
            $('#clockInBtn').prop('disabled', true);
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Clocked Out Successfully',
                html: `
                    <div class="text-left">
                        <p class="mb-1"><strong>Time:</strong> ${timeString}</p>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-map-marker-alt mr-1"></i> 
                            ${window.currentLocationAddress || 'Location recorded'}
                        </p>
                    </div>
                    <div class="mt-3">
                        <img src="${photoData}" class="img-fluid rounded" style="max-height: 150px;">
                    </div>
                `,
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'swal-minimalist'
                }
            });
            
            // Here you would typically send the data to your server
            // sendAttendanceData('clockOut', timeString, photoData, userCoordinates);
        }
        
        // Send attendance data to server (placeholder function)
        function sendAttendanceData(action, time, photoData, location) {
            // This would be your AJAX call to submit the data to the server
            console.log('Sending attendance data to server:', action, time, location);
            
            // Example AJAX call (commented out as it's just a demonstration)
            /*
            $.ajax({
                url: '/api/attendance',
                method: 'POST',
                data: {
                    action: action,
                    time: time,
                    photo: photoData,
                    location: location
                },
                success: function(response) {
                    console.log('Attendance recorded successfully', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error recording attendance:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to record attendance. Please try again.',
                        customClass: {
                            popup: 'swal-minimalist'
                        }
                    });
                }
            });
            */
        }
        
        // Intercept the original clock in/out buttons
        $('#clockInBtn').click(function(e) {
            e.preventDefault();
            
            // Check if we have location
            if (!userCoordinates) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Location Required',
                    text: 'Please allow location access to clock in',
                    confirmButtonText: 'Try Again',
                    customClass: {
                        popup: 'swal-minimalist'
                    }
                }).then(() => {
                    getUserLocation();
                });
                return;
            }
            
            // Open camera for clock in
            openCameraModal('clockIn');
        });
        
        $('#clockOutBtn').click(function(e) {
            e.preventDefault();
            
            // Check if we have location
            if (!userCoordinates) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Location Required',
                    text: 'Please allow location access to clock out',
                    confirmButtonText: 'Try Again',
                    customClass: {
                        popup: 'swal-minimalist'
                    }
                }).then(() => {
                    getUserLocation();
                });
                return;
            }
            
            // Open camera for clock out
            openCameraModal('clockOut');
        });
        
        // Add custom style for SweetAlert
        $('<style>.swal-minimalist{width:360px !important; padding:1.5rem !important;}</style>').appendTo('head');
    });
</script>
@endpush


