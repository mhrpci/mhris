<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Full Screen Camera</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            overflow: hidden;
            background-color: #000;
            color: white;
            width: 100%;
            height: 100%;
            position: fixed;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        .camera-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        #video {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        
        /* For iOS devices to ensure proper display */
        @supports (-webkit-touch-callout: none) {
            #video {
                object-fit: cover;
                height: 100% !important;
                width: 100% !important;
            }
        }
        
        #canvas {
            display: none;
        }
        
        #photo {
            display: none;
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 5;
        }
        
        .controls {
            position: absolute;
            bottom: min(30px, 8vh);
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }
        
        .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            touch-action: manipulation;
        }
        
        .btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        
        .btn:active {
            transform: scale(0.95);
        }
        
        .btn-capture {
            width: clamp(50px, 15vw, 70px);
            height: clamp(50px, 15vw, 70px);
            background: rgba(255, 255, 255, 0.3);
            margin: 0 min(20px, 5vw);
        }
        
        .btn-gallery, .btn-switch {
            width: clamp(40px, 10vw, 50px);
            height: clamp(40px, 10vw, 50px);
            padding: clamp(8px, 2vw, 15px);
        }
        
        .btn-capture::before {
            content: '';
            display: block;
            width: 80%;
            height: 80%;
            background: white;
            border-radius: 50%;
        }
        
        .top-controls {
            position: absolute;
            top: max(env(safe-area-inset-top), 20px);
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            padding: 0 min(20px, 5vw);
            z-index: 10;
        }
        
        .flash-options, .camera-options {
            background: rgba(0, 0, 0, 0.5);
            padding: clamp(5px, 2vw, 10px) clamp(8px, 3vw, 15px);
            border-radius: 20px;
            font-size: clamp(12px, 3vw, 16px);
        }
        
        .photo-preview {
            position: absolute;
            bottom: min(30px, 8vh);
            left: min(20px, 5vw);
            width: clamp(40px, 10vw, 60px);
            height: clamp(40px, 10vw, 60px);
            border-radius: 8px;
            border: 2px solid white;
            overflow: hidden;
            z-index: 10;
        }
        
        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .gallery {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 20;
            display: none;
            flex-direction: column;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        .gallery-header {
            display: flex;
            justify-content: space-between;
            padding: clamp(10px, 3vw, 20px);
            align-items: center;
        }
        
        .gallery-content {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            padding: 10px;
            overflow-y: auto;
            justify-content: center;
            gap: 2vw;
        }
        
        .gallery-item {
            width: clamp(100px, 30vw, 180px);
            height: clamp(100px, 30vw, 180px);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .preview-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 20;
            display: none;
            flex-direction: column;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            padding: clamp(10px, 3vw, 20px);
            align-items: center;
        }
        
        .preview-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        
        .preview-content img {
            max-width: 95%;
            max-height: 80%;
            object-fit: contain;
        }
        
        .preview-actions {
            display: flex;
            justify-content: space-around;
            padding: clamp(15px, 4vw, 25px);
        }
        
        .close-btn, .gallery-btn, .action-btn {
            background: none;
            border: none;
            color: white;
            font-size: clamp(14px, 4vw, 16px);
            cursor: pointer;
            padding: 8px 15px;
            touch-action: manipulation;
        }
        
        .camera-modes {
            position: absolute;
            bottom: clamp(80px, 22vh, 120px);
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            z-index: 10;
        }
        
        .mode-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: clamp(12px, 3.5vw, 16px);
            margin: 0 clamp(8px, 3vw, 15px);
            cursor: pointer;
            padding: 5px 10px;
            touch-action: manipulation;
        }
        
        .mode-btn.active {
            color: white;
            border-bottom: 2px solid white;
        }
        
        .switch-camera {
            position: absolute;
            top: max(env(safe-area-inset-top), 20px);
            right: min(20px, 5vw);
            background: rgba(0, 0, 0, 0.5);
            width: clamp(35px, 10vw, 45px);
            height: clamp(35px, 10vw, 45px);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            cursor: pointer;
            touch-action: manipulation;
        }
        
        .no-camera {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #000;
            z-index: 30;
            text-align: center;
            padding: 20px;
        }
        
        .no-camera h2 {
            margin-bottom: 10px;
            font-size: clamp(18px, 5vw, 24px);
        }
        
        .no-camera p {
            font-size: clamp(14px, 4vw, 16px);
            max-width: 80%;
        }
        
        /* For iPhone X and newer devices with notches */
        @supports (padding: max(0px)) {
            .camera-container {
                padding-top: max(0px, env(safe-area-inset-top));
                padding-bottom: max(0px, env(safe-area-inset-bottom));
                padding-left: max(0px, env(safe-area-inset-left));
                padding-right: max(0px, env(safe-area-inset-right));
            }
            
            .controls {
                bottom: max(30px, env(safe-area-inset-bottom) + 20px);
            }
            
            .photo-preview {
                bottom: max(30px, env(safe-area-inset-bottom) + 20px);
                left: max(20px, env(safe-area-inset-left) + 10px);
            }
        }
        
        /* Orientation handling */
        @media screen and (orientation: landscape) {
            .camera-modes {
                bottom: auto;
                right: min(30px, 8vw);
                left: auto;
                top: 50%;
                transform: translateY(-50%);
                flex-direction: column;
            }
            
            .mode-btn {
                margin: clamp(5px, 1.5vh, 10px) 0;
            }
            
            .mode-btn.active {
                border-bottom: none;
                border-right: 2px solid white;
            }
        }
        
        /* Loading indicator */
        .loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 25;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        .error-message {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            z-index: 30;
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Prevent text selection */
        .camera-container, .gallery, .preview-screen {
            user-select: none;
            -webkit-user-select: none;
        }
    </style>
</head>
<body>
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <div class="no-camera" id="noCamera">
        <h2>Camera Access Required</h2>
        <p>Please allow access to your camera to use this application. You may need to update your browser settings.</p>
    </div>

    <div class="error-message" id="errorMessage"></div>

    <div class="camera-container">
        <video id="video" autoplay playsinline muted></video>
        <canvas id="canvas"></canvas>
        <img id="photo" alt="">
        
        <div class="top-controls">
            <div class="flash-options" id="flashOptions">Flash: Auto</div>
            <div class="camera-options" id="cameraOptions">HD</div>
        </div>
        
        <div class="switch-camera" id="switchCameraBtn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 5c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"></path>
                <path d="M14 19c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"></path>
                <path d="M5 12V5"></path>
                <path d="M19 12v7"></path>
                <path d="M5 12a7 7 0 0 0 14 0"></path>
                <path d="M19 12a7 7 0 0 0-14 0"></path>
            </svg>
        </div>
        
        <div class="camera-modes">
            <button class="mode-btn">VIDEO</button>
            <button class="mode-btn active">PHOTO</button>
            <button class="mode-btn">PORTRAIT</button>
        </div>
        
        <div class="controls">
            <div class="btn btn-gallery" id="galleryBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
            </div>
            <div class="btn btn-capture" id="captureBtn"></div>
            <div class="btn btn-switch" id="switchBtn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 12v9"></path>
                    <path d="M12 12L8 8"></path>
                    <path d="M12 12l4-4"></path>
                </svg>
            </div>
        </div>
        
        <div class="photo-preview" id="photoPreview">
            <!-- Preview thumbnail will be added here -->
        </div>
    </div>
    
    <div class="gallery" id="gallery">
        <div class="gallery-header">
            <button class="close-btn" id="closeGallery">Close</button>
            <h2>Gallery</h2>
            <button class="action-btn" id="selectBtn">Select</button>
        </div>
        <div class="gallery-content" id="galleryContent">
            <!-- Gallery items will be added here -->
        </div>
    </div>
    
    <div class="preview-screen" id="previewScreen">
        <div class="preview-header">
            <button class="close-btn" id="closePreview">Back</button>
            <button class="action-btn" id="editBtn">Edit</button>
        </div>
        <div class="preview-content" id="previewContent">
            <!-- Preview image will be added here -->
        </div>
        <div class="preview-actions">
            <button class="action-btn" id="shareBtn">Share</button>
            <button class="action-btn" id="deleteBtn">Delete</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photo = document.getElementById('photo');
            const captureBtn = document.getElementById('captureBtn');
            const switchBtn = document.getElementById('switchBtn');
            const switchCameraBtn = document.getElementById('switchCameraBtn');
            const photoPreview = document.getElementById('photoPreview');
            const gallery = document.getElementById('gallery');
            const galleryBtn = document.getElementById('galleryBtn');
            const closeGallery = document.getElementById('closeGallery');
            const galleryContent = document.getElementById('galleryContent');
            const previewScreen = document.getElementById('previewScreen');
            const closePreview = document.getElementById('closePreview');
            const previewContent = document.getElementById('previewContent');
            const loading = document.getElementById('loading');
            const noCamera = document.getElementById('noCamera');
            const errorMessage = document.getElementById('errorMessage');
            const flashOptions = document.getElementById('flashOptions');
            const cameraOptions = document.getElementById('cameraOptions');
            const shareBtn = document.getElementById('shareBtn');
            const deleteBtn = document.getElementById('deleteBtn');
            
            const photos = [];
            let currentStream = null;
            let facingMode = 'environment'; // Start with back camera
            let hasMultipleCameras = false;
            let wakeLock = null;
            let flashMode = 'auto';
            let currentCameraId = null;
            let videoDevices = [];
            let retryAttempts = 0;
            const MAX_RETRY_ATTEMPTS = 3;
            
            // Show error message
            function showError(message, duration = 3000) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
                
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, duration);
            }
            
            // Check if device has multiple cameras
            async function checkForMultipleCameras() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                    console.log("enumerateDevices() not supported.");
                    return false;
                }
                
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    videoDevices = devices.filter(device => device.kind === 'videoinput');
                    hasMultipleCameras = videoDevices.length > 1;
                    
                    // Hide camera switch button if only one camera
                    if (!hasMultipleCameras) {
                        switchCameraBtn.style.display = 'none';
                        switchBtn.style.display = 'none';
                    } else {
                        switchCameraBtn.style.display = 'flex';
                        switchBtn.style.display = 'flex';
                    }
                    
                    return hasMultipleCameras;
                } catch(err) {
                    console.error("Error checking cameras:", err);
                    return false;
                }
            }
            
            // Get optimal camera constraints
            function getOptimalConstraints() {
                const isLandscape = window.matchMedia("(orientation: landscape)").matches;
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                
                // Base constraints
                let constraints = {
                    video: {
                        facingMode: facingMode,
                        width: { ideal: isLandscape ? 1920 : 1080 },
                        height: { ideal: isLandscape ? 1080 : 1920 }
                    },
                    audio: false
                };
                
                // Add device ID if we have one selected
                if (currentCameraId) {
                    constraints.video.deviceId = { exact: currentCameraId };
                    delete constraints.video.facingMode;
                }
                
                // Add flash if supported and requested
                if ('torch' in ImageCapture.prototype && flashMode === 'on') {
                    constraints.video.advanced = [{ torch: true }];
                }
                
                return constraints;
            }
            
            // Access the camera
            async function startCamera() {
                if (currentStream) {
                    stopCamera();
                }
                
                loading.style.display = 'flex';
                noCamera.style.display = 'none';
                
                try {
                    const constraints = getOptimalConstraints();
                    currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                    
                    // Check for new permissions after access
                    await checkForMultipleCameras();
                    
                    video.srcObject = currentStream;
                    
                    // Get camera information
                    const track = currentStream.getVideoTracks()[0];
                    const capabilities = track.getCapabilities ? track.getCapabilities() : {};
                    
                    // Update UI based on camera capabilities
                    updateCameraUI(capabilities);
                    
                    loading.style.display = 'none';
                    retryAttempts = 0;
                    
                    // Try to enable torch if requested and available
                    if (flashMode === 'on' && 'torch' in capabilities) {
                        try {
                            await track.applyConstraints({ advanced: [{ torch: true }] });
                        } catch (e) {
                            console.log('Could not enable torch:', e);
                        }
                    }
                    
                    // Request wake lock
                    requestWakeLock();
                    
                } catch(err) {
                    console.error('Error accessing camera:', err);
                    
                    if (err.name === 'NotAllowedError') {
                        loading.style.display = 'none';
                        noCamera.style.display = 'flex';
                    } else if (retryAttempts < MAX_RETRY_ATTEMPTS) {
                        retryAttempts++;
                        // Try different constraints on failure
                        facingMode = facingMode === 'user' ? 'environment' : 'user';
                        setTimeout(() => {
                            startCamera();
                        }, 500);
                    } else {
                        loading.style.display = 'none';
                        showError('Could not access camera. Please try again.');
                    }
                }
            }
            
            // Stop camera stream
            function stopCamera() {
                if (currentStream) {
                    currentStream.getTracks().forEach(track => {
                        track.stop();
                    });
                    currentStream = null;
                }
            }
            
            // Update UI based on camera capabilities
            function updateCameraUI(capabilities) {
                // Update flash options based on availability
                if ('torch' in capabilities) {
                    flashOptions.style.display = 'block';
                    flashOptions.textContent = `Flash: ${flashMode.charAt(0).toUpperCase() + flashMode.slice(1)}`;
                    flashOptions.addEventListener('click', toggleFlash);
                } else {
                    flashOptions.style.display = 'none';
                }
                
                // Update camera quality options
                const track = currentStream.getVideoTracks()[0];
                const settings = track.getSettings();
                
                if (settings.width && settings.height) {
                    const mp = (settings.width * settings.height / 1000000).toFixed(1);
                    cameraOptions.textContent = `${mp}MP`;
                }
            }
            
            // Toggle flash modes (if supported)
            function toggleFlash() {
                if (!currentStream) return;
                
                const track = currentStream.getVideoTracks()[0];
                if (!track.getCapabilities || !('torch' in track.getCapabilities())) {
                    return;
                }
                
                // Cycle through flash modes
                if (flashMode === 'auto') {
                    flashMode = 'on';
                    track.applyConstraints({ advanced: [{ torch: true }] });
                } else if (flashMode === 'on') {
                    flashMode = 'off';
                    track.applyConstraints({ advanced: [{ torch: false }] });
                } else {
                    flashMode = 'auto';
                }
                
                flashOptions.textContent = `Flash: ${flashMode.charAt(0).toUpperCase() + flashMode.slice(1)}`;
            }
            
            // Check if video is ready and playing
            video.addEventListener('loadedmetadata', () => {
                video.play().catch(err => {
                    console.error('Error playing video:', err);
                });
            });
            
            // Switch between front and back cameras
            function toggleCamera() {
                if (!hasMultipleCameras) return;
                
                if (videoDevices.length > 1) {
                    // Use device IDs if available
                    if (currentCameraId) {
                        const currentIndex = videoDevices.findIndex(device => device.deviceId === currentCameraId);
                        const nextIndex = (currentIndex + 1) % videoDevices.length;
                        currentCameraId = videoDevices[nextIndex].deviceId;
                    } else {
                        currentCameraId = videoDevices[0].deviceId;
                    }
                } else {
                    // Fallback to facingMode
                    facingMode = facingMode === 'user' ? 'environment' : 'user';
                    currentCameraId = null;
                }
                
                startCamera();
            }
            
            // Take a photo with proper sizing
            function takePhoto() {
                const width = video.videoWidth;
                const height = video.videoHeight;
                
                if (width === 0 || height === 0) {
                    showError('Camera not ready yet. Please try again.');
                    return;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                // Draw the video frame to the canvas
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, width, height);
                
                // Convert canvas to data URL
                const imgData = canvas.toDataURL('image/jpeg', 0.85);
                
                // Store the photo
                photos.unshift(imgData);
                
                // Show in preview
                updatePhotoPreview();
                
                // Create shutter animation effect
                photo.src = imgData;
                photo.style.display = 'block';
                
                setTimeout(() => {
                    photo.style.display = 'none';
                }, 200);
                
                // Haptic feedback if available
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }
            
            // Update the small preview thumbnail
            function updatePhotoPreview() {
                if (photos.length > 0) {
                    photoPreview.innerHTML = `<img src="${photos[0]}" alt="Latest photo">`;
                    photoPreview.style.display = 'block';
                } else {
                    photoPreview.style.display = 'none';
                }
            }
            
            // Show all photos in gallery
            function showGallery() {
                galleryContent.innerHTML = '';
                
                if (photos.length === 0) {
                    galleryContent.innerHTML = '<p style="color: white; text-align: center; width: 100%;">No photos yet</p>';
                } else {
                    photos.forEach((photo, index) => {
                        const item = document.createElement('div');
                        item.className = 'gallery-item';
                        item.innerHTML = `<img src="${photo}" alt="Photo ${index}">`;
                        
                        item.addEventListener('click', () => {
                            showPreview(photo, index);
                        });
                        
                        galleryContent.appendChild(item);
                    });
                }
                
                gallery.style.display = 'flex';
            }
            
            // Show a single photo in preview mode
            function showPreview(photoSrc, index) {
                gallery.style.display = 'none';
                previewContent.innerHTML = `<img src="${photoSrc}" alt="Preview">`;
                previewScreen.style.display = 'flex';
                
                // Set up delete handler
                deleteBtn.onclick = () => {
                    deletePhoto(index);
                };
                
                // Set up share handler
                shareBtn.onclick = () => {
                    sharePhoto(photoSrc);
                };
            }
            
            // Delete a photo
            function deletePhoto(index) {
                if (index >= 0 && index < photos.length) {
                    photos.splice(index, 1);
                    
                    // Update the preview thumbnail
                    updatePhotoPreview();
                    
                    // Close preview and return to gallery
                    previewScreen.style.display = 'none';
                    showGallery();
                }
            }
            
            // Share a photo if Web Share API is available
            async function sharePhoto(photoSrc) {
                if (!navigator.share) {
                    showError('Sharing not supported on this browser');
                    return;
                }
                
                try {
                    // Convert data URL to blob for sharing
                    const blob = await (await fetch(photoSrc)).blob();
                    const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
                    
                    await navigator.share({
                        files: [file],
                        title: 'Camera Photo',
                    });
                } catch (err) {
                    console.error('Error sharing:', err);
                    showError('Could not share the photo');
                }
            }
            
            // Prevent double tap zoom on buttons
            function preventZoom(event) {
                event.preventDefault();
            }
            
            // Event listeners
            captureBtn.addEventListener('click', takePhoto);
            captureBtn.addEventListener('touchend', preventZoom);
            
            // Camera switch buttons
            switchBtn.addEventListener('click', toggleCamera);
            switchBtn.addEventListener('touchend', preventZoom);
            switchCameraBtn.addEventListener('click', toggleCamera);
            switchCameraBtn.addEventListener('touchend', preventZoom);
            
            galleryBtn.addEventListener('click', showGallery);
            galleryBtn.addEventListener('touchend', preventZoom);
            
            closeGallery.addEventListener('click', () => {
                gallery.style.display = 'none';
            });
            
            closePreview.addEventListener('click', () => {
                previewScreen.style.display = 'none';
                gallery.style.display = 'flex';
            });
            
            photoPreview.addEventListener('click', () => {
                if (photos.length > 0) {
                    showPreview(photos[0], 0);
                }
            });
            
            // Handle mode buttons
            const modeButtons = document.querySelectorAll('.mode-btn');
            modeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    modeButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                });
                btn.addEventListener('touchend', preventZoom);
            });
            
            // Handle orientation changes
            window.addEventListener('orientationchange', () => {
                // Small delay to allow the orientation to fully change
                setTimeout(() => {
                    if (currentStream) {
                        startCamera();
                    }
                }, 300);
            });
            
            // Handle resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                // Debounce to prevent multiple restarts
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    if (currentStream) {
                        startCamera();
                    }
                }, 500);
            });
            
            // Handle visibility change (when user switches tabs/apps)
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') {
                    if (!currentStream || !currentStream.active) {
                        startCamera();
                    }
                } else {
                    // Release wake lock when not visible
                    releaseWakeLock();
                }
            });
            
            // Create full-screen experience
            function requestFullscreen() {
                const elem = document.documentElement;
                
                if (elem.requestFullscreen) {
                    elem.requestFullscreen().catch(err => {
                        console.log('Error attempting to enable fullscreen:', err);
                    });
                } else if (elem.webkitRequestFullscreen) {
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem.msRequestFullscreen();
                }
            }
            
            // Wake lock to prevent screen from turning off (if supported)
            async function requestWakeLock() {
                if ('wakeLock' in navigator) {
                    try {
                        wakeLock = await navigator.wakeLock.request('screen');
                        wakeLock.addEventListener('release', () => {
                            console.log('Wake Lock released');
                        });
                    } catch (err) {
                        console.log(`Wake Lock error: ${err.name}, ${err.message}`);
                    }
                }
            }
            
            // Release wake lock
            function releaseWakeLock() {
                if (wakeLock !== null) {
                    wakeLock.release()
                        .then(() => {
                            wakeLock = null;
                        });
                }
            }
            
            // Prevent default touch behaviors except for gallery scrolling
            document.addEventListener('touchmove', (e) => {
                if (e.target.closest('.gallery-content')) return;
                e.preventDefault();
            }, { passive: false });
            
            // Prevent pull-to-refresh
            document.addEventListener('touchstart', (e) => {
                if (e.touches.length !== 1) return;
                startY = e.touches[0].clientY;
            }, { passive: false });
            
            let startY = 0;
            document.addEventListener('touchmove', (e) => {
                if (e.touches.length !== 1) return;
                const y = e.touches[0].clientY;
                const deltaY = y - startY;
                
                // Prevent pull-to-refresh behavior
                if (deltaY > 0 && window.scrollY === 0) {
                    e.preventDefault();
                }
            }, { passive: false });
            
            // Fix for iOS audio context
            function unlockAudioContext() {
                if (!/iPhone|iPad|iPod/i.test(navigator.userAgent)) return;
                
                document.addEventListener('touchstart', () => {
                    // Create and play silent audio to unlock audio context
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioCtx.createOscillator();
                    oscillator.frequency.value = 1;
                    oscillator.connect(audioCtx.destination);
                    oscillator.start(0);
                    oscillator.stop(0.001);
                }, { once: true });
            }
            
            // Fix ImageCapture polyfill if not available
            if (typeof ImageCapture === 'undefined') {
                window.ImageCapture = function() {
                    this.prototype = {};
                };
            }
            
            // Start the app
            unlockAudioContext();
            
            // Try to start in fullscreen for a better experience
            document.addEventListener('click', requestFullscreen, { once: true });
            
            // Start the camera when page loads
            startCamera();
            
            // Listen for unload to clean up resources
            window.addEventListener('beforeunload', () => {
                stopCamera();
                releaseWakeLock();
            });
        });
    </script>
</body>
</html>