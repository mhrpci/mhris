@extends('layouts.app')

@section('styles')
<style>
    /* Base styles */
    .preview-container {
        min-height: calc(100vh - 80px);
        padding: 1.5rem 0;
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
    
    /* Preview image container */
    .preview-image-container {
        position: relative;
        width: 100%;
        background: #000;
        overflow: hidden;
    }
    
    .preview-image {
        width: 100%;
        height: auto;
        display: block;
    }
    
    /* Overlay elements */
    .preview-overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        color: white;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 70%, transparent 100%);
    }
    
    .preview-overlay-content {
        max-width: 80%;
    }
    
    .overlay-time {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 4px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    
    .overlay-date {
        font-size: 1.1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.9);
        margin-bottom: 12px;
    }
    
    .overlay-name {
        font-size: 1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.95);
        margin-bottom: 8px;
    }
    
    .overlay-location {
        font-size: 0.9rem;
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
    
    /* Clock status badge */
    .clock-status-badge {
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
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 600;
        z-index: 10;
    }
    
    .clock-status-badge.in {
        background: rgba(16, 185, 129, 0.2);
        border-color: rgba(16, 185, 129, 0.4);
    }
    
    .clock-status-badge.out {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
    }
    
    .clock-status-badge i {
        font-size: 1.2rem;
    }
    
    /* Logo */
    .preview-logo-container {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 10;
        background: rgba(255, 255, 255, 0.9);
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .preview-logo {
        width: 100px;
        height: auto;
        opacity: 0.8;
        display: block;
    }
    
    /* Action buttons */
    .preview-actions {
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .btn-confirm, .btn-retake {
        border: none;
        padding: 1rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .btn-confirm {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2);
    }
    
    .btn-retake {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(107, 114, 128, 0.2);
    }
    
    .btn-confirm:hover, .btn-retake:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    
    .btn-confirm:active, .btn-retake:active {
        transform: translateY(0);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-confirm i, .btn-retake i {
        font-size: 1.2rem;
    }
    
    /* Work duration display */
    .work-duration {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 10;
    }
    
    .work-duration i {
        color: #4285f4;
    }
    
    /* Photo code */
    .photo-code {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 10;
    }
    
    .photo-code i {
        color: #4285f4;
    }
    
    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
        display: none;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #4285f4;
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 1rem;
    }
    
    .loading-text {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .preview-container {
            padding: 1rem 0;
        }
        
        .preview-overlay {
            padding: 15px;
        }
        
        .preview-overlay-content {
            max-width: 100%;
        }
        
        .overlay-time {
            font-size: 1.6rem;
        }
        
        .overlay-date {
            font-size: 1rem;
        }
        
        .overlay-name {
            font-size: 0.9rem;
        }
        
        .overlay-location {
            font-size: 0.8rem;
        }
        
        .clock-status-badge {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
        
        .preview-logo {
            width: 80px;
        }
        
        .preview-actions {
            flex-direction: column;
            padding: 1rem;
        }
        
        .btn-confirm, .btn-retake {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .clock-status-badge {
            top: 10px;
            left: 10px;
            padding: 4px 10px;
            font-size: 0.8rem;
        }
        
        .preview-logo-container {
            top: 10px;
            right: 10px;
            padding: 4px;
        }
        
        .preview-logo {
            width: 60px;
        }
        
        .work-duration, .photo-code {
            padding: 4px 10px;
            font-size: 0.8rem;
            bottom: 10px;
        }
        
        .work-duration {
            right: 10px;
        }
        
        .photo-code {
            left: 10px;
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
                    <div class="preview-image-container">
                        <img id="preview-image" class="preview-image" src="" alt="Attendance Preview">
                        
                        <!-- Clock status badge -->
                        <div id="clock-status-badge" class="clock-status-badge">
                            <i class="fas fa-clock"></i>
                            <span id="status-text">Clock In</span>
                        </div>
                        
                        <!-- Logo -->
                        <div class="preview-logo-container">
                            <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="preview-logo">
                        </div>
                        
                        <!-- Overlay with time, date, name, location -->
                        <div class="preview-overlay">
                            <div class="preview-overlay-content">
                                <div class="overlay-time" id="overlay-time">00:00 AM</div>
                                <div class="overlay-date" id="overlay-date"></div>
                                <div class="overlay-name" id="overlay-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="overlay-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="overlay-location">Loading location...</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Work duration (only for clock out) -->
                        <div id="work-duration" class="work-duration" style="display: none;">
                            <i class="fas fa-business-time"></i>
                            <span id="duration-text">0h 0min</span>
                        </div>
                        
                        <!-- Photo code -->
                        <div class="photo-code">
                            <i class="fas fa-qrcode"></i>
                            <span id="photo-code-text">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="preview-actions">
                        <button id="btn-retake" class="btn-retake">
                            <i class="fas fa-camera"></i>
                            Retake Photo
                        </button>
                        <button id="btn-confirm" class="btn-confirm">
                            <i class="fas fa-check"></i>
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Processing your attendance...</div>
</div>

<!-- Form to submit attendance data -->
<form id="attendance-form" method="POST" action="{{ route('attendance.store') }}" style="display: none;">
    @csrf
    <input type="hidden" name="image_data" id="image-data">
    <input type="hidden" name="location" id="location-data">
    <input type="hidden" name="timestamp" id="timestamp-data">
    <input type="hidden" name="attendance_type" id="attendance-type">
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get attendance type from URL
        const urlParams = new URLSearchParams(window.location.search);
        const attendanceType = urlParams.get('type') || 'in';
        
        // Get data from localStorage
        const imageData = localStorage.getItem('capturedImage');
        const userLocation = localStorage.getItem('userLocation');
        const serverTimestamp = localStorage.getItem('serverTimestamp');
        
        if (!imageData || !serverTimestamp) {
            // Redirect back to attendance page if data is missing
            window.location.href = '/attendance';
            return;
        }
        
        // Set form data
        document.getElementById('image-data').value = imageData;
        document.getElementById('location-data').value = userLocation;
        document.getElementById('timestamp-data').value = serverTimestamp;
        document.getElementById('attendance-type').value = attendanceType;
        
        // Set preview image
        document.getElementById('preview-image').src = imageData;
        
        // Update status badge
        const statusBadge = document.getElementById('clock-status-badge');
        const statusText = document.getElementById('status-text');
        
        if (attendanceType === 'in') {
            statusBadge.classList.add('in');
            statusText.textContent = 'Clock In';
        } else {
            statusBadge.classList.add('out');
            statusText.textContent = 'Clock Out';
            
            // Show work duration for clock out
            document.getElementById('work-duration').style.display = 'flex';
            
            // Fetch work duration
            fetch('/api/work-duration')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('duration-text').textContent = data.duration;
                    }
                })
                .catch(error => {
                    console.error('Error fetching work duration:', error);
                });
        }
        
        // Parse timestamp and update overlay
        try {
            const timestamp = new Date(serverTimestamp);
            
            document.getElementById('overlay-time').textContent = new Intl.DateTimeFormat('en-US', { 
                timeZone: 'Asia/Manila',
                hour12: true,
                hour: '2-digit',
                minute: '2-digit'
            }).format(timestamp).toUpperCase();
            
            document.getElementById('overlay-date').textContent = new Intl.DateTimeFormat('en-US', { 
                timeZone: 'Asia/Manila',
                weekday: 'short',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            }).format(timestamp);
            
            // Set location
            document.getElementById('overlay-location').textContent = userLocation;
            
            // Generate photo code
            const photoCode = generatePhotoCode(attendanceType);
            document.getElementById('photo-code-text').textContent = photoCode;
            
        } catch (error) {
            console.error('Error parsing timestamp:', error);
        }
        
        // Button event listeners
        document.getElementById('btn-retake').addEventListener('click', function() {
            // Go back to attendance page to retake photo
            window.location.href = '/attendance';
        });
        
        document.getElementById('btn-confirm').addEventListener('click', function() {
            // Show loading overlay
            document.getElementById('loading-overlay').style.display = 'flex';
            
            // Submit form
            document.getElementById('attendance-form').submit();
        });
    });
    
    // Generate a random photo code
    function generatePhotoCode(type) {
        const prefix = type === 'in' ? 'WRDL' : 'KWVJ';
        const randomNum = Math.floor(Math.random() * 9000) + 1000; // 4-digit number
        return `${prefix}${randomNum}`;
    }
</script>
@endsection
