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
                            <i class="fas fa-building me-2"></i>
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

<!-- Full Screen Camera Modal -->
<div id="cameraModal" class="modal fade camera-modal-fullscreen" tabindex="-1">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content camera-modal">
            <div class="modal-header border-0 bg-dark text-white py-3">
                <h5 class="modal-title">
                    <i class="fas fa-camera me-2"></i>
                    <span id="cameraTitle">Clock In Camera</span>
                </h5>
                <div class="camera-controls">
                    <button id="switchCamera" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-sync-alt"></i>
                        <span class="ms-1 d-none d-sm-inline">Switch Camera</span>
                    </button>
                    <button class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        <span class="ms-1 d-none d-sm-inline">Close</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center bg-dark">
                <div class="camera-container">
                    <video id="cameraFeed" autoplay playsinline></video>
                    <canvas id="photoCanvas" style="display: none;"></canvas>
                    
                    <!-- Camera Guide Overlay -->
                    <div class="camera-guide-overlay">
                        <div class="face-guide"></div>
                        <div class="guide-text">Position your face within the circle</div>
                    </div>
                    
                    <!-- Info Overlay -->
                    <div class="camera-overlay">
                        <!-- Company Logo -->
                        <div class="company-logo">
                            <img src="/images/company-logo.png" alt="Company Logo">
                        </div>
                        
                        <!-- Employee Info Overlay -->
                        <div class="employee-info">
                            <div class="datetime-info">
                                <div id="overlayTime" class="overlay-time"></div>
                                <div id="overlayDate" class="overlay-date"></div>
                            </div>
                            <div class="personal-info">
                                <div id="employeeName"></div>
                                <div id="employeePosition"></div>
                                <div id="employeeDepartment"></div>
                                <div id="employeeLocation" class="location-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-dark py-3">
                <button id="capturePhoto" class="btn btn-lg btn-primary capture-btn">
                    <i class="fas fa-camera me-2"></i>
                    Capture Photo
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

    /* Full Screen Camera Modal Styles */
    .camera-modal-fullscreen {
        padding: 0 !important;
    }

    .camera-modal-fullscreen .modal-dialog {
        max-width: none;
        margin: 0;
    }

    .camera-modal {
        background: #000;
        min-height: 100vh;
    }

    .camera-container {
        position: relative;
        width: 100%;
        height: calc(100vh - 130px); /* Account for header and footer */
        background: #000;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #cameraFeed {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Camera Guide Overlay */
    .camera-guide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }

    .face-guide {
        width: 300px;
        height: 300px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        margin-bottom: 20px;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    }

    .guide-text {
        color: white;
        font-size: 1.1rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
        background: rgba(0, 0, 0, 0.5);
        padding: 8px 16px;
        border-radius: 20px;
    }

    /* Enhanced Camera Overlay */
    .camera-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 20px;
        pointer-events: none;
    }

    .company-logo {
        position: absolute;
        top: 20px;
        right: 20px;
        max-width: 120px;
        opacity: 0.9;
        z-index: 10;
    }

    .employee-info {
        position: absolute;
        bottom: 20px;
        right: 20px;
        text-align: right;
        color: white;
        background: rgba(0, 0, 0, 0.6);
        padding: 15px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
        z-index: 10;
    }

    .modal-footer {
        position: relative;
        z-index: 20;
    }

    .capture-btn {
        font-size: 1.2rem;
        padding: 15px 40px;
        border-radius: 30px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .camera-container {
            height: calc(100vh - 120px);
        }

        .face-guide {
            width: 250px;
            height: 250px;
        }

        .guide-text {
            font-size: 1rem;
            padding: 6px 12px;
        }

        .capture-btn {
            width: 100%;
            padding: 12px 20px;
        }

        .employee-info {
            left: 20px;
            right: 20px;
            text-align: center;
        }
    }

    @media (orientation: landscape) and (max-height: 600px) {
        .camera-container {
            height: calc(100vh - 100px);
        }

        .face-guide {
            width: 200px;
            height: 200px;
        }

        .modal-header {
            padding: 0.5rem 1rem;
        }

        .modal-footer {
            padding: 0.5rem;
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
    let currentCamera = 'environment'; // 'environment' for rear, 'user' for front
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
    
    // Enhanced location handling
    async function getUserLocation() {
        if ("geolocation" in navigator) {
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
                
                // Get address using reverse geocoding
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLocation.latitude}&lon=${userLocation.longitude}`);
                const data = await response.json();
                
                const addressElement = document.getElementById('address');
                addressElement.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i></span>${data.display_name}`;
            } catch (error) {
                document.getElementById('address').innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Location access denied</span>`;
                console.error('Error getting location:', error);
                disableAttendanceButton();
            }
        } else {
            document.getElementById('address').innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Geolocation not supported</span>`;
            disableAttendanceButton();
        }
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

    // Enhanced camera start function
    async function startCamera() {
        try {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Get the device's screen dimensions
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;

            // Set ideal camera resolution based on screen size
            const constraints = {
                video: {
                    facingMode: currentCamera,
                    width: { ideal: Math.max(screenWidth, 1920) },
                    height: { ideal: Math.max(screenHeight, 1080) },
                    aspectRatio: { ideal: 16/9 }
                },
                audio: false
            };

            stream = await navigator.mediaDevices.getUserMedia(constraints);
            const video = document.getElementById('cameraFeed');
            video.srcObject = stream;
            video.classList.toggle('mirror', currentCamera === 'user');

            // Wait for video to be ready
            await new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    video.play();
                    resolve();
                };
            });

            // Update employee info overlay
            updateEmployeeInfo();
            // Start updating overlay datetime
            updateOverlayDateTime();
            setInterval(updateOverlayDateTime, 1000);

        } catch (error) {
            console.error('Error accessing camera:', error);
            showError('Unable to access camera. Please check permissions.');
        }
    }

    // Function to update employee info
    function updateEmployeeInfo() {
        document.getElementById('employeeName').textContent = '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
        document.getElementById('employeePosition').textContent = '{{ Auth::user()->position }}';
        document.getElementById('employeeDepartment').textContent = '{{ Auth::user()->department }}';
        document.getElementById('employeeLocation').querySelector('span').textContent = document.getElementById('address').textContent;
    }

    // Function to switch camera
    document.getElementById('switchCamera').addEventListener('click', async () => {
        currentCamera = currentCamera === 'user' ? 'environment' : 'user';
        await startCamera();
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

    // Function to stop camera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    // Modify handleAttendance to open camera
    async function handleAttendance() {
        if (!userLocation) {
            showError('Please enable location access to clock in/out');
            return;
        }

        if (!serverTimestamp || !serverHash) {
            showError('Server time synchronization required. Please wait.');
            return;
        }

        // Update modal title
        document.getElementById('cameraTitle').textContent = `Clock ${isClockIn ? 'In' : 'Out'} Camera`;

        // Start camera and show modal
        await startCamera();
        cameraModal.show();
    }

    // Clean up camera when modal is closed
    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', stopCamera);
    
    // Event listener for the clock button
    document.getElementById('clockButton').addEventListener('click', handleAttendance);
    
    // Initialize
    getServerTime();
    getUserLocation();
    updateButtonState();
    
    // Periodic server sync (every 5 minutes)
    setInterval(getServerTime, 300000);
});
</script>
@endpush

@endsection