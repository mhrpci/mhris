<!-- Full Screen Camera Capture -->
<div class="modal fade camera-fullscreen-modal" id="cameraCaptureModal" tabindex="-1" role="dialog" aria-labelledby="cameraCaptureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen m-0" role="document">
        <div class="modal-content border-0">
            <div class="camera-header">
                <div class="d-flex justify-content-between align-items-center w-100 px-3 py-2">
                    <button type="button" class="close-camera btn text-white" data-dismiss="modal">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h5 class="text-white mb-0">
                        <span id="captureTitle">Attendance Verification</span>
                    </h5>
                    <div class="camera-controls">
                        <button id="toggleFilters" class="camera-ctrl-btn" type="button">
                            <i class="fas fa-magic"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="camera-body position-relative p-0">
                <!-- Camera Preview with Mirror Effect for Front Camera -->
                <div class="camera-container">
                    <video id="cameraPreview" playsinline autoplay></video>
                    
                    <!-- Camera Feedback Overlay -->
                    <div id="cameraOverlay" class="camera-overlay">
                        <div class="camera-guide">
                            <div class="face-outline">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <p class="guide-text">Position your face in the center</p>
                        </div>
                    </div>
                    
                    <!-- Filter Overlay -->
                    <div id="filterOverlay" class="filter-overlay"></div>
                    
                    <!-- Loading State -->
                    <div id="cameraLoading" class="camera-loading d-none">
                        <div class="spinner-grow text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="loading-text">Initializing camera...</p>
                    </div>
                    
                    <!-- Error State -->
                    <div id="cameraError" class="camera-error d-none">
                        <i class="fas fa-exclamation-triangle text-danger mb-3"></i>
                        <p class="error-text">Camera access denied</p>
                        <p class="error-subtext">Please allow camera access to continue</p>
                        <button id="retryCamera" class="btn btn-sm btn-outline-light mt-2">
                            <i class="fas fa-redo mr-1"></i> Try Again
                        </button>
                    </div>
                    
                    <!-- Capture Feedback -->
                    <div id="captureSuccess" class="capture-feedback d-none">
                        <div class="success-animation">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Captured Image Preview (Hidden initially) - Clean View without UI Elements -->
                <div id="capturedImageContainer" class="captured-container d-none">
                    <img id="capturedImage" src="" alt="Captured photo">
                </div>
                
                <!-- Camera Controls -->
                <div class="camera-top-controls">
                    <button id="toggleFlash" class="camera-ctrl-btn" disabled>
                        <i class="fas fa-bolt"></i>
                    </button>
                    <button id="toggleBeautify" class="camera-ctrl-btn">
                        <i class="fas fa-smile"></i>
                    </button>
                </div>
                
                <!-- Zoom Control -->
                <div class="zoom-control">
                    <input type="range" class="zoom-slider" id="zoomLevel" min="1" max="5" step="0.1" value="1">
                </div>
                
                <!-- Action Status -->
                <div class="action-status">
                    <p id="captureStatus" class="mb-0">Ready to capture</p>
                </div>
            </div>
            
            <!-- Bottom Camera Controls -->
            <div class="camera-footer">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <button id="switchCamera" class="camera-ctrl-btn">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    
                    <div class="capture-buttons-wrapper">
                        <button id="captureBtn" type="button" class="capture-btn">
                            <div class="capture-btn-inner"></div>
                        </button>
                    </div>
                    
                    <div class="camera-actions">
                        <button id="confirmBtn" type="button" class="btn btn-success rounded-circle p-3 d-none">
                            <i class="fas fa-check"></i>
                        </button>
                        <button id="retakeBtn" type="button" class="btn btn-outline-light rounded-circle p-3 d-none">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Filter Selection Panel (Hidden by default) -->
                <div id="filterPanel" class="filter-panel d-none">
                    <div class="filter-options">
                        <div class="filter-option" data-filter="none">
                            <div class="filter-preview">
                                <span>Normal</span>
                            </div>
                        </div>
                        <div class="filter-option" data-filter="grayscale">
                            <div class="filter-preview filter-grayscale">
                                <span>B&W</span>
                            </div>
                        </div>
                        <div class="filter-option" data-filter="sepia">
                            <div class="filter-preview filter-sepia">
                                <span>Sepia</span>
                            </div>
                        </div>
                        <div class="filter-option" data-filter="brightness">
                            <div class="filter-preview filter-brightness">
                                <span>Bright</span>
                            </div>
                        </div>
                        <div class="filter-option" data-filter="contrast">
                            <div class="filter-preview filter-contrast">
                                <span>Contrast</span>
                            </div>
                        </div>
                        <div class="filter-option" data-filter="blur">
                            <div class="filter-preview filter-blur">
                                <span>Blur</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Beautify Controls Panel (Hidden by default) -->
                <div id="beautifyPanel" class="filter-panel beautify-panel d-none">
                    <div class="beautify-controls">
                        <div class="beautify-control">
                            <label>Smoothness</label>
                            <input type="range" id="smoothnessLevel" min="0" max="5" step="0.5" value="2">
                            <span class="value-display">2</span>
                        </div>
                        <div class="beautify-control">
                            <label>Face Shape</label>
                            <input type="range" id="faceShapeLevel" min="0" max="5" step="0.5" value="1">
                            <span class="value-display">1</span>
                        </div>
                        <div class="beautify-control">
                            <label>Eyes Enlarge</label>
                            <input type="range" id="eyesLevel" min="0" max="5" step="0.5" value="1">
                            <span class="value-display">1</span>
                        </div>
                        <div class="beautify-control">
                            <label>Brightness</label>
                            <input type="range" id="skinBrightnessLevel" min="0" max="5" step="0.5" value="1">
                            <span class="value-display">1</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this after the camera-overlay div -->
<div class="attendance-info-overlay">
    <div class="attendance-info-content">
        <div class="attendance-type">
            <i class="fas fa-user-circle"></i>
            <span id="attendanceTypeText">Clock In</span>
        </div>
        <div class="attendance-details">
            <div class="detail-item">
                <i class="fas fa-clock"></i>
                <span id="attendanceTime">--:--:--</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span id="attendanceLocation">Fetching location...</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-user"></i>
                <span id="attendanceName">John Doe</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-building"></i>
                <span id="attendanceCompany">Company Name</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-sitemap"></i>
                <span id="attendanceDepartment">Department Name</span>
            </div>
            <div class="detail-item">
                <i class="fas fa-briefcase"></i>
                <span id="attendancePosition">Position Name</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Full Screen Camera Styles */
    .modal-fullscreen {
        width: 100vw;
        height: 100vh;
        max-width: none;
        margin: 0;
        padding: 0;
    }
    
    .modal-fullscreen .modal-content {
        height: 100vh;
        border: 0;
        border-radius: 0;
        display: flex;
        flex-direction: column;
    }
    
    .camera-fullscreen-modal {
        padding: 0 !important;
    }
    
    .camera-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1050;
        background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
    }
    
    .camera-body {
        flex: 1;
        overflow: hidden;
        background-color: #000;
        position: relative;
        height: 100vh;
    }
    
    .camera-container {
        width: 100%;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
    
    #cameraPreview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Mirror effect for front camera */
    #cameraPreview.mirror {
        transform: scaleX(-1);
    }
    
    .filter-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 10;
    }
    
    .camera-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 20;
    }
    
    .camera-guide {
        text-align: center;
    }
    
    .face-outline {
        width: 120px;
        height: 120px;
        margin: 0 auto 15px;
        border-radius: 50%;
        border: 2px dashed rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .face-outline i {
        font-size: 80px;
        color: rgba(255, 255, 255, 0.3);
    }
    
    .guide-text {
        color: #fff;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        font-size: 14px;
        margin: 0;
    }
    
    .camera-top-controls {
        position: absolute;
        top: 70px;
        right: 20px;
        display: flex;
        flex-direction: column;
        z-index: 30;
    }
    
    .camera-ctrl-btn {
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        transition: all 0.2s;
    }
    
    .camera-ctrl-btn:focus {
        outline: none;
    }
    
    .camera-ctrl-btn:active {
        transform: scale(0.95);
    }
    
    .camera-ctrl-btn.active {
        background: rgba(13, 110, 253, 0.7);
        color: white;
    }
    
    .zoom-control {
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        z-index: 30;
    }
    
    .zoom-slider {
        -webkit-appearance: none;
        width: 150px;
        height: 4px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.3);
        outline: none;
        writing-mode: bt-lr;
        -webkit-writing-mode: bt-lr;
        transform: rotate(90deg);
    }
    
    .zoom-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: white;
        cursor: pointer;
    }
    
    .zoom-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: white;
        cursor: pointer;
    }
    
    .action-status {
        position: absolute;
        bottom: 120px;
        left: 0;
        right: 0;
        text-align: center;
        color: #fff;
        background: rgba(0, 0, 0, 0.4);
        padding: 8px;
        font-size: 14px;
    }
    
    .camera-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
        padding: 20px;
        z-index: 1050;
    }
    
    .capture-buttons-wrapper {
        position: relative;
    }
    
    .capture-btn {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        border: 3px solid rgba(255, 255, 255, 0.8);
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .capture-btn-inner {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: white;
        transition: all 0.3s ease;
    }
    
    .capture-btn:active .capture-btn-inner {
        width: 48px;
        height: 48px;
    }
    
    .filter-panel {
        position: absolute;
        bottom: 100px;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        padding: 15px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .filter-options {
        display: flex;
        overflow-x: auto;
        padding-bottom: 10px;
        -webkit-overflow-scrolling: touch;
    }
    
    .filter-options::-webkit-scrollbar {
        height: 0;
        width: 0;
        background: transparent;
    }
    
    .filter-option {
        margin-right: 15px;
        text-align: center;
        min-width: 60px;
    }
    
    .filter-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-bottom: 5px;
        background-color: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        border: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .filter-option.active .filter-preview {
        border-color: #0d6efd;
    }
    
    .filter-grayscale {
        filter: grayscale(1);
    }
    
    .filter-sepia {
        filter: sepia(0.8);
    }
    
    .filter-brightness {
        filter: brightness(1.5);
    }
    
    .filter-contrast {
        filter: contrast(1.5);
    }
    
    .filter-blur {
        filter: blur(2px);
        overflow: hidden;
    }
    
    /* Beautify Panel Styles */
    .beautify-panel {
        padding: 20px;
    }
    
    .beautify-controls {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .beautify-control {
        display: flex;
        flex-direction: column;
    }
    
    .beautify-control label {
        color: white;
        font-size: 14px;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .beautify-control input[type="range"] {
        -webkit-appearance: none;
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.3);
        outline: none;
        margin-bottom: 5px;
    }
    
    .beautify-control input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        cursor: pointer;
    }
    
    .beautify-control input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        cursor: pointer;
    }
    
    .beautify-control .value-display {
        color: rgba(255, 255, 255, 0.8);
        font-size: 12px;
        text-align: center;
    }
    
    .camera-loading, .camera-error, .capture-feedback {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
        z-index: 40;
    }
    
    .camera-error i {
        font-size: 48px;
    }
    
    .error-text {
        font-size: 18px;
        margin-bottom: 5px;
    }
    
    .error-subtext {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 10px;
    }
    
    .loading-text {
        margin-top: 15px;
        font-size: 14px;
    }
    
    .captured-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        z-index: 50; /* Higher z-index to be above all UI elements */
    }
    
    #capturedImage {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .success-animation {
        animation: pulse 0.5s ease-in-out;
    }
    
    .success-animation i {
        font-size: 80px;
    }
    
    @keyframes pulse {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    /* Make the captured image container full screen without any UI elements */
    .captured-preview-mode .camera-controls,
    .captured-preview-mode .camera-top-controls,
    .captured-preview-mode .zoom-control,
    .captured-preview-mode .action-status,
    .captured-preview-mode .camera-footer > *:not(.camera-actions) {
        display: none !important;
    }
    
    .captured-preview-mode .camera-footer {
        background: none;
    }
    
    .captured-preview-mode .camera-actions {
        position: fixed;
        bottom: 30px;
        right: 30px;
    }
    
    /* Professional polish */
    .camera-header h5 {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .action-status {
        letter-spacing: 0.5px;
        font-weight: 300;
    }
    
    /* Attendance Info Overlay Styles */
    .attendance-info-overlay {
        position: absolute;
        bottom: 120px;
        left: 20px;
        z-index: 30;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 15px;
        max-width: 300px;
        color: white;
        font-size: 13px;
        pointer-events: none;
    }
    
    .attendance-info-content {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .attendance-type {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 500;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .attendance-type i {
        font-size: 20px;
        color: #0d6efd;
    }
    
    .attendance-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .detail-item i {
        width: 16px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
    }
    
    .detail-item span {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Adjust camera footer to not overlap with info overlay */
    .camera-footer {
        padding-bottom: 30px;
    }
    
    /* Ensure the info overlay doesn't interfere with camera controls */
    .camera-top-controls {
        z-index: 31;
    }
    
    .zoom-control {
        z-index: 31;
    }
    
    .action-status {
        z-index: 31;
    }
</style>

<script>
    // Camera handling script
    let cameraStream = null;
    let capturedData = null;
    let actionType = ''; // 'clock-in' or 'clock-out'
    let currentCameraFacing = 'user'; // 'user' (front) or 'environment' (back)
    let currentFilter = 'none';
    let isBeautifyActive = false;
    let zoomLevel = 1;
    let flashActive = false;
    let availableCameras = [];
    let currentCameraIndex = 0;
    let videoTrack = null;
    
    // Beautify settings (default values)
    let beautifySettings = {
        smoothness: 2,
        faceShape: 1,
        eyes: 1,
        skinBrightness: 1
    };
    
    // Add these variables at the top with other variables
    let attendanceInfo = {
        name: 'John Doe',
        company: 'Company Name',
        department: 'Department Name',
        position: 'Position Name'
    };
    
    // Initialize the camera when modal is opened
    $('#cameraCaptureModal').on('shown.bs.modal', function () {
        document.body.style.overflow = 'hidden'; // Prevent body scrolling when modal is open
        initializeCamera();
        
        // Update modal title based on action type
        $('#captureTitle').text(actionType === 'clock-in' ? 'Clock In Verification' : 'Clock Out Verification');
    });
    
    // Stop the camera when modal is closed
    $('#cameraCaptureModal').on('hidden.bs.modal', function () {
        document.body.style.overflow = ''; // Restore body scrolling
        stopCamera();
    });
    
    // Get available cameras
    async function getAvailableCameras() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
            console.log("enumerateDevices() not supported.");
            return [];
        }
        
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            return devices.filter(device => device.kind === 'videoinput');
        } catch (error) {
            console.error('Error enumerating devices:', error);
            return [];
        }
    }
    
    // Initialize camera
    async function initializeCamera() {
        // Show loading state
        $('#cameraLoading').removeClass('d-none');
        $('#cameraOverlay').addClass('d-none');
        $('#cameraError').addClass('d-none');
        $('#capturedImageContainer').addClass('d-none');
        $('#cameraPreview').removeClass('d-none');
        
        // Reset buttons
        $('#captureBtn').removeClass('d-none');
        $('#confirmBtn, #retakeBtn').addClass('d-none');
        
        // Get available cameras
        try {
            availableCameras = await getAvailableCameras();
            
            // Get camera constraints
            const constraints = {
                video: { 
                    facingMode: currentCameraFacing,
                    width: { ideal: 1920 },
                    height: { ideal: 1080 },
                    zoom: zoomLevel
                },
                audio: false 
            };
            
            // Access the user's camera
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            cameraStream = stream;
            const video = document.getElementById('cameraPreview');
            video.srcObject = stream;
            
            // Apply mirror effect for front camera
            if (currentCameraFacing === 'user') {
                $('#cameraPreview').addClass('mirror');
            } else {
                $('#cameraPreview').removeClass('mirror');
            }
            
            // Get video track for capabilities
            videoTrack = stream.getVideoTracks()[0];
            
            // Check if flash is supported
            const capabilities = videoTrack.getCapabilities ? videoTrack.getCapabilities() : {};
            if (capabilities.torch) {
                $('#toggleFlash').prop('disabled', false);
            } else {
                $('#toggleFlash').prop('disabled', true).removeClass('active');
            }
            
            // Apply active filter
            applyFilter(currentFilter);
            
            // Apply beautify if active
            if (isBeautifyActive) {
                applyBeautify();
            }
            
            // Wait for video to be ready
            video.onloadedmetadata = function() {
                // Hide loading, show camera guide
                $('#cameraLoading').addClass('d-none');
                $('#cameraOverlay').removeClass('d-none');
                $('#captureStatus').text('Position your face clearly in the frame');
                
                // Update attendance info
                updateAttendanceInfo();
                
                // Update time every second
                setInterval(updateAttendanceInfo, 1000);
            };
        } catch (error) {
            console.error('Camera error:', error);
            $('#cameraLoading').addClass('d-none');
            $('#cameraError').removeClass('d-none');
            $('#captureStatus').text('Camera access denied');
        }
    }
    
    // Stop camera stream
    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => {
                // Turn off flash if it was on
                if (flashActive && track.getCapabilities && track.getCapabilities().torch) {
                    track.applyConstraints({ advanced: [{ torch: false }] });
                }
                track.stop();
            });
            cameraStream = null;
        }
    }
    
    // Toggle between front and back cameras
    $('#switchCamera').click(async function() {
        // First stop current camera
        stopCamera();
        
        // Toggle camera facing mode
        currentCameraFacing = currentCameraFacing === 'user' ? 'environment' : 'user';
        
        // Restart camera with new facing mode
        await initializeCamera();
    });
    
    // Handle zoom level change
    $('#zoomLevel').on('input', function() {
        zoomLevel = parseFloat($(this).val());
        applyZoom();
    });
    
    // Apply zoom level to video track
    function applyZoom() {
        if (videoTrack && videoTrack.getCapabilities && videoTrack.getCapabilities().zoom) {
            videoTrack.applyConstraints({ advanced: [{ zoom: zoomLevel }] })
                .catch(error => console.error('Error applying zoom:', error));
        } else {
            // If zoom is not supported by the API, use CSS scale as fallback
            $('#cameraPreview').css('transform', `scale(${zoomLevel}) ${currentCameraFacing === 'user' ? 'scaleX(-1)' : ''}`);
        }
    }
    
    // Toggle flash/torch
    $('#toggleFlash').click(function() {
        if ($(this).prop('disabled')) return;
        
        flashActive = !flashActive;
        $(this).toggleClass('active', flashActive);
        
        if (videoTrack && videoTrack.getCapabilities && videoTrack.getCapabilities().torch) {
            videoTrack.applyConstraints({ advanced: [{ torch: flashActive }] })
                .catch(error => console.error('Error toggling flash:', error));
        }
    });
    
    // Toggle beautify filter and panel
    $('#toggleBeautify').click(function() {
        isBeautifyActive = !isBeautifyActive;
        $(this).toggleClass('active', isBeautifyActive);
        
        // Hide filter panel if it's open
        $('#filterPanel').addClass('d-none');
        
        // Toggle beautify panel
        $('#beautifyPanel').toggleClass('d-none', !isBeautifyActive);
        
        applyBeautify();
    });
    
    // Update beautify sliders
    $('#smoothnessLevel').on('input', function() {
        beautifySettings.smoothness = parseFloat($(this).val());
        $(this).siblings('.value-display').text(beautifySettings.smoothness);
        applyBeautify();
    });
    
    $('#faceShapeLevel').on('input', function() {
        beautifySettings.faceShape = parseFloat($(this).val());
        $(this).siblings('.value-display').text(beautifySettings.faceShape);
        applyBeautify();
    });
    
    $('#eyesLevel').on('input', function() {
        beautifySettings.eyes = parseFloat($(this).val());
        $(this).siblings('.value-display').text(beautifySettings.eyes);
        applyBeautify();
    });
    
    $('#skinBrightnessLevel').on('input', function() {
        beautifySettings.skinBrightness = parseFloat($(this).val());
        $(this).siblings('.value-display').text(beautifySettings.skinBrightness);
        applyBeautify();
    });
    
    // Apply enhanced beautify filter using CSS
    function applyBeautify() {
        if (isBeautifyActive) {
            // Create a CSS filter string based on beautify settings
            const smoothnessBlur = beautifySettings.smoothness * 0.25; // 0-1.25px blur
            const brightness = 1 + (beautifySettings.skinBrightness * 0.04); // 1-1.2 brightness
            const contrast = 1 - (beautifySettings.faceShape * 0.02); // 0.9-1 contrast
            const saturation = 1 + (beautifySettings.eyes * 0.05); // 1-1.25 saturation
            
            $('#cameraPreview').css({
                'filter': `brightness(${brightness}) contrast(${contrast}) saturate(${saturation}) blur(${smoothnessBlur}px)`
            });
        } else {
            // Remove the beautify filter, but keep any other active filter
            $('#beautifyPanel').addClass('d-none');
            applyFilter(currentFilter);
        }
    }
    
    // Toggle filter panel
    $('#toggleFilters').click(function() {
        // Hide beautify panel if it's open
        $('#beautifyPanel').addClass('d-none');
        
        // Toggle filter panel
        $('#filterPanel').toggleClass('d-none');
        
        // Update active state
        if ($('#toggleBeautify').hasClass('active')) {
            $('#toggleBeautify').removeClass('active');
            isBeautifyActive = false;
            applyFilter(currentFilter);
        }
    });
    
    // Select filter
    $('.filter-option').click(function() {
        const filter = $(this).data('filter');
        currentFilter = filter;
        
        // Update active filter UI
        $('.filter-option').removeClass('active');
        $(this).addClass('active');
        
        // Apply the selected filter
        applyFilter(filter);
    });
    
    // Apply filter to video
    function applyFilter(filter) {
        let filterStyle = '';
        
        switch(filter) {
            case 'grayscale':
                filterStyle = 'grayscale(1)';
                break;
            case 'sepia':
                filterStyle = 'sepia(0.8)';
                break;
            case 'brightness':
                filterStyle = 'brightness(1.5)';
                break;
            case 'contrast':
                filterStyle = 'contrast(1.5)';
                break;
            case 'blur':
                filterStyle = 'blur(2px)';
                break;
            default:
                filterStyle = 'none';
        }
        
        // Apply the filter to the video element
        $('#cameraPreview').css('filter', filterStyle);
        
        // If beautify is active, apply it on top of the filter
        if (isBeautifyActive) {
            applyBeautify();
        }
    }
    
    // Capture image from camera
    $('#captureBtn').click(function() {
        if (!cameraStream) return;
        
        const video = document.getElementById('cameraPreview');
        const canvas = document.createElement('canvas');
        const isMirrored = currentCameraFacing === 'user';
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        
        // Handle mirroring for front camera when capturing
        if (isMirrored) {
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
        }
        
        // Apply the same filters to the canvas as were applied to the video
        if (currentFilter !== 'none' || isBeautifyActive) {
            context.filter = $('#cameraPreview').css('filter');
        }
        
        // Draw the current video frame on canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert to data URL
        capturedData = canvas.toDataURL('image/jpeg', 0.9);
        
        // Show success animation
        $('#captureSuccess').removeClass('d-none');
        setTimeout(() => {
            $('#captureSuccess').addClass('d-none');
            
            // Hide all camera UI for clean preview
            $('.modal-content').addClass('captured-preview-mode');
            
            // Hide video, show captured image
            $('#cameraPreview').addClass('d-none');
            $('#cameraOverlay').addClass('d-none');
            $('#filterPanel').addClass('d-none');
            $('#beautifyPanel').addClass('d-none');
            $('#capturedImageContainer').removeClass('d-none');
            $('#capturedImage').attr('src', capturedData);
            
            // Update buttons
            $('#captureBtn').addClass('d-none');
            $('#confirmBtn, #retakeBtn').removeClass('d-none');
            
            $('#captureStatus').text('Verify your photo');
        }, 1000);
    });
    
    // Retake photo
    $('#retakeBtn').click(function() {
        // Restore camera UI
        $('.modal-content').removeClass('captured-preview-mode');
        
        // Hide captured image, show video again
        $('#capturedImageContainer').addClass('d-none');
        $('#cameraPreview').removeClass('d-none');
        $('#cameraOverlay').removeClass('d-none');
        
        // Reset buttons
        $('#captureBtn').removeClass('d-none');
        $('#confirmBtn, #retakeBtn').addClass('d-none');
        
        $('#captureStatus').text('Position your face clearly in the frame');
    });
    
    // Confirm photo and perform attendance action
    $('#confirmBtn').click(function() {
        // Get current time for the appropriate clock action
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        
        // Close modal
        $('#cameraCaptureModal').modal('hide');
        
        // Call the appropriate function in the parent page
        if (actionType === 'clock-in') {
            // Notify parent page the clock in was successful with photo
            window.completeClockIn(timeString, capturedData);
        } else {
            // Notify parent page the clock out was successful with photo
            window.completeClockOut(timeString, capturedData);
        }
    });
    
    // Retry camera after error
    $('#retryCamera').click(function() {
        initializeCamera();
    });
    
    // Public function to set action type and open camera
    window.openCameraForAction = function(action) {
        actionType = action;
        $('#cameraCaptureModal').modal('show');
        
        // Reset any active filters/beautify
        $('.filter-option').removeClass('active');
        $('.filter-option[data-filter="none"]').addClass('active');
        currentFilter = 'none';
        
        // Reset beautify settings to defaults
        $('#smoothnessLevel').val(2).siblings('.value-display').text('2');
        $('#faceShapeLevel').val(1).siblings('.value-display').text('1');
        $('#eyesLevel').val(1).siblings('.value-display').text('1');
        $('#skinBrightnessLevel').val(1).siblings('.value-display').text('1');
        
        beautifySettings = {
            smoothness: 2,
            faceShape: 1,
            eyes: 1,
            skinBrightness: 1
        };
        
        // Reset modal state
        $('.modal-content').removeClass('captured-preview-mode');
        
        // Update attendance info immediately
        updateAttendanceInfo();
    };
    
    // Add this function to update attendance info
    function updateAttendanceInfo() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        $('#attendanceTypeText').text(actionType === 'clock-in' ? 'Clock In' : 'Clock Out');
        $('#attendanceTime').text(timeString);
        $('#attendanceLocation').text(window.currentLocationAddress || 'Fetching location...');
        $('#attendanceName').text(attendanceInfo.name);
        $('#attendanceCompany').text(attendanceInfo.company);
        $('#attendanceDepartment').text(attendanceInfo.department);
        $('#attendancePosition').text(attendanceInfo.position);
    }
</script> 