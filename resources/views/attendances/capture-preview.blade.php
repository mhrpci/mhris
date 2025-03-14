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
    
    /* Status badge - Professional styling */
    .preview-status-badge {
        position: absolute;
        bottom: 200px; /* Adjusted position */
        left: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(10px);
        padding: 15px 30px;
        border-radius: 15px;
        color: white;
        font-weight: 600;
        z-index: 9992;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        min-width: 180px;
    }
    
    .preview-status-badge i {
        font-size: 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .preview-status-badge span {
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.9) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .preview-status-badge.in {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.85) 0%, rgba(5, 150, 105, 0.85) 100%);
        border: 1px solid rgba(16, 185, 129, 0.4);
    }
    
    .preview-status-badge.out {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.85) 0%, rgba(220, 38, 38, 0.85) 100%);
        border: 1px solid rgba(239, 68, 68, 0.4);
    }
    
    /* Remove large status indicator as it's no longer needed */
    .preview-status-large {
        display: none;
    }
    
    /* Info overlay - Adjusted for new status badge position */
    .preview-info-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        color: white;
        z-index: 9992;
        padding: 30px 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, transparent 100%);
    }
    
    .preview-overlay-content {
        max-width: 80%;
    }
    
    .preview-time {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    
    .preview-date {
        font-size: 1.1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.9);
        margin-bottom: 12px;
    }
    
    .preview-name {
        font-size: 1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.95);
        margin-bottom: 8px;
    }
    
    .preview-position {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 8px;
    }
    
    .preview-department {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 12px;
    }
    
    .preview-location {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.9);
        line-height: 1.4;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }
    
    .preview-location i {
        margin-top: 4px;
        color: #4285f4;
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
    
    /* Responsive design updates */
    @media (max-width: 768px) {
        .preview-logo {
            width: 80px;
            top: 15px;
            right: 15px;
        }
        
        .preview-status-badge {
            bottom: 180px;
            padding: 12px 25px;
            min-width: 160px;
        }
        
        .preview-status-badge i {
            font-size: 1.3rem;
        }
        
        .preview-status-badge span {
            font-size: 1.1rem;
        }
        
        .preview-info-overlay {
            padding: 25px 15px;
        }
        
        .preview-overlay-content {
            max-width: 100%;
        }
        
        .preview-time {
            font-size: 1.5rem;
        }
        
        .preview-date {
            font-size: 1rem;
        }
        
        .preview-actions {
            padding: 1.2rem;
        }
        
        .btn-confirm, .btn-retake {
            padding: 0.7rem 1.5rem;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .preview-logo {
            width: 60px;
            top: 10px;
            right: 10px;
        }
        
        .preview-status-badge {
            bottom: 160px;
            left: 15px;
            padding: 10px 20px;
            min-width: 140px;
            border-radius: 12px;
        }
        
        .preview-status-badge i {
            font-size: 1.1rem;
        }
        
        .preview-status-badge span {
            font-size: 1rem;
            letter-spacing: 0.3px;
        }
        
        .preview-time {
            font-size: 1.3rem;
        }
        
        .preview-date {
            font-size: 0.9rem;
        }
        
        .preview-name, .preview-position, .preview-department {
            font-size: 0.8rem;
        }
        
        .preview-actions {
            padding: 1rem;
            gap: 0.8rem;
        }
        
        .btn-confirm, .btn-retake {
            padding: 0.6rem 1.2rem;
            font-size: 0.8rem;
        }
        
        .preview-info-overlay {
            padding: 20px 15px;
        }
    }

    /* Professional identification section */
    .preview-identification {
        position: absolute;
        left: 20px;
        bottom: 30px;
        width: calc(100% - 40px);
        background: linear-gradient(to right, rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.75));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        color: white;
        z-index: 9992;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    }

    .identification-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .clock-status {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-weight: 600;
        min-width: 160px;
    }

    .clock-status.in {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.3), rgba(5, 150, 105, 0.3));
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .clock-status.out {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.3));
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .clock-status i {
        font-size: 1.4rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .clock-status span {
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.9);
    }

    .datetime-display {
        flex-grow: 1;
        text-align: right;
    }

    .time-display {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 4px;
        background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 1px;
    }

    .date-display {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .identification-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .employee-info, .workplace-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    .location-info {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .location-info i {
        color: #4285f4;
        font-size: 1.2rem;
    }

    .location-info .info-value {
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .preview-identification {
            padding: 20px;
            left: 15px;
            width: calc(100% - 30px);
        }

        .identification-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .datetime-display {
            text-align: left;
        }

        .time-display {
            font-size: 1.8rem;
        }

        .date-display {
            font-size: 1rem;
        }

        .identification-details {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .preview-identification {
            padding: 15px;
            left: 10px;
            width: calc(100% - 20px);
        }

        .clock-status {
            padding: 8px 15px;
            min-width: 140px;
        }

        .clock-status i {
            font-size: 1.2rem;
        }

        .clock-status span {
            font-size: 1rem;
        }

        .time-display {
            font-size: 1.6rem;
        }

        .info-label {
            font-size: 0.8rem;
        }

        .info-value {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="preview-container">
    <div class="image-preview-container">
        <img id="preview-image" class="preview-image" src="" alt="Attendance Capture">
        
        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
        
        <div class="preview-identification">
            <div class="identification-header">
                <div class="clock-status" id="clock-status">
                    <i class="fas fa-clock"></i>
                    <span id="status-text">Clock In</span>
                </div>
                <div class="datetime-display">
                    <div class="time-display" id="preview-time"></div>
                    <div class="date-display" id="preview-date"></div>
                </div>
            </div>
            <div class="identification-details">
                <div class="employee-info">
                    <div class="info-label">Employee Name</div>
                    <div class="info-value" id="preview-name"></div>
                </div>
                <div class="workplace-info">
                    <div class="info-label">Position</div>
                    <div class="info-value" id="preview-position"></div>
                </div>
                <div class="workplace-info">
                    <div class="info-label">Department</div>
                    <div class="info-value" id="preview-department"></div>
                </div>
                <div class="location-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <div class="info-label">Location</div>
                        <div class="info-value" id="preview-location"></div>
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
            const statusBadge = document.getElementById('clock-status');
            const statusText = document.getElementById('status-text');
            
            statusBadge.className = `clock-status ${attendanceType}`;
            statusText.textContent = attendanceType === 'in' ? 'Clock In' : 'Clock Out';
            
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
        
        // Add a professional gradient overlay
        const overlayHeight = height * 0.3;
        const gradient = ctx.createLinearGradient(0, height - overlayHeight, 0, height);
        gradient.addColorStop(0, 'rgba(0, 0, 0, 0)');
        gradient.addColorStop(0.5, 'rgba(0, 0, 0, 0.75)');
        gradient.addColorStop(1, 'rgba(0, 0, 0, 0.9)');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, height - overlayHeight, width, overlayHeight);
        
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

        // Set up text styles
        ctx.textBaseline = 'middle';
        
        // Add Clock In/Out status with large text
        ctx.font = 'bold 24px Arial';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
        ctx.textAlign = 'left';
        ctx.fillText(`${details.type.toUpperCase()} VERIFICATION`, 30, height - overlayHeight + 40);

        // Add timestamp and details
        ctx.font = '16px Arial';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
        
        // Left column - Time details
        const leftX = 30;
        ctx.textAlign = 'left';
        ctx.fillText(formattedTime, leftX, height - overlayHeight + 70);
        ctx.fillText(formattedDate, leftX, height - overlayHeight + 95);
        
        // Right column - Employee details
        const rightX = width - 30;
        ctx.textAlign = 'right';
        ctx.fillText(details.name, rightX, height - overlayHeight + 70);
        ctx.fillText(`${details.position} - ${details.department}`, rightX, height - overlayHeight + 95);
        
        // Center - Location and verification
        ctx.textAlign = 'center';
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.fillText(`Location: ${details.location}`, width/2, height - overlayHeight + 70);
        
        // Add verification details
        ctx.font = 'bold 14px Arial';
        const verificationId = `ID: ${Math.random().toString(36).substring(2, 10).toUpperCase()}`;
        ctx.fillText(`${verificationId} | HRIS ATTENDANCE SYSTEM`, width/2, height - overlayHeight + 95);
        
        // Add subtle watermark
        ctx.save();
        ctx.globalAlpha = 0.07;
        ctx.font = 'bold 150px Arial';
        ctx.textAlign = 'center';
        ctx.translate(width/2, height/2);
        ctx.rotate(-Math.PI/6);
        ctx.fillText(`${details.type === 'in' ? 'CLOCK IN' : 'CLOCK OUT'}`, 0, 0);
        ctx.font = 'bold 80px Arial';
        ctx.fillText('VERIFIED', 0, 100);
        ctx.restore();
    }
    
    // Clean up when leaving the page
    window.addEventListener('beforeunload', () => {
        document.body.classList.remove('preview-active');
    });
</script>
@endsection
