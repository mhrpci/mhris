@extends('layouts.app')

@section('styles')
<style>
    /* Base styles */
    body.preview-active {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
        background: #000;
    }
    
    .preview-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #000;
        z-index: 9990;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Image preview section */
    .image-preview-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background-color: #000;
        max-width: 1440px;
        margin: 0 auto;
    }
    
    .preview-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.95);
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
        top: 24px;
        right: 24px;
        width: 120px;
        height: auto;
        z-index: 9992;
        background: rgba(255, 255, 255, 0.95);
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    /* Status badge */
    .preview-status-badge {
        position: absolute;
        top: 24px;
        left: 24px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        padding: 14px 28px;
        border-radius: 16px;
        color: white;
        font-weight: 600;
        z-index: 9992;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .preview-status-badge.in {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.3);
    }
    
    .preview-status-badge.out {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
    }
    
    /* Large status indicator */
    .preview-status-large {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.08);
        text-transform: uppercase;
        pointer-events: none;
        z-index: 9991;
        text-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        letter-spacing: 8px;
        transition: all 0.5s ease;
    }

    .preview-status-large.in {
        color: rgba(16, 185, 129, 0.08);
    }

    .preview-status-large.out {
        color: rgba(239, 68, 68, 0.08);
    }
    
    /* Info overlay */
    .preview-info-overlay {
        position: absolute;
        left: 0;
        bottom: 100px;
        width: 100%;
        color: white;
        z-index: 9992;
        padding: 32px;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
        backdrop-filter: blur(8px);
    }
    
    .preview-overlay-content {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .preview-time {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        letter-spacing: 1px;
    }
    
    .preview-date {
        font-size: 1.2rem;
        font-weight: 500;
        color: rgba(255,255,255,0.95);
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }
    
    .preview-name {
        font-size: 1.3rem;
        font-weight: 600;
        color: rgba(255,255,255,1);
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }
    
    .preview-position {
        font-size: 1rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }
    
    .preview-department {
        font-size: 1rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 16px;
        letter-spacing: 0.3px;
    }
    
    .preview-location {
        font-size: 1rem;
        color: rgba(255,255,255,0.9);
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(8px);
        max-width: fit-content;
    }
    
    .preview-location i {
        color: #4285f4;
        font-size: 1.2rem;
    }
    
    /* Action buttons */
    .preview-actions {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 24px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.8) 50%, transparent 100%);
        backdrop-filter: blur(12px);
        z-index: 9992;
        gap: 20px;
    }
    
    .btn-confirm, .btn-retake {
        padding: 16px 36px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        color: white;
        cursor: pointer;
        min-width: 180px;
        justify-content: center;
    }
    
    .btn-confirm {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.2);
    }
    
    .btn-retake {
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
        box-shadow: 0 4px 20px rgba(75, 85, 99, 0.2);
    }
    
    .btn-confirm:hover, .btn-retake:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    
    .btn-confirm:active, .btn-retake:active {
        transform: translateY(1px);
    }
    
    /* Loading overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(12px);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    
    .loading-spinner {
        border: 4px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        border-top: 4px solid #4285f4;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        margin-bottom: 24px;
    }
    
    .loading-text {
        color: white;
        font-size: 1.2rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    /* Alert messages */
    .alert-message {
        position: fixed;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        padding: 16px 32px;
        border-radius: 16px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 90%;
        backdrop-filter: blur(12px);
        letter-spacing: 0.3px;
    }
    
    .alert-message.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    
    .alert-message.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, rgba(5, 150, 105, 0.95) 100%);
    }
    
    .alert-message.error {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.95) 0%, rgba(220, 38, 38, 0.95) 100%);
    }
    
    .alert-message i {
        font-size: 1.4rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive design */
    @media (max-width: 1200px) {
        .preview-status-large {
            font-size: 10rem;
        }
        
        .preview-time {
            font-size: 2.2rem;
        }
    }
    
    @media (max-width: 992px) {
        .preview-status-large {
            font-size: 8rem;
        }
        
        .preview-info-overlay {
            padding: 24px;
        }
        
        .preview-time {
            font-size: 2rem;
        }
        
        .btn-confirm, .btn-retake {
            padding: 14px 32px;
            font-size: 1rem;
            min-width: 160px;
        }
    }
    
    @media (max-width: 768px) {
        .preview-logo {
            width: 100px;
            top: 20px;
            right: 20px;
            padding: 10px;
        }
        
        .preview-status-badge {
            top: 20px;
            left: 20px;
            padding: 12px 24px;
            font-size: 1rem;
        }
        
        .preview-status-large {
            font-size: 6rem;
        }
        
        .preview-info-overlay {
            padding: 20px;
            bottom: 90px;
        }
        
        .preview-time {
            font-size: 1.8rem;
        }
        
        .preview-date {
            font-size: 1.1rem;
        }
        
        .preview-name {
            font-size: 1.2rem;
        }
        
        .preview-location {
            padding: 10px 16px;
        }
    }
    
    @media (max-width: 576px) {
        .preview-logo {
            width: 80px;
            top: 16px;
            right: 16px;
            padding: 8px;
        }
        
        .preview-status-badge {
            top: 16px;
            left: 16px;
            padding: 10px 20px;
            font-size: 0.9rem;
        }
        
        .preview-status-large {
            font-size: 4rem;
        }
        
        .preview-info-overlay {
            padding: 16px;
            bottom: 85px;
        }
        
        .preview-time {
            font-size: 1.6rem;
        }
        
        .preview-date {
            font-size: 1rem;
            margin-bottom: 16px;
        }
        
        .preview-name {
            font-size: 1.1rem;
        }
        
        .preview-position,
        .preview-department {
            font-size: 0.9rem;
        }
        
        .preview-location {
            font-size: 0.9rem;
            padding: 8px 14px;
        }
        
        .preview-actions {
            padding: 16px;
            gap: 12px;
        }
        
        .btn-confirm, .btn-retake {
            padding: 12px 24px;
            font-size: 0.9rem;
            min-width: 140px;
        }
        
        .alert-message {
            padding: 12px 24px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 360px) {
        .preview-status-large {
            font-size: 3rem;
        }
        
        .preview-time {
            font-size: 1.4rem;
        }
        
        .btn-confirm, .btn-retake {
            padding: 10px 20px;
            font-size: 0.85rem;
            min-width: 120px;
        }
    }
</style>
@endsection

@section('content')
<div class="preview-container">
    <div class="image-preview-container">
        <img id="preview-image" class="preview-image" src="" alt="Attendance Capture">
        
        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
        
        <div id="preview-status-badge" class="preview-status-badge">
            <i class="fas fa-clock"></i>
            <span id="status-text">Clock In</span>
        </div>
        
        <div class="preview-status-large" id="preview-status-large">IN</div>
        
        <div class="preview-info-overlay">
            <div class="preview-overlay-content">
                <div class="preview-time" id="preview-time"></div>
                <div class="preview-date" id="preview-date"></div>
                <div class="preview-name" id="preview-name"></div>
                <div class="preview-position" id="preview-position"></div>
                <div class="preview-department" id="preview-department"></div>
                <div class="preview-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="preview-location"></span>
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
            document.getElementById('preview-image').src = capturedImage;
            
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
                throw new Error('Failed to fetch employee information');
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
            
        } catch (error) {
            console.error('Error fetching employee info:', error);
            // Fallback to Auth user data
            document.getElementById('preview-name').textContent = '{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}';
            document.getElementById('preview-position').textContent = 'Position not available';
            document.getElementById('preview-department').textContent = 'Department not available';
        }
    }
    
    // Go back to attendance page
    function goBack() {
        document.body.classList.remove('preview-active');
        window.location.href = '/attendance';
    }
    
    // Confirm attendance
    async function confirmAttendance() {
        try {
            // Show loading overlay
            document.getElementById('loading-overlay').style.display = 'flex';
            
            // Capture the entire preview with overlays
            const previewImage = await capturePreviewWithOverlays();
            
            if (!previewImage) {
                throw new Error('Failed to capture preview image');
            }
            
            // Prepare data for submission
            const attendanceData = {
                type: attendanceType,
                image: previewImage, // Use the captured preview image with overlays
                location: userLocation,
                timestamp: serverTimestamp
            };
            
            // Submit attendance data
            const response = await fetch('/api/attendance/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(attendanceData)
            });
            
            const result = await response.json();
            
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
            }
            
        } catch (error) {
            console.error('Error confirming attendance:', error);
            
            // Hide loading overlay
            document.getElementById('loading-overlay').style.display = 'none';
            
            // Show error message
            showAlert(error.message || 'Failed to record attendance. Please try again.', 'error');
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
            
            // Wait a moment for display changes to take effect
            await new Promise(resolve => setTimeout(resolve, 100));
            
            // Use html2canvas library to capture the preview with overlays
            const canvas = await html2canvas(previewContainer, {
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#000000',
                scale: 2, // Higher quality
                logging: false
            });
            
            // Restore the buttons and alert
            actionsElement.style.display = originalActionsDisplay;
            alertElement.style.display = originalAlertDisplay;
            
            // Add additional information to the image
            enhanceCanvasWithDetails(canvas, {
                name: employeeName,
                position: employeePosition,
                department: employeeDepartment,
                location: userLocation,
                timestamp: serverTimestamp,
                type: attendanceType
            });
            
            // Convert canvas to base64 image
            const imageData = canvas.toDataURL('image/jpeg', 0.95);
            
            return imageData;
        } catch (error) {
            console.error('Error capturing preview with overlays:', error);
            return null;
        }
    }
    
    // Add additional information to the canvas
    function enhanceCanvasWithDetails(canvas, details) {
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
        const timestamp = new Date(details.timestamp);
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
        const verificationText = `${details.type.toUpperCase()} VERIFICATION`;
        ctx.fillText(verificationText, 20, height - footerHeight + 15);
        
        // Add timestamp details
        ctx.font = '14px Arial';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
        ctx.fillText(`Date: ${formattedDate}`, 20, height - footerHeight + 35);
        ctx.fillText(`Time: ${formattedTime}`, 20, height - footerHeight + 55);
        
        // Add employee details on the right
        ctx.textAlign = 'right';
        ctx.fillText(`${details.name}`, width - 20, height - footerHeight + 15);
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.fillText(`${details.position}`, width - 20, height - footerHeight + 35);
        ctx.fillText(`${details.department}`, width - 20, height - footerHeight + 55);
        
        // Add location in the middle
        ctx.textAlign = 'center';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.fillText(`Location: ${details.location}`, width/2, height - footerHeight + 35);
        
        // Add system verification text
        ctx.font = 'bold 12px Arial';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
        const systemText = 'HRIS ATTENDANCE SYSTEM';
        ctx.fillText(systemText, width/2, height - footerHeight + 55);
        
        // Add unique verification ID
        const verificationId = `ID: ${Math.random().toString(36).substring(2, 10).toUpperCase()}`;
        ctx.fillText(verificationId, width/2, height - footerHeight + 15);
        
        // Add professional watermark
        ctx.save();
        ctx.globalAlpha = 0.07;
        ctx.font = 'bold 120px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.translate(width/2, height/2);
        ctx.rotate(-Math.PI/6); // Rotate -30 degrees
        const watermarkText = `${details.type === 'in' ? 'CLOCK IN' : 'CLOCK OUT'}`;
        ctx.fillText(watermarkText, 0, 0);
        ctx.font = 'bold 60px Arial';
        ctx.fillText('VERIFIED', 0, 80);
        ctx.restore();
    }
    
    // Clean up when leaving the page
    window.addEventListener('beforeunload', () => {
        document.body.classList.remove('preview-active');
    });
</script>
@endsection
