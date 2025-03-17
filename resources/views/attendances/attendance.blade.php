@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 attendance-card">
                <div class="card-header position-relative py-4">
                    <div class="header-background"></div>
                    <div class="position-relative">
                        <h2 class="mb-0 text-center text-white">
                            MHR Employee Attendance
                        </h2>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="text-center attendance-content">
                        <div class="time-display mb-4">
                            <div id="clock" class="clock-text mb-2">--:--:-- --</div>
                            <div id="date" class="date-text mb-3">Loading...</div>
                        </div>
                        
                        <div class="location-info mb-4">
                            <div class="location-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div id="address" class="location-text">Fetching location...</div>
                        </div>
                        
                        <div class="d-grid col-lg-7 col-md-9 mx-auto">
                            <button id="clockButton" class="btn btn-lg attendance-btn">
                                <div class="btn-content">
                                    <i class="fas"></i>
                                    <span class="ms-2">Loading...</span>
                                </div>
                                <div class="btn-ripple"></div>
                            </button>
                        </div>
                    </div>
                    
                    <div class="status-container mt-4">
                        <div class="alert alert-info text-center status-message" role="alert">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Attendance is being recorded with secure verification</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Screen Camera Interface -->
<div id="cameraModal" class="modal fade camera-modal-fullscreen" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0 p-0">
        <div class="modal-content camera-modal">
            <!-- Camera Status Bar -->
            <div class="camera-status-bar">
                <div class="status-left">
                    <button type="button" class="btn-close-camera" id="closeCamera">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="status-center">
                    <div class="clock-status" id="clockStatus">Clock In Camera</div>
                </div>
                <div class="status-right">
                    <button id="switchCamera" class="btn-switch-camera">
                        <i class="fas fa-camera-rotate"></i>
                    </button>
                </div>
            </div>

            <!-- Camera View -->
            <div class="camera-view">
                <video id="cameraFeed" autoplay playsinline></video>
                <canvas id="photoCanvas" style="display: none;"></canvas>

                <!-- Camera Guide -->
                <div class="camera-guide-overlay">
                    <div class="face-guide"></div>
                </div>

                <!-- Info Overlay -->
                <div class="camera-overlay">
                    <div class="employee-info">
                        <div class="datetime-info">
                            <div id="overlayTime" class="overlay-time"></div>
                            <div id="overlayDate" class="overlay-date"></div>
                        </div>
                        <div class="personal-info">
                            <div id="employeeName"></div>
                            <div id="employeePosition"></div>
                            <div id="employeeDepartment"></div>
                            <div id="employeeLocation">
                                <i class="fas fa-map-marker-alt"></i>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Camera Controls -->
            <div class="camera-controls-bottom">
                <button id="capturePhoto" class="capture-button">
                    <div class="capture-button-inner"></div>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Card Styles */
    .attendance-card {
        border-radius: 20px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: transparent;
        border: none;
        overflow: hidden;
    }

    .header-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        z-index: 0;
    }

    /* Clock Display */
    .time-display {
        padding: 2rem;
        background: linear-gradient(to bottom, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .clock-text {
        font-size: 3.5rem;
        font-weight: 700;
        color: #2c3e50;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .date-text {
        font-size: 1.25rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Location Info */
    .location-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .location-icon {
        color: #4e73df;
        font-size: 1.2rem;
    }

    .location-text {
        color: #495057;
        font-size: 0.95rem;
    }

    /* Button Styles */
    .attendance-btn {
        position: relative;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-clock-in {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
        border: none;
    }

    .btn-clock-out {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
    }

    .attendance-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .attendance-btn:active {
        transform: translateY(1px);
    }

    .btn-ripple {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        transform: scale(0);
        transition: transform 0.6s;
    }

    .attendance-btn:hover .btn-ripple {
        transform: scale(2);
    }

    /* Status Message */
    .status-message {
        border: none;
        background: #e8f4fd;
        color: #0d6efd;
        border-radius: 10px;
        padding: 1rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .status-message.error {
        background: #fee7e7;
        color: #dc3545;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .clock-text {
            font-size: 2.5rem;
        }
        
        .date-text {
            font-size: 1.1rem;
        }
        
        .attendance-btn {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    /* Loading Animation */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .loading {
        animation: pulse 1.5s infinite;
    }

    /* Disabled State */
    .disabled {
        opacity: 0.65;
        pointer-events: none;
        filter: grayscale(30%);
    }

    /* Full Screen Camera Styles */
    .camera-modal-fullscreen {
        margin: 0;
        padding: 0 !important;
        background: #000;
    }

    .camera-modal-fullscreen .modal-dialog {
        margin: 0;
        padding: 0;
        width: 100vw;
        max-width: 100vw;
        height: 100vh;
        max-height: 100vh;
    }

    .camera-modal {
        background: #000;
        height: 100vh;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 0;
        overflow: hidden;
    }

    /* Status Bar */
    .camera-status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: env(safe-area-inset-top) 1rem 1rem;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        height: 60px;
    }

    .clock-status {
        font-size: 1.1rem;
        font-weight: 500;
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    .btn-close-camera,
    .btn-switch-camera {
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        font-size: 1.2rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-close-camera:hover,
    .btn-switch-camera:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .btn-close-camera:active,
    .btn-switch-camera:active {
        transform: scale(0.95);
    }

    /* Camera View */
    .camera-view {
        flex: 1;
        position: relative;
        background: #000;
        overflow: hidden;
        width: 100vw;
        height: calc(100vh - 60px);
        margin-top: 60px;
    }

    #cameraFeed {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Camera Guide */
    .camera-guide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .face-guide {
        width: min(280px, 70vw);
        height: min(280px, 70vw);
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.3);
    }

    /* Info Overlay */
    .camera-overlay {
        position: absolute;
        bottom: 100px;
        right: 20px;
        color: white;
        text-align: right;
        z-index: 10;
        max-width: 90vw;
    }

    .employee-info {
        background: rgba(0, 0, 0, 0.6);
        padding: 12px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        font-size: clamp(0.8rem, 2.5vw, 0.9rem);
    }

    .datetime-info {
        margin-bottom: 8px;
    }

    .overlay-time {
        font-size: clamp(1rem, 3vw, 1.2rem);
        font-weight: 600;
    }

    /* Camera Controls */
    .camera-controls-bottom {
        position: fixed;
        bottom: max(env(safe-area-inset-bottom), 20px);
        left: 0;
        right: 0;
        padding: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        background: transparent;
    }

    .capture-button {
        width: min(70px, 15vw);
        height: min(70px, 15vw);
        border-radius: 50%;
        background: transparent;
        border: 4px solid white;
        padding: 2px;
        cursor: pointer;
    }

    .capture-button-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: white;
        transition: transform 0.2s;
    }

    .capture-button:active .capture-button-inner {
        transform: scale(0.9);
    }

    /* Mirror effect for front camera */
    .mirror {
        transform: scaleX(-1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .camera-status-bar {
            height: 50px;
            padding: env(safe-area-inset-top) 10px 10px;
        }

        .camera-view {
            height: calc(100vh - 50px);
            margin-top: 50px;
        }

        .camera-overlay {
            bottom: 120px;
            right: 10px;
            left: 10px;
        }

        .employee-info {
            text-align: center;
        }
    }

    /* Landscape Mode */
    @media (orientation: landscape) {
        .camera-view {
            height: 100vh;
        }

        .camera-status-bar {
            background: rgba(0, 0, 0, 0.7);
        }

        .camera-overlay {
            bottom: 20px;
            right: 20px;
            max-width: 300px;
        }

        .camera-controls-bottom {
            right: 20px;
            left: auto;
            bottom: 50%;
            transform: translateY(50%);
        }
    }

    /* Notch Support */
    @supports (padding-top: env(safe-area-inset-top)) {
        .camera-status-bar {
            padding-top: max(env(safe-area-inset-top), 10px);
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let serverTimestamp = '';
    let serverHash = '';
    let userLocation = null;
    let lastVerifiedTime = null;
    let isClockIn = true;
    let clientTimeOffset = 0;
    let stream = null;
    let currentCamera = 'environment';
    let permissionsGranted = false;
    const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
    
    // Function to update button state
    function updateButtonState() {
        const button = document.getElementById('clockButton');
        const icon = button.querySelector('i');
        const text = button.querySelector('span');
        
        if (isClockIn) {
            button.className = 'btn btn-lg attendance-btn btn-clock-in';
            icon.className = 'fas fa-sign-in-alt';
            text.textContent = 'Clock In';
        } else {
            button.className = 'btn btn-lg attendance-btn btn-clock-out';
            icon.className = 'fas fa-sign-out-alt';
            text.textContent = 'Clock Out';
        }
    }
    
    // Function to get server time and calculate offset
    async function getServerTime() {
        try {
            const response = await fetch('/api/server-time');
            if (!response.ok) throw new Error('Server time fetch failed');
            
            const data = await response.json();
            serverTimestamp = data.timestamp;
            serverHash = data.hash;
            
            // Calculate client-server time offset
            const serverTime = new Date(data.timestamp).getTime();
            const clientTime = new Date().getTime();
            clientTimeOffset = serverTime - clientTime;
            
            // Verify timestamp
            const verifyResponse = await fetch(`/api/verify-timestamp/${encodeURIComponent(serverTimestamp)}/${encodeURIComponent(serverHash)}`);
            if (!verifyResponse.ok) throw new Error('Timestamp verification failed');
            
            const verifyData = await verifyResponse.json();
            if (!verifyData.valid) {
                console.error('Invalid timestamp detected');
                disableAttendanceButton();
                showError('Time verification failed. Please refresh the page.');
                return;
            }
            
            lastVerifiedTime = data.formatted;
            updateTimeDisplay();
            startLocalTimeUpdate();
        } catch (error) {
            console.error('Error with server time:', error);
            showError('Unable to sync with server time. Please refresh the page.');
            disableAttendanceButton();
        }
    }
    
    // Function to start local time updates
    function startLocalTimeUpdate() {
        // Update every second
        setInterval(() => {
            const now = new Date(Date.now() + clientTimeOffset);
            updateTimeDisplay(now);
        }, 1000);
    }
    
    // Function to update time display
    function updateTimeDisplay(date = new Date()) {
        const timeString = date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true,
            timeZone: 'Asia/Manila'
        });
        
        const dateString = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'Asia/Manila'
        });
        
        const clockElement = document.getElementById('clock');
        const dateElement = document.getElementById('date');
        
        // Smooth update animation
        clockElement.style.opacity = '0';
        setTimeout(() => {
            clockElement.textContent = timeString;
            clockElement.style.opacity = '1';
        }, 100);
        
        dateElement.textContent = dateString;
    }
    
    // Function to disable attendance button
    function disableAttendanceButton() {
        const button = document.getElementById('clockButton');
        button.classList.add('disabled');
        showError('System unavailable. Please try again later.');
    }
    
    // Function to show error message
    function showError(message) {
        const statusMessage = document.querySelector('.status-message');
        statusMessage.classList.remove('alert-info');
        statusMessage.classList.add('alert-danger', 'error');
        statusMessage.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    }
    
    // Function to show success message
    function showSuccess(message) {
        const statusMessage = document.querySelector('.status-message');
        statusMessage.classList.remove('alert-info', 'alert-danger', 'error');
        statusMessage.classList.add('alert-success');
        statusMessage.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
        
        // Reset to info message after 5 seconds
        setTimeout(() => {
            statusMessage.classList.remove('alert-success');
            statusMessage.classList.add('alert-info');
            statusMessage.innerHTML = `<i class="fas fa-shield-alt me-2"></i>Attendance is being recorded with secure verification`;
        }, 5000);
    }
    
    // Function to check and request permissions
    async function checkAndRequestPermissions() {
        try {
            // Check location permission
            const locationPermission = await checkLocationPermission();
            
            // Check camera permission
            const cameraPermission = await checkCameraPermission();
            
            permissionsGranted = locationPermission && cameraPermission;
            
            if (!permissionsGranted) {
                const missingPermissions = [];
                if (!locationPermission) missingPermissions.push('Location');
                if (!cameraPermission) missingPermissions.push('Camera');
                
                showError(`Please enable ${missingPermissions.join(' and ')} access in your browser settings to use this feature.`);
                return false;
            }
            
            return true;
        } catch (error) {
            console.error('Error checking permissions:', error);
            showError('Unable to verify permissions. Please check your browser settings.');
            return false;
        }
    }

    // Function to check location permission
    async function checkLocationPermission() {
        try {
            if (!navigator.geolocation) {
                showError('Geolocation is not supported by your browser');
                return false;
            }

            const permission = await navigator.permissions.query({ name: 'geolocation' });
            
            if (permission.state === 'denied') {
                showError('Location access is blocked. Please enable it in your browser settings.');
                return false;
            }

            // Test location access
            await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
            });

            return true;
        } catch (error) {
            console.error('Location permission error:', error);
            showError('Unable to access location. Please check your browser settings.');
            return false;
        }
    }

    // Function to check camera permission
    async function checkCameraPermission() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const cameras = devices.filter(device => device.kind === 'videoinput');
            
            if (cameras.length === 0) {
                showError('No camera detected on your device');
                return false;
            }

            // Test camera access
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            stream.getTracks().forEach(track => track.stop());
            
            return true;
        } catch (error) {
            console.error('Camera permission error:', error);
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                showError('Camera access is blocked. Please enable it in your browser settings.');
            } else if (error.name === 'NotFoundError') {
                showError('No camera found on your device.');
            } else {
                showError('Unable to access camera. Please check your browser settings.');
            }
            return false;
        }
    }

    // Enhanced getUserLocation function
    async function getUserLocation() {
        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
            });
            
            userLocation = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            };
            
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLocation.latitude}&lon=${userLocation.longitude}`);
                if (!response.ok) throw new Error('Geocoding failed');
                
                const data = await response.json();
                const addressElement = document.getElementById('address');
                addressElement.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i></span>${data.display_name}`;
                
                // Update location in camera overlay
                const overlayLocation = document.querySelector('#employeeLocation span');
                if (overlayLocation) {
                    overlayLocation.textContent = data.display_name;
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                // Still show coordinates if geocoding fails
                const addressElement = document.getElementById('address');
                addressElement.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i></span>Location Found (${userLocation.latitude.toFixed(6)}, ${userLocation.longitude.toFixed(6)})`;
            }
        } catch (error) {
            console.error('Location error:', error);
            handleLocationError(error);
        }
    }

    // Function to handle location errors
    function handleLocationError(error) {
        let errorMessage = 'Unable to access location.';
        
        switch(error.code) {
            case error.PERMISSION_DENIED:
                errorMessage = 'Location access was denied. Please enable it in your browser settings.';
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = 'Location information is unavailable. Please check your device settings.';
                break;
            case error.TIMEOUT:
                errorMessage = 'Location request timed out. Please try again.';
                break;
            case error.UNKNOWN_ERROR:
                errorMessage = 'An unknown error occurred while accessing location.';
                break;
        }
        
        document.getElementById('address').innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i>${errorMessage}</span>`;
        disableAttendanceButton();
    }

    // Function to update overlay datetime
    function updateOverlayDateTime() {
        const now = new Date(Date.now() + clientTimeOffset);
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true,
            timeZone: 'Asia/Manila'
        });
        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'Asia/Manila'
        });
        
        document.getElementById('overlayTime').textContent = timeString;
        document.getElementById('overlayDate').textContent = dateString;
    }

    // Update clock status based on current state
    function updateClockStatus() {
        const statusText = isClockIn ? 'Clock In Camera' : 'Clock Out Camera';
        document.getElementById('clockStatus').textContent = statusText;
    }

    // Function to properly stop camera and clean up
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => {
                track.stop();
            });
            stream = null;
        }
        const video = document.getElementById('cameraFeed');
        video.srcObject = null;
    }

    // Enhanced close button functionality
    document.getElementById('closeCamera').addEventListener('click', function() {
        stopCamera();
        cameraModal.hide();
    });

    // Ensure camera is stopped when modal is closed by any means
    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function() {
        stopCamera();
    });

    // Enhanced camera start function
    async function startCamera() {
        try {
            stopCamera(); // Clean up any existing stream

            // Check permissions before starting camera
            if (!await checkAndRequestPermissions()) {
                return;
            }

            const constraints = {
                video: {
                    facingMode: currentCamera,
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                },
                audio: false
            };

            stream = await navigator.mediaDevices.getUserMedia(constraints);
            const video = document.getElementById('cameraFeed');
            video.srcObject = stream;
            video.classList.toggle('mirror', currentCamera === 'user');

            // Wait for video to be loaded
            await new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    video.play().then(resolve).catch(error => {
                        console.error('Error playing video:', error);
                        resolve();
                    });
                };
            });

            // Update overlays
            updateEmployeeInfo();
            updateOverlayDateTime();
            setInterval(updateOverlayDateTime, 1000);

        } catch (error) {
            console.error('Camera error:', error);
            handleCameraError(error);
        }
    }

    // Function to update employee info
    function updateEmployeeInfo() {
        document.getElementById('employeeName').textContent = '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
        document.getElementById('employeePosition').textContent = '{{ Auth::user()->position }}';
        document.getElementById('employeeDepartment').textContent = '{{ Auth::user()->department }}';
        document.getElementById('employeeLocation').querySelector('span').textContent = document.getElementById('address').textContent;
    }

    // Switch camera function with error handling
    document.getElementById('switchCamera').addEventListener('click', async () => {
        try {
            currentCamera = currentCamera === 'user' ? 'environment' : 'user';
            await startCamera();
        } catch (error) {
            console.error('Error switching camera:', error);
            showError('Unable to switch camera. Please try again.');
        }
    });

    // Function to capture photo
    document.getElementById('capturePhoto').addEventListener('click', async () => {
        const video = document.getElementById('cameraFeed');
        const canvas = document.getElementById('photoCanvas');
        const context = canvas.getContext('2d');

        // Set canvas size to match video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Draw the video frame to the canvas
        if (currentCamera === 'user') {
            // Mirror the image for front camera
            context.scale(-1, 1);
            context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
            context.scale(-1, 1); // Reset transform
        } else {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
        }

        // Convert to base64
        const photoData = canvas.toDataURL('image/jpeg');

        // Here you would send the photo data to your server
        // along with the attendance data
        try {
            const attendanceData = {
                type: isClockIn ? 'in' : 'out',
                timestamp: serverTimestamp,
                hash: serverHash,
                location: userLocation,
                photo: photoData,
                timezone: 'Asia/Manila'
            };

            const response = await fetch('/api/attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(attendanceData)
            });

            if (!response.ok) throw new Error('Attendance submission failed');

            // Close camera and modal
            stopCamera();
            cameraModal.hide();

            // Toggle button state
            isClockIn = !isClockIn;
            updateButtonState();

            // Show success message
            const action = isClockIn ? 'Out' : 'In';
            showSuccess(`Clock ${action} recorded successfully!`);
        } catch (error) {
            console.error('Error submitting attendance:', error);
            showError('Failed to record attendance. Please try again.');
        }
    });

    // Modified handleAttendance with permission checks
    async function handleAttendance() {
        try {
            // Check permissions first
            if (!await checkAndRequestPermissions()) {
                return;
            }

            if (!userLocation || !serverTimestamp || !serverHash) {
                showError('Please ensure location and time sync are available');
                return;
            }

            updateClockStatus();
            await startCamera();
            cameraModal.show();
        } catch (error) {
            console.error('Attendance error:', error);
            showError('Unable to start attendance process. Please try again.');
        }
    }

    // Event listener for the clock button
    document.getElementById('clockButton').addEventListener('click', handleAttendance);
    
    // Initialize with permission checks
    checkAndRequestPermissions().then(granted => {
        if (granted) {
            getServerTime();
            getUserLocation();
        }
    });
});
</script>
@endpush

@endsection