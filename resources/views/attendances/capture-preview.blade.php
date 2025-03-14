@extends('layouts.app')

@section('styles')
<style>
    /* Base styles */
    .preview-container {
        min-height: calc(100vh - 80px);
        padding: 2rem 0;
        background: linear-gradient(135deg, #f6f9fc 0%, #ecf3f8 100%);
    }
    
    /* Card styles */
    .preview-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    /* Image preview section */
    .image-preview-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 75%; /* 4:3 aspect ratio */
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
    
    /* Logo overlay */
    .preview-logo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 100px;
        height: auto;
        z-index: 10;
        background: rgba(255, 255, 255, 0.9);
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Status badge */
    .preview-status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 8px 16px;
        border-radius: 30px;
        color: white;
        font-weight: bold;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .preview-status-badge.in {
        background: rgba(16, 185, 129, 0.2);
        border-color: rgba(16, 185, 129, 0.4);
    }
    
    .preview-status-badge.out {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    /* Info overlay */
    .preview-info-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        color: white;
        z-index: 10;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 70%, transparent 100%);
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
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
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
    
    /* Responsive design */
    @media (max-width: 768px) {
        .preview-container {
            padding: 1rem 0;
        }
        
        .preview-logo {
            width: 80px;
            top: 15px;
            right: 15px;
        }
        
        .preview-status-badge {
            top: 15px;
            left: 15px;
            padding: 6px 12px;
            font-size: 0.9rem;
        }
        
        .preview-info-overlay {
            padding: 15px;
        }
        
        .preview-time {
            font-size: 1.5rem;
        }
        
        .preview-date {
            font-size: 1rem;
        }
        
        .preview-actions {
            padding: 1.2rem;
            flex-direction: column;
        }
        
        .btn-confirm, .btn-retake {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="preview-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="preview-card">
                    <div class="image-preview-container">
                        <img id="preview-image" class="preview-image" src="" alt="Attendance Capture">
                        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
                        <div id="preview-status-badge" class="preview-status-badge">
                            <i class="fas fa-clock"></i>
                            <span id="status-text">Clock In</span>
                        </div>
                        <div class="preview-info-overlay">
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
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Processing your attendance...</div>
</div>
@endsection

@section('scripts')
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
            // Get data from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            attendanceType = urlParams.get('type') || 'in';
            
            // Get data from localStorage
            capturedImage = localStorage.getItem('capturedImage');
            userLocation = localStorage.getItem('userLocation');
            serverTimestamp = localStorage.getItem('serverTimestamp');
            
            if (!capturedImage || !serverTimestamp) {
                alert('Missing capture data. Please try again.');
                window.location.href = '/attendance';
                return;
            }
            
            // Set the captured image
            document.getElementById('preview-image').src = capturedImage;
            
            // Set the status badge
            const statusBadge = document.getElementById('preview-status-badge');
            const statusText = document.getElementById('status-text');
            statusBadge.className = `preview-status-badge ${attendanceType}`;
            statusText.textContent = attendanceType === 'in' ? 'Clock In' : 'Clock Out';
            
            // Get server time for display
            await updateTimeDisplay();
            
            // Get employee information
            await getEmployeeInfo();
            
            // Set location
            document.getElementById('preview-location').textContent = userLocation || 'Location not available';
            
        } catch (error) {
            console.error('Error initializing preview:', error);
            alert('An error occurred while loading the preview. Please try again.');
            window.location.href = '/attendance';
        }
    });
    
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
        window.location.href = '/attendance';
    }
    
    // Confirm attendance
    async function confirmAttendance() {
        try {
            // Show loading overlay
            document.getElementById('loading-overlay').style.display = 'flex';
            
            // Prepare data for submission
            const attendanceData = {
                type: attendanceType,
                image: capturedImage,
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
            
            if (result.status === 'success') {
                // Clear localStorage
                localStorage.removeItem('capturedImage');
                localStorage.removeItem('userLocation');
                localStorage.removeItem('serverTimestamp');
                
                // Redirect to attendance page with success message
                window.location.href = '/attendance?success=' + encodeURIComponent(result.message);
            } else {
                throw new Error(result.message || 'Failed to record attendance');
            }
            
        } catch (error) {
            console.error('Error confirming attendance:', error);
            alert('Error: ' + (error.message || 'Failed to record attendance. Please try again.'));
            
            // Hide loading overlay
            document.getElementById('loading-overlay').style.display = 'none';
        }
    }
</script>
@endsection
