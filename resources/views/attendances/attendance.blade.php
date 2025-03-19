@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center g-4">
        <!-- Time Tracking Card -->
        <div class="col-xl-7 col-lg-8 col-md-12">
            <div class="card border-0 shadow-sm hover-shadow-lg rounded-4 overflow-hidden transition-all">
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-clock me-2"></i>Time Tracking Dashboard</h5>
                        <div class="badge bg-white text-primary fs-6 px-3 py-2 rounded-pill">{{ now()->format('l') }}</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Current Date and Time with enhanced styling -->
                    <div class="text-center mb-4 py-3 bg-light rounded-4 shadow-sm">
                        <p class="text-uppercase text-primary mb-0 small fw-bold">Current Time</p>
                        <h2 id="current-time" class="display-5 fw-bold mb-0 time-animate">{{ now()->format('H:i:s') }}</h2>
                        <p id="current-date" class="text-muted mb-0 mt-1">{{ now()->format('F d, Y') }}</p>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-7">
                            <!-- Location Display with improved UI -->
                            <div class="mb-4">
                                <label for="location" class="form-label text-primary fw-semibold mb-2 d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill me-2"></i> Current Location
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 text-primary">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 bg-white shadow-none" id="location" 
                                        value="Headquarters - Floor 5, East Wing" readonly>
                                </div>
                                <div class="d-flex align-items-center mt-2">
                                    <small class="text-success" id="locationStatus"><i class="bi bi-check-circle-fill me-1"></i> Location verified</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <!-- Status Card -->
                            <div class="card h-100 bg-light border-0 rounded-4 shadow-sm">
                                <div class="card-body p-3">
                                    <h6 class="text-uppercase text-primary mb-3 small fw-bold d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2"></i>Today's Status
                                    </h6>
                                    <div class="d-flex justify-content-between mb-2 status-item">
                                        <span class="text-muted"><i class="bi bi-box-arrow-in-right me-1"></i>Clock In:</span>
                                        <span id="clockInTime" class="fw-semibold badge bg-light text-dark">--:--</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 status-item">
                                        <span class="text-muted"><i class="bi bi-box-arrow-right me-1"></i>Clock Out:</span>
                                        <span id="clockOutTime" class="fw-semibold badge bg-light text-dark">--:--</span>
                                    </div>
                                    <div class="d-flex justify-content-between status-item">
                                        <span class="text-muted"><i class="bi bi-hourglass-split me-1"></i>Duration:</span>
                                        <span id="duration" class="fw-semibold badge bg-light text-dark">--:--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Clock In/Out Button with enhanced animation -->
                    <div class="mt-4">
                        <button id="clockBtn" class="btn btn-primary btn-lg w-100 py-3 shadow-sm position-relative overflow-hidden transition-all rounded-pill" type="button">
                            <div class="d-flex justify-content-center align-items-center">
                                <span id="clockBtnSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                                <i id="clockBtnIcon" class="bi bi-box-arrow-in-right fs-4 me-2"></i>
                                <span id="clockBtnText" class="fw-bold">Clock In</span>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted" id="lastActivity"><i class="bi bi-activity me-1"></i>Last activity: Not available</small>
                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">Web Check-in</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Enhanced gradients and colors */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    /* Enhanced border radius */
    .rounded-4 {
        border-radius: 0.75rem;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
    
    /* Enhanced transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    /* Hover effects */
    #clockBtn:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .hover-shadow-lg:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Status item styling */
    .status-item {
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .status-item:last-child {
        border-bottom: none;
    }
    
    /* Time animation */
    .time-animate {
        animation: pulse 1s infinite alternate;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        100% { opacity: 0.8; }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2.3rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
    }
    
    @media (max-width: 576px) {
        .display-5 {
            font-size: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize components when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Update the time every second
        setInterval(updateTime, 1000);
        
        // Initialize button click handler
        initializeClockButton();
        
        // Add smooth fade-in animation to cards
        document.querySelectorAll('.card').forEach(card => {
            card.classList.add('animate__animated', 'animate__fadeIn');
        });
    });
    
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        const dateElement = document.getElementById('current-date');
        
        // Format time with leading zeros
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        timeElement.textContent = `${hours}:${minutes}:${seconds}`;
        
        // Format date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.textContent = now.toLocaleDateString(undefined, options);
    }
    
    // Simulate getting location
    function simulateLocationDetection() {
        // Function kept for backward compatibility but no longer used
    }
    
    // Initialize clock button functionality
    function initializeClockButton() {
        const clockBtn = document.getElementById('clockBtn');
        const clockBtnText = document.getElementById('clockBtnText');
        const clockBtnIcon = document.getElementById('clockBtnIcon');
        const clockBtnSpinner = document.getElementById('clockBtnSpinner');
        
        clockBtn.addEventListener('click', function() {
            // Show spinner and disable button during processing
            clockBtnSpinner.classList.remove('d-none');
            clockBtnIcon.classList.add('d-none');
            clockBtn.disabled = true;
            
            // Redirect to camera page
            window.location.href = "{{ route('attendance.camera') }}";
        });
    }
    
    // Duration timer variables
    let durationInterval;
    let durationSeconds = 0;
    
    // Start tracking duration
    function startDurationTimer() {
        durationSeconds = 0;
        updateDurationDisplay();
        
        durationInterval = setInterval(() => {
            durationSeconds++;
            updateDurationDisplay();
        }, 1000);
    }
    
    // Stop tracking duration
    function stopDurationTimer() {
        clearInterval(durationInterval);
    }
    
    // Update duration display
    function updateDurationDisplay() {
        const hours = Math.floor(durationSeconds / 3600);
        const minutes = Math.floor((durationSeconds % 3600) / 60);
        const seconds = durationSeconds % 60;
        
        const formattedTime = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
            
        document.getElementById('duration').textContent = formattedTime;
        
        // Change duration badge color based on time worked
        const durationBadge = document.getElementById('duration');
        if (hours >= 8) {
            durationBadge.classList.remove('bg-light', 'text-dark', 'bg-warning', 'text-dark');
            durationBadge.classList.add('bg-success', 'text-white');
        } else if (hours >= 6) {
            durationBadge.classList.remove('bg-light', 'text-dark', 'bg-success', 'text-white');
            durationBadge.classList.add('bg-warning', 'text-dark');
        }
    }
</script>
@endpush
@endsection