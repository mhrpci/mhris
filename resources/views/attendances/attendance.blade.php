@extends('layouts.app')

@section('styles')
<style>
    /* Base styles */
    .attendance-container {
        min-height: calc(100vh - 80px);
        padding: 1.5rem 0;
        background: linear-gradient(135deg, #f6f9fc 0%, #ecf3f8 100%);
    }
    
    /* Card styles */
    .attendance-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .attendance-card:hover {
        transform: translateY(-5px);
    }
    
    /* Profile section */
    .profile-section {
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        background: linear-gradient(135deg, #4285f4 0%, #3b77db 100%);
        color: white;
    }
    
    .profile-initial {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 1.2rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .profile-photo:hover {
        transform: scale(1.05);
    }
    
    .profile-section h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .profile-section p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }
    
    /* Clock display */
    .clock-display {
        background: white;
        color: #2d3748;
        padding: 2.5rem 2rem;
        text-align: center;
        font-family: 'Inter', sans-serif;
        border-bottom: 1px solid #edf2f7;
    }
    
    .time {
        font-size: 4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    
    .date {
        font-size: 1.25rem;
        color: #718096;
        font-weight: 500;
    }
    
    /* Action buttons */
    .action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        padding: 2rem;
        background: #f8fafc;
    }
    
    .btn-clock-in, .btn-clock-out {
        border: none;
        padding: 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-clock-in {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .btn-clock-out {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-clock-in:hover, .btn-clock-out:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .btn-clock-in i, .btn-clock-out i {
        font-size: 1.5rem;
        margin-bottom: 0.3rem;
    }
    
    .btn-clock-in div, .btn-clock-out div {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    /* Location info */
    .location-info {
        padding: 1.5rem 2rem;
        background: white;
        border-top: 1px solid #edf2f7;
    }
    
    .location-text {
        color: #4a5568;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .location-text i {
        color: #4285f4;
    }
    
    /* Camera modal */
    .camera-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #000;
        z-index: 9999;
    }
    
    /* Modern camera interface */
    .camera-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        display: flex;
        flex-direction: column;
        background: #000;
    }

    #camera-feed {
        width: 100%;
        height: 100vh;
        object-fit: cover;
        background: #000;
        position: fixed;
        top: 0;
        left: 0;
    }

    /* Beautification Controls Panel */
    .beautify-controls {
        position: fixed;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1rem;
        z-index: 1002;
        width: 280px;
        transition: transform 0.3s ease;
    }

    .beautify-controls.collapsed {
        transform: translateX(calc(100% - 48px)) translateY(-50%);
    }

    .beautify-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: white;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .beautify-title {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .toggle-controls {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background 0.3s ease;
    }

    .toggle-controls:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .beautify-slider-group {
        margin-bottom: 1rem;
    }

    .slider-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .slider-value {
        color: rgba(255, 255, 255, 0.7);
        font-variant-numeric: tabular-nums;
    }

    .beautify-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.1);
        outline: none;
        margin: 0.5rem 0;
    }

    .beautify-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #4285f4;
        cursor: pointer;
        transition: transform 0.1s ease;
    }

    .beautify-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }

    .beautify-slider::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #4285f4;
        cursor: pointer;
        transition: transform 0.1s ease;
        border: none;
    }

    .beautify-slider::-moz-range-thumb:hover {
        transform: scale(1.2);
    }

    /* Modern Camera Controls */
    .camera-controls {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        z-index: 1002;
    }

    .camera-btn {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: none;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-capture {
        width: 72px;
        height: 72px;
        background: rgba(255, 255, 255, 0.2);
        border: 3px solid rgba(255, 255, 255, 0.8);
    }

    .btn-capture:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .camera-btn:not(.btn-capture):hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    /* Top Controls */
    .top-controls {
        position: fixed;
        top: 1rem;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding: 0 1rem;
        z-index: 1002;
    }

    .btn-close-camera {
        color: #ff4444;
    }

    .btn-close-camera:hover {
        background: #ff4444;
        color: white;
    }

    /* Camera Overlay Info */
    .camera-overlay {
        position: fixed;
        left: 1rem;
        bottom: 2rem;
        color: white;
        z-index: 1002;
        font-family: 'Inter', sans-serif;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        padding: 1rem;
        border-radius: 12px;
        max-width: 320px;
    }

    .camera-overlay-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .beautify-controls {
            right: 0;
            width: 240px;
        }

        .camera-controls {
            bottom: 1.5rem;
            gap: 1rem;
        }

        .btn-capture {
            width: 64px;
            height: 64px;
        }

        .camera-btn {
            width: 42px;
            height: 42px;
        }

        .camera-overlay {
            left: 0.5rem;
            bottom: 1.5rem;
            padding: 0.8rem;
            max-width: 280px;
        }
    }

    @media (max-width: 480px) {
        .beautify-controls {
            width: 200px;
        }

        .camera-controls {
            bottom: 1rem;
        }

        .btn-capture {
            width: 56px;
            height: 56px;
        }

        .camera-btn {
            width: 38px;
            height: 38px;
            font-size: 1rem;
        }
    }

    /* Logo styles */
    .camera-logo-container {
        position: fixed;
        top: 1rem;
        right: 4rem; /* Adjusted to make room for close button */
        z-index: 1001;
        background: rgba(255, 255, 255, 0.9);
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .camera-logo {
        width: 100px;
        height: auto;
        opacity: 0.8;
        display: block;
    }

    /* Updated font sizes */
    .overlay-time {
        font-size: 1.8rem; /* Reduced from 2.2rem */
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .overlay-date {
        font-size: 1.1rem; /* Reduced from 1.3rem */
        font-weight: 500;
        color: rgba(255,255,255,0.9);
        margin-bottom: 12px;
    }

    .overlay-name {
        font-size: 1rem; /* Reduced from 1.2rem */
        font-weight: 500;
        color: rgba(255,255,255,0.95);
        margin-bottom: 8px;
    }

    .overlay-location {
        font-size: 0.9rem; /* Reduced from 1rem */
        color: rgba(255,255,255,0.9);
        line-height: 1.4;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .overlay-location i {
        margin-top: 4px;
        color: #4285f4;
    }

    /* Large status indicator */
    .camera-status-large {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 8rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.15);
        text-transform: uppercase;
        pointer-events: none;
        z-index: 1001;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        letter-spacing: 4px;
    }

    .camera-status-large.in {
        color: rgba(40, 167, 69, 0.15);
    }

    .camera-status-large.out {
        color: rgba(220, 53, 69, 0.15);
    }

    /* Profile Preview Modal */
    .profile-preview-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-preview-modal.active {
        display: flex;
        opacity: 1;
        justify-content: center;
        align-items: center;
    }

    .profile-preview-content {
        position: relative;
        max-width: 90%;
        max-height: 90vh;
    }

    .profile-preview-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .profile-preview-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 24px;
        cursor: pointer;
        background: none;
        border: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .profile-preview-close:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .profile-preview-content {
            max-width: 95%;
        }
    }
</style>
@endsection

@section('content')
<div class="app-content">
    <div class="attendance-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="attendance-card">
                        @if($employee)
                        <div class="profile-section">
                            <div class="profile-initial">
                                @if($employee->profile)   
                                <img src="{{ asset('storage/' . $employee->profile) }}" alt="Employee Photo" class="profile-photo" onclick="openProfilePreview(this.src)">
                                @else
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                @endif
                            </div>
                            <h4>{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                            <p>{{ $employee->email_address }}</p>
                        </div>
                        @endif

                        <!-- Profile Preview Modal -->
                        <div class="profile-preview-modal" id="profilePreviewModal">
                            <div class="profile-preview-content">
                                <button class="profile-preview-close" onclick="closeProfilePreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                                <img class="profile-preview-image" id="profilePreviewImage" src="" alt="Profile Preview">
                            </div>
                        </div>

                        <div class="clock-display">
                            <div class="time" id="current-time">00:00:00</div>
                            <div class="date" id="current-date"></div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn-clock-in" onclick="startAttendance('in')">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Clock In</span>
                                <div>Start your workday</div>
                            </button>
                            <button class="btn-clock-out" onclick="startAttendance('out')">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Clock Out</span>
                                <div>End your workday</div>
                            </button>
                        </div>

                        <div class="location-info">
                            <p class="location-text">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="current-location">Fetching location...</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Camera Modal -->
<div class="camera-modal" id="cameraModal">
    <div class="top-controls">
        <button class="camera-btn btn-close-camera" onclick="closeCamera()">
            <i class="fas fa-times"></i>
        </button>
        <div class="camera-logo-container">
            <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="camera-logo">
        </div>
    </div>
    
    <div class="camera-container">
        <canvas id="beautify-canvas"></canvas>
        <video id="camera-feed" autoplay playsinline></video>
        
        <div class="beautify-controls" id="beautifyControls">
            <div class="beautify-header">
                <span class="beautify-title">Beautify</span>
                <button class="toggle-controls" onclick="toggleBeautifyControls()">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </div>
            
            <div class="beautify-slider-group">
                <div class="slider-label">
                    <span>Smoothness</span>
                    <span class="slider-value" id="smoothnessValue">0</span>
                </div>
                <input type="range" class="beautify-slider" id="smoothnessSlider" 
                       min="0" max="100" value="0" oninput="updateBeautifyEffect('smoothness')">
            </div>
            
            <div class="beautify-slider-group">
                <div class="slider-label">
                    <span>Brightness</span>
                    <span class="slider-value" id="brightnessValue">0</span>
                </div>
                <input type="range" class="beautify-slider" id="brightnessSlider" 
                       min="-100" max="100" value="0" oninput="updateBeautifyEffect('brightness')">
            </div>
            
            <div class="beautify-slider-group">
                <div class="slider-label">
                    <span>Contrast</span>
                    <span class="slider-value" id="contrastValue">0</span>
                </div>
                <input type="range" class="beautify-slider" id="contrastSlider" 
                       min="-100" max="100" value="0" oninput="updateBeautifyEffect('contrast')">
            </div>
            
            <div class="beautify-slider-group">
                <div class="slider-label">
                    <span>Warmth</span>
                    <span class="slider-value" id="warmthValue">0</span>
                </div>
                <input type="range" class="beautify-slider" id="warmthSlider" 
                       min="-100" max="100" value="0" oninput="updateBeautifyEffect('warmth')">
            </div>
            
            <div class="beautify-slider-group">
                <div class="slider-label">
                    <span>Saturation</span>
                    <span class="slider-value" id="saturationValue">0</span>
                </div>
                <input type="range" class="beautify-slider" id="saturationSlider" 
                       min="-100" max="100" value="0" oninput="updateBeautifyEffect('saturation')">
            </div>
        </div>

        <div class="camera-flash"></div>
        
        <div class="camera-overlay">
            <div class="camera-overlay-content">
                <div class="camera-status-badge in" id="overlay-status">
                    <i class="fas fa-clock"></i>
                    <span>Clock In</span>
                </div>
                <div class="overlay-info-group">
                    <div class="overlay-time" id="overlay-time">00:00 AM</div>
                    <div class="overlay-date" id="overlay-date"></div>
                </div>
                <div class="overlay-name" id="overlay-name"></div>
                <div class="overlay-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="overlay-location">Fetching location...</span>
                </div>
            </div>
        </div>
        
        <div class="camera-controls">
            <button class="camera-btn" onclick="switchCamera()">
                <i class="fas fa-sync"></i>
            </button>
            <button class="camera-btn btn-capture" onclick="captureImage()">
                <i class="fas fa-camera"></i>
            </button>
            <button class="camera-btn" onclick="toggleBeautifyControls()">
                <i class="fas fa-magic"></i>
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentStream = null;
    let currentFacingMode = 'user';
    let locationWatchId = null;
    let currentAttendanceType = 'in';
    let beautifyCanvas = null;
    let beautifyContext = null;
    let beautifyShaderProgram = null;
    let beautifySettings = {
        smoothness: 0,
        brightness: 0,
        contrast: 0,
        warmth: 0,
        saturation: 0
    };

    // Profile Preview Functions
    function openProfilePreview(imageSrc) {
        const modal = document.getElementById('profilePreviewModal');
        const previewImage = document.getElementById('profilePreviewImage');
        
        previewImage.src = imageSrc;
        modal.classList.add('active');
        
        // Close modal when clicking outside the image
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeProfilePreview();
            }
        });

        // Add escape key listener
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfilePreview();
            }
        });
    }

    function closeProfilePreview() {
        const modal = document.getElementById('profilePreviewModal');
        modal.classList.remove('active');
    }

    // Update time and date with server time
    async function updateDateTime() {
        try {
            // Fetch server time instead of using client time
            const response = await fetch('/api/server-time');
            if (!response.ok) {
                throw new Error('Failed to fetch server time');
            }
            
            const data = await response.json();
            const serverTime = new Date(data.timestamp);
            
            // Update time display with server time
            document.getElementById('current-time').textContent = new Intl.DateTimeFormat('en-US', { 
                timeZone: 'Asia/Manila',
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }).format(serverTime).toUpperCase();
            
            document.getElementById('current-date').textContent = new Intl.DateTimeFormat('en-US', { 
                timeZone: 'Asia/Manila',
                weekday: 'long',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }).format(serverTime);
            
            // Update overlay time if camera is active
            if (document.getElementById('cameraModal').style.display === 'block') {
                document.getElementById('overlay-time').textContent = new Intl.DateTimeFormat('en-US', { 
                    timeZone: 'Asia/Manila',
                    hour12: true,
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(serverTime).toUpperCase();
                
                document.getElementById('overlay-date').textContent = new Intl.DateTimeFormat('en-US', { 
                    timeZone: 'Asia/Manila',
                    weekday: 'short',
                    month: 'short',
                    day: '2-digit',
                    year: 'numeric'
                }).format(serverTime);
            }
        } catch (error) {
            console.error('Error updating time:', error);
        }
    }

    // Update overlay information with server time
    async function updateOverlayInfo(type) {
        try {
            // Fetch server time
            const response = await fetch('/api/server-time');
            if (!response.ok) {
                throw new Error('Failed to fetch server time');
            }
            
            const data = await response.json();
            const serverTime = new Date(data.timestamp);
            
            const timeStr = new Intl.DateTimeFormat('en-US', { 
                timeZone: 'Asia/Manila',
                hour12: true,
                hour: '2-digit',
                minute: '2-digit'
            }).format(serverTime).toUpperCase();
            
            const dateStr = new Intl.DateTimeFormat('en-US', { 
                timeZone: 'Asia/Manila',
                weekday: 'short',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            }).format(serverTime);

            // Update status badge
            const statusElement = document.getElementById('overlay-status');
            const statusText = type === 'in' ? 'Clock In' : 'Clock Out';
            statusElement.innerHTML = `<i class="fas fa-clock"></i><span>${statusText}</span>`;
            statusElement.className = `camera-status-badge ${type}`;

            // Update other overlay elements with server time
            document.getElementById('overlay-time').textContent = timeStr;
            document.getElementById('overlay-date').textContent = dateStr;
            document.getElementById('overlay-name').textContent = `{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}`;
            
            // Location will be updated by the location watcher
            const locationElement = document.getElementById('overlay-location');
            if (locationElement.textContent === '') {
                locationElement.textContent = 'Fetching location...';
            }
        } catch (error) {
            console.error('Error updating overlay info:', error);
        }
    }

    // Initialize location tracking with high accuracy
    function initializeLocation() {
        if ("geolocation" in navigator) {
            const options = {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            };

            locationWatchId = navigator.geolocation.watchPosition(
                position => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    
                    // Use reverse geocoding to get address with more parameters
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`)
                        .then(response => response.json())
                        .then(data => {
                            let locationText = '';
                            if (data.address) {
                                const addr = data.address;
                                // Construct a more precise address
                                const parts = [];
                                if (addr.building) parts.push(addr.building);
                                if (addr.road) parts.push(addr.road);
                                if (addr.suburb) parts.push(addr.suburb);
                                if (addr.city || addr.town) parts.push(addr.city || addr.town);
                                locationText = parts.join(', ');
                            } else {
                                locationText = data.display_name;
                            }
                            
                            // Add accuracy indicator if precision is low
                            if (accuracy > 100) { // More than 100 meters
                                locationText += ` (±${Math.round(accuracy)}m)`;
                            }
                            
                            document.getElementById('current-location').textContent = locationText;
                            document.getElementById('overlay-location').textContent = locationText;
                        })
                        .catch(() => {
                            const locationText = `${latitude.toFixed(6)}, ${longitude.toFixed(6)} (±${Math.round(accuracy)}m)`;
                            document.getElementById('current-location').textContent = locationText;
                            document.getElementById('overlay-location').textContent = locationText;
                        });
                },
                error => {
                    let errorText = 'Unable to retrieve location';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorText = 'Location access denied. Please enable location services.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorText = 'Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorText = 'Location request timed out.';
                            break;
                    }
                    document.getElementById('current-location').textContent = errorText;
                    document.getElementById('overlay-location').textContent = errorText;
                },
                options
            );
        }
    }

    // Initialize camera
    async function initializeCamera(facingMode = 'user') {
        try {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            const constraints = {
                video: {
                    facingMode: facingMode,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };

            currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            const videoElement = document.getElementById('camera-feed');
            videoElement.srcObject = currentStream;
            
            // Initialize beautify canvas if not already done
            if (!beautifyCanvas) {
                initBeautifyCanvas();
            }
            
            // Apply mirroring only for front camera
            videoElement.style.transform = facingMode === 'user' ? 'scaleX(-1)' : 'none';
            currentFacingMode = facingMode;
            
            // Start rendering loop
            requestAnimationFrame(renderFrame);
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Unable to access camera. Please ensure camera permissions are granted.');
        }
    }

    // Close camera
    function closeCamera() {
        document.getElementById('cameraModal').style.display = 'none';
        document.body.classList.remove('camera-active');
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    }

    // Switch camera
    function switchCamera() {
        const newFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
        initializeCamera(newFacingMode);
    }

    // Start attendance process
    function startAttendance(type) {
        currentAttendanceType = type;
        document.getElementById('cameraModal').style.display = 'block';
        document.body.classList.add('camera-active');
        initializeCamera('user');
        updateOverlayInfo(type);
        
        // Handle orientation change
        window.addEventListener('resize', () => {
            if (currentStream) {
                initializeCamera(currentFacingMode);
            }
        });
    }

    // Capture image with server timestamp
    async function captureImage() {
        try {
            // Fetch server time before capturing
            const timeResponse = await fetch('/api/server-time');
            if (!timeResponse.ok) {
                throw new Error('Failed to fetch server time');
            }
            
            const timeData = await timeResponse.json();
            const serverTimestamp = timeData.timestamp;

            // Add flash effect
            const flash = document.querySelector('.camera-flash');
            flash.classList.add('flash-active');
            
            // Remove flash class after animation
            setTimeout(() => {
                flash.classList.remove('flash-active');
            }, 300);

            const video = document.getElementById('camera-feed');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const context = canvas.getContext('2d');
            if (currentFacingMode === 'user') {
                context.scale(-1, 1);
                context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
            } else {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
            }

            // Add timestamp to the image
            context.font = '14px Arial';
            context.fillStyle = 'rgba(255, 255, 255, 0.8)';
            context.fillText(`Timestamp: ${serverTimestamp}`, 10, canvas.height - 10);

            const imageData = canvas.toDataURL('image/jpeg');
            
            // Store image data, location, and server timestamp in localStorage
            localStorage.setItem('capturedImage', imageData);
            localStorage.setItem('userLocation', document.getElementById('current-location').textContent);
            localStorage.setItem('serverTimestamp', serverTimestamp);
            
            // Close camera
            closeCamera();
            
            // Redirect to preview page with necessary parameters
            const params = new URLSearchParams({
                type: currentAttendanceType,
                name: '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}',
                timestamp: serverTimestamp
            });
            
            window.location.href = `/attendance/preview?${params.toString()}`;
        } catch (error) {
            console.error('Error capturing image:', error);
            alert('Error capturing image. Please try again.');
        }
    }

    // Initialize WebGL for beautification
    function initBeautifyCanvas() {
        beautifyCanvas = document.getElementById('beautify-canvas');
        beautifyCanvas.width = window.innerWidth;
        beautifyCanvas.height = window.innerHeight;
        
        try {
            beautifyContext = beautifyCanvas.getContext('webgl') || beautifyCanvas.getContext('experimental-webgl');
        } catch (e) {
            console.error('WebGL not supported:', e);
            return;
        }
        
        // Initialize shader program
        const vertexShader = createShader(beautifyContext.VERTEX_SHADER, `
            attribute vec2 a_position;
            attribute vec2 a_texCoord;
            varying vec2 v_texCoord;
            void main() {
                gl_Position = vec4(a_position, 0, 1);
                v_texCoord = a_texCoord;
            }
        `);
        
        const fragmentShader = createShader(beautifyContext.FRAGMENT_SHADER, `
            precision mediump float;
            uniform sampler2D u_image;
            uniform float u_smoothness;
            uniform float u_brightness;
            uniform float u_contrast;
            uniform float u_warmth;
            uniform float u_saturation;
            varying vec2 v_texCoord;
            
            void main() {
                vec4 color = texture2D(u_image, v_texCoord);
                
                // Apply smoothness (Gaussian blur)
                if (u_smoothness > 0.0) {
                    vec4 blur = vec4(0.0);
                    float total = 0.0;
                    for (float x = -2.0; x <= 2.0; x += 1.0) {
                        for (float y = -2.0; y <= 2.0; y += 1.0) {
                            vec2 offset = vec2(x, y) * u_smoothness / 100.0;
                            blur += texture2D(u_image, v_texCoord + offset);
                            total += 1.0;
                        }
                    }
                    color = mix(color, blur / total, u_smoothness / 100.0);
                }
                
                // Apply brightness
                color.rgb += u_brightness / 100.0;
                
                // Apply contrast
                color.rgb = (color.rgb - 0.5) * (1.0 + u_contrast / 100.0) + 0.5;
                
                // Apply warmth
                color.r += u_warmth / 200.0;
                color.b -= u_warmth / 200.0;
                
                // Apply saturation
                float gray = dot(color.rgb, vec3(0.299, 0.587, 0.114));
                color.rgb = mix(vec3(gray), color.rgb, 1.0 + u_saturation / 100.0);
                
                gl_FragColor = color;
            }
        `);
        
        // Create shader program
        beautifyShaderProgram = createProgram(vertexShader, fragmentShader);
        beautifyContext.useProgram(beautifyShaderProgram);
    }

    // Create shader helper function
    function createShader(type, source) {
        const shader = beautifyContext.createShader(type);
        beautifyContext.shaderSource(shader, source);
        beautifyContext.compileShader(shader);
        
        if (!beautifyContext.getShaderParameter(shader, beautifyContext.COMPILE_STATUS)) {
            console.error('Shader compile error:', beautifyContext.getShaderInfoLog(shader));
            beautifyContext.deleteShader(shader);
            return null;
        }
        
        return shader;
    }

    // Create program helper function
    function createProgram(vertexShader, fragmentShader) {
        const program = beautifyContext.createProgram();
        beautifyContext.attachShader(program, vertexShader);
        beautifyContext.attachShader(program, fragmentShader);
        beautifyContext.linkProgram(program);
        
        if (!beautifyContext.getProgramParameter(program, beautifyContext.LINK_STATUS)) {
            console.error('Program link error:', beautifyContext.getProgramInfoLog(program));
            beautifyContext.deleteProgram(program);
            return null;
        }
        
        return program;
    }

    // Update beautify effect
    function updateBeautifyEffect(type) {
        const value = document.getElementById(`${type}Slider`).value;
        document.getElementById(`${type}Value`).textContent = value;
        beautifySettings[type] = parseInt(value);
        
        if (beautifyShaderProgram) {
            const location = beautifyContext.getUniformLocation(beautifyShaderProgram, `u_${type}`);
            beautifyContext.uniform1f(location, beautifySettings[type]);
        }
    }

    // Toggle beautify controls
    function toggleBeautifyControls() {
        const controls = document.getElementById('beautifyControls');
        controls.classList.toggle('collapsed');
    }

    // Render frame function
    function renderFrame() {
        if (!currentStream || !beautifyContext || !beautifyShaderProgram) {
            requestAnimationFrame(renderFrame);
            return;
        }
        
        const video = document.getElementById('camera-feed');
        
        // Update canvas size if needed
        if (beautifyCanvas.width !== video.videoWidth || beautifyCanvas.height !== video.videoHeight) {
            beautifyCanvas.width = video.videoWidth;
            beautifyCanvas.height = video.videoHeight;
            beautifyContext.viewport(0, 0, beautifyCanvas.width, beautifyCanvas.height);
        }
        
        // Draw video frame to canvas with effects
        beautifyContext.drawImage(video, 0, 0);
        
        // Apply beautification effects
        // ... (WebGL rendering code here)
        
        requestAnimationFrame(renderFrame);
    }

    // Initialize with server time sync
    document.addEventListener('DOMContentLoaded', () => {
        // Initial update
        updateDateTime();
        
        // Set up periodic updates every second, but fetch from server every minute
        let secondsCounter = 0;
        setInterval(async () => {
            secondsCounter++;
            if (secondsCounter >= 60) {
                // Fetch fresh server time every minute
                secondsCounter = 0;
                await updateDateTime();
            } else {
                // For intermediate seconds, just update the display
                const timeDisplay = document.getElementById('current-time');
                const currentParts = timeDisplay.textContent.split(':');
                if (currentParts.length === 3) {
                    const seconds = parseInt(currentParts[2]);
                    currentParts[2] = ((seconds + 1) % 60).toString().padStart(2, '0');
                    timeDisplay.textContent = currentParts.join(':');
                }
            }
        }, 1000);

        initializeLocation();
    });

    // Clean up
    window.addEventListener('beforeunload', () => {
        document.body.classList.remove('camera-active');
        if (locationWatchId) {
            navigator.geolocation.clearWatch(locationWatchId);
        }
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endsection