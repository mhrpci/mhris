@extends('layouts.app')

@section('content')
<!-- Add viewport meta tag to ensure proper scaling -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

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
        .card-body {
            padding: 1rem;
        }
        .location-text {
            max-width: 150px;
        }
        #current-time {
            font-size: 3rem;
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
    
    /* Camera modal styles */
    #camera-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        z-index: 9999;
    }
    
    .camera-container {
        position: relative;
        width: 100%;
        height: 100%;
        background: #000;
        overflow: hidden;
    }
    
    .camera-body {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    #camera-view {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        transition: opacity 0.3s ease;
    }
    
    .camera-controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 25px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 100%);
    }
    
    .camera-options {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 15px;
        z-index: 10;
        background: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);
        display: none;
    }
    
    .camera-controls-group {
        display: flex;
        gap: 25px;
        align-items: center;
    }
    
    .camera-option {
        color: white;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        font-size: 1.2rem;
        width: 48px;
        height: 48px;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0.85;
        transition: all 0.2s;
        position: relative;
        border-radius: 50%;
    }
    
    .action-banner {
        position: absolute;
        top: 70px;
        left: 0;
        right: 0;
        text-align: center;
        z-index: 10;
        pointer-events: none;
    }
    
    .action-text {
        display: inline-block;
        padding: 10px 24px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        border-radius: 30px;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    .switch-camera-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        font-size: 1.3rem;
        color: #fff;
        cursor: pointer;
        padding: 12px;
        border-radius: 50%;
        opacity: 0.9;
        transition: all 0.25s;
    }
    
    .gallery-btn-wrapper {
        display: none;
    }
    
    .capture-container {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .capture-btn {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: 4px solid rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        transition: all 0.2s;
    }
    
    .capture-btn::before {
        content: '';
        width: 62px;
        height: 62px;
        border-radius: 50%;
        background: white;
        border: 2px solid #f0f0f0;
    }
    
    .capture-btn:active {
        transform: scale(0.95);
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
    }
    
    .zoom-controls {
        display: none;
    }
    
    .timer-options {
        position: absolute;
        top: 70px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        border-radius: 14px;
        padding: 8px;
        display: none;
        z-index: 15;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    .filter-options {
        display: none;
    }
    
    .timer-countdown {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 8rem;
        font-weight: bold;
        z-index: 20;
        display: none;
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    
    .flash-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        opacity: 0;
        z-index: 15;
        pointer-events: none;
    }
    
    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        z-index: 15;
        opacity: 0.9;
        transition: all 0.25s;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .camera-transition {
        opacity: 0.1;
    }
    
    .clock-in-text {
        background-color: rgba(25, 135, 84, 0.8);
    }
    
    .clock-out-text {
        background-color: rgba(220, 53, 69, 0.8);
    }
    
    .info-sidebar {
        display: none;
    }
    
    @media (max-width: 576px) {
        .capture-btn {
            width: 65px;
            height: 65px;
        }
        .capture-btn::before {
            width: 52px;
            height: 52px;
        }
    }
    
    @media (max-width: 400px) {
        .capture-btn {
            width: 60px;
            height: 60px;
        }
        .capture-btn::before {
            width: 48px;
            height: 48px;
        }
        .switch-camera-btn {
            font-size: 1.1rem;
            padding: 8px;
        }
        .action-text {
            font-size: 1rem;
            padding: 6px 16px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Feature detection helper
    const hasFeature = {
        geolocation: 'geolocation' in navigator,
        mediaDevices: !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia),
        imageCapture: typeof ImageCapture !== 'undefined',
        canvas: !!document.createElement('canvas').getContext,
        touchEvents: 'ontouchstart' in window,
        orientation: 'orientation' in window || 'orientation' in screen
    };
    
    // Handle device orientation changes
    if (hasFeature.orientation) {
        window.addEventListener('orientationchange', function() {
            // Adjust UI for orientation change
            setTimeout(function() {
                const cameraFrame = document.querySelector('.camera-frame');
                if (cameraFrame) {
                    // Adjust frame size based on new orientation
                    if (window.innerWidth < 576) {
                        cameraFrame.style.width = '160px';
                        cameraFrame.style.height = '160px';
                    } else if (window.innerWidth < 768) {
                        cameraFrame.style.width = '180px';
                        cameraFrame.style.height = '180px';
                    } else {
                        cameraFrame.style.width = '220px';
                        cameraFrame.style.height = '220px';
                    }
                }
                
                // Re-center content
                if (document.querySelector('.camera-controls')) {
                    document.querySelector('.camera-controls').style.display = 'none';
                    setTimeout(function() {
                        document.querySelector('.camera-controls').style.display = 'flex';
                    }, 300);
                }
            }, 300); // Small delay to allow the browser to complete the orientation change
        });
    }
    
    // Log available features for debugging
    console.log('Device features:', hasFeature);
    
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

    // Attendance button functionality
    const attendanceBtn = document.getElementById('attendance-btn');
    const lastAction = document.getElementById('last-action');
    let isClockIn = true;

    // Camera variables - enhanced with device capability checks
    let stream = null;
    let cameraFacingMode = 'environment'; // Start with rear camera
    let actionType = '';
    let imageCapture = null;
    let hasFlash = false;
    let flashOn = false;
    let timerValue = 0;
    let zoomValue = 1;
    let hdrActive = false;
    let activeFilter = 'normal';
    let timerInterval = null;
    let cameraAvailable = hasFeature.mediaDevices;
    let cameraInitialized = false;
    let availableCameras = [];
    
    // Create camera modal element
    const cameraModal = document.createElement('div');
    cameraModal.id = 'camera-modal';
    cameraModal.innerHTML = `
        <div class="camera-container">
            <div class="action-banner">
                <div class="action-text" id="action-text">CLOCK IN</div>
            </div>
            <div class="timer-options" id="timer-options">
                <button class="timer-option" data-timer="0">Off</button>
                <button class="timer-option" data-timer="3">3s</button>
                <button class="timer-option" data-timer="5">5s</button>
                <button class="timer-option" data-timer="10">10s</button>
            </div>
            <div class="camera-body">
                <video id="camera-view" autoplay playsinline class="filter-normal"></video>
                <div class="timer-countdown" id="timer-countdown">3</div>
                <div class="flash-animation" id="flash-animation"></div>
            </div>
            <div class="camera-controls">
                <div class="capture-container">
                    <div class="capture-btn" id="capture-photo"></div>
                </div>
                <button class="switch-camera-btn" id="switch-camera">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <button class="close-btn" id="close-camera">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(cameraModal);
    
    // Get elements
    const closeCamera = document.getElementById('close-camera');
    const switchCamera = document.getElementById('switch-camera');
    const capturePhoto = document.getElementById('capture-photo');
    const cameraView = document.getElementById('camera-view');
    const flashToggle = document.getElementById('flash-toggle');
    const hdrToggle = document.getElementById('hdr-toggle');
    const timerToggle = document.getElementById('timer-toggle');
    const filterToggle = document.getElementById('filter-toggle');
    const timerOptions = document.getElementById('timer-options');
    const timerOptionButtons = document.querySelectorAll('.timer-option');
    const zoomIndicator = document.getElementById('zoom-indicator');
    const zoomSlider = document.getElementById('zoom-slider');
    const zoomSliderContainer = document.getElementById('zoom-slider-container');
    const timerCountdown = document.getElementById('timer-countdown');
    const flashAnimation = document.getElementById('flash-animation');
    const filterOptionsContainer = document.getElementById('filter-options');
    const filterOptions = document.querySelectorAll('.filter-option');
    const galleryInput = document.getElementById('gallery-input');
    const captureLabel = document.getElementById('capture-label');
    const actionText = document.getElementById('action-text');
    
    // Get references to the new elements
    const clockStatus = document.getElementById('clock-status');
    const clockTime = document.getElementById('clock-time');
    const dateDisplay = document.getElementById('date-display');
    const locationDisplay = document.getElementById('location-display');
    const userName = document.getElementById('user-name');
    const userCompany = document.getElementById('user-company');
    const userPosition = document.getElementById('user-position');
    const accentLine = document.getElementById('accent-line');
    
    // Function to open camera with better device compatibility
    async function openCamera(facing) {
        try {
            if (stream) {
                // Add transition effect
                cameraView.classList.add('camera-transition');
                
                // Wait for transition to complete
                await new Promise(resolve => setTimeout(resolve, 300));
                
                stopCamera(false);
            }
            
            // Define constraints for highest quality video
            const constraints = {
                video: {
                    facingMode: facing,
                    width: { ideal: 3840 }, // 4K UHD resolution
                    height: { ideal: 2160 },
                    frameRate: { ideal: 30 }
                },
                audio: false
            };
            
            // Try to get stream with highest quality
            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
            } catch (e) {
                console.warn('Could not access camera with 4K resolution, trying with HD', e);
                // Fall back to Full HD
                const hdConstraints = {
                    video: {
                        facingMode: facing,
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    },
                    audio: false
                };
                try {
                    stream = await navigator.mediaDevices.getUserMedia(hdConstraints);
                } catch (e2) {
                    console.warn('Could not access camera with HD resolution, trying default', e2);
                    // Default fallback
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: facing },
                        audio: false
                    });
                }
            }
            
            cameraView.srcObject = stream;
            
            // Wait for video to be ready
            await new Promise(resolve => {
                cameraView.onloadedmetadata = () => {
                    cameraView.play().then(resolve).catch(resolve);
                };
                // Fallback if onloadedmetadata doesn't fire
                setTimeout(resolve, 1000);
            });
            
            // Create ImageCapture object if supported
            const videoTrack = stream.getVideoTracks()[0];
            if (hasFeature.imageCapture) {
                try {
                    imageCapture = new ImageCapture(videoTrack);
                    
                    // Set highest image capture quality if possible
                    if (imageCapture.getPhotoCapabilities) {
                        const capabilities = await imageCapture.getPhotoCapabilities();
                        if (capabilities) {
                            // Use highest available quality for photo capture
                            console.log('Photo capabilities:', capabilities);
                        }
                    }
                } catch (e) {
                    console.warn('ImageCapture API failed:', e);
                }
            }
            
            // Check capabilities with error handling for different devices
            try {
                if ('getCapabilities' in videoTrack) {
                    const capabilities = videoTrack.getCapabilities();
                    console.log('Camera capabilities:', capabilities);
                    
                    // Check flash support
                    hasFlash = !!capabilities.torch;
                    
                    // Log the actual camera resolution being used
                    const settings = videoTrack.getSettings();
                    console.log('Camera actual settings:', settings);
                    
                    if (settings.width && settings.height) {
                        console.log(`Camera resolution: ${settings.width}x${settings.height}`);
                    }
                } 
            } catch (e) {
                console.warn('Device capabilities check failed:', e);
            }
            
            // Apply mirroring if using front camera
            if (facing === 'user') {
                cameraView.style.transform = 'scaleX(-1)';
            } else {
                cameraView.style.transform = 'scaleX(1)';
            }
            
            // Check if we can enumerate devices to show camera switch button only when multiple cameras
            if (navigator.mediaDevices.enumerateDevices) {
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    availableCameras = devices.filter(device => device.kind === 'videoinput');
                    document.getElementById('switch-camera').style.display = availableCameras.length > 1 ? 'block' : 'none';
                } catch (e) {
                    console.warn('Could not enumerate devices:', e);
                    document.getElementById('switch-camera').style.display = 'block'; // Show by default
                }
            }
            
            // Force fullscreen mode
            try {
                const requestFullscreen = document.documentElement.requestFullscreen || 
                                      document.documentElement.webkitRequestFullscreen ||
                                      document.documentElement.mozRequestFullScreen ||
                                      document.documentElement.msRequestFullscreen;
                
                if (requestFullscreen) {
                    await requestFullscreen.call(document.documentElement);
                }
            } catch (e) {
                console.warn('Fullscreen request failed:', e);
            }
            
            cameraModal.style.display = 'block';
            
            // Hide scrollbars on body and prevent scrolling on touch devices
            document.body.style.overflow = 'hidden';
            if (hasFeature.touchEvents) {
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            }
            
            // Remove transition class after a short delay
            setTimeout(() => {
                cameraView.classList.remove('camera-transition');
            }, 50);
            
            cameraInitialized = true;
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            // Show more helpful error message based on the error
            let errorMessage = 'Unable to access camera. ';
            
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                errorMessage += 'Please ensure you have granted camera permissions in your browser settings.';
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                errorMessage += 'No camera found on this device.';
            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                errorMessage += 'Camera may be in use by another application.';
            } else if (error.name === 'OverconstrainedError') {
                errorMessage += 'Camera constraints not supported on this device.';
            } else {
                errorMessage += error.message || 'Please try again later.';
            }
            
            alert(errorMessage);
            
            // Proceed with attendance without camera if error
            processAttendance();
        }
    }
    
    // Function to stop camera with enhanced cleanup
    function stopCamera(hideModal = true) {
        if (stream) {
            stream.getTracks().forEach(track => {
                try {
                    track.stop();
                } catch (e) {
                    console.warn('Error stopping track:', e);
                }
            });
            stream = null;
            imageCapture = null;
        }
        
        if (hideModal) {
            cameraModal.style.display = 'none';
            
            // Restore scrollbars and body positioning
            document.body.style.overflow = '';
            if (hasFeature.touchEvents) {
                document.body.style.position = '';
                document.body.style.width = '';
            }
            
            // Exit fullscreen if we're in it
            if (document.fullscreenElement || 
                document.webkitFullscreenElement || 
                document.mozFullScreenElement || 
                document.msFullscreenElement) {
                try {
                    exitFullscreen();
                } catch (e) {
                    console.warn('Error exiting fullscreen:', e);
                }
            }
        }
        
        // Clear any timer
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            timerCountdown.style.display = 'none';
        }
    }
    
    // Toggle flash - improved for better device compatibility
    flashToggle.addEventListener('click', async function() {
        if (!hasFlash || !stream) return;
        
        try {
            const track = stream.getVideoTracks()[0];
            flashOn = !flashOn;
            
            // Different methods to control flash/torch on different devices
            if ('applyConstraints' in track) {
                try {
                    await track.applyConstraints({
                        advanced: [{ torch: flashOn }]
                    });
                } catch (e) {
                    // Some Android devices need a different approach
                    if (track.getSettings && track.getSettings().torch !== undefined) {
                        await track.applyConstraints({ torch: flashOn });
                    } else {
                        throw e;
                    }
                }
            } else {
                // Fallback for older browsers/devices
                console.warn('Track applyConstraints not supported');
                // Cannot control flash
                hasFlash = false;
                flashToggle.style.display = 'none';
                return;
            }
            
            if (flashOn) {
                flashToggle.classList.add('active');
            } else {
                flashToggle.classList.remove('active');
            }
        } catch (e) {
            console.error('Error toggling flash:', e);
            alert('Unable to control flash on this device.');
            hasFlash = false;
            flashToggle.style.display = 'none';
        }
    });
    
    // Zoom handling enhanced for better cross-device compatibility
    zoomSlider.addEventListener('input', async function() {
        if (!stream) return;
        
        try {
            zoomValue = parseFloat(this.value);
            zoomIndicator.textContent = `${zoomValue.toFixed(1)}×`;
            
            // Check if we're using the CSS fallback mode
            if (cameraView.hasAttribute('data-zoom-fallback')) {
                const scale = zoomValue;
                cameraView.style.transform = `${cameraFacingMode === 'user' ? 'scaleX(-1)' : 'scaleX(1)'} scale(${scale})`;
                return;
            }
            
            // Use the standard API if available
            const track = stream.getVideoTracks()[0];
            if ('applyConstraints' in track) {
                try {
                    await track.applyConstraints({
                        advanced: [{ zoom: zoomValue }]
                    });
                } catch (e) {
                    // Alternative approach for some devices
                    await track.applyConstraints({ zoom: zoomValue });
                }
            } else {
                throw new Error('Zoom not supported via constraints');
            }
        } catch (e) {
            console.warn('Zoom not supported on this device:', e);
            // If zoom fails, try to create a fallback digital zoom using CSS transform
            try {
                cameraView.setAttribute('data-zoom-fallback', 'true');
                const scale = zoomValue;
                cameraView.style.transform = `${cameraFacingMode === 'user' ? 'scaleX(-1)' : 'scaleX(1)'} scale(${scale})`;
            } catch (cssError) {
                console.warn('CSS fallback zoom also failed:', cssError);
                // Hide zoom controls if they don't work
                document.getElementById('zoom-indicator').style.display = 'none';
                document.getElementById('zoom-slider-container').style.display = 'none';
            }
        }
    });
    
    // Take photo function with enhanced fallbacks for different devices
    function takePhoto() {
        if (!stream) return;
        
        // Show flash animation
        showFlashAnimation();
        
        // Try ImageCapture API first if available for highest quality
        if (imageCapture && hasFeature.imageCapture) {
            // Set photo options for max quality
            const photoSettings = {
                imageWidth: 3840,
                imageHeight: 2160,
                fillLightMode: hasFlash ? "flash" : "off"
            };
            
            imageCapture.takePhoto(photoSettings)
                .then(blob => {
                    console.log('Photo captured with ImageCapture API:', blob);
                    stopCamera();
                    processAttendance();
                })
                .catch(error => {
                    console.warn('ImageCapture API failed, falling back to canvas:', error);
                    captureWithCanvas();
                });
        } else {
            // Fallback to canvas capture
            captureWithCanvas();
        }
    }
    
    // Canvas capture as fallback method - optimized for quality
    function captureWithCanvas() {
        if (!stream || !hasFeature.canvas) {
            console.error('Canvas capture not supported');
            stopCamera();
            processAttendance();
            return;
        }
        
        try {
            const canvas = document.createElement('canvas');
            const video = document.getElementById('camera-view');
            
            // Use actual video dimensions for highest quality
            canvas.width = video.videoWidth || 1920;
            canvas.height = video.videoHeight || 1080;
            
            const ctx = canvas.getContext('2d');
            
            // Clear canvas first
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Apply mirroring if needed
            ctx.save();
            if (cameraFacingMode === 'user') {
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }
            
            // Draw video frame to canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Restore context
            ctx.restore();
            
            // Export as high quality blob
            canvas.toBlob(blob => {
                console.log('Photo captured with canvas:', blob);
                stopCamera();
                processAttendance();
            }, 'image/jpeg', 0.95); // High quality JPEG
            
        } catch (e) {
            console.error('Canvas capture failed:', e);
            stopCamera();
            processAttendance();
        }
    }
    
    // Location tracking with enhanced error handling and device compatibility
    const locationStatus = document.getElementById('location-status');
    const currentLocation = document.getElementById('current-location');
    const coordinatesInfo = document.getElementById('coordinates-info');
    const coordinates = document.getElementById('coordinates');

    if (hasFeature.geolocation) {
        try {
            // First try to get a quick location from cache
            navigator.geolocation.getCurrentPosition(
                position => {
                    // Display coordinates immediately
                    coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                    coordinatesInfo.classList.remove('d-none');
                    
                    // Set a temporary location while we get the address
                    currentLocation.textContent = 'Getting location address...';
                },
                error => {
                    // Just log the error, watchPosition will handle the UI updates
                    console.warn('Initial position check failed:', error);
                },
                { maximumAge: 60000, timeout: 2000, enableHighAccuracy: false }
            );
            
            // Start watching position with high accuracy
            navigator.geolocation.watchPosition(
                function(position) {
                    // Get address from coordinates using reverse geocoding
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}&zoom=18&addressdetails=1`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            currentLocation.textContent = data.display_name;
                            coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                            coordinatesInfo.classList.remove('d-none');
                            locationStatus.classList.add('d-none');
                        })
                        .catch(error => {
                            console.error('Geocoding error:', error);
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
                    
                    // More user-friendly error messages
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location permissions in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location information unavailable. Check your device GPS settings.';
                            break;
                        case error.TIMEOUT:
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location request timed out. Please try again.';
                            break;
                        default:
                            locationStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>Location error: ${error.message || 'Unknown error'}`;
                    }
                    
                    currentLocation.textContent = 'Location access required';
                    coordinatesInfo.classList.add('d-none');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000, // Increased timeout for slower connections
                    maximumAge: 0
                }
            );
        } catch (e) {
            console.error('Geolocation error:', e);
            locationStatus.classList.remove('d-none');
            locationStatus.className = 'alert alert-danger';
            locationStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>Geolocation error: ${e.message || 'Unknown error'}`;
            currentLocation.textContent = 'Location access failed';
            coordinatesInfo.classList.add('d-none');
        }
    } else {
        locationStatus.classList.remove('d-none');
        locationStatus.className = 'alert alert-danger';
        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Geolocation is not supported by your browser or device.';
        currentLocation.textContent = 'Location services not supported';
        coordinatesInfo.classList.add('d-none');
    }
    
    // Handle fullscreen properly for different browsers
    function requestFullscreen(element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) { /* Safari */
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) { /* IE11 */
            element.msRequestFullscreen();
        } else if (element.mozRequestFullScreen) { /* Firefox */
            element.mozRequestFullScreen();
        }
    }
    
    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) { /* Firefox */
            document.mozCancelFullScreen();
        }
    }
    
    // Improved function for stopping camera
    function stopCamera(hideModal = true) {
        if (stream) {
            stream.getTracks().forEach(track => {
                try {
                    track.stop();
                } catch (e) {
                    console.warn('Error stopping track:', e);
                }
            });
            stream = null;
            imageCapture = null;
        }
        
        if (hideModal) {
            cameraModal.style.display = 'none';
            
            // Restore scrollbars and prevent scrolling issues on mobile
            document.body.style.overflow = '';
            if (hasFeature.touchEvents) {
                document.body.style.position = '';
                document.body.style.width = '';
            }
            
            // Exit fullscreen if we're in it
            if (document.fullscreenElement || 
                document.webkitFullscreenElement || 
                document.mozFullScreenElement || 
                document.msFullscreenElement) {
                try {
                    exitFullscreen();
                } catch (e) {
                    console.warn('Error exiting fullscreen:', e);
                }
            }
        }
        
        // Clear any timer
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            timerCountdown.style.display = 'none';
        }
    }
    
    // Improved error handling for window events
    window.addEventListener('error', function(e) {
        console.error('Global error caught:', e.message, e);
        // Prevent errors from breaking the UI
        return true;
    });
    
    // Handle window resize to adjust UI for different device sizes
    window.addEventListener('resize', function() {
        // Force fullscreen mode if camera is active
        if (cameraInitialized && stream) {
            try {
                if (!document.fullscreenElement) {
                    const requestFullscreen = document.documentElement.requestFullscreen || 
                        document.documentElement.webkitRequestFullscreen ||
                        document.documentElement.mozRequestFullScreen ||
                        document.documentElement.msRequestFullscreen;
                    
                    if (requestFullscreen) {
                        requestFullscreen.call(document.documentElement);
                    }
                }
            } catch (e) {
                console.warn('Fullscreen request failed on resize:', e);
            }
        }
    });
    
    // HDR toggle
    hdrToggle.addEventListener('click', function() {
        hdrActive = !hdrActive;
        if (hdrActive) {
            hdrToggle.classList.add('active');
        } else {
            hdrToggle.classList.remove('active');
        }
    });
    
    // Timer toggle
    timerToggle.addEventListener('click', function() {
        const isVisible = timerOptions.style.display === 'block';
        timerOptions.style.display = isVisible ? 'none' : 'block';
        
        if (!isVisible) {
            // Set default timer option active
            timerOptionButtons.forEach(btn => {
                if (parseInt(btn.dataset.timer) === timerValue) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    });
    
    // Timer options
    timerOptionButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            timerValue = parseInt(this.dataset.timer);
            
            // Update UI
            timerOptionButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Hide options after selection
            timerOptions.style.display = 'none';
            
            // Update timer toggle button
            if (timerValue > 0) {
                timerToggle.classList.add('active');
            } else {
                timerToggle.classList.remove('active');
            }
        });
    });
    
    // Zoom indicator click to show/hide slider
    zoomIndicator.addEventListener('click', function() {
        const isVisible = zoomSliderContainer.style.display === 'block';
        zoomSliderContainer.style.display = isVisible ? 'none' : 'block';
    });
    
    // Filter toggle
    filterToggle.addEventListener('click', function() {
        const isVisible = filterOptionsContainer.style.display === 'flex';
        
        if (isVisible) {
            // Animate hiding
            filterOptionsContainer.style.opacity = '0';
            setTimeout(() => {
                filterOptionsContainer.style.display = 'none';
                filterOptionsContainer.style.opacity = '1';
            }, 300);
        } else {
            // Animate showing
            filterOptionsContainer.style.opacity = '0';
            filterOptionsContainer.style.display = 'flex';
            setTimeout(() => {
                filterOptionsContainer.style.opacity = '1';
            }, 10);
        }
    });
    
    // Filter option selection
    filterOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update UI
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Apply filter to video
            cameraView.className = '';
            cameraView.classList.add(`filter-${filter}`);
            
            activeFilter = filter;
        });
    });
    
    // Show flash animation
    function showFlashAnimation() {
        flashAnimation.style.opacity = '1';
        setTimeout(() => {
            flashAnimation.style.opacity = '0';
        }, 50);
    }
    
    // Start timer and take photo when timer completes
    function startTimer() {
        if (timerValue <= 0) {
            takePhoto();
            return;
        }
        
        let timeLeft = timerValue;
        timerCountdown.textContent = timeLeft;
        timerCountdown.style.display = 'flex';
        
        timerInterval = setInterval(() => {
            timeLeft--;
            timerCountdown.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerInterval = null;
                timerCountdown.style.display = 'none';
                takePhoto();
            }
        }, 1000);
    }
    
    // Switch camera
    switchCamera.addEventListener('click', function() {
        cameraFacingMode = cameraFacingMode === 'user' ? 'environment' : 'user';
        openCamera(cameraFacingMode);
    });
    
    // Close camera
    closeCamera.addEventListener('click', function() {
        stopCamera();
    });
    
    // Capture photo button
    capturePhoto.addEventListener('click', function() {
        startTimer();
    });
    
    // Gallery input change
    galleryInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            // Process the selected image
            stopCamera();
            processAttendance();
        }
    });
    
    // Main attendance button (starts the process)
    attendanceBtn.addEventListener('click', function() {
        actionType = isClockIn ? 'clock-in' : 'clock-out';
        
        // Update action text and styles
        if (actionText) {
            actionText.textContent = isClockIn ? 'CLOCK IN' : 'CLOCK OUT';
            actionText.className = 'action-text';
            actionText.classList.add(isClockIn ? 'clock-in-text' : 'clock-out-text');
        }
        
        // Check if camera is available and initialize
        if (cameraAvailable) {
            openCamera(cameraFacingMode);
        } else {
            // Fallback if no camera
            processAttendance();
        }
    });
    
    // Process attendance (after photo is taken or if no camera)
    function processAttendance() {
        // Get current time
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        
        // Get location text (or fallback)
        const locationText = currentLocation.textContent || 'Location unavailable';
        
        // Update UI based on action type (clock in or out)
        if (isClockIn) {
            // Update the button to allow clocking out next
            attendanceBtn.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Clock Out';
            attendanceBtn.classList.remove('btn-primary');
            attendanceBtn.classList.add('btn-danger');
            
            // Update last action
            lastAction.textContent = `Last action: Clocked in at ${timeString}`;
            
            // Add to activity log
            updateActivityLog(timeString, locationText, '', '', 'Working');
            
        } else {
            // Update the button to allow clocking in again
            attendanceBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Clock In';
            attendanceBtn.classList.remove('btn-danger');
            attendanceBtn.classList.add('btn-primary');
            
            // Update last action
            lastAction.textContent = `Last action: Clocked out at ${timeString}`;
            
            // Update the activity log with clock out time
            updateActivityLog(null, null, timeString, locationText, 'Completed');
        }
        
        // Toggle state for next action
        isClockIn = !isClockIn;
        
        // Send data to server if needed (Ajax call)
        const attendanceData = {
            action: actionType,
            timestamp: now.toISOString(),
            location: locationText,
            coordinates: coordinates.textContent || ''
        };
        
        console.log('Attendance data recorded:', attendanceData);
        
        // Optional: Send data to server
        /*
        fetch('/api/attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(attendanceData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
        */
    }
    
    // Update activity log with new entry or update existing
    function updateActivityLog(clockInTime, clockInLocation, clockOutTime, clockOutLocation, status) {
        const activityLog = document.getElementById('activity-log');
        
        // Clear "no activity" message if it exists
        if (activityLog.querySelector('td[colspan="5"]')) {
            activityLog.innerHTML = '';
        }
        
        if (isClockIn) {
            // Create a new row for clock in
            const row = document.createElement('tr');
            row.id = 'current-activity';
            row.className = 'text-center';
            row.innerHTML = `
                <td class="activity-time">${clockInTime}</td>
                <td><div class="location-text">${clockInLocation}</div></td>
                <td>-</td>
                <td>-</td>
                <td><span class="badge bg-success status-badge">${status}</span></td>
            `;
            activityLog.appendChild(row);
        } else {
            // Update existing row with clock out information
            const row = document.getElementById('current-activity');
            if (row) {
                row.cells[2].textContent = clockOutTime;
                row.cells[3].innerHTML = `<div class="location-text">${clockOutLocation}</div>`;
                row.cells[4].innerHTML = `<span class="badge bg-info status-badge">${status}</span>`;
                row.removeAttribute('id');
            }
        }
    }

    // Add after camera modal is created
    const cameraBody = document.querySelector('.camera-body');

    // Add pinch-to-zoom gesture support for mobile devices
    let initialDistance = 0;
    let currentZoom = 1.0;

    // Prevent default touch behavior to avoid page zooming/scrolling
    cameraBody.addEventListener('touchmove', function(e) {
        if (e.touches.length >= 2) {
            e.preventDefault();
        }
    }, { passive: false });

    // Detect pinch gesture start
    cameraBody.addEventListener('touchstart', function(e) {
        if (e.touches.length >= 2) {
            initialDistance = getDistance(e.touches[0], e.touches[1]);
            currentZoom = parseFloat(zoomSlider.value);
        }
    });

    // Handle pinch gesture
    cameraBody.addEventListener('touchmove', function(e) {
        if (e.touches.length >= 2) {
            const currentDistance = getDistance(e.touches[0], e.touches[1]);
            const deltaDistance = currentDistance - initialDistance;
            
            // Calculate new zoom value based on pinch distance
            let newZoom = currentZoom + (deltaDistance / 200);
            
            // Constrain to min/max
            newZoom = Math.min(Math.max(newZoom, 1.0), 10.0);
            
            // Update zoom slider and apply zoom
            zoomSlider.value = newZoom;
            
            // Trigger input event to apply zoom
            const event = new Event('input', { bubbles: true });
            zoomSlider.dispatchEvent(event);
        }
    });

    // Helper to calculate distance between two touch points
    function getDistance(touch1, touch2) {
        const x = touch1.clientX - touch2.clientX;
        const y = touch1.clientY - touch2.clientY;
        return Math.sqrt(x * x + y * y);
    }

    // Double tap to toggle between 1.0 and 3.0 zoom
    let lastTap = 0;
    cameraBody.addEventListener('touchend', function(e) {
        const currentTime = new Date().getTime();
        const tapLength = currentTime - lastTap;
        
        if (tapLength < 300 && tapLength > 0 && e.touches.length === 0) {
            // Double tap detected
            if (parseFloat(zoomSlider.value) > 1.1) {
                // If already zoomed, reset to 1.0
                zoomSlider.value = 1.0;
            } else {
                // Zoom to 3.0
                zoomSlider.value = 3.0;
            }
            
            // Trigger input event to apply zoom
            const event = new Event('input', { bubbles: true });
            zoomSlider.dispatchEvent(event);
        }
        
        lastTap = currentTime;
    });

    // After cameraModal creation, add a quality indicator
    const qualityIndicator = document.createElement('div');
    qualityIndicator.className = 'camera-quality-indicator';
    qualityIndicator.innerHTML = '<i class="fas fa-check-circle"></i> HD Mode Enabled';
    cameraModal.querySelector('.camera-container').appendChild(qualityIndicator);

    // Get reference to HD toggle button
    const hdToggle = document.getElementById('hd-toggle');

    // HD toggle functionality
    hdToggle.addEventListener('click', async function() {
        const isActive = hdToggle.classList.contains('active');
        
        if (isActive) {
            // Already in HD mode, switch to standard
            hdToggle.classList.remove('active');
            qualityIndicator.innerHTML = '<i class="fas fa-circle" style="color: #ffcc00;"></i> Standard Mode';
            
            // Re-initialize camera with lower resolution
            if (stream) {
                const facing = cameraFacingMode;
                await openCameraWithResolution(facing, 640, 480);
            }
        } else {
            // Switch to HD mode
            hdToggle.classList.add('active');
            qualityIndicator.innerHTML = '<i class="fas fa-check-circle"></i> HD Mode Enabled';
            
            // Re-initialize camera with HD resolution
            if (stream) {
                const facing = cameraFacingMode;
                await openCameraWithResolution(facing, 1920, 1080);
            }
        }
    });

    // Helper function to open camera with specific resolution - always use high quality
    async function openCameraWithResolution(facing, width, height) {
        try {
            if (stream) {
                // Add transition effect
                cameraView.classList.add('camera-transition');
                
                // Wait for transition to complete
                await new Promise(resolve => setTimeout(resolve, 300));
                
                stopCamera(false);
            }
            
            // Define constraints with specified resolution
            const constraints = {
                video: {
                    facingMode: facing,
                    width: { ideal: width },
                    height: { ideal: height }
                },
                audio: false
            };
            
            // Try to get stream with specified facing mode
            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
            } catch (e) {
                console.warn('Could not access specific camera, trying with default', e);
                constraints.video = { 
                    width: { ideal: width },
                    height: { ideal: height }
                };
                stream = await navigator.mediaDevices.getUserMedia(constraints);
            }
            
            cameraView.srcObject = stream;
            
            // Apply settings as before
            // Additional initialization code...
            
            // Apply mirroring if using front camera
            if (facing === 'user') {
                cameraView.style.transform = 'scaleX(-1)';
            } else {
                cameraView.style.transform = 'scaleX(1)';
            }
            
            // Remove transition class after a short delay
            setTimeout(() => {
                cameraView.classList.remove('camera-transition');
            }, 50);
            
        } catch (error) {
            console.error('Error accessing camera with specified resolution:', error);
            // Fallback to regular open camera
            openCamera(facing);
        }
    }
});
</script>
@endpush
@endsection
