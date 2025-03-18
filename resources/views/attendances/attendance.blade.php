@extends('layouts.app')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2 px-3 mb-4 rounded shadow-sm">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
    </ol>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <!-- Time and Attendance Card -->
        <div class="col-lg-8 col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Attendance System
                    </h5>
                    <span class="badge bg-light text-primary" id="current-date"></span>
                </div>
                <div class="card-body">
                    <!-- Real-time Clock -->
                    <div class="text-center mb-4">
                        <div class="display-1 fw-bold text-primary mb-2" id="current-time">00:00:00</div>
                        <div class="text-muted small">Local Time</div>
                    </div>

                    <!-- Clock In/Out Button -->
                    <div class="text-center mb-4">
                        <div class="d-grid gap-2 col-lg-6 col-md-8 mx-auto">
                            <button id="attendance-btn" class="btn btn-lg btn-primary px-5 py-3 shadow-sm">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Clock In
                            </button>
                            <div class="text-muted small mt-2" id="last-action">
                                Last action: Not available
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Card -->
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Location Details
                    </h5>
                </div>
                <div class="card-body">
                    <div id="location-status" class="alert alert-info d-none">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span class="status-message">Waiting for location access...</span>
                    </div>
                    
                    <div class="location-info">
                        <div class="mb-3">
                            <label class="text-muted small">Current Address</label>
                            <p id="current-location" class="mb-0 fw-bold">Waiting for location...</p>
                        </div>
                        <div id="coordinates-info" class="d-none">
                            <label class="text-muted small">Coordinates</label>
                            <p id="coordinates" class="mb-0 font-monospace"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Today's Activity
                    </h5>
                    <span class="badge bg-primary" id="activity-date"></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width: 15%">Clock In Time</th>
                                    <th style="width: 25%">Clock In Location</th>
                                    <th style="width: 15%">Clock Out Time</th>
                                    <th style="width: 25%">Clock Out Location</th>
                                    <th style="width: 20%">Status</th>
                                </tr>
                            </thead>
                            <tbody id="activity-log">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No activity recorded today</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .breadcrumb {
        background: transparent;
    }
    .location-info {
        border-radius: 0.375rem;
    }
    #attendance-btn {
        transition: all 0.3s ease;
        border-radius: 50px;
    }
    #attendance-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    #current-time {
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
    }
    .card {
        border: none;
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    @media (max-width: 768px) {
        .display-1 {
            font-size: 3.5rem;
        }
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    .table td {
        vertical-align: middle;
    }
    .activity-time {
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }
    .location-text {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.9rem;
    }
    .status-badge {
        min-width: 90px;
    }
    
    /* Camera modal styles */
    #camera-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        z-index: 9999;
    }
    
    .camera-container {
        position: relative;
        width: 100%;
        height: 100%;
        background: #000;
        overflow: hidden;
    }
    
    .camera-body {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    #camera-view {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        transition: opacity 0.3s ease;
    }
    
    .camera-frame {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 220px;
        height: 220px;
        border: 2px solid rgba(255, 215, 0, 0.8);
        box-sizing: border-box;
        z-index: 5;
        pointer-events: none;
    }
    
    .camera-frame::before,
    .camera-frame::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-color: rgba(255, 215, 0, 0.8);
        border-style: solid;
    }
    
    /* Top left corner */
    .camera-frame::before {
        top: -2px;
        left: -2px;
        border-width: 2px 0 0 2px;
    }
    
    /* Bottom right corner */
    .camera-frame::after {
        bottom: -2px;
        right: -2px;
        border-width: 0 2px 2px 0;
    }
    
    .camera-controls {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 10;
    }
    
    .camera-options {
        display: flex;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 15px 15px 10px;
        justify-content: center;
        z-index: 10;
        background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0) 100%);
    }
    
    .camera-controls-group {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    
    .camera-option {
        color: white;
        background: none;
        border: none;
        font-size: 1.2rem;
        width: 44px;
        height: 44px;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0.85;
        transition: all 0.2s;
        position: relative;
    }
    
    .camera-option.active {
        color: #ffcc00;
        opacity: 1;
    }
    
    .camera-option.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #ffcc00;
    }
    
    .camera-option:hover {
        opacity: 1;
        transform: scale(1.05);
    }
    
    .switch-camera-btn {
        background: none;
        border: none;
        font-size: 1.3rem;
        color: #fff;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .switch-camera-btn:hover {
        opacity: 1;
    }
    
    /* Gallery button */
    .gallery-btn-wrapper {
        position: relative;
        width: 40px;
        height: 40px;
    }
    
    .gallery-btn {
        width: 40px;
        height: 40px;
        border-radius: 5px;
        background-color: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
    }
    
    .gallery-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    /* Enhanced capture button with text */
    .capture-container {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .capture-btn {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: white;
        border: 4px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }
    
    .capture-btn::before {
        content: '';
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: white;
        border: 2px solid #ddd;
    }
    
    .capture-btn:active {
        transform: scale(0.95);
    }
    
    .capture-label {
        position: absolute;
        bottom: -30px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
        font-weight: 500;
        font-size: 14px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        background: rgba(0,0,0,0.3);
        padding: 4px 8px;
        border-radius: 20px;
        white-space: nowrap;
        max-width: 120px;
        margin: 0 auto;
        display: none; /* Hide the label */
    }
    
    .zoom-controls {
        position: absolute;
        bottom: 100px;
        left: 0;
        right: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 5;
    }
    
    .zoom-indicator {
        color: white;
        background: rgba(0, 0, 0, 0.4);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    
    .zoom-slider-container {
        width: 80%;
        max-width: 300px;
        background: rgba(0, 0, 0, 0.4);
        border-radius: 20px;
        padding: 5px 15px;
        display: none;
    }
    
    .zoom-slider {
        width: 100%;
        cursor: pointer;
        -webkit-appearance: none;
        height: 6px;
        border-radius: 3px;
        background: rgba(255, 255, 255, 0.3);
        outline: none;
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
    
    .timer-options {
        position: absolute;
        top: 60px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.6);
        border-radius: 10px;
        padding: 5px;
        display: none;
        z-index: 15;
    }
    
    .timer-option {
        color: white;
        background: none;
        border: none;
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .timer-option.active {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .timer-countdown {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 8rem;
        font-weight: bold;
        z-index: 20;
        display: none;
    }
    
    .flash-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        opacity: 0;
        z-index: 15;
        pointer-events: none;
    }
    
    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.3);
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        z-index: 15;
        opacity: 0.9;
        transition: opacity 0.2s;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .close-btn:hover {
        opacity: 1;
        background: rgba(0, 0, 0, 0.5);
    }
    
    /* Camera switching animation */
    .camera-transition {
        opacity: 0.1;
    }
    
    /* Enhanced filter effects */
    .filter-options {
        position: absolute;
        bottom: 100px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        z-index: 11;
        overflow-x: auto;
        padding: 15px 0;
        display: none;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
    
    .filter-option {
        position: relative;
        width: 60px;
        height: 60px;
        margin: 0 8px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    
    .filter-option.active {
        border-color: #ffcc00;
        transform: scale(1.05);
    }
    
    .filter-option:not(.active):hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.7);
    }
    
    .filter-preview {
        width: 100%;
        height: 100%;
        background-position: center;
        background-size: cover;
    }
    
    .filter-label {
        position: absolute;
        bottom: -22px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }
    
    /* Enhanced filter effects */
    .filter-normal {
        filter: none;
    }
    
    .filter-grayscale {
        filter: grayscale(100%);
    }
    
    .filter-sepia {
        filter: sepia(80%);
    }
    
    .filter-invert {
        filter: invert(85%);
    }
    
    .filter-saturate {
        filter: saturate(200%) contrast(110%);
    }
    
    .filter-warm {
        filter: sepia(30%) saturate(150%) brightness(105%) contrast(105%);
    }
    
    .filter-cool {
        filter: hue-rotate(340deg) saturate(120%) brightness(102%);
    }
    
    .filter-beauty {
        filter: brightness(105%) contrast(105%) saturate(110%);
    }
    
    .filter-smooth {
        filter: brightness(105%) contrast(95%) saturate(105%) blur(0.5px);
    }
    
    @media (max-width: 768px) {
        .camera-container {
            width: 100%;
            height: 100%;
        }
        
        .camera-frame {
            width: 180px;
            height: 180px;
        }
        
        .timer-option {
            padding: 8px 12px;
        }
    }

    /* Action Identification Banner */
    .action-banner {
        position: absolute;
        top: 60px;
        left: 0;
        right: 0;
        text-align: center;
        z-index: 10;
        pointer-events: none;
    }
    
    .action-text {
        display: inline-block;
        padding: 8px 20px;
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        border-radius: 30px;
        letter-spacing: 1px;
        text-transform: uppercase;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    .clock-in-text {
        background-color: rgba(25, 135, 84, 0.8);
    }
    
    .clock-out-text {
        background-color: rgba(220, 53, 69, 0.8);
    }
    
    /* Action identifier at bottom left */
    .minimized-action {
        position: absolute;
        bottom: 90px;
        left: 15px;
        z-index: 10;
        pointer-events: none;
    }
    
    .minimized-action-text {
        display: inline-block;
        padding: 4px 8px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 4px;
        text-transform: uppercase;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
    }
    
    .minimized-clock-in {
        color: #20c997;
    }
    
    .minimized-clock-out {
        color: #ff6b6b;
    }
    
    /* User information overlay */
    .user-info-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 70%, rgba(0, 0, 0, 0) 100%);
        padding: 20px 15px 80px;
        z-index: 5;
        pointer-events: none;
        display: flex;
        flex-direction: column;
    }
    
    .user-info-datetime {
        color: white;
        font-size: 0.9rem;
        margin-bottom: 5px;
        font-family: 'Courier New', monospace;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.8);
    }
    
    .user-info-location {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.8rem;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.8);
    }
    
    .user-info-details {
        display: flex;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.75rem;
        margin-bottom: 2px;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.8);
    }
    
    .user-info-name {
        font-weight: 600;
        margin-right: 10px;
    }

    /* New styled info panel like the image */
    .info-sidebar {
        position: absolute;
        bottom: 0;
        left: 0;
        top: 0;
        width: auto;
        max-width: 400px;
        z-index: 6;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 0;
        pointer-events: none;
        background: linear-gradient(to right, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.15) 70%, rgba(0,0,0,0) 100%);
    }
    
    .info-content {
        padding: 20px;
        margin-top: auto;
        margin-bottom: 90px;
    }
    
    .clock-badge {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        background: none;
    }
    
    .clock-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #28a745;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        margin-right: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .clock-out-status {
        background-color: #dc3545;
    }
    
    .clock-time {
        font-size: 2rem;
        font-weight: bold;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        font-family: Arial, sans-serif;
    }
    
    .date-display {
        font-size: 1.1rem;
        color: white;
        margin-bottom: 15px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }
    
    .location-display {
        color: white;
        margin-bottom: 15px;
        font-size: 0.9rem;
        line-height: 1.4;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }
    
    .user-display {
        color: white;
        margin-bottom: 5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }
    
    .user-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .user-company, .user-position {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }
    
    /* Vertical accent line */
    .accent-line {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 5px;
        background-color: #28a745;
    }
    
    .accent-line.clock-out {
        background-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update time every second
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('activity-date').textContent = now.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Attendance button functionality
    const attendanceBtn = document.getElementById('attendance-btn');
    const lastAction = document.getElementById('last-action');
    let isClockIn = true;

    // Camera variables
    let stream = null;
    let cameraFacingMode = 'environment'; // Start with rear camera
    let actionType = '';
    let imageCapture = null;
    let hasFlash = false;
    let flashOn = false;
    let timerValue = 0;
    let zoomValue = 1;
    let hdrActive = false;
    let activeFilter = 'normal';
    let timerInterval = null;
    
    // Create camera modal element
    const cameraModal = document.createElement('div');
    cameraModal.id = 'camera-modal';
    cameraModal.innerHTML = `
        <div class="camera-container">
            <div class="camera-options">
                <div class="camera-controls-group">
                    <button class="camera-option" id="flash-toggle" title="Flash">
                        <i class="fas fa-bolt"></i>
                    </button>
                    <button class="camera-option" id="hdr-toggle" title="HDR">
                        HDR
                    </button>
                    <button class="camera-option" id="timer-toggle" title="Timer">
                        <i class="fas fa-clock"></i>
                    </button>
                    <button class="camera-option" id="filter-toggle" title="Filters & Beauty">
                        <i class="fas fa-magic"></i>
                    </button>
                </div>
            </div>
            <div class="action-banner">
                <div class="action-text" id="action-text">CLOCK IN</div>
            </div>
            <div class="timer-options" id="timer-options">
                <button class="timer-option" data-timer="0">Off</button>
                <button class="timer-option" data-timer="3">3s</button>
                <button class="timer-option" data-timer="5">5s</button>
                <button class="timer-option" data-timer="10">10s</button>
            </div>
            <div class="camera-body">
                <video id="camera-view" autoplay playsinline class="filter-normal"></video>
                <div class="camera-frame"></div>
                <div class="timer-countdown" id="timer-countdown">3</div>
                <div class="flash-animation" id="flash-animation"></div>
                
                <div class="info-sidebar">
                    <div class="accent-line" id="accent-line"></div>
                    <div class="info-content">
                        <div class="clock-badge">
                            <div class="clock-status" id="clock-status">Clock In</div>
                            <div class="clock-time" id="clock-time">07:55</div>
                        </div>
                        <div class="date-display" id="date-display">Tue, Mar 18, 2025</div>
                        <div class="location-display" id="location-display">
                            Jose L Briones Street, Lungsod ng Cebu,<br>
                            6000 Lalawigan ng Cebu
                        </div>
                        <div class="user-display">
                            <div class="user-name" id="user-name">Name: Edmar Crescencio</div>
                            <div class="user-company" id="user-company">Company: MHR Property Conglomerates, Inc.</div>
                            <div class="user-position" id="user-position">Position: IT Staff - Admin Department</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="zoom-controls">
                <div class="zoom-indicator" id="zoom-indicator">1×</div>
                <div class="zoom-slider-container" id="zoom-slider-container">
                    <input type="range" min="1" max="5" step="0.1" value="1" class="zoom-slider" id="zoom-slider">
                </div>
            </div>
            <div class="filter-options" id="filter-options">
                <div class="filter-option active" data-filter="normal">
                    <div class="filter-preview" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Normal</div>
                </div>
                <div class="filter-option" data-filter="beauty">
                    <div class="filter-preview filter-beauty" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Beauty</div>
                </div>
                <div class="filter-option" data-filter="smooth">
                    <div class="filter-preview filter-smooth" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Smooth</div>
                </div>
                <div class="filter-option" data-filter="warm">
                    <div class="filter-preview filter-warm" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Warm</div>
                </div>
                <div class="filter-option" data-filter="cool">
                    <div class="filter-preview filter-cool" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Cool</div>
                </div>
                <div class="filter-option" data-filter="grayscale">
                    <div class="filter-preview filter-grayscale" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">B&W</div>
                </div>
                <div class="filter-option" data-filter="sepia">
                    <div class="filter-preview filter-sepia" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Sepia</div>
                </div>
                <div class="filter-option" data-filter="saturate">
                    <div class="filter-preview filter-saturate" style="background-image: url('https://images.pexels.com/photos/1270076/pexels-photo-1270076.jpeg?auto=compress&cs=tinysrgb&w=120');"></div>
                    <div class="filter-label">Vivid</div>
                </div>
            </div>
            <div class="camera-controls">
                <div class="gallery-btn-wrapper">
                    <div class="gallery-btn" id="gallery-btn">
                        <i class="fas fa-images"></i>
                    </div>
                    <input type="file" accept="image/*" class="gallery-input" id="gallery-input">
                </div>
                <div class="capture-container">
                    <div class="capture-btn" id="capture-photo"></div>
                    <div class="capture-label" id="capture-label">Clock In</div>
                </div>
                <button class="switch-camera-btn" id="switch-camera">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <button class="close-btn" id="close-camera">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(cameraModal);
    
    // Get elements
    const closeCamera = document.getElementById('close-camera');
    const switchCamera = document.getElementById('switch-camera');
    const capturePhoto = document.getElementById('capture-photo');
    const cameraView = document.getElementById('camera-view');
    const flashToggle = document.getElementById('flash-toggle');
    const hdrToggle = document.getElementById('hdr-toggle');
    const timerToggle = document.getElementById('timer-toggle');
    const filterToggle = document.getElementById('filter-toggle');
    const timerOptions = document.getElementById('timer-options');
    const timerOptionButtons = document.querySelectorAll('.timer-option');
    const zoomIndicator = document.getElementById('zoom-indicator');
    const zoomSlider = document.getElementById('zoom-slider');
    const zoomSliderContainer = document.getElementById('zoom-slider-container');
    const timerCountdown = document.getElementById('timer-countdown');
    const flashAnimation = document.getElementById('flash-animation');
    const filterOptionsContainer = document.getElementById('filter-options');
    const filterOptions = document.querySelectorAll('.filter-option');
    const galleryInput = document.getElementById('gallery-input');
    const captureLabel = document.getElementById('capture-label');
    const actionText = document.getElementById('action-text');
    
    // Get references to the new elements
    const clockStatus = document.getElementById('clock-status');
    const clockTime = document.getElementById('clock-time');
    const dateDisplay = document.getElementById('date-display');
    const locationDisplay = document.getElementById('location-display');
    const userName = document.getElementById('user-name');
    const userCompany = document.getElementById('user-company');
    const userPosition = document.getElementById('user-position');
    const accentLine = document.getElementById('accent-line');
    
    // Function to open camera
    async function openCamera(facing) {
        try {
            if (stream) {
                // Add transition effect
                cameraView.classList.add('camera-transition');
                
                // Wait for transition to complete
                await new Promise(resolve => setTimeout(resolve, 300));
                
                stopCamera(false);
            }
            
            const constraints = {
                video: {
                    facingMode: facing,
                    width: { ideal: 1920 },
                    height: { ideal: 1080 }
                },
                audio: false
            };
            
            stream = await navigator.mediaDevices.getUserMedia(constraints);
            cameraView.srcObject = stream;
            
            // Create ImageCapture object
            const videoTrack = stream.getVideoTracks()[0];
            imageCapture = new ImageCapture(videoTrack);
            
            // Check if flash is supported
            try {
                const capabilities = videoTrack.getCapabilities();
                hasFlash = !!capabilities.torch;
                flashToggle.style.display = hasFlash ? 'block' : 'none';
                
                // Get supported zoom range
                if (capabilities.zoom) {
                    zoomSlider.min = capabilities.zoom.min;
                    zoomSlider.max = capabilities.zoom.max;
                    zoomSlider.step = (capabilities.zoom.max - capabilities.zoom.min) / 20;
                    zoomSlider.value = 1;
                    zoomValue = 1;
                    zoomIndicator.textContent = '1×';
                }
            } catch (e) {
                console.log('Capabilities API not supported');
                hasFlash = false;
                flashToggle.style.display = 'none';
            }
            
            // Apply mirroring if using front camera
            if (facing === 'user') {
                cameraView.style.transform = 'scaleX(-1)';
            } else {
                cameraView.style.transform = 'scaleX(1)';
            }
            
            // Force fullscreen on mobile if possible
            if (document.documentElement.requestFullscreen && window.innerWidth < 768) {
                try {
                    await document.documentElement.requestFullscreen();
                } catch (e) {
                    console.log('Fullscreen not supported or not allowed');
                }
            }
            
            cameraModal.style.display = 'block';
            
            // Hide scrollbars on body
            document.body.style.overflow = 'hidden';
            
            // Remove transition class after a short delay
            setTimeout(() => {
                cameraView.classList.remove('camera-transition');
            }, 50);
            
            // Reset HDR and flash status
            hdrActive = false;
            flashOn = false;
            hdrToggle.classList.remove('active');
            flashToggle.classList.remove('active');
            
            // Reset timer
            timerValue = 0;
            timerOptions.style.display = 'none';
            timerOptionButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.timer === '0') {
                    btn.classList.add('active');
                }
            });
            
            // Reset zoom
            zoomValue = 1;
            zoomIndicator.textContent = '1×';
            zoomSlider.value = 1;
            
            // Reset filter
            activeFilter = 'normal';
            cameraView.className = 'filter-normal';
            filterOptionsContainer.style.display = 'none';
            filterOptions.forEach(option => {
                option.classList.remove('active');
                if (option.dataset.filter === 'normal') {
                    option.classList.add('active');
                }
            });
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Unable to access camera. Please ensure you have granted camera permissions.');
            
            // Proceed with attendance without camera if error
            processAttendance();
        }
    }
    
    // Function to stop camera
    function stopCamera(hideModal = true) {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            imageCapture = null;
        }
        
        if (hideModal) {
            cameraModal.style.display = 'none';
            
            // Restore scrollbars
            document.body.style.overflow = '';
            
            // Exit fullscreen if we're in it
            if (document.fullscreenElement) {
                document.exitFullscreen().catch(err => {
                    console.log('Error exiting fullscreen:', err);
                });
            }
        }
        
        // Clear any timer
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            timerCountdown.style.display = 'none';
        }
    }
    
    // Toggle flash
    flashToggle.addEventListener('click', async function() {
        if (!hasFlash || !stream) return;
        
        try {
            const track = stream.getVideoTracks()[0];
            flashOn = !flashOn;
            
            await track.applyConstraints({
                advanced: [{ torch: flashOn }]
            });
            
            if (flashOn) {
                flashToggle.classList.add('active');
            } else {
                flashToggle.classList.remove('active');
            }
        } catch (e) {
            console.error('Error toggling flash:', e);
            alert('Unable to control flash on this device.');
            hasFlash = false;
            flashToggle.style.display = 'none';
        }
    });
    
    // Toggle HDR
    hdrToggle.addEventListener('click', function() {
        hdrActive = !hdrActive;
        if (hdrActive) {
            hdrToggle.classList.add('active');
        } else {
            hdrToggle.classList.remove('active');
        }
    });
    
    // Timer toggle
    timerToggle.addEventListener('click', function() {
        if (timerOptions.style.display === 'none' || timerOptions.style.display === '') {
            timerOptions.style.display = 'block';
            filterOptionsContainer.style.display = 'none';
        } else {
            timerOptions.style.display = 'none';
        }
    });
    
    // Timer options
    timerOptionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            timerValue = parseInt(this.dataset.timer);
            timerOptionButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            timerOptions.style.display = 'none';
            
            if (timerValue > 0) {
                timerToggle.classList.add('active');
            } else {
                timerToggle.classList.remove('active');
            }
        });
    });
    
    // Filter toggle
    filterToggle.addEventListener('click', function() {
        if (filterOptionsContainer.style.display === 'none' || filterOptionsContainer.style.display === '') {
            // Show with animation
            filterOptionsContainer.style.opacity = '0';
            filterOptionsContainer.style.display = 'flex';
            setTimeout(() => {
                filterOptionsContainer.style.opacity = '1';
            }, 10);
            timerOptions.style.display = 'none';
            zoomSliderContainer.style.display = 'none';
        } else {
            // Hide with animation
            filterOptionsContainer.style.opacity = '0';
            setTimeout(() => {
                filterOptionsContainer.style.display = 'none';
            }, 200);
        }
    });
    
    // Filter options
    filterOptions.forEach(option => {
        option.addEventListener('click', function() {
            activeFilter = this.dataset.filter;
            
            // Update UI
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Apply filter with transition
            cameraView.style.transition = 'filter 0.3s ease';
            cameraView.className = '';
            cameraView.classList.add(`filter-${activeFilter}`);
            
            // Update filter toggle indicator
            if (activeFilter !== 'normal') {
                filterToggle.classList.add('active');
            } else {
                filterToggle.classList.remove('active');
            }
        });
    });
    
    // Zoom controls
    zoomIndicator.addEventListener('click', function() {
        if (zoomSliderContainer.style.display === 'none' || zoomSliderContainer.style.display === '') {
            zoomSliderContainer.style.display = 'block';
        } else {
            zoomSliderContainer.style.display = 'none';
        }
    });
    
    zoomSlider.addEventListener('input', async function() {
        if (!stream) return;
        
        try {
            const track = stream.getVideoTracks()[0];
            zoomValue = parseFloat(this.value);
            zoomIndicator.textContent = `${zoomValue.toFixed(1)}×`;
            
            await track.applyConstraints({
                advanced: [{ zoom: zoomValue }]
            });
        } catch (e) {
            console.log('Zoom not supported on this device');
        }
    });
    
    // Add click event to attendance button
    attendanceBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Set action type
        actionType = isClockIn ? 'Clock In' : 'Clock Out';
        
        // Update text and styles for the action banner
        actionText.textContent = actionType.toUpperCase();
        actionText.className = 'action-text';
        if (isClockIn) {
            actionText.classList.add('clock-in-text');
        } else {
            actionText.classList.add('clock-out-text');
        }
        
        // Get current date and time
        const now = new Date();
        
        // Format time as shown in the image
        clockTime.textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        
        // Update clock status and accent line
        clockStatus.textContent = actionType;
        if (isClockIn) {
            clockStatus.classList.remove('clock-out-status');
            accentLine.classList.remove('clock-out');
        } else {
            clockStatus.classList.add('clock-out-status');
            accentLine.classList.add('clock-out');
        }
        
        // Format date like "Tue, Mar 18, 2025"
        dateDisplay.textContent = now.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
        
        // Use location from the document if available
        const locationElement = document.getElementById('current-location');
        if (locationElement && locationElement.textContent) {
            // Split location into two lines for better readability
            const fullLocation = locationElement.textContent;
            const locationParts = fullLocation.split(',');
            
            if (locationParts.length >= 3) {
                const firstLine = locationParts.slice(0, 2).join(',');
                const secondLine = locationParts.slice(2).join(',');
                locationDisplay.innerHTML = `${firstLine.trim()},<br>${secondLine.trim()}`;
            } else {
                locationDisplay.innerHTML = fullLocation;
            }
        }
        
        // In a real application, these would be populated from the user's profile data
        userName.textContent = 'Name: Edmar Crescencio';
        userCompany.textContent = 'Company: MHR Property Conglomerates, Inc.';
        userPosition.textContent = 'Position: IT Staff - Admin Department';
        
        // Open camera
        openCamera(cameraFacingMode);
    });
    
    // Switch camera
    switchCamera.addEventListener('click', function() {
        cameraFacingMode = cameraFacingMode === 'user' ? 'environment' : 'user';
        openCamera(cameraFacingMode);
    });
    
    // Close camera
    closeCamera.addEventListener('click', function() {
        stopCamera();
    });
    
    // Gallery input
    galleryInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Here you would process the selected image
                // For this demo, we'll just proceed with attendance
                stopCamera();
                processAttendance();
                
                // Reset the input so the same file can be selected again
                galleryInput.value = '';
            };
            
            reader.readAsDataURL(file);
        }
    });
    
    // Show flash animation
    function showFlashAnimation() {
        flashAnimation.style.opacity = '1';
        setTimeout(() => {
            flashAnimation.style.opacity = '0';
        }, 100);
    }
    
    // Capture photo
    capturePhoto.addEventListener('click', function() {
        if (timerValue > 0) {
            // Start timer countdown
            let countdown = timerValue;
            timerCountdown.textContent = countdown;
            timerCountdown.style.display = 'flex';
            
            timerInterval = setInterval(() => {
                countdown--;
                timerCountdown.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    timerCountdown.style.display = 'none';
                    
                    // Take photo after countdown
                    takePhoto();
                }
            }, 1000);
        } else {
            // Take photo immediately
            takePhoto();
        }
    });
    
    // Take photo
    function takePhoto() {
        if (!imageCapture) return;
        
        // Show flash animation
        showFlashAnimation();
        
        // Here you would typically capture the image from video
        imageCapture.takePhoto()
            .then(blob => {
                // In a real implementation, you would process the image here
                console.log('Photo captured:', blob);
                
                // Stop camera after capture
                stopCamera();
                
                // Process the attendance
                processAttendance();
            })
            .catch(error => {
                console.error('Error taking photo:', error);
                
                // Fallback to canvas capture if ImageCapture API fails
                const canvas = document.createElement('canvas');
                canvas.width = cameraView.videoWidth;
                canvas.height = cameraView.videoHeight;
                const ctx = canvas.getContext('2d');
                
                // Apply the current filter, mirroring if needed
                if (cameraFacingMode === 'user') {
                    ctx.translate(canvas.width, 0);
                    ctx.scale(-1, 1);
                }
                
                ctx.drawImage(cameraView, 0, 0);
                
                // Apply filters if any
                // Note: Canvas filters aren't well supported in all browsers
                // A better approach would be to use WebGL for filters
                
                canvas.toBlob(blob => {
                    console.log('Fallback photo captured:', blob);
                    
                    // Stop camera after capture
                    stopCamera();
                    
                    // Process the attendance
                    processAttendance();
                });
            });
    }
    
    // Process attendance after camera identification
    function processAttendance() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        
        if (isClockIn) {
            attendanceBtn.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Clock Out';
            attendanceBtn.classList.remove('btn-primary');
            attendanceBtn.classList.add('btn-danger');
            lastAction.textContent = `Last action: Clocked in at ${timeString}`;
        } else {
            attendanceBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Clock In';
            attendanceBtn.classList.remove('btn-danger');
            attendanceBtn.classList.add('btn-primary');
            lastAction.textContent = `Last action: Clocked out at ${timeString}`;
        }
        isClockIn = !isClockIn;
        
        // Here you can add AJAX call to your backend to record the attendance
        updateActivityLog(actionType);
    }

    // Activity Log Update
    let currentActivityRow = null;
    
    function updateActivityLog(action) {
        const tbody = document.getElementById('activity-log');
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        const location = document.getElementById('current-location').textContent;
        
        if (action === 'Clock In') {
            // Create new row for new clock in
            if (tbody.firstElementChild.getElementsByTagName('td')[0].colSpan) {
                tbody.innerHTML = '';
            }
            
            const row = document.createElement('tr');
            row.className = 'text-center';
            row.innerHTML = `
                <td class="activity-time text-success">${timeString}</td>
                <td class="location-text" title="${location}">${location}</td>
                <td class="activity-time text-muted">--:--:-- --</td>
                <td class="location-text text-muted">--</td>
                <td><span class="badge bg-warning status-badge">In Progress</span></td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
            currentActivityRow = row;
        } else if (action === 'Clock Out' && currentActivityRow) {
            // Update existing row with clock out time
            currentActivityRow.children[2].textContent = timeString;
            currentActivityRow.children[2].className = 'activity-time text-danger';
            currentActivityRow.children[3].textContent = location;
            currentActivityRow.children[3].className = 'location-text';
            currentActivityRow.children[3].title = location;
            currentActivityRow.children[4].innerHTML = '<span class="badge bg-success status-badge">Completed</span>';
            currentActivityRow = null;
        }
    }

    // Location tracking
    const locationStatus = document.getElementById('location-status');
    const currentLocation = document.getElementById('current-location');
    const coordinatesInfo = document.getElementById('coordinates-info');
    const coordinates = document.getElementById('coordinates');

    if ("geolocation" in navigator) {
        locationStatus.classList.remove('d-none');
        locationStatus.className = 'alert alert-info';
        locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Acquiring accurate location, please wait...';
        
        // High-accuracy options for geolocation
        const geoOptions = {
            enableHighAccuracy: true,  // Request the most accurate position available
            timeout: 15000,           // Wait longer for a more accurate position
            maximumAge: 0             // Always get a fresh location
        };
        
        navigator.permissions.query({name: 'geolocation'}).then(permissionStatus => {
            // Handle permission status changes
            permissionStatus.onchange = () => {
                if (permissionStatus.state === 'granted') {
                    locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Acquiring accurate location, please wait...';
                    startLocationTracking();
                } else if (permissionStatus.state === 'denied') {
                    locationStatus.classList.remove('d-none');
                    locationStatus.className = 'alert alert-danger';
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location services.';
                    currentLocation.textContent = 'Location access required';
                    coordinatesInfo.classList.add('d-none');
                }
            };
            
            if (permissionStatus.state === 'granted') {
                startLocationTracking();
            } else if (permissionStatus.state === 'prompt') {
                locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Please allow location access when prompted';
                startLocationTracking();
            } else if (permissionStatus.state === 'denied') {
                locationStatus.classList.remove('d-none');
                locationStatus.className = 'alert alert-danger';
                locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location services.';
                currentLocation.textContent = 'Location access required';
                coordinatesInfo.classList.add('d-none');
            }
        }).catch(error => {
            // Fallback if permissions API not supported
            startLocationTracking();
        });
        
        function startLocationTracking() {
            // First get a single position with high accuracy
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Check if accuracy is good enough (less than 100 meters)
                    if (position.coords.accuracy > 100) {
                        locationStatus.classList.remove('d-none');
                        locationStatus.className = 'alert alert-warning';
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Location accuracy is low. Please go to an open area.';
                    } else {
                        getDetailedAddress(position.coords.latitude, position.coords.longitude, position.coords.accuracy);
                    }
                    
                    // Start watching position for changes
                    startWatchPosition();
                },
                handleLocationError,
                geoOptions
            );
        }
        
        function startWatchPosition() {
            // Continue watching the position for changes
            navigator.geolocation.watchPosition(
                function(position) {
                    // Only update if accuracy is good (less than 100 meters)
                    if (position.coords.accuracy <= 100) {
                        getDetailedAddress(position.coords.latitude, position.coords.longitude, position.coords.accuracy);
                    }
                },
                handleLocationError,
                geoOptions
            );
        }
        
        function getDetailedAddress(latitude, longitude, accuracy) {
            // Update the coordinates display
            coordinates.textContent = `${latitude.toFixed(6)}, ${longitude.toFixed(6)} (±${Math.round(accuracy)}m)`;
            coordinatesInfo.classList.remove('d-none');
            
            // Use a combination of services for better address resolution
            // First try Nominatim (OpenStreetMap)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data && data.display_name) {
                        // Format the address nicely
                        let formattedAddress = formatDisplayAddress(data);
                        currentLocation.textContent = formattedAddress;
                        
                        // Update location in the camera modal
                        if (document.getElementById('location-display')) {
                            const locationParts = formattedAddress.split(',');
                            if (locationParts.length >= 3) {
                                const firstLine = locationParts.slice(0, 2).join(',');
                                const secondLine = locationParts.slice(2).join(',');
                                document.getElementById('location-display').innerHTML = `${firstLine.trim()},<br>${secondLine.trim()}`;
                            } else {
                                document.getElementById('location-display').innerHTML = formattedAddress;
                            }
                        }
                        
                        // If we have a good accurate address, hide the status
                        locationStatus.classList.add('d-none');
                    } else {
                        throw new Error('No address found');
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    // Fallback message if address lookup fails
                    currentLocation.textContent = 'Address lookup failed. Using coordinate-based location.';
                    locationStatus.classList.remove('d-none');
                    locationStatus.className = 'alert alert-warning';
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Unable to fetch detailed address';
                });
        }
        
        function formatDisplayAddress(data) {
            // Create a more user-friendly formatted address from Nominatim data
            let address = '';
            const details = data.address;
            
            // Building components
            if (details.building) address += details.building + ', ';
            if (details.house_number) address += details.house_number + ' ';
            if (details.road) address += details.road + ', ';
            if (details.suburb) address += details.suburb + ', ';
            
            // City/town components
            if (details.city || details.town || details.village) {
                address += (details.city || details.town || details.village) + ', ';
            }
            
            // Region and postal code
            if (details.state || details.state_district) {
                address += (details.state || details.state_district) + ' ';
            }
            if (details.postcode) address += details.postcode + ', ';
            
            // Country
            if (details.country) address += details.country;
            
            // If we couldn't build a good address, use the display_name
            if (address.length < 10) {
                address = data.display_name;
            }
            
            return address;
        }
        
        function handleLocationError(error) {
            locationStatus.classList.remove('d-none');
            locationStatus.className = 'alert alert-danger';
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location services in your browser settings.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location information unavailable. Please check your device\'s GPS.';
                    break;
                case error.TIMEOUT:
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location request timed out. Please try again in an area with better GPS signal.';
                    break;
                default:
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Unknown error occurred while getting location.';
            }
            
            currentLocation.textContent = 'Location services inaccessible';
            coordinatesInfo.classList.add('d-none');
        }
    } else {
        locationStatus.classList.remove('d-none');
        locationStatus.className = 'alert alert-danger';
        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Geolocation is not supported by your browser.';
        currentLocation.textContent = 'Location services not supported';
        coordinatesInfo.classList.add('d-none');
    }
});
</script>
@endpush
@endsection
