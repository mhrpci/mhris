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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
        background: #f8fafc;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-clock-in, .btn-clock-out {
        border: none;
        padding: 1.5rem;
        border-radius: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        min-height: 150px;
        position: relative;
        overflow: hidden;
    }
    
    .btn-clock-in {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2);
    }
    
    .btn-clock-out {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.2);
    }
    
    .btn-clock-in:hover, .btn-clock-out:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    
    .btn-clock-in:active, .btn-clock-out:active {
        transform: translateY(0);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-clock-in i, .btn-clock-out i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .btn-clock-in span, .btn-clock-out span {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        position: relative;
        z-index: 1;
    }
    
    .btn-clock-in div, .btn-clock-out div {
        font-size: 0.95rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        text-align: center;
        line-height: 1.4;
    }
    
    /* Button background effects */
    .btn-clock-in::before, .btn-clock-out::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .btn-clock-in:hover::before, .btn-clock-out:hover::before {
        opacity: 1;
    }
    
    /* Alert styles within action buttons */
    .action-buttons .alert {
        grid-column: 1 / -1;
        padding: 1.5rem;
        border-radius: 16px;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        font-size: 1.1rem;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .action-buttons .alert i {
        font-size: 1.5rem;
    }
    
    .action-buttons .alert-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
    }
    
    .action-buttons .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
    }
    
    /* Responsive design for action buttons */
    @media (max-width: 1200px) {
        .action-buttons {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            padding: 1.8rem;
            gap: 1.2rem;
        }
        
        .btn-clock-in, .btn-clock-out {
            min-height: 140px;
            padding: 1.3rem;
        }
    }
    
    @media (max-width: 992px) {
        .action-buttons {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            padding: 1.5rem;
            gap: 1rem;
        }
        
        .btn-clock-in i, .btn-clock-out i {
            font-size: 1.8rem;
        }
        
        .btn-clock-in span, .btn-clock-out span {
            font-size: 1.15rem;
        }
        
        .btn-clock-in div, .btn-clock-out div {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            grid-template-columns: 1fr;
            padding: 1.2rem;
        }
        
        .btn-clock-in, .btn-clock-out {
            min-height: 130px;
            padding: 1.2rem;
        }
        
        .action-buttons .alert {
            padding: 1.2rem;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .action-buttons {
            padding: 1rem;
        }
        
        .btn-clock-in, .btn-clock-out {
            min-height: 120px;
            padding: 1rem;
        }
        
        .btn-clock-in i, .btn-clock-out i {
            font-size: 1.6rem;
        }
        
        .btn-clock-in span, .btn-clock-out span {
            font-size: 1.1rem;
        }
        
        .btn-clock-in div, .btn-clock-out div {
            font-size: 0.85rem;
        }
        
        .action-buttons .alert {
            padding: 1rem;
            font-size: 0.95rem;
        }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .action-buttons {
            background: #1a1a1a;
        }
        
        .btn-clock-in {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        
        .btn-clock-out {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        
        .action-buttons .alert-info {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }
        
        .action-buttons .alert-danger {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
    }
    
    /* High contrast mode support */
    @media (forced-colors: active) {
        .btn-clock-in, .btn-clock-out {
            border: 2px solid currentColor;
        }
        
        .action-buttons .alert {
            border: 2px solid currentColor;
        }
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

    .camera-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-close-camera:hover {
        background: #ff4444;
        color: white;
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
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Attendance Camera</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="attendance-status-container mb-4 text-center">
                        <div id="attendanceStatusContainer" class="mb-3">
                            <h4>Your Attendance Status</h4>
                            <div id="statusIndicator" class="d-inline-block px-4 py-2 font-weight-bold rounded">
                                <span id="currentStatus">Checking status...</span>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="text-center mb-4">
                                <div class="attendance-type-selector mb-3">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-primary active">
                                            <input type="radio" name="attendance_type" id="time_in" value="time_in" checked> Time In
                                        </label>
                                        <label class="btn btn-outline-primary">
                                            <input type="radio" name="attendance_type" id="time_out" value="time_out"> Time Out
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="camera-container mb-4">
                                <div class="video-container text-center">
                                    <video id="camera" class="img-fluid border" style="max-height: 400px; background-color: #f8f9fa;" autoplay playsinline></video>
                                    <canvas id="canvas" style="display: none;"></canvas>
                                </div>

                                <div class="text-center mt-3">
                                    <p class="text-muted mb-1">Make sure your face is clearly visible</p>
                                    <button id="captureBtn" class="btn btn-primary btn-lg">
                                        <i class="fas fa-camera"></i> Capture Photo
                                    </button>
                                </div>
                            </div>

                            <div class="location-info text-center mb-3">
                                <div id="locationStatus">
                                    <i class="fas fa-map-marker-alt"></i> Getting your location...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="text-center text-muted">
                        <small>Your attendance will be recorded with your photo, timestamp, and location</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const locationStatus = document.getElementById('locationStatus');
    let locationData = null;
    
    // Check attendance status when page loads
    checkAttendanceStatus();
    
    // Initialize camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            } 
        })
        .then(function(stream) {
            video.srcObject = stream;
        })
        .catch(function(error) {
            console.error('Camera error:', error);
            alert('Unable to access camera. Please ensure camera permissions are granted and try again.');
        });
    } else {
        alert('Your browser does not support camera access. Please use a modern browser.');
    }
    
    // Get user location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            // Success callback
            function(position) {
                locationData = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                
                locationStatus.innerHTML = `<i class="fas fa-check-circle text-success"></i> Location acquired`;
                
                // Store location in session storage
                sessionStorage.setItem('latitude', locationData.latitude);
                sessionStorage.setItem('longitude', locationData.longitude);
            },
            // Error callback
            function(error) {
                console.error('Geolocation error:', error);
                locationStatus.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> Location unavailable. Please enable location access.`;
            }
        );
    } else {
        locationStatus.innerHTML = `<i class="fas fa-times-circle text-danger"></i> Geolocation not supported by your browser`;
    }
    
    // Capture button click handler
    captureBtn.addEventListener('click', function() {
        if (!locationData) {
            if (!confirm('Your location has not been acquired yet. Continue without location data?')) {
                return;
            }
        }
        
        // Draw current video frame to canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Get image data
        const imageData = canvas.toDataURL('image/jpeg');
        
        // Store data in session storage for the preview page
        const timestamp = new Date().toISOString();
        sessionStorage.setItem('capturedImageData', imageData);
        sessionStorage.setItem('timestamp', timestamp);
        
        // Get selected attendance type
        const attendanceType = document.querySelector('input[name="attendance_type"]:checked').value;
        sessionStorage.setItem('attendanceType', attendanceType);
        
        // Navigate to preview page
        window.location.href = "{{ route('attendance.preview') }}";
    });
    
    // Function to check attendance status
    function checkAttendanceStatus() {
        const statusContainer = document.getElementById('statusIndicator');
        const currentStatus = document.getElementById('currentStatus');
        
        fetch("{{ route('attendance.status') }}")
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.attendance_status === 'timed_in') {
                        statusContainer.className = 'd-inline-block px-4 py-2 font-weight-bold rounded bg-success text-white';
                        currentStatus.textContent = 'You are TIMED IN';
                        // Auto-select time_out radio button
                        document.getElementById('time_out').checked = true;
                        document.getElementById('time_out').parentElement.classList.add('active');
                        document.getElementById('time_in').parentElement.classList.remove('active');
                    } else if (data.attendance_status === 'timed_out') {
                        statusContainer.className = 'd-inline-block px-4 py-2 font-weight-bold rounded bg-danger text-white';
                        currentStatus.textContent = 'You are TIMED OUT';
                        // Auto-select time_in radio button
                        document.getElementById('time_in').checked = true;
                        document.getElementById('time_in').parentElement.classList.add('active');
                        document.getElementById('time_out').parentElement.classList.remove('active');
                    } else {
                        statusContainer.className = 'd-inline-block px-4 py-2 font-weight-bold rounded bg-warning text-dark';
                        currentStatus.textContent = 'No attendance record today';
                        // Auto-select time_in radio button
                        document.getElementById('time_in').checked = true;
                        document.getElementById('time_in').parentElement.classList.add('active');
                        document.getElementById('time_out').parentElement.classList.remove('active');
                    }
                } else {
                    statusContainer.className = 'd-inline-block px-4 py-2 font-weight-bold rounded bg-secondary text-white';
                    currentStatus.textContent = 'Unable to check status';
                }
            })
            .catch(error => {
                console.error('Error checking attendance status:', error);
                statusContainer.className = 'd-inline-block px-4 py-2 font-weight-bold rounded bg-secondary text-white';
                currentStatus.textContent = 'Error checking status';
            });
    }
});
</script>
@endsection