@extends('layouts.app')

@section('content')
<!-- Add viewport meta tag to ensure proper scaling -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

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
        .card-body {
            padding: 1rem;
        }
        .location-text {
            max-width: 150px;
        }
        #current-time {
            font-size: 3rem;
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
    
    /* Small devices (landscape phones) */
    @media (max-width: 576px) {
        .display-1 {
            font-size: 2.8rem;
        }
        .capture-btn {
            width: 60px;
            height: 60px;
        }
        .capture-btn::before {
            width: 46px;
            height: 46px;
        }
        .card-header h5 {
            font-size: 1rem;
        }
        .camera-frame {
            width: 160px;
            height: 160px;
        }
        .location-text {
            max-width: 100px;
        }
        .card-body {
            padding: 0.75rem;
        }
        .table th, .table td {
            padding: 0.5rem 0.25rem;
            font-size: 0.8rem;
        }
        .breadcrumb {
            font-size: 0.85rem;
        }
        .info-content {
            padding: 15px;
            margin-bottom: 70px;
        }
        .clock-time {
            font-size: 1.5rem;
        }
        .date-display {
            font-size: 0.9rem;
        }
        .user-name, .user-company, .user-position {
            font-size: 0.8rem;
        }
    }
    
    /* Extra small devices */
    @media (max-width: 400px) {
        .display-1 {
            font-size: 2.5rem;
        }
        #current-time {
            font-size: 2.5rem;
        }
        .camera-frame {
            width: 140px;
            height: 140px;
        }
        .camera-option {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
        .camera-controls-group {
            gap: 10px;
        }
        .capture-btn {
            width: 55px;
            height: 55px;
        }
        .capture-btn::before {
            width: 42px;
            height: 42px;
        }
        .gallery-btn-wrapper, .gallery-btn {
            width: 36px;
            height: 36px;
        }
        .switch-camera-btn {
            font-size: 1.1rem;
        }
        .action-text {
            font-size: 1rem;
            padding: 6px 16px;
        }
        .info-content {
            padding: 10px;
            margin-bottom: 60px;
        }
        .info-sidebar {
            max-width: 70%;
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
    // Feature detection helper
    const hasFeature = {
        geolocation: 'geolocation' in navigator,
        mediaDevices: !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia),
        imageCapture: typeof ImageCapture !== 'undefined',
        canvas: !!document.createElement('canvas').getContext,
        touchEvents: 'ontouchstart' in window,
        orientation: 'orientation' in window || 'orientation' in screen
    };
    
    // Handle device orientation changes
    if (hasFeature.orientation) {
        window.addEventListener('orientationchange', function() {
            // Adjust UI for orientation change
            setTimeout(function() {
                const cameraFrame = document.querySelector('.camera-frame');
                if (cameraFrame) {
                    // Adjust frame size based on new orientation
                    if (window.innerWidth < 576) {
                        cameraFrame.style.width = '160px';
                        cameraFrame.style.height = '160px';
                    } else if (window.innerWidth < 768) {
                        cameraFrame.style.width = '180px';
                        cameraFrame.style.height = '180px';
                    } else {
                        cameraFrame.style.width = '220px';
                        cameraFrame.style.height = '220px';
                    }
                }
                
                // Re-center content
                if (document.querySelector('.camera-controls')) {
                    document.querySelector('.camera-controls').style.display = 'none';
                    setTimeout(function() {
                        document.querySelector('.camera-controls').style.display = 'flex';
                    }, 300);
                }
            }, 300); // Small delay to allow the browser to complete the orientation change
        });
    }
    
    // Log available features for debugging
    console.log('Device features:', hasFeature);
    
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

    // Camera variables - enhanced with device capability checks
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
    let cameraAvailable = hasFeature.mediaDevices;
    let cameraInitialized = false;
    let availableCameras = [];
    
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
    
    // Function to open camera with better device compatibility
    async function openCamera(facing) {
        try {
            if (stream) {
                // Add transition effect
                cameraView.classList.add('camera-transition');
                
                // Wait for transition to complete
                await new Promise(resolve => setTimeout(resolve, 300));
                
                stopCamera(false);
            }
            
            // Define constraints with fallbacks for different devices
            const constraints = {
                video: {
                    facingMode: facing,
                    width: { ideal: window.innerWidth < 768 ? 720 : 1280 },
                    height: { ideal: window.innerWidth < 768 ? 1280 : 720 }
                },
                audio: false
            };
            
            // Try to get stream with specified facing mode
            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
            } catch (e) {
                // If specific camera facing mode fails, try with any camera
                console.warn('Could not access specific camera, trying with default', e);
                constraints.video = { 
                    width: { ideal: window.innerWidth < 768 ? 720 : 1280 },
                    height: { ideal: window.innerWidth < 768 ? 1280 : 720 }
                };
                stream = await navigator.mediaDevices.getUserMedia(constraints);
            }
            
            cameraView.srcObject = stream;
            
            // Wait for video to be ready
            await new Promise(resolve => {
                cameraView.onloadedmetadata = () => {
                    cameraView.play().then(resolve).catch(resolve);
                };
                // Fallback if onloadedmetadata doesn't fire
                setTimeout(resolve, 1000);
            });
            
            // Create ImageCapture object if supported
            const videoTrack = stream.getVideoTracks()[0];
            if (hasFeature.imageCapture) {
                try {
                    imageCapture = new ImageCapture(videoTrack);
                } catch (e) {
                    console.warn('ImageCapture API failed:', e);
                }
            }
            
            // Check capabilities with error handling for different devices
            try {
                if ('getCapabilities' in videoTrack) {
                    const capabilities = videoTrack.getCapabilities();
                    
                    // Check flash support
                    hasFlash = !!capabilities.torch;
                    flashToggle.style.display = hasFlash ? 'block' : 'none';
                    
                    // Check zoom support
                    if (capabilities.zoom) {
                        const zoomSliderContainer = document.getElementById('zoom-slider-container');
                        const zoomIndicator = document.getElementById('zoom-indicator');
                        
                        zoomSlider.min = capabilities.zoom.min || 1;
                        zoomSlider.max = capabilities.zoom.max || 5;
                        zoomSlider.step = (parseFloat(zoomSlider.max) - parseFloat(zoomSlider.min)) / 20;
                        zoomSlider.value = 1;
                        
                        // Show zoom controls
                        zoomIndicator.style.display = 'block';
                    } else {
                        // Hide zoom controls if not supported
                        document.getElementById('zoom-indicator').style.display = 'none';
                    }
                } else {
                    // If getCapabilities is not supported, hide the controls
                    hasFlash = false;
                    flashToggle.style.display = 'none';
                    document.getElementById('zoom-indicator').style.display = 'none';
                }
            } catch (e) {
                console.warn('Device capabilities check failed:', e);
                hasFlash = false;
                flashToggle.style.display = 'none';
                document.getElementById('zoom-indicator').style.display = 'none';
            }
            
            // Apply mirroring if using front camera
            if (facing === 'user') {
                cameraView.style.transform = 'scaleX(-1)';
            } else {
                cameraView.style.transform = 'scaleX(1)';
            }
            
            // Check if we can enumerate devices to show camera switch button only when multiple cameras
            if (navigator.mediaDevices.enumerateDevices) {
                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    availableCameras = devices.filter(device => device.kind === 'videoinput');
                    document.getElementById('switch-camera').style.display = availableCameras.length > 1 ? 'block' : 'none';
                } catch (e) {
                    console.warn('Could not enumerate devices:', e);
                    document.getElementById('switch-camera').style.display = 'block'; // Show by default
                }
            }
            
            // Force fullscreen on mobile if possible and only if not already fullscreen
            if (window.innerWidth < 768 && !document.fullscreenElement) {
                try {
                    const requestFullscreen = document.documentElement.requestFullscreen || 
                                          document.documentElement.webkitRequestFullscreen ||
                                          document.documentElement.mozRequestFullScreen ||
                                          document.documentElement.msRequestFullscreen;
                    
                    if (requestFullscreen) {
                        await requestFullscreen.call(document.documentElement);
                    }
                } catch (e) {
                    console.warn('Fullscreen request failed:', e);
                }
            }
            
            cameraModal.style.display = 'block';
            
            // Hide scrollbars on body and prevent scrolling on touch devices
            document.body.style.overflow = 'hidden';
            if (hasFeature.touchEvents) {
                document.body.style.position = 'fixed';
                document.body.style.width = '100%';
            }
            
            // Remove transition class after a short delay
            setTimeout(() => {
                cameraView.classList.remove('camera-transition');
            }, 50);
            
            // Reset UI states
            // ... existing code ...
            
            cameraInitialized = true;
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            // Show more helpful error message based on the error
            let errorMessage = 'Unable to access camera. ';
            
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                errorMessage += 'Please ensure you have granted camera permissions in your browser settings.';
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                errorMessage += 'No camera found on this device.';
            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                errorMessage += 'Camera may be in use by another application.';
            } else if (error.name === 'OverconstrainedError') {
                errorMessage += 'Camera constraints not supported on this device.';
            } else {
                errorMessage += error.message || 'Please try again later.';
            }
            
            alert(errorMessage);
            
            // Proceed with attendance without camera if error
            processAttendance();
        }
    }
    
    // Function to stop camera with enhanced cleanup
    function stopCamera(hideModal = true) {
        if (stream) {
            stream.getTracks().forEach(track => {
                try {
                    track.stop();
                } catch (e) {
                    console.warn('Error stopping track:', e);
                }
            });
            stream = null;
            imageCapture = null;
        }
        
        if (hideModal) {
            cameraModal.style.display = 'none';
            
            // Restore scrollbars and body positioning
            document.body.style.overflow = '';
            if (hasFeature.touchEvents) {
                document.body.style.position = '';
                document.body.style.width = '';
            }
            
            // Exit fullscreen if we're in it
            if (document.fullscreenElement || 
                document.webkitFullscreenElement || 
                document.mozFullScreenElement || 
                document.msFullscreenElement) {
                try {
                    const exitFullscreen = document.exitFullscreen || 
                                      document.webkitExitFullscreen ||
                                      document.mozCancelFullScreen ||
                                      document.msExitFullscreen;
                    
                    if (exitFullscreen) {
                        exitFullscreen.call(document);
                    }
                } catch (e) {
                    console.warn('Error exiting fullscreen:', e);
                }
            }
        }
        
        // Clear any timer
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            timerCountdown.style.display = 'none';
        }
    }
    
    // Toggle flash - improved for better device compatibility
    flashToggle.addEventListener('click', async function() {
        if (!hasFlash || !stream) return;
        
        try {
            const track = stream.getVideoTracks()[0];
            flashOn = !flashOn;
            
            // Different methods to control flash/torch on different devices
            if ('applyConstraints' in track) {
                try {
                    await track.applyConstraints({
                        advanced: [{ torch: flashOn }]
                    });
                } catch (e) {
                    // Some Android devices need a different approach
                    if (track.getSettings && track.getSettings().torch !== undefined) {
                        await track.applyConstraints({ torch: flashOn });
                    } else {
                        throw e;
                    }
                }
            } else {
                // Fallback for older browsers/devices
                console.warn('Track applyConstraints not supported');
                // Cannot control flash
                hasFlash = false;
                flashToggle.style.display = 'none';
                return;
            }
            
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
    
    // Zoom handling enhanced for better cross-device compatibility
    zoomSlider.addEventListener('input', async function() {
        if (!stream) return;
        
        try {
            const track = stream.getVideoTracks()[0];
            zoomValue = parseFloat(this.value);
            zoomIndicator.textContent = `${zoomValue.toFixed(1)}×`;
            
            if ('applyConstraints' in track) {
                try {
                    await track.applyConstraints({
                        advanced: [{ zoom: zoomValue }]
                    });
                } catch (e) {
                    // Alternative approach for some devices
                    await track.applyConstraints({ zoom: zoomValue });
                }
            }
        } catch (e) {
            console.warn('Zoom not supported on this device:', e);
            // Hide zoom controls if they don't work
            document.getElementById('zoom-indicator').style.display = 'none';
            document.getElementById('zoom-slider-container').style.display = 'none';
        }
    });
    
    // Take photo function with enhanced fallbacks for different devices
    function takePhoto() {
        if (!stream) return;
        
        // Show flash animation
        showFlashAnimation();
        
        // Try ImageCapture API first if available
        if (imageCapture && hasFeature.imageCapture) {
            imageCapture.takePhoto()
                .then(blob => {
                    console.log('Photo captured with ImageCapture API:', blob);
                    stopCamera();
                    processAttendance();
                })
                .catch(error => {
                    console.warn('ImageCapture API failed, falling back to canvas:', error);
                    captureWithCanvas();
                });
        } else {
            // Fallback to canvas capture
            captureWithCanvas();
        }
    }
    
    // Canvas capture as fallback method
    function captureWithCanvas() {
        if (!stream || !hasFeature.canvas) {
            console.error('Canvas capture not supported');
            stopCamera();
            processAttendance();
            return;
        }
        
        try {
            const canvas = document.createElement('canvas');
            const video = document.getElementById('camera-view');
            
            // Use actual video dimensions, not element dimensions
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            
            const ctx = canvas.getContext('2d');
            
            // Clear canvas first
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Apply the current filter and mirroring if needed
            ctx.save();
            if (cameraFacingMode === 'user') {
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }
            
            // Draw video frame to canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Restore context
            ctx.restore();
            
            // Apply CSS-like filters programmatically for browsers that don't support canvas filters
            if (activeFilter !== 'normal' && !ctx.filter) {
                // Simple filter approximations
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = imageData.data;
                
                switch (activeFilter) {
                    case 'grayscale':
                        for (let i = 0; i < data.length; i += 4) {
                            const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
                            data[i] = avg;
                            data[i + 1] = avg;
                            data[i + 2] = avg;
                        }
                        break;
                    case 'sepia':
                        for (let i = 0; i < data.length; i += 4) {
                            const r = data[i];
                            const g = data[i + 1];
                            const b = data[i + 2];
                            data[i] = Math.min(255, (r * 0.393) + (g * 0.769) + (b * 0.189));
                            data[i + 1] = Math.min(255, (r * 0.349) + (g * 0.686) + (b * 0.168));
                            data[i + 2] = Math.min(255, (r * 0.272) + (g * 0.534) + (b * 0.131));
                        }
                        break;
                }
                
                ctx.putImageData(imageData, 0, 0);
            }
            
            // Export as blob
            canvas.toBlob(blob => {
                console.log('Photo captured with canvas:', blob);
                stopCamera();
                processAttendance();
            }, 'image/jpeg', 0.85);
            
        } catch (e) {
            console.error('Canvas capture failed:', e);
            stopCamera();
            processAttendance();
        }
    }
    
    // Location tracking with enhanced error handling and device compatibility
    const locationStatus = document.getElementById('location-status');
    const currentLocation = document.getElementById('current-location');
    const coordinatesInfo = document.getElementById('coordinates-info');
    const coordinates = document.getElementById('coordinates');

    if (hasFeature.geolocation) {
        try {
            // First try to get a quick location from cache
            navigator.geolocation.getCurrentPosition(
                position => {
                    // Display coordinates immediately
                    coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                    coordinatesInfo.classList.remove('d-none');
                    
                    // Set a temporary location while we get the address
                    currentLocation.textContent = 'Getting location address...';
                },
                error => {
                    // Just log the error, watchPosition will handle the UI updates
                    console.warn('Initial position check failed:', error);
                },
                { maximumAge: 60000, timeout: 2000, enableHighAccuracy: false }
            );
            
            // Start watching position with high accuracy
            navigator.geolocation.watchPosition(
                function(position) {
                    // Get address from coordinates using reverse geocoding
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}&zoom=18&addressdetails=1`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            currentLocation.textContent = data.display_name;
                            coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                            coordinatesInfo.classList.remove('d-none');
                            locationStatus.classList.add('d-none');
                        })
                        .catch(error => {
                            console.error('Geocoding error:', error);
                            currentLocation.textContent = 'Unable to fetch address';
                            coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                            coordinatesInfo.classList.remove('d-none');
                            locationStatus.classList.remove('d-none');
                            locationStatus.className = 'alert alert-warning';
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Unable to fetch address details';
                        });
                },
                function(error) {
                    locationStatus.classList.remove('d-none');
                    locationStatus.className = 'alert alert-danger';
                    
                    // More user-friendly error messages
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location permissions in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location information unavailable. Check your device GPS settings.';
                            break;
                        case error.TIMEOUT:
                            locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location request timed out. Please try again.';
                            break;
                        default:
                            locationStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>Location error: ${error.message || 'Unknown error'}`;
                    }
                    
                    currentLocation.textContent = 'Location access required';
                    coordinatesInfo.classList.add('d-none');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000, // Increased timeout for slower connections
                    maximumAge: 0
                }
            );
        } catch (e) {
            console.error('Geolocation error:', e);
            locationStatus.classList.remove('d-none');
            locationStatus.className = 'alert alert-danger';
            locationStatus.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>Geolocation error: ${e.message || 'Unknown error'}`;
            currentLocation.textContent = 'Location access failed';
            coordinatesInfo.classList.add('d-none');
        }
    } else {
        locationStatus.classList.remove('d-none');
        locationStatus.className = 'alert alert-danger';
        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Geolocation is not supported by your browser or device.';
        currentLocation.textContent = 'Location services not supported';
        coordinatesInfo.classList.add('d-none');
    }
    
    // Handle fullscreen properly for different browsers
    function requestFullscreen(element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) { /* Safari */
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) { /* IE11 */
            element.msRequestFullscreen();
        } else if (element.mozRequestFullScreen) { /* Firefox */
            element.mozRequestFullScreen();
        }
    }
    
    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) { /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE11 */
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) { /* Firefox */
            document.mozCancelFullScreen();
        }
    }
    
    // Improved function for stopping camera
    function stopCamera(hideModal = true) {
        if (stream) {
            stream.getTracks().forEach(track => {
                try {
                    track.stop();
                } catch (e) {
                    console.warn('Error stopping track:', e);
                }
            });
            stream = null;
            imageCapture = null;
        }
        
        if (hideModal) {
            cameraModal.style.display = 'none';
            
            // Restore scrollbars and prevent scrolling issues on mobile
            document.body.style.overflow = '';
            if (hasFeature.touchEvents) {
                document.body.style.position = '';
                document.body.style.width = '';
            }
            
            // Exit fullscreen if we're in it
            if (document.fullscreenElement || 
                document.webkitFullscreenElement || 
                document.mozFullScreenElement || 
                document.msFullscreenElement) {
                try {
                    exitFullscreen();
                } catch (e) {
                    console.warn('Error exiting fullscreen:', e);
                }
            }
        }
        
        // Clear any timer
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            timerCountdown.style.display = 'none';
        }
    }
    
    // Improved error handling for window events
    window.addEventListener('error', function(e) {
        console.error('Global error caught:', e.message, e);
        // Prevent errors from breaking the UI
        return true;
    });
    
    // Handle window resize to adjust UI for different device sizes
    window.addEventListener('resize', function() {
        // Adjust camera frame size for different screen sizes
        const cameraFrame = document.querySelector('.camera-frame');
        if (cameraFrame) {
            if (window.innerWidth < 576) {
                cameraFrame.style.width = '160px';
                cameraFrame.style.height = '160px';
            } else if (window.innerWidth < 768) {
                cameraFrame.style.width = '180px';
                cameraFrame.style.height = '180px';
            } else {
                cameraFrame.style.width = '220px';
                cameraFrame.style.height = '220px';
            }
        }
    });
    
    // HDR toggle
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
        const isVisible = timerOptions.style.display === 'block';
        timerOptions.style.display = isVisible ? 'none' : 'block';
        
        if (!isVisible) {
            // Set default timer option active
            timerOptionButtons.forEach(btn => {
                if (parseInt(btn.dataset.timer) === timerValue) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    });
    
    // Timer options
    timerOptionButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            timerValue = parseInt(this.dataset.timer);
            
            // Update UI
            timerOptionButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Hide options after selection
            timerOptions.style.display = 'none';
            
            // Update timer toggle button
            if (timerValue > 0) {
                timerToggle.classList.add('active');
            } else {
                timerToggle.classList.remove('active');
            }
        });
    });
    
    // Zoom indicator click to show/hide slider
    zoomIndicator.addEventListener('click', function() {
        const isVisible = zoomSliderContainer.style.display === 'block';
        zoomSliderContainer.style.display = isVisible ? 'none' : 'block';
    });
    
    // Filter toggle
    filterToggle.addEventListener('click', function() {
        const isVisible = filterOptionsContainer.style.display === 'flex';
        
        if (isVisible) {
            // Animate hiding
            filterOptionsContainer.style.opacity = '0';
            setTimeout(() => {
                filterOptionsContainer.style.display = 'none';
                filterOptionsContainer.style.opacity = '1';
            }, 300);
        } else {
            // Animate showing
            filterOptionsContainer.style.opacity = '0';
            filterOptionsContainer.style.display = 'flex';
            setTimeout(() => {
                filterOptionsContainer.style.opacity = '1';
            }, 10);
        }
    });
    
    // Filter option selection
    filterOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update UI
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Apply filter to video
            cameraView.className = '';
            cameraView.classList.add(`filter-${filter}`);
            
            activeFilter = filter;
        });
    });
    
    // Show flash animation
    function showFlashAnimation() {
        flashAnimation.style.opacity = '1';
        setTimeout(() => {
            flashAnimation.style.opacity = '0';
        }, 50);
    }
    
    // Start timer and take photo when timer completes
    function startTimer() {
        if (timerValue <= 0) {
            takePhoto();
            return;
        }
        
        let timeLeft = timerValue;
        timerCountdown.textContent = timeLeft;
        timerCountdown.style.display = 'flex';
        
        timerInterval = setInterval(() => {
            timeLeft--;
            timerCountdown.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerInterval = null;
                timerCountdown.style.display = 'none';
                takePhoto();
            }
        }, 1000);
    }
    
    // Switch camera
    switchCamera.addEventListener('click', function() {
        cameraFacingMode = cameraFacingMode === 'user' ? 'environment' : 'user';
        openCamera(cameraFacingMode);
    });
    
    // Close camera
    closeCamera.addEventListener('click', function() {
        stopCamera();
    });
    
    // Capture photo button
    capturePhoto.addEventListener('click', function() {
        startTimer();
    });
    
    // Gallery input change
    galleryInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            // Process the selected image
            stopCamera();
            processAttendance();
        }
    });
    
    // Main attendance button (starts the process)
    attendanceBtn.addEventListener('click', function() {
        actionType = isClockIn ? 'clock-in' : 'clock-out';
        
        // Update action text and styles
        if (actionText) {
            actionText.textContent = isClockIn ? 'CLOCK IN' : 'CLOCK OUT';
            actionText.className = 'action-text';
            actionText.classList.add(isClockIn ? 'clock-in-text' : 'clock-out-text');
        }
        
        if (clockStatus) {
            clockStatus.textContent = isClockIn ? 'Clock In' : 'Clock Out';
            clockStatus.className = 'clock-status';
            if (!isClockIn) clockStatus.classList.add('clock-out-status');
        }
        
        if (accentLine) {
            accentLine.className = 'accent-line';
            if (!isClockIn) accentLine.classList.add('clock-out');
        }
        
        // Update time in info sidebar
        const now = new Date();
        clockTime.textContent = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        
        dateDisplay.textContent = now.toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        
        // Try to get user info from the window object if available
        if (window.userInfo) {
            userName.textContent = `Name: ${window.userInfo.name || 'Unknown'}`;
            userCompany.textContent = `Company: ${window.userInfo.company || 'Unknown'}`;
            userPosition.textContent = `Position: ${window.userInfo.position || 'Unknown'}`;
        }
        
        // Update location display with current location
        if (currentLocation) {
            locationDisplay.textContent = currentLocation.textContent;
        }
        
        // Check if camera is available and initialize
        if (cameraAvailable) {
            openCamera(cameraFacingMode);
        } else {
            // Fallback if no camera
            processAttendance();
        }
    });
    
    // Process attendance (after photo is taken or if no camera)
    function processAttendance() {
        // Get current time
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        
        // Get location text (or fallback)
        const locationText = currentLocation.textContent || 'Location unavailable';
        
        // Update UI based on action type (clock in or out)
        if (isClockIn) {
            // Update the button to allow clocking out next
            attendanceBtn.innerHTML = '<i class="fas fa-sign-out-alt me-2"></i>Clock Out';
            attendanceBtn.classList.remove('btn-primary');
            attendanceBtn.classList.add('btn-danger');
            
            // Update last action
            lastAction.textContent = `Last action: Clocked in at ${timeString}`;
            
            // Add to activity log
            updateActivityLog(timeString, locationText, '', '', 'Working');
            
        } else {
            // Update the button to allow clocking in again
            attendanceBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Clock In';
            attendanceBtn.classList.remove('btn-danger');
            attendanceBtn.classList.add('btn-primary');
            
            // Update last action
            lastAction.textContent = `Last action: Clocked out at ${timeString}`;
            
            // Update the activity log with clock out time
            updateActivityLog(null, null, timeString, locationText, 'Completed');
        }
        
        // Toggle state for next action
        isClockIn = !isClockIn;
        
        // Send data to server if needed (Ajax call)
        const attendanceData = {
            action: actionType,
            timestamp: now.toISOString(),
            location: locationText,
            coordinates: coordinates.textContent || ''
        };
        
        console.log('Attendance data recorded:', attendanceData);
        
        // Optional: Send data to server
        /*
        fetch('/api/attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(attendanceData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
        */
    }
    
    // Update activity log with new entry or update existing
    function updateActivityLog(clockInTime, clockInLocation, clockOutTime, clockOutLocation, status) {
        const activityLog = document.getElementById('activity-log');
        
        // Clear "no activity" message if it exists
        if (activityLog.querySelector('td[colspan="5"]')) {
            activityLog.innerHTML = '';
        }
        
        if (isClockIn) {
            // Create a new row for clock in
            const row = document.createElement('tr');
            row.id = 'current-activity';
            row.className = 'text-center';
            row.innerHTML = `
                <td class="activity-time">${clockInTime}</td>
                <td><div class="location-text">${clockInLocation}</div></td>
                <td>-</td>
                <td>-</td>
                <td><span class="badge bg-success status-badge">${status}</span></td>
            `;
            activityLog.appendChild(row);
        } else {
            // Update existing row with clock out information
            const row = document.getElementById('current-activity');
            if (row) {
                row.cells[2].textContent = clockOutTime;
                row.cells[3].innerHTML = `<div class="location-text">${clockOutLocation}</div>`;
                row.cells[4].innerHTML = `<span class="badge bg-info status-badge">${status}</span>`;
                row.removeAttribute('id');
            }
        }
    }
});
</script>
@endpush
@endsection
