@extends('layouts.app')

@section('styles')
<style>
    /* Base styles */
    body.preview-active {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    
    .preview-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #000;
        z-index: 9990;
    }
    
    /* Image preview section */
    .image-preview-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background-color: #000;
    }
    
    .preview-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Camera interface elements */
    .camera-interface {
        display: none;
    }

    .camera-frame {
        display: none;
    }

    .camera-corners {
        position: absolute;
        width: 30px;
        height: 30px;
        border: 3px solid #4285f4;
    }

    .corner-top-left {
        top: -3px;
        left: -3px;
        border-right: none;
        border-bottom: none;
        border-top-left-radius: 8px;
    }

    .corner-top-right {
        top: -3px;
        right: -3px;
        border-left: none;
        border-bottom: none;
        border-top-right-radius: 8px;
    }

    .corner-bottom-left {
        bottom: -3px;
        left: -3px;
        border-right: none;
        border-top: none;
        border-bottom-left-radius: 8px;
    }

    .corner-bottom-right {
        bottom: -3px;
        right: -3px;
        border-left: none;
        border-top: none;
        border-bottom-right-radius: 8px;
    }
    
    /* Logo overlay */
    .preview-logo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 100px;
        height: auto;
        z-index: 9992;
        background: rgba(255, 255, 255, 0.9);
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Status badge */
    .preview-status-badge {
        position: absolute;
        bottom: 160px; /* Position above the datetime */
        left: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 12px 24px;
        border-radius: 30px;
        color: white;
        font-weight: bold;
        z-index: 9992;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 1.2rem;
    }
    
    .preview-status-badge.in {
        background: rgba(16, 185, 129, 0.2);
        border-color: rgba(16, 185, 129, 0.4);
    }
    
    .preview-status-badge.out {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    /* Large status indicator */
    .preview-status-large {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 8rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.15);
        text-transform: uppercase;
        pointer-events: none;
        z-index: 9991;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        letter-spacing: 4px;
    }

    .preview-status-large.in {
        color: rgba(40, 167, 69, 0.15);
    }

    .preview-status-large.out {
        color: rgba(220, 53, 69, 0.15);
    }
    
    /* Info overlay */
    .preview-info-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        color: white;
        z-index: 9992;
        padding: 25px;
        background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.8) 50%, rgba(0,0,0,0.4) 85%, transparent 100%);
    }
    
    .preview-overlay-content {
        max-width: 100%;
        display: grid;
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .preview-company-name {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 5px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
    }
    
    .preview-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .preview-status-badge {
        position: relative;
        bottom: auto;
        left: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 8px 16px;
        border-radius: 30px;
        color: white;
        font-weight: bold;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 1rem;
    }
    
    .preview-datetime {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .preview-time {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        letter-spacing: 0.5px;
    }
    
    .preview-date {
        font-size: 1.1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.9);
        margin: 0;
    }
    
    .preview-details {
        display: grid;
        gap: 12px;
        margin-top: 5px;
    }
    
    .preview-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: rgba(255,255,255,0.95);
        margin: 0;
        letter-spacing: 0.3px;
    }
    
    .preview-info-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .preview-info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .preview-info-label {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .preview-info-value {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.9);
        font-weight: 500;
    }
    
    .preview-location {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-top: 5px;
    }
    
    .preview-location i {
        margin-top: 3px;
        color: #4285f4;
        font-size: 1rem;
    }
    
    .preview-location-text {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.9);
        line-height: 1.4;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .preview-info-overlay {
            padding: 20px;
        }
        
        .preview-time {
            font-size: 1.8rem;
        }
        
        .preview-date {
            font-size: 1rem;
        }
        
        .preview-name {
            font-size: 1.1rem;
        }
        
        .preview-info-value {
            font-size: 0.9rem;
        }
        
        .preview-company-name {
            font-size: 0.9rem;
            padding: 6px 10px;
        }
    }
    
    @media (max-width: 480px) {
        .preview-info-overlay {
            padding: 15px;
        }
        
        .preview-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .preview-time {
            font-size: 1.6rem;
        }
        
        .preview-date {
            font-size: 0.9rem;
        }
        
        .preview-name {
            font-size: 1rem;
        }
        
        .preview-info-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .preview-info-value {
            font-size: 0.85rem;
        }
        
        .preview-status-badge {
            font-size: 0.9rem;
            padding: 6px 12px;
        }
        
        .preview-company-name {
            font-size: 0.8rem;
            padding: 5px 8px;
        }
    }
    
    /* Action buttons */
    .preview-actions {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.7) 50%, transparent 100%);
        height: 100px;
        z-index: 9992;
        gap: 1.5rem;
    }
    
    .btn-confirm {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }
    
    .btn-retake {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
    }
    
    .btn-confirm:hover, .btn-retake:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    
    .btn-confirm:active, .btn-retake:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Loading overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    
    .loading-spinner {
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 5px solid #4285f4;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }
    
    .loading-text {
        color: white;
        font-size: 1.2rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Alert messages */
    .alert-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 25px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
        max-width: 90%;
    }
    
    .alert-message.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    
    .alert-message.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .alert-message.error {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .alert-message i {
        font-size: 1.2rem;
    }
</style>
@endsection

@section('content')
<div class="preview-container">
    <div class="image-preview-container">
        <img id="preview-image" class="preview-image" src="" alt="Attendance Capture">
        
        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
        
        <div class="preview-status-large" id="preview-status-large">IN</div>
        
        <div class="preview-info-overlay">
            <div class="preview-overlay-content">
                <div class="preview-company-name" id="preview-company-name"></div>
                <div class="preview-header">
                    <div id="preview-status-badge" class="preview-status-badge">
                        <i class="fas fa-clock"></i>
                        <span id="status-text">Clock In</span>
                    </div>
                    
                    <div class="preview-datetime">
                        <div class="preview-time" id="preview-time"></div>
                        <div class="preview-date" id="preview-date"></div>
                    </div>
                </div>
                
                <div class="preview-details">
                    <div class="preview-name" id="preview-name"></div>
                    
                    <div class="preview-info-row">
                        <div class="preview-info-item">
                            <span class="preview-info-label">Position</span>
                            <span class="preview-info-value" id="preview-position"></span>
                        </div>
                        <div class="preview-info-item">
                            <span class="preview-info-label">Department</span>
                            <span class="preview-info-value" id="preview-department"></span>
                        </div>
                    </div>
                    
                    <div class="preview-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="preview-location-text" id="preview-location"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="preview-actions">
            <button class="btn-retake" onclick="goBack()">
                <i class="fas fa-redo"></i>
                Retake
            </button>
            <button class="btn-confirm" onclick="confirmAttendance()">
                <i class="fas fa-check"></i>
                Confirm
            </button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Processing your attendance...</div>
</div>

<!-- Alert Message -->
<div class="alert-message" id="alert-message">
    <i class="fas fa-check-circle"></i>
    <span id="alert-text">Message goes here</span>
</div>
@endsection

@section('scripts')
<!-- Include html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Variables to store attendance data
    let capturedImage = '';
    let attendanceType = '';
    let userLocation = '';
    let serverTimestamp = '';
    let employeeName = '';
    let employeePosition = '';
    let employeeDepartment = '';
    let isSubmitting = false; // Prevent multiple submissions
    
    // Initialize the preview page
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            // Add class to body for full screen mode
            document.body.classList.add('preview-active');
            
            // Get data from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            attendanceType = urlParams.get('type') || 'in';
            
            // Get data from localStorage
            capturedImage = localStorage.getItem('capturedImage');
            userLocation = localStorage.getItem('userLocation');
            serverTimestamp = localStorage.getItem('serverTimestamp');
            
            if (!capturedImage || !serverTimestamp) {
                showAlert('Missing capture data. Please try again.', 'error');
                setTimeout(() => {
                    window.location.href = '/attendance';
                }, 2000);
                return;
            }
            
            // Set the captured image
            const previewImage = document.getElementById('preview-image');
            previewImage.onload = function() {
                console.log("Image loaded successfully");
            };
            previewImage.onerror = function() {
                console.error("Error loading preview image");
                showAlert('Error loading captured image. Please try again.', 'error');
            };
            previewImage.src = capturedImage;
            
            // Set the status badge and large status text
            const statusBadge = document.getElementById('preview-status-badge');
            const statusText = document.getElementById('status-text');
            const statusLarge = document.getElementById('preview-status-large');
            
            statusBadge.className = `preview-status-badge ${attendanceType}`;
            statusText.textContent = attendanceType === 'in' ? 'Clock In' : 'Clock Out';
            
            statusLarge.textContent = attendanceType === 'in' ? 'IN' : 'OUT';
            statusLarge.className = `preview-status-large ${attendanceType}`;
            
            // Get server time for display
            await updateTimeDisplay();
            
            // Get employee information
            await getEmployeeInfo();
            
            // Set location
            document.getElementById('preview-location').textContent = userLocation || 'Location not available';
            
            // Check for success or error messages in URL
            const successMsg = urlParams.get('success');
            const errorMsg = urlParams.get('error');
            
            if (successMsg) {
                showAlert(decodeURIComponent(successMsg), 'success');
            } else if (errorMsg) {
                showAlert(decodeURIComponent(errorMsg), 'error');
            }
            
        } catch (error) {
            console.error('Error initializing preview:', error);
            showAlert('An error occurred while loading the preview. Please try again.', 'error');
            setTimeout(() => {
                window.location.href = '/attendance';
            }, 2000);
        }
    });
    
    // Show alert message
    function showAlert(message, type = 'success') {
        const alertElement = document.getElementById('alert-message');
        const alertText = document.getElementById('alert-text');
        
        alertText.textContent = message;
        alertElement.className = `alert-message ${type}`;
        
        // Show the alert
        setTimeout(() => {
            alertElement.classList.add('show');
        }, 100);
        
        // Hide after 5 seconds
        setTimeout(() => {
            alertElement.classList.remove('show');
        }, 5000);
    }
    
    // Update time display with server time
    async function updateTimeDisplay() {
        try {
            // Parse the timestamp from localStorage
            const timestamp = new Date(serverTimestamp);
            
            if (isNaN(timestamp.getTime())) {
                throw new Error('Invalid timestamp');
            }
            
            // Format time and date
            const timeStr = new Intl.DateTimeFormat('en-US', { 
                hour12: true,
                hour: '2-digit',
                minute: '2-digit'
            }).format(timestamp).toUpperCase();
            
            const dateStr = new Intl.DateTimeFormat('en-US', { 
                weekday: 'short',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            }).format(timestamp);
            
            // Update the display
            document.getElementById('preview-time').textContent = timeStr;
            document.getElementById('preview-date').textContent = dateStr;
            
        } catch (error) {
            console.error('Error updating time display:', error);
            document.getElementById('preview-time').textContent = 'Time unavailable';
            document.getElementById('preview-date').textContent = 'Date unavailable';
        }
    }
    
    // Get employee information
    async function getEmployeeInfo() {
        try {
            // Get authenticated user info
            const response = await fetch('/api/employee-info');
            if (!response.ok) {
                throw new Error(`Failed to fetch employee information: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Update employee information
            document.getElementById('preview-name').textContent = data.name || '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
            document.getElementById('preview-position').textContent = data.position || 'Position not available';
            document.getElementById('preview-department').textContent = data.department || 'Department not available';
            
            // Store for later use
            employeeName = data.name || '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
            employeePosition = data.position || '';
            employeeDepartment = data.department || '';
            
            // Update company name based on department
            const department = data.department || '{{ Auth::user()->department }}';
            let companyName = '';
            
            switch(department.toUpperCase()) {
                case 'MHRHCI':
                    companyName = 'Medical & Resources Health Care, Inc.';
                    break;
                case 'BGPDI':
                    companyName = 'Bay Gas and Petroleum Distribution, Inc.';
                    break;
                case 'VHI':
                    companyName = 'Verbena Hotel Inc.';
                    break;
                default:
                    companyName = 'MHR Property Conglomerates, Inc.';
            }
            
            document.getElementById('preview-company-name').textContent = companyName;
            
        } catch (error) {
            console.error('Error fetching employee info:', error);
            // Fallback to Auth user data
            document.getElementById('preview-name').textContent = '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
            document.getElementById('preview-position').textContent = 'Position not available';
            document.getElementById('preview-department').textContent = 'Department not available';
            document.getElementById('preview-company-name').textContent = 'MHR Property Conglomerates, Inc.';
        }
    }
    
    // Go back to attendance page
    function goBack() {
        document.body.classList.remove('preview-active');
        window.location.href = '/attendance';
    }
    
    // Confirm attendance
    async function confirmAttendance() {
        // Prevent multiple submissions
        if (isSubmitting) {
            console.log('Submission already in progress');
            return;
        }
        
        isSubmitting = true;
        
        try {
            // Show loading overlay
            document.getElementById('loading-overlay').style.display = 'flex';
            
            // Capture the entire preview with overlays
            console.log('Starting image capture');
            let previewImage = await capturePreviewWithOverlays();
            
            if (!previewImage) {
                throw new Error('Failed to capture preview image');
            }
            console.log('Image captured successfully');
            
            // Check image size
            let approximateSize = Math.round(previewImage.length * 0.75); // base64 size approximation
            console.log(`Approximate image size: ${Math.round(approximateSize / 1024)} KB`);
            
            // Ensure image doesn't exceed 10MB limit
            const MAX_SIZE = 10 * 1024 * 1024; // 10MB limit
            
            // If image is too large, reduce quality until it's under the limit
            if (approximateSize > MAX_SIZE) {
                console.warn('Image size too large, reducing quality');
                
                // Start with current quality and reduce as needed
                let quality = 0.8;
                const minQuality = 0.3; // Don't go below this quality
                const tempCanvas = document.createElement('canvas');
                const ctx = tempCanvas.getContext('2d');
                
                // Create image element from the current data
                const img = new Image();
                img.src = previewImage;
                
                // Wait for image to load
                await new Promise(resolve => {
                    img.onload = resolve;
                    img.onerror = () => {
                        console.error('Error loading image for resizing');
                        resolve(); // Continue even if there's an error
                    };
                });
                
                // Set canvas dimensions
                tempCanvas.width = img.width;
                tempCanvas.height = img.height;
                
                // Draw image to canvas
                ctx.drawImage(img, 0, 0);
                
                // Reduce quality until size is acceptable or we hit minimum quality
                while (approximateSize > MAX_SIZE && quality > minQuality) {
                    quality -= 0.1;
                    previewImage = tempCanvas.toDataURL('image/jpeg', quality);
                    approximateSize = Math.round(previewImage.length * 0.75);
                    console.log(`Reduced quality to ${quality.toFixed(1)}, new size: ${Math.round(approximateSize / 1024)} KB`);
                }
                
                // If still too large, try scaling down the image
                if (approximateSize > MAX_SIZE) {
                    console.warn('Still too large, reducing dimensions');
                    
                    // Gradually reduce dimensions until size is acceptable
                    let scale = 0.9;
                    const minScale = 0.5; // Don't go below 50% of original size
                    
                    while (approximateSize > MAX_SIZE && scale > minScale) {
                        // Reduce dimensions
                        tempCanvas.width = img.width * scale;
                        tempCanvas.height = img.height * scale;
                        
                        // Clear canvas and redraw scaled image
                        ctx.drawImage(img, 0, 0, tempCanvas.width, tempCanvas.height);
                        
                        // Convert to data URL with current quality
                        previewImage = tempCanvas.toDataURL('image/jpeg', quality);
                        approximateSize = Math.round(previewImage.length * 0.75);
                        
                        console.log(`Reduced scale to ${scale.toFixed(1)}, new size: ${Math.round(approximateSize / 1024)} KB`);
                        scale -= 0.1;
                    }
                }
                
                console.log(`Final image size: ${Math.round(approximateSize / 1024)} KB`);
            }
            
            // Prepare data for submission
            const attendanceData = {
                type: attendanceType,
                image: previewImage,
                location: userLocation,
                timestamp: serverTimestamp
            };
            
            console.log('Submitting attendance data');
            // Submit attendance data
            const response = await fetch('/attendance/capture', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(attendanceData)
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Server error: ${response.status}. ${errorText}`);
            }
            
            const result = await response.json();
            console.log('Server response:', result);
            
            // Hide loading overlay
            document.getElementById('loading-overlay').style.display = 'none';
            
            if (result.status === 'success') {
                // Show success message
                showAlert(result.message, 'success');
                
                // Clear localStorage
                localStorage.removeItem('capturedImage');
                localStorage.removeItem('userLocation');
                localStorage.removeItem('serverTimestamp');
                
                // Redirect to attendance page with success message after a delay
                setTimeout(() => {
                    document.body.classList.remove('preview-active');
                    window.location.href = '/attendance?success=' + encodeURIComponent(result.message);
                }, 2000);
            } else {
                // Show error message
                showAlert(result.message || 'Failed to record attendance', 'error');
                isSubmitting = false;
            }
            
        } catch (error) {
            console.error('Error confirming attendance:', error);
            
            // Hide loading overlay
            document.getElementById('loading-overlay').style.display = 'none';
            
            // Show error message
            showAlert(error.message || 'Failed to record attendance. Please try again.', 'error');
            isSubmitting = false;
        }
    }
    
    // Capture the preview with all overlays
    async function capturePreviewWithOverlays() {
        try {
            // Hide the buttons and alert message during capture
            const actionsElement = document.querySelector('.preview-actions');
            const alertElement = document.getElementById('alert-message');
            const originalActionsDisplay = actionsElement.style.display;
            const originalAlertDisplay = alertElement.style.display;
            
            actionsElement.style.display = 'none';
            alertElement.style.display = 'none';
            
            // Use html2canvas to capture the entire preview container
            const previewContainer = document.querySelector('.image-preview-container');
            
            // Wait longer for display changes to take effect
            await new Promise(resolve => setTimeout(resolve, 300));
            
            console.log('Starting html2canvas rendering');
            // Use html2canvas library to capture the preview with overlays
            const canvas = await html2canvas(previewContainer, {
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#000000',
                scale: 1.5, // Medium quality to reduce size
                logging: false,
                onclone: function(clonedDoc) {
                    // Ensure all images are loaded in the cloned document
                    const images = clonedDoc.getElementsByTagName('img');
                    console.log(`Processing ${images.length} images in cloned document`);
                    for (let img of images) {
                        if (!img.complete) {
                            console.warn(`Image not loaded: ${img.src}`);
                        }
                    }
                }
            });
            
            console.log('Canvas rendered successfully');
            
            // Restore the buttons and alert
            actionsElement.style.display = originalActionsDisplay;
            alertElement.style.display = originalAlertDisplay;
            
            try {
                // Add additional information to the image
                enhanceCanvasWithDetails(canvas, {
                    name: employeeName || 'User',
                    position: employeePosition || 'Not available',
                    department: employeeDepartment || 'Not available',
                    location: userLocation || 'Not available',
                    timestamp: serverTimestamp || new Date().toISOString(),
                    type: attendanceType || 'in'
                });
                console.log('Canvas enhanced with details');
            } catch (enhanceError) {
                console.error('Error enhancing canvas:', enhanceError);
                // Continue without enhancement
            }
            
            // Convert canvas to base64 image with reduced quality to manage size
            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            console.log('Canvas converted to image data');
            
            return imageData;
        } catch (error) {
            console.error('Error capturing preview with overlays:', error);
            // Try a simpler method as fallback
            try {
                console.log('Attempting fallback capture method');
                const previewContainer = document.querySelector('.image-preview-container');
                
                // Use a more basic configuration
                const canvas = await html2canvas(previewContainer, {
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#000000',
                    scale: 1, // Lowest quality
                    imageTimeout: 0, // No timeout
                    logging: true, // Enable logging
                    ignoreElements: (element) => {
                        // Ignore problematic elements
                        return element.classList.contains('preview-logo');
                    }
                });
                
                return canvas.toDataURL('image/jpeg', 0.7);
            } catch (fallbackError) {
                console.error('Fallback capture also failed:', fallbackError);
                return null;
            }
        }
    }
    
    // Add additional information to the canvas
    function enhanceCanvasWithDetails(canvas, details) {
        try {
            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;
            
            // Add a gradient footer for additional information
            const footerHeight = 60;
            const gradient = ctx.createLinearGradient(0, height - footerHeight - 20, 0, height);
            gradient.addColorStop(0, 'rgba(0, 0, 0, 0)');
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0.9)');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, height - footerHeight - 20, width, footerHeight + 20);
            
            // Set text style for main verification text
            ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
            ctx.font = 'bold 16px Arial';
            ctx.textBaseline = 'middle';
            
            // Format timestamp
            let timestamp;
            try {
                timestamp = new Date(details.timestamp);
                if (isNaN(timestamp.getTime())) {
                    throw new Error('Invalid date');
                }
            } catch (e) {
                console.warn('Invalid timestamp, using current date:', e);
                timestamp = new Date();
            }
            
            const formattedDate = timestamp.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const formattedTime = timestamp.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            
            // Add detailed verification text
            const verificationText = `${(details.type || 'in').toUpperCase()} VERIFICATION`;
            ctx.fillText(verificationText, 20, height - footerHeight + 15);
            
            // Add timestamp details
            ctx.font = '14px Arial';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
            ctx.fillText(`Date: ${formattedDate}`, 20, height - footerHeight + 35);
            ctx.fillText(`Time: ${formattedTime}`, 20, height - footerHeight + 55);
            
            // Add employee details on the right
            ctx.textAlign = 'right';
            ctx.fillText(`${details.name || 'User'}`, width - 20, height - footerHeight + 15);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
            ctx.fillText(`${details.position || 'Position not available'}`, width - 20, height - footerHeight + 35);
            ctx.fillText(`${details.department || 'Department not available'}`, width - 20, height - footerHeight + 55);
            
            // Add location in the middle
            ctx.textAlign = 'center';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
            ctx.fillText(`Location: ${details.location || 'Not available'}`, width/2, height - footerHeight + 35);
            
            // Add system verification text
            ctx.font = 'bold 12px Arial';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
            const systemText = 'HRIS ATTENDANCE SYSTEM';
            ctx.fillText(systemText, width/2, height - footerHeight + 55);
            
            // Add unique verification ID (shortened to reduce canvas processing)
            const verificationId = `ID: ${Math.random().toString(36).substring(2, 10).toUpperCase()}`;
            ctx.fillText(verificationId, width/2, height - footerHeight + 15);
            
            // Add simplified watermark (reduced size to improve performance)
            ctx.save();
            ctx.globalAlpha = 0.05; // Reduced opacity
            ctx.font = 'bold 100px Arial'; // Smaller font
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.translate(width/2, height/2);
            ctx.rotate(-Math.PI/6); // Rotate -30 degrees
            const watermarkText = `${details.type === 'in' ? 'IN' : 'OUT'}`;
            ctx.fillText(watermarkText, 0, 0);
            ctx.font = 'bold 50px Arial'; // Smaller font
            ctx.fillText('VERIFIED', 0, 70);
            ctx.restore();
        } catch (error) {
            console.error('Error enhancing canvas with details:', error);
            // Continue without enhancement
        }
    }
    
    // Clean up when leaving the page
    window.addEventListener('beforeunload', () => {
        document.body.classList.remove('preview-active');
    });
    
    // Add event listener to retry if image loading fails
    window.addEventListener('error', function(e) {
        if (e.target.tagName.toLowerCase() === 'img') {
            console.warn('Image loading error:', e);
            // Try to reload the image once
            if (!e.target.dataset.reloaded) {
                e.target.dataset.reloaded = 'true';
                setTimeout(() => {
                    e.target.src = e.target.src;
                }, 500);
            }
        }
    }, true);
</script>
@endsection
