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
    
    .camera-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
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
        transition: transform 0.3s ease;
    }
    
    .camera-frame {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 220px;
        height: 220px;
        border: 2px solid rgba(255, 215, 0, 0.8);
        box-sizing: border-box;
        z-index: 5;
        pointer-events: none;
    }
    
    .camera-frame::before,
    .camera-frame::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-color: rgba(255, 215, 0, 0.8);
        border-style: solid;
    }
    
    /* Top left corner */
    .camera-frame::before {
        top: -2px;
        left: -2px;
        border-width: 2px 0 0 2px;
    }
    
    /* Bottom right corner */
    .camera-frame::after {
        bottom: -2px;
        right: -2px;
        border-width: 0 2px 2px 0;
    }
    
    .camera-controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
    }
    
    .camera-options {
        display: flex;
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        justify-content: space-between;
        z-index: 10;
    }
    
    .camera-option {
        color: white;
        background: none;
        border: none;
        font-size: 1.2rem;
        padding: 8px;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .camera-option.active {
        color: #ffcc00;
        opacity: 1;
    }
    
    .camera-option:hover {
        opacity: 1;
    }
    
    .switch-camera-btn {
        background: none;
        border: none;
        font-size: 1.3rem;
        color: #fff;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        opacity: 0.8;
        transition: opacity 0.2s, transform 0.3s;
    }
    
    .switch-camera-btn:hover {
        opacity: 1;
    }
    
    .switch-camera-btn.rotating {
        transform: rotate(180deg);
    }
    
    .gallery-btn {
        width: 40px;
        height: 40px;
        border-radius: 5px;
        background-color: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
    }
    
    .gallery-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .capture-btn {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: white;
        border: 4px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }
    
    .capture-btn::before {
        content: '';
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: white;
        border: 2px solid #ddd;
    }
    
    .capture-btn:active {
        transform: scale(0.95);
    }
    
    .zoom-indicator {
        position: absolute;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        background: rgba(0, 0, 0, 0.4);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        z-index: 5;
    }
    
    .zoom-controls {
        position: absolute;
        bottom: 130px;
        left: 50%;
        transform: translateX(-50%);
        width: 200px;
        z-index: 5;
        display: flex;
        flex-direction: column;
    }
    
    .zoom-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.3);
        outline: none;
    }
    
    .zoom-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: white;
        cursor: pointer;
    }
    
    .timer-countdown {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 10rem;
        color: white;
        z-index: 15;
        display: none;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
    
    .flash-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        opacity: 0;
        z-index: 12;
    }
    
    .cancel-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 8px;
        opacity: 0.8;
        transition: opacity 0.2s;
        z-index: 20;
    }
    
    .cancel-btn:hover {
        opacity: 1;
    }
    
    .hidden-file-input {
        display: none;
    }
    
    @media (max-width: 768px) {
        .camera-container {
            width: 100%;
            height: 100%;
        }
        
        .camera-frame {
            width: 180px;
            height: 180px;
        }
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

    // Attendance button functionality
    const attendanceBtn = document.getElementById('attendance-btn');
    const lastAction = document.getElementById('last-action');
    let isClockIn = true;

    // Camera variables
    let stream = null;
    let cameraFacingMode = 'environment'; // Start with rear camera
    let actionType = '';
    let zoomLevel = 1;
    let flashMode = 'off';
    let hdrMode = false;
    let timerSeconds = 0;
    let activeFilter = 'none';
    let cameraSwitching = false;
    let mediaRecorder = null;
    
    // Create camera modal element
    const cameraModal = document.createElement('div');
    cameraModal.id = 'camera-modal';
    cameraModal.innerHTML = `
        <div class="camera-container">
            <div class="camera-options">
                <button class="camera-option" id="flash-toggle">
                    <i class="fas fa-bolt"></i>
                </button>
                <button class="camera-option" id="hdr-toggle">
                    HDR
                </button>
                <button class="camera-option" id="timer-toggle">
                    <i class="fas fa-clock"></i>
                </button>
                <button class="camera-option" id="filter-toggle">
                    <i class="fas fa-circle"></i>
                </button>
            </div>
            <div class="camera-body">
                <video id="camera-view" autoplay playsinline></video>
                <div class="camera-frame"></div>
                <div class="zoom-indicator">1×</div>
                <div class="zoom-controls">
                    <input type="range" min="1" max="5" step="0.1" value="1" class="zoom-slider" id="zoom-slider">
                </div>
                <div class="timer-countdown" id="timer-countdown">3</div>
                <div class="flash-overlay" id="flash-overlay"></div>
            </div>
            <div class="camera-controls">
                <div class="gallery-btn" id="gallery-btn">
                    <i class="fas fa-images"></i>
                </div>
                <div class="capture-btn" id="capture-photo"></div>
                <button class="switch-camera-btn" id="switch-camera">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <button class="cancel-btn" id="close-camera">
                <i class="fas fa-times"></i>
            </button>
            <input type="file" accept="image/*" class="hidden-file-input" id="gallery-file-input">
        </div>
    `;
    document.body.appendChild(cameraModal);
    
    // Get elements
    const closeCamera = document.getElementById('close-camera');
    const switchCamera = document.getElementById('switch-camera');
    const capturePhoto = document.getElementById('capture-photo');
    const cameraView = document.getElementById('camera-view');
    const galleryBtn = document.getElementById('gallery-btn');
    const zoomIndicator = document.querySelector('.zoom-indicator');
    const zoomSlider = document.getElementById('zoom-slider');
    const flashToggle = document.getElementById('flash-toggle');
    const hdrToggle = document.getElementById('hdr-toggle');
    const timerToggle = document.getElementById('timer-toggle');
    const filterToggle = document.getElementById('filter-toggle');
    const timerCountdown = document.getElementById('timer-countdown');
    const flashOverlay = document.getElementById('flash-overlay');
    const galleryFileInput = document.getElementById('gallery-file-input');
    
    // Function to open camera
    async function openCamera(facing) {
        try {
            if (stream && !cameraSwitching) {
                stopCamera();
            }
            
            if (cameraSwitching && stream) {
                // Stop current stream before switching
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            const constraints = {
                video: {
                    facingMode: facing,
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                },
                audio: false
            };
            
            // Add loading indicator
            cameraView.style.opacity = '0.5';
            switchCamera.classList.add('rotating');
            cameraSwitching = true;
            
            stream = await navigator.mediaDevices.getUserMedia(constraints);
            cameraView.srcObject = stream;
            
            // Get camera capabilities for features like zoom and torch
            const videoTrack = stream.getVideoTracks()[0];
            const capabilities = videoTrack.getCapabilities();
            
            // Check if zoom is supported
            if (capabilities.zoom) {
                zoomSlider.min = capabilities.zoom.min;
                zoomSlider.max = capabilities.zoom.max;
                zoomSlider.step = (capabilities.zoom.max - capabilities.zoom.min) / 20;
                zoomSlider.value = zoomLevel;
                document.querySelector('.zoom-controls').style.display = 'flex';
            } else {
                document.querySelector('.zoom-controls').style.display = 'none';
            }
            
            // Check if torch/flash is supported
            if (capabilities.torch) {
                flashToggle.style.display = 'block';
                updateFlashMode();
            } else {
                flashToggle.style.display = 'none';
            }
            
            // Apply mirroring if using front camera
            if (facing === 'user') {
                cameraView.style.transform = 'scaleX(-1)';
            } else {
                cameraView.style.transform = 'scaleX(1)';
            }
            
            // Wait for video to be ready
            cameraView.onloadedmetadata = function() {
                // Remove loading state
                cameraView.style.opacity = '1';
                switchCamera.classList.remove('rotating');
                cameraSwitching = false;
                
                // Apply current zoom level
                applyZoom(zoomLevel);
                
                // Apply current filter
                applyFilter(activeFilter);
            };
            
            // Force fullscreen on mobile if possible
            if (document.documentElement.requestFullscreen && window.innerWidth < 768) {
                try {
                    await document.documentElement.requestFullscreen();
                } catch (e) {
                    console.log('Fullscreen not supported or not allowed');
                }
            }
            
            cameraModal.style.display = 'block';
            
            // Hide scrollbars on body
            document.body.style.overflow = 'hidden';
            
            // Set camera modal to fixed position with full dimensions
            cameraModal.style.position = 'fixed';
            cameraModal.style.top = '0';
            cameraModal.style.left = '0';
            cameraModal.style.width = '100%';
            cameraModal.style.height = '100%';
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Unable to access camera. Please ensure you have granted camera permissions.');
            
            // Reset camera switching state
            switchCamera.classList.remove('rotating');
            cameraSwitching = false;
            
            // Proceed with attendance without camera if error
            processAttendance();
        }
    }
    
    // Function to stop camera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraModal.style.display = 'none';
        
        // Restore scrollbars
        document.body.style.overflow = '';
        
        // Exit fullscreen if we're in it
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    }
    
    // Function to apply zoom
    function applyZoom(level) {
        if (!stream) return;
        
        const videoTrack = stream.getVideoTracks()[0];
        const capabilities = videoTrack.getCapabilities();
        
        if (capabilities.zoom) {
            try {
                videoTrack.applyConstraints({
                    advanced: [{ zoom: level }]
                });
                zoomIndicator.textContent = `${level.toFixed(1)}×`;
            } catch (error) {
                console.error('Error applying zoom:', error);
            }
        }
    }
    
    // Function to toggle flash
    function updateFlashMode() {
        if (!stream) return;
        
        const videoTrack = stream.getVideoTracks()[0];
        
        switch (flashMode) {
            case 'off':
                try {
                    videoTrack.applyConstraints({
                        advanced: [{ torch: false }]
                    });
                    flashToggle.innerHTML = '<i class="fas fa-bolt"></i>';
                    flashToggle.classList.remove('active');
                } catch (error) {
                    console.error('Error turning off torch:', error);
                }
                break;
            case 'on':
                try {
                    videoTrack.applyConstraints({
                        advanced: [{ torch: true }]
                    });
                    flashToggle.innerHTML = '<i class="fas fa-bolt"></i>';
                    flashToggle.classList.add('active');
                } catch (error) {
                    console.error('Error turning on torch:', error);
                }
                break;
            case 'auto':
                // Auto flash would trigger flash only when taking photo
                videoTrack.applyConstraints({
                    advanced: [{ torch: false }]
                });
                flashToggle.innerHTML = '<i class="fas fa-bolt"></i> A';
                flashToggle.classList.add('active');
                break;
        }
    }
    
    // Function to show flash effect
    function flashEffect() {
        flashOverlay.style.opacity = '1';
        setTimeout(() => {
            flashOverlay.style.opacity = '0';
        }, 100);
    }
    
    // Function to apply filter
    function applyFilter(filter) {
        switch (filter) {
            case 'none':
                cameraView.style.filter = 'none';
                break;
            case 'grayscale':
                cameraView.style.filter = 'grayscale(100%)';
                break;
            case 'sepia':
                cameraView.style.filter = 'sepia(100%)';
                break;
            case 'saturate':
                cameraView.style.filter = 'saturate(200%)';
                break;
            case 'contrast':
                cameraView.style.filter = 'contrast(150%)';
                break;
        }
        activeFilter = filter;
    }
    
    // Function to cycle through filters
    function cycleFilter() {
        const filters = ['none', 'grayscale', 'sepia', 'saturate', 'contrast'];
        const currentIndex = filters.indexOf(activeFilter);
        const nextIndex = (currentIndex + 1) % filters.length;
        applyFilter(filters[nextIndex]);
    }
    
    // Function to start timer
    function startTimer() {
        if (timerSeconds <= 0) return;
        
        timerCountdown.textContent = timerSeconds;
        timerCountdown.style.display = 'block';
        
        let secondsLeft = timerSeconds;
        
        const countdownInterval = setInterval(() => {
            secondsLeft--;
            timerCountdown.textContent = secondsLeft;
            
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                timerCountdown.style.display = 'none';
                captureImage();
            }
        }, 1000);
    }
    
    // Function to capture image
    function captureImage() {
        // If using auto flash, flash when taking photo
        if (flashMode === 'auto') {
            flashEffect();
        }
        
        // Here you would capture the actual image from video
        // For now we just simulate the capture
        
        // Process the attendance
        stopCamera();
        processAttendance();
    }
    
    // Add click event to attendance button
    attendanceBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Set action type
        actionType = isClockIn ? 'Clock In' : 'Clock Out';
        
        // Open camera
        openCamera(cameraFacingMode);
    });
    
    // Switch camera
    switchCamera.addEventListener('click', function() {
        // Prevent multiple clicks
        if (cameraSwitching) return;
        
        cameraSwitching = true;
        cameraFacingMode = cameraFacingMode === 'user' ? 'environment' : 'user';
        openCamera(cameraFacingMode);
    });
    
    // Close camera
    closeCamera.addEventListener('click', function() {
        stopCamera();
    });
    
    // Gallery button
    galleryBtn.addEventListener('click', function() {
        galleryFileInput.click();
    });
    
    // Handle gallery file selection
    galleryFileInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            // Close camera and process attendance
            stopCamera();
            processAttendance();
        }
    });
    
    // Zoom slider
    zoomSlider.addEventListener('input', function() {
        zoomLevel = parseFloat(this.value);
        applyZoom(zoomLevel);
    });
    
    // Flash toggle
    flashToggle.addEventListener('click', function() {
        switch (flashMode) {
            case 'off':
                flashMode = 'on';
                break;
            case 'on':
                flashMode = 'auto';
                break;
            case 'auto':
                flashMode = 'off';
                break;
        }
        updateFlashMode();
    });
    
    // HDR toggle
    hdrToggle.addEventListener('click', function() {
        hdrMode = !hdrMode;
        if (hdrMode) {
            hdrToggle.classList.add('active');
        } else {
            hdrToggle.classList.remove('active');
        }
    });
    
    // Timer toggle
    timerToggle.addEventListener('click', function() {
        switch (timerSeconds) {
            case 0:
                timerSeconds = 3;
                timerToggle.innerHTML = '<i class="fas fa-clock"></i> 3s';
                timerToggle.classList.add('active');
                break;
            case 3:
                timerSeconds = 5;
                timerToggle.innerHTML = '<i class="fas fa-clock"></i> 5s';
                timerToggle.classList.add('active');
                break;
            case 5:
                timerSeconds = 10;
                timerToggle.innerHTML = '<i class="fas fa-clock"></i> 10s';
                timerToggle.classList.add('active');
                break;
            case 10:
                timerSeconds = 0;
                timerToggle.innerHTML = '<i class="fas fa-clock"></i>';
                timerToggle.classList.remove('active');
                break;
        }
    });
    
    // Filter toggle
    filterToggle.addEventListener('click', function() {
        cycleFilter();
        if (activeFilter !== 'none') {
            filterToggle.classList.add('active');
        } else {
            filterToggle.classList.remove('active');
        }
    });
    
    // Capture photo
    capturePhoto.addEventListener('click', function() {
        if (timerSeconds > 0) {
            startTimer();
        } else {
            captureImage();
        }
    });
    
    // Process attendance after camera identification
    function processAttendance() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        
        if (isClockIn) {
            attendanceBtn.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Clock Out';
            attendanceBtn.classList.remove('btn-primary');
            attendanceBtn.classList.add('btn-danger');
            lastAction.textContent = `Last action: Clocked in at ${timeString}`;
        } else {
            attendanceBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Clock In';
            attendanceBtn.classList.remove('btn-danger');
            attendanceBtn.classList.add('btn-primary');
            lastAction.textContent = `Last action: Clocked out at ${timeString}`;
        }
        isClockIn = !isClockIn;
        
        // Here you can add AJAX call to your backend to record the attendance
        updateActivityLog(actionType);
    }

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
