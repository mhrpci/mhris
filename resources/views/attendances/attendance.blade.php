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
    
    /* Camera interface enhancements */
    .camera-interface {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        pointer-events: none;
        z-index: 1001;
    }

    .camera-frame {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 85%;
        height: 70vh;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
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

    .camera-guide-text {
        position: absolute;
        top: 15%;
        left: 50%;
        transform: translateX(-50%);
        color: white;
        font-size: 1.1rem;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        background: rgba(0, 0, 0, 0.6);
        padding: 8px 16px;
        border-radius: 20px;
        white-space: nowrap;
    }

    /* Enhanced status badge */
    .camera-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 8px 16px;
        border-radius: 30px;
        margin-bottom: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .camera-status-badge.in {
        background: rgba(16, 185, 129, 0.2);
        border-color: rgba(16, 185, 129, 0.4);
    }

    .camera-status-badge.out {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
    }

    .camera-status-badge i {
        font-size: 1.2rem;
    }

    /* Enhanced capture button */
    .btn-capture {
        background: rgba(66, 133, 244, 0.9);
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .btn-capture:hover {
        background: #4285f4;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    }

    .btn-capture:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .btn-capture i {
        font-size: 1.3rem;
    }

    /* Camera controls container */
    .camera-controls {
        position: fixed;
        top: 1rem;
        left: 1rem;
        display: flex;
        gap: 1rem;
        z-index: 1002;
    }

    /* Enhanced camera buttons */
    .camera-btn {
        width: 45px;
        height: 45px;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-switch-camera {
        background: rgba(255, 255, 255, 0.15);
        color: white;
    }

    .btn-close-camera {
        background: rgba(255, 255, 255, 0.15);
        color: #ff4444;
    }

    .btn-filter {
        background: rgba(255, 255, 255, 0.15);
        color: #00c6ff;
    }

    .camera-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-close-camera:hover {
        background: #ff4444;
        color: white;
    }

    .btn-filter:hover {
        background: #00c6ff;
        color: white;
    }

    /* Filter Panel Styles */
    .filter-panel {
        position: fixed;
        top: 1rem;
        left: 5rem;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1rem;
        z-index: 1002;
        display: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .filter-panel.active {
        display: block;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-options {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        color: white;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-slider {
        -webkit-appearance: none;
        width: 200px;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        outline: none;
    }

    .filter-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background: #00c6ff;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 0 10px rgba(0, 198, 255, 0.5);
    }

    .filter-preset {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .preset-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 8px;
        padding: 0.5rem;
        color: white;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .preset-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .preset-btn.active {
        background: #00c6ff;
        color: white;
    }

    /* Camera quality indicator */
    .quality-indicator {
        position: fixed;
        top: 1rem;
        right: 1rem;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        color: white;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 1002;
    }

    .quality-indicator i {
        color: #00c6ff;
    }

    /* Enhanced video feed */
    #camera-feed {
        width: 100%;
        height: 100vh;
        object-fit: cover;
        background: #000;
        position: fixed;
        top: 0;
        left: 0;
        filter: none;
        transition: filter 0.3s ease;
    }

    #camera-feed.beauty-mode {
        filter: brightness(1.1) contrast(1.1) saturate(1.2);
    }

    /* Camera flash animation */
    @keyframes cameraFlash {
        0% { opacity: 0; }
        50% { opacity: 1; }
        100% { opacity: 0; }
    }

    .camera-flash {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: white;
        opacity: 0;
        pointer-events: none;
        z-index: 1003;
    }

    .flash-active {
        animation: cameraFlash 0.3s ease-out;
    }

    /* Hide app content when camera is open */
    body.camera-active {
        overflow: hidden;
        position: fixed;
        width: 100%;
    }

    body.camera-active .app-content {
        display: none;
    }

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
        object-fit: cover; /* Changed to cover for full screen */
        background: #000;
        position: fixed;
        top: 0;
        left: 0;
    }
    
    .camera-buttons {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 1.5rem;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.7) 50%, transparent 100%);
        height: 100px;
        z-index: 1001;
    }
    
    .btn-capture:hover, .btn-switch-camera:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
    
    /* Updated Camera overlay text styles */
    .camera-overlay {
        position: fixed;
        left: 0;
        bottom: 80px;
        width: 100%;
        color: white;
        z-index: 1002;
        font-family: 'Inter', sans-serif;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 70%, transparent 100%);
    }

    .camera-overlay-content {
        max-width: 80%;
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

    /* Responsive design */
    @media (max-width: 768px) {
        .attendance-container {
            padding: 1rem 0;
        }
        
        .profile-section {
            padding: 2rem 1.5rem;
        }
        
        .profile-initial {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
        
        .time {
            font-size: 3rem;
        }
        
        .date {
            font-size: 1.1rem;
        }
        
        .action-buttons {
            padding: 1.5rem;
            gap: 1rem;
        }
        
        .btn-clock-in, .btn-clock-out {
            padding: 1rem;
        }

        .camera-overlay {
            padding: 15px;
        }

        .camera-overlay-content {
            max-width: 100%;
        }

        .overlay-time {
            font-size: 1.8rem;
        }

        .overlay-date {
            font-size: 1.1rem;
        }

        .overlay-name {
            font-size: 1rem;
        }

        .overlay-location {
            font-size: 0.9rem;
        }

        .camera-status-badge {
            padding: 8px 20px;
            font-size: 1.2rem;
        }

        .camera-status-large {
            font-size: 4rem;
        }

        .btn-capture {
            padding: 0.8rem 2rem;
            font-size: 1rem;
        }

        .btn-switch-camera {
            width: 40px;
            height: 40px;
        }

        .camera-logo-container {
            padding: 6px;
        }
        
        .camera-logo {
            width: 80px;
        }
    }
    
    @media (max-width: 480px) {
        .time {
            font-size: 2.5rem;
        }
        
        .action-buttons {
            grid-template-columns: 1fr;
        }
        
        .camera-buttons {
            padding: 0.8rem;
            gap: 1rem;
        }
        
        .btn-capture {
            padding: 0.8rem 1.8rem;
            font-size: 0.9rem;
        }

        .camera-logo-container {
            padding: 4px;
        }
        
        .camera-logo {
            width: 60px;
        }
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
    <div class="camera-controls">
        <button class="camera-btn btn-switch-camera" onclick="switchCamera()">
            <i class="fas fa-sync"></i>
        </button>
        <button class="camera-btn btn-close-camera" onclick="closeCamera()">
            <i class="fas fa-times"></i>
        </button>
        <button class="camera-btn btn-filter" onclick="toggleFilter()">
            <i class="fas fa-filter"></i>
        </button>
    </div>
    
    <div class="camera-logo-container">
        <img src="{{ asset('/vendor/adminlte/dist/img/LOGO4.png') }}" alt="Logo" class="camera-logo">
    </div>
    
    <div class="camera-container">
        <video id="camera-feed" autoplay playsinline></video>
        
        <div class="camera-interface">
            <div class="camera-frame">
                <div class="camera-corners corner-top-left"></div>
                <div class="camera-corners corner-top-right"></div>
                <div class="camera-corners corner-bottom-left"></div>
                <div class="camera-corners corner-bottom-right"></div>
            </div>
            <div class="camera-guide-text">
                Position your face within the frame
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
        
        <div class="camera-buttons">
            <button class="btn-capture" onclick="captureImage()">
                <i class="fas fa-camera"></i>
                Capture
            </button>
        </div>
    </div>
</div>

<!-- Filter Panel -->
<div class="filter-panel" id="filterPanel">
    <div class="filter-options">
        <div class="filter-group">
            <label class="filter-label">Brightness</label>
            <input type="range" class="filter-slider" min="0" max="2" step="0.01" value="1">
        </div>
        <div class="filter-group">
            <label class="filter-label">Contrast</label>
            <input type="range" class="filter-slider" min="0" max="2" step="0.01" value="1">
        </div>
        <div class="filter-group">
            <label class="filter-label">Saturation</label>
            <input type="range" class="filter-slider" min="0" max="2" step="0.01" value="1">
        </div>
        <div class="filter-group">
            <label class="filter-label">Sharpness</label>
            <input type="range" class="filter-slider" min="0" max="2" step="0.01" value="1">
        </div>
        <div class="filter-group">
            <label class="filter-label">Temperature</label>
            <input type="range" class="filter-slider" min="2000" max="10000" step="1" value="5000">
        </div>
        <div class="filter-group">
            <label class="filter-label">Tint</label>
            <input type="range" class="filter-slider" min="-100" max="100" step="1" value="0">
        </div>
        <div class="filter-group">
            <label class="filter-label">Exposure</label>
            <input type="range" class="filter-slider" min="0" max="2" step="0.01" value="1">
        </div>
        <div class="filter-group">
            <label class="filter-label">ISO</label>
            <input type="range" class="filter-slider" min="100" max="800" step="1" value="400">
        </div>
        <div class="filter-group">
            <label class="filter-label">Focus</label>
            <input type="range" class="filter-slider" min="0" max="100" step="1" value="50">
        </div>
        <div class="filter-preset">
            <button class="preset-btn" onclick="applyPreset('natural')">Natural</button>
            <button class="preset-btn" onclick="applyPreset('warm')">Warm</button>
            <button class="preset-btn" onclick="applyPreset('cool')">Cool</button>
            <button class="preset-btn" onclick="applyPreset('daylight')">Daylight</button>
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
                    width: { ideal: 3840 }, // 4K UHD
                    height: { ideal: 2160 },
                    frameRate: { ideal: 30 },
                    zoom: 1.0,
                    advanced: [
                        { zoom: 1.0 },
                        { whiteBalanceMode: "continuous" },
                        { exposureMode: "continuous" },
                        { focusMode: "continuous" }
                    ]
                }
            };

            currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            const videoElement = document.getElementById('camera-feed');
            videoElement.srcObject = currentStream;
            
            // Apply mirroring only for front camera
            videoElement.style.transform = facingMode === 'user' ? 'scaleX(-1)' : 'none';
            
            // Ensure video maintains aspect ratio
            videoElement.style.objectFit = 'contain';
            
            currentFacingMode = facingMode;

            // Get video track capabilities
            const videoTrack = currentStream.getVideoTracks()[0];
            const capabilities = videoTrack.getCapabilities();
            
            // Initialize quality indicator
            updateQualityIndicator(videoTrack);

            // Initialize filter controls based on capabilities
            initializeFilterControls(capabilities);

        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Unable to access camera. Please ensure camera permissions are granted.');
        }
    }

    // Update quality indicator
    function updateQualityIndicator(videoTrack) {
        const settings = videoTrack.getSettings();
        const quality = document.createElement('div');
        quality.className = 'quality-indicator';
        
        let qualityText = 'HD';
        if (settings.width >= 3840) {
            qualityText = '4K UHD';
        } else if (settings.width >= 1920) {
            qualityText = 'Full HD';
        }
        
        quality.innerHTML = `
            <i class="fas fa-video"></i>
            <span>${qualityText} (${settings.width}×${settings.height})</span>
        `;
        
        // Remove existing quality indicator if any
        const existingQuality = document.querySelector('.quality-indicator');
        if (existingQuality) {
            existingQuality.remove();
        }
        
        document.querySelector('.camera-container').appendChild(quality);
    }

    // Initialize filter controls
    function initializeFilterControls(capabilities) {
        const sliders = document.querySelectorAll('.filter-slider');
        sliders.forEach(slider => {
            slider.addEventListener('input', updateFilters);
        });
    }

    // Update filters based on slider values
    function updateFilters() {
        const video = document.getElementById('camera-feed');
        const brightness = document.querySelector('.filter-slider[min="0"][max="2"]').value;
        const contrast = document.querySelectorAll('.filter-slider[min="0"][max="2"]')[1].value;
        const saturation = document.querySelectorAll('.filter-slider[min="0"][max="2"]')[2].value;
        const sharpness = document.querySelectorAll('.filter-slider[min="0"][max="2"]')[3].value;
        
        let filterString = `
            brightness(${brightness})
            contrast(${contrast})
            saturate(${saturation})
        `;
        
        if (sharpness > 1) {
            filterString += ` url(data:image/svg+xml;base64,${btoa(`
                <svg xmlns="http://www.w3.org/2000/svg">
                    <filter id="sharpen">
                        <feConvolveMatrix order="3" preserveAlpha="true" kernelMatrix="0 -${sharpness-1} 0 -${sharpness-1} ${sharpness*4} -${sharpness-1} 0 -${sharpness-1} 0"/>
                    </filter>
                </svg>
            `)})`;
        }
        
        video.style.filter = filterString;
    }

    // Apply preset filters
    function applyPreset(preset) {
        const sliders = document.querySelectorAll('.filter-slider');
        const presetButtons = document.querySelectorAll('.preset-btn');
        
        // Remove active class from all preset buttons
        presetButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to selected preset button
        event.target.classList.add('active');
        
        let values;
        switch(preset) {
            case 'natural':
                values = [1, 1, 1, 1, 5500, 0, 1, 400, 50];
                break;
            case 'warm':
                values = [1.1, 1.1, 1.2, 1, 6500, 10, 1.1, 400, 50];
                break;
            case 'cool':
                values = [1, 1.1, 0.9, 1, 4500, -10, 1, 400, 50];
                break;
            case 'daylight':
                values = [1.2, 1.1, 1.1, 1, 5500, 0, 1.2, 200, 50];
                break;
        }
        
        // Update slider values
        sliders.forEach((slider, index) => {
            slider.value = values[index];
        });
        
        // Apply filters
        updateFilters();
    }

    // Toggle filter panel
    function toggleFilter() {
        const filterPanel = document.getElementById('filterPanel');
        const filterBtn = document.querySelector('.btn-filter');
        
        if (filterPanel.classList.contains('active')) {
            filterPanel.classList.remove('active');
            filterBtn.style.background = 'rgba(255, 255, 255, 0.15)';
        } else {
            filterPanel.classList.add('active');
            filterBtn.style.background = '#00c6ff';
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