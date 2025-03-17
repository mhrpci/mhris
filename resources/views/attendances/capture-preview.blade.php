@extends('layouts.app')

@section('styles')
<style>
    .capture-container {
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, #f6f9fc 0%, #ecf3f8 100%);
        padding: 2rem 0;
    }
    
    .capture-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #4285f4 0%, #3b77db 100%);
        color: white;
        padding: 1.5rem;
        position: relative;
    }
    
    .card-header h4 {
        margin-bottom: 0;
        font-weight: 600;
    }
    
    .card-header .back-button {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 1.2rem;
    }
    
    .camera-container {
        width: 100%;
        position: relative;
        background: #000;
        border-radius: 0;
        overflow: hidden;
    }
    
    .camera-feed {
        width: 100%;
        display: block;
        margin: 0 auto;
        max-height: 70vh;
        object-fit: cover;
    }
    
    .snapshot-container {
        display: none;
        position: relative;
    }
    
    .snapshot {
        width: 100%;
        display: block;
        margin: 0 auto;
        max-height: 70vh;
        object-fit: cover;
    }
    
    .camera-overlay {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 2rem;
    }
    
    .camera-btn {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        border: 3px solid rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .camera-btn:hover {
        background: rgba(255,255,255,0.5);
    }
    
    .camera-btn i {
        font-size: 1.8rem;
        color: white;
    }
    
    .snap-btn {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.8);
    }
    
    .snap-btn i {
        color: #3b77db;
    }
    
    .location-info {
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #edf2f7;
    }
    
    .location-details {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.5rem;
    }
    
    .controls {
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
    }
    
    .btn-retake, .btn-submit {
        flex: 1;
        padding: 1rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: none;
    }
    
    .btn-retake {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    
    .btn-retake:hover {
        background: #e5e7eb;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }
    
    #loadingOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        flex-direction: column;
        color: white;
        display: none;
    }
    
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-text {
        font-size: 1.2rem;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container capture-container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="capture-card">
                <div class="card-header d-flex justify-content-center align-items-center position-relative">
                    <a href="{{ route('attendances.attendance') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="mb-0">
                        <span id="captureType">Attendance Clock In/Out</span>
                    </h4>
                </div>
                
                <div class="camera-container">
                    <video id="cameraFeed" class="camera-feed" autoplay></video>
                    <div class="snapshot-container" id="snapshotContainer">
                        <img id="snapshot" class="snapshot">
                    </div>
                    <div class="camera-overlay">
                        <div class="camera-btn switch-btn" id="switchCamera">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="camera-btn snap-btn" id="captureButton">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="camera-btn flash-btn" id="flashButton">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                </div>
                
                <div class="location-info">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                        <h5 class="mb-0 ml-2">Your Location</h5>
                    </div>
                    <div class="location-details">
                        <p id="locationText" class="mb-0">Retrieving your location...</p>
                    </div>
                </div>
                
                <div class="controls">
                    <button id="retakeButton" class="btn btn-retake">
                        <i class="fas fa-redo mr-2"></i> Retake Photo
                    </button>
                    <button id="submitButton" class="btn btn-submit">
                        <i class="fas fa-check mr-2"></i> Submit Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay">
    <div class="spinner"></div>
    <div class="loading-text">Processing your attendance...</div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM elements
        const cameraFeed = document.getElementById('cameraFeed');
        const snapshotContainer = document.getElementById('snapshotContainer');
        const snapshot = document.getElementById('snapshot');
        const captureButton = document.getElementById('captureButton');
        const switchCameraButton = document.getElementById('switchCamera');
        const flashButton = document.getElementById('flashButton');
        const retakeButton = document.getElementById('retakeButton');
        const submitButton = document.getElementById('submitButton');
        const locationText = document.getElementById('locationText');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const captureTypeElement = document.getElementById('captureType');
        
        // Camera states
        let stream = null;
        let facingMode = 'user'; // Start with front-facing camera
        let flashEnabled = false;
        let imageCapture = null;
        let locationData = '';
        let captureType = 'in'; // Default to clock in
        
        // Check current attendance status
        checkAttendanceStatus();
        
        // Initialize camera
        initCamera();
        
        // Get user location
        getLocation();
        
        // Event listeners
        captureButton.addEventListener('click', captureImage);
        switchCameraButton.addEventListener('click', switchCamera);
        flashButton.addEventListener('click', toggleFlash);
        retakeButton.addEventListener('click', retakePhoto);
        submitButton.addEventListener('click', submitAttendance);
        
        // Functions
        async function checkAttendanceStatus() {
            try {
                const response = await fetch('/attendances/status');
                const data = await response.json();
                
                if (data.status === 'success') {
                    if (data.action === 'clock_in') {
                        captureType = 'in';
                        captureTypeElement.textContent = 'Attendance Clock In';
                    } else if (data.action === 'clock_out') {
                        captureType = 'out';
                        captureTypeElement.textContent = 'Attendance Clock Out';
                    } else {
                        // Already clocked in and out
                        window.location.href = '{{ route("attendances.attendance") }}';
                    }
                } else {
                    console.error('Error checking attendance status:', data.message);
                    alert('Error checking attendance status: ' + data.message);
                }
            } catch (error) {
                console.error('Error checking attendance status:', error);
                alert('Failed to check attendance status. Please try again.');
            }
        }
        
        async function initCamera() {
            try {
                if (stream) {
                    stopCamera();
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
                
                // Create ImageCapture object
                const videoTrack = stream.getVideoTracks()[0];
                imageCapture = new ImageCapture(videoTrack);
                
                // Check if flashlight is available
                const capabilities = videoTrack.getCapabilities();
                if (capabilities.torch) {
                    flashButton.style.display = 'flex';
                } else {
                    flashButton.style.display = 'none';
                }
            } catch (error) {
                console.error('Error accessing camera:', error);
                alert('Error accessing camera: ' + error.message);
            }
        }
        
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => {
                    track.stop();
                });
                stream = null;
            }
        }
        
        async function switchCamera() {
            facingMode = facingMode === 'user' ? 'environment' : 'user';
            await initCamera();
        }
        
        async function toggleFlash() {
            if (stream) {
                const videoTrack = stream.getVideoTracks()[0];
                if (videoTrack.getCapabilities().torch) {
                    flashEnabled = !flashEnabled;
                    try {
                        await videoTrack.applyConstraints({
                            advanced: [{ torch: flashEnabled }]
                        });
                        flashButton.style.background = flashEnabled ? 'rgba(255,255,0,0.5)' : 'rgba(255,255,255,0.3)';
                    } catch (error) {
                        console.error('Error toggling flash:', error);
                    }
                }
            }
        }
        
        async function captureImage() {
            try {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                
                // Set canvas dimensions to match video
                canvas.width = cameraFeed.videoWidth;
                canvas.height = cameraFeed.videoHeight;
                
                // Draw video frame to canvas
                context.drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);
                
                // Get image data
                const imageDataUrl = canvas.toDataURL('image/jpeg');
                
                // Show captured image
                snapshot.src = imageDataUrl;
                cameraFeed.style.display = 'none';
                snapshotContainer.style.display = 'block';
                
                // Show retake and submit buttons
                retakeButton.style.display = 'block';
                submitButton.style.display = 'block';
                
                // Hide camera controls
                captureButton.style.display = 'none';
                switchCameraButton.style.display = 'none';
                flashButton.style.display = 'none';
            } catch (error) {
                console.error('Error capturing image:', error);
                alert('Error capturing image: ' + error.message);
            }
        }
        
        function retakePhoto() {
            // Hide snapshot, show camera
            snapshotContainer.style.display = 'none';
            cameraFeed.style.display = 'block';
            
            // Hide retake and submit buttons
            retakeButton.style.display = 'none';
            submitButton.style.display = 'none';
            
            // Show camera controls
            captureButton.style.display = 'flex';
            switchCameraButton.style.display = 'flex';
            if (stream && stream.getVideoTracks()[0].getCapabilities().torch) {
                flashButton.style.display = 'flex';
            }
        }
        
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Use reverse geocoding to get address
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    locationData = data.display_name;
                                    locationText.textContent = locationData;
                                } else {
                                    locationData = `Latitude: ${lat}, Longitude: ${lng}`;
                                    locationText.textContent = locationData;
                                }
                            })
                            .catch(error => {
                                console.error('Error getting address:', error);
                                locationData = `Latitude: ${lat}, Longitude: ${lng}`;
                                locationText.textContent = locationData;
                            });
                    },
                    function(error) {
                        console.error('Error getting location:', error);
                        locationText.textContent = 'Could not retrieve location';
                        locationData = 'Location not available';
                    }
                );
            } else {
                locationText.textContent = 'Geolocation is not supported by this browser';
                locationData = 'Location not available';
            }
        }
        
        async function submitAttendance() {
            // Show loading overlay
            loadingOverlay.style.display = 'flex';
            
            try {
                // Prepare data for submission
                const data = {
                    type: captureType,
                    image: snapshot.src,
                    location: locationData,
                    timestamp: new Date().toISOString()
                };
                
                // Send to server
                const response = await fetch('/attendances/capture', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                // Hide loading overlay
                loadingOverlay.style.display = 'none';
                
                if (result.status === 'success') {
                    alert('Attendance recorded successfully');
                    window.location.href = '{{ route("attendances.attendance") }}';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error submitting attendance:', error);
                loadingOverlay.style.display = 'none';
                alert('Error submitting attendance: ' + error.message);
            }
        }
    });
</script>
@endsection
