@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 attendance-card">
                <div class="card-header position-relative py-4">
                    <div class="header-background"></div>
                    <div class="position-relative">
                        <h2 class="mb-0 text-center text-white">
                            <i class="fas fa-building me-2"></i>
                            MHR Employee Attendance
                        </h2>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="text-center attendance-content">
                        <div class="time-display mb-4">
                            <div id="clock" class="clock-text mb-2">--:--:-- --</div>
                            <div id="date" class="date-text mb-3">Loading...</div>
                        </div>
                        
                        <div class="location-info mb-4">
                            <div class="location-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div id="address" class="location-text">Fetching location...</div>
                        </div>
                        
                        <div class="d-grid col-lg-7 col-md-9 mx-auto">
                            <button id="clockButton" class="btn btn-lg attendance-btn">
                                <div class="btn-content">
                                    <i class="fas"></i>
                                    <span class="ms-2">Loading...</span>
                                </div>
                                <div class="btn-ripple"></div>
                            </button>
                        </div>
                    </div>
                    
                    <div class="status-container mt-4">
                        <div class="alert alert-info text-center status-message" role="alert">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Attendance is being recorded with secure verification</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Card Styles */
    .attendance-card {
        border-radius: 20px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: transparent;
        border: none;
        overflow: hidden;
    }

    .header-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        z-index: 0;
    }

    /* Clock Display */
    .time-display {
        padding: 2rem;
        background: linear-gradient(to bottom, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .clock-text {
        font-size: 3.5rem;
        font-weight: 700;
        color: #2c3e50;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .date-text {
        font-size: 1.25rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Location Info */
    .location-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .location-icon {
        color: #4e73df;
        font-size: 1.2rem;
    }

    .location-text {
        color: #495057;
        font-size: 0.95rem;
    }

    /* Button Styles */
    .attendance-btn {
        position: relative;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-clock-in {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        color: white;
        border: none;
    }

    .btn-clock-out {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: none;
    }

    .attendance-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .attendance-btn:active {
        transform: translateY(1px);
    }

    .btn-ripple {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        transform: scale(0);
        transition: transform 0.6s;
    }

    .attendance-btn:hover .btn-ripple {
        transform: scale(2);
    }

    /* Status Message */
    .status-message {
        border: none;
        background: #e8f4fd;
        color: #0d6efd;
        border-radius: 10px;
        padding: 1rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .status-message.error {
        background: #fee7e7;
        color: #dc3545;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .clock-text {
            font-size: 2.5rem;
        }
        
        .date-text {
            font-size: 1.1rem;
        }
        
        .attendance-btn {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    /* Loading Animation */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .loading {
        animation: pulse 1.5s infinite;
    }

    /* Disabled State */
    .disabled {
        opacity: 0.65;
        pointer-events: none;
        filter: grayscale(30%);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let serverTimestamp = '';
    let serverHash = '';
    let userLocation = null;
    let lastVerifiedTime = null;
    let isClockIn = true;
    let clientTimeOffset = 0;
    
    // Function to update button state
    function updateButtonState() {
        const button = document.getElementById('clockButton');
        const icon = button.querySelector('i');
        const text = button.querySelector('span');
        
        if (isClockIn) {
            button.className = 'btn btn-lg attendance-btn btn-clock-in';
            icon.className = 'fas fa-sign-in-alt';
            text.textContent = 'Clock In';
        } else {
            button.className = 'btn btn-lg attendance-btn btn-clock-out';
            icon.className = 'fas fa-sign-out-alt';
            text.textContent = 'Clock Out';
        }
    }
    
    // Function to get server time and calculate offset
    async function getServerTime() {
        try {
            const response = await fetch('/api/server-time');
            if (!response.ok) throw new Error('Server time fetch failed');
            
            const data = await response.json();
            serverTimestamp = data.timestamp;
            serverHash = data.hash;
            
            // Calculate client-server time offset
            const serverTime = new Date(data.timestamp).getTime();
            const clientTime = new Date().getTime();
            clientTimeOffset = serverTime - clientTime;
            
            // Verify timestamp
            const verifyResponse = await fetch(`/api/verify-timestamp/${encodeURIComponent(serverTimestamp)}/${encodeURIComponent(serverHash)}`);
            if (!verifyResponse.ok) throw new Error('Timestamp verification failed');
            
            const verifyData = await verifyResponse.json();
            if (!verifyData.valid) {
                console.error('Invalid timestamp detected');
                disableAttendanceButton();
                showError('Time verification failed. Please refresh the page.');
                return;
            }
            
            lastVerifiedTime = data.formatted;
            updateTimeDisplay();
            startLocalTimeUpdate();
        } catch (error) {
            console.error('Error with server time:', error);
            showError('Unable to sync with server time. Please refresh the page.');
            disableAttendanceButton();
        }
    }
    
    // Function to start local time updates
    function startLocalTimeUpdate() {
        // Update every second
        setInterval(() => {
            const now = new Date(Date.now() + clientTimeOffset);
            updateTimeDisplay(now);
        }, 1000);
    }
    
    // Function to update time display
    function updateTimeDisplay(date = new Date()) {
        const timeString = date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true,
            timeZone: 'Asia/Manila'
        });
        
        const dateString = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'Asia/Manila'
        });
        
        const clockElement = document.getElementById('clock');
        const dateElement = document.getElementById('date');
        
        // Smooth update animation
        clockElement.style.opacity = '0';
        setTimeout(() => {
            clockElement.textContent = timeString;
            clockElement.style.opacity = '1';
        }, 100);
        
        dateElement.textContent = dateString;
    }
    
    // Function to disable attendance button
    function disableAttendanceButton() {
        const button = document.getElementById('clockButton');
        button.classList.add('disabled');
        showError('System unavailable. Please try again later.');
    }
    
    // Function to show error message
    function showError(message) {
        const statusMessage = document.querySelector('.status-message');
        statusMessage.classList.remove('alert-info');
        statusMessage.classList.add('alert-danger', 'error');
        statusMessage.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    }
    
    // Function to show success message
    function showSuccess(message) {
        const statusMessage = document.querySelector('.status-message');
        statusMessage.classList.remove('alert-info', 'alert-danger', 'error');
        statusMessage.classList.add('alert-success');
        statusMessage.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
        
        // Reset to info message after 5 seconds
        setTimeout(() => {
            statusMessage.classList.remove('alert-success');
            statusMessage.classList.add('alert-info');
            statusMessage.innerHTML = `<i class="fas fa-shield-alt me-2"></i>Attendance is being recorded with secure verification`;
        }, 5000);
    }
    
    // Enhanced location handling
    async function getUserLocation() {
        if ("geolocation" in navigator) {
            try {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    });
                });
                
                userLocation = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy
                };
                
                // Get address using reverse geocoding
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLocation.latitude}&lon=${userLocation.longitude}`);
                const data = await response.json();
                
                const addressElement = document.getElementById('address');
                addressElement.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i></span>${data.display_name}`;
            } catch (error) {
                document.getElementById('address').innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Location access denied</span>`;
                console.error('Error getting location:', error);
                disableAttendanceButton();
            }
        } else {
            document.getElementById('address').innerHTML = `<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Geolocation not supported</span>`;
            disableAttendanceButton();
        }
    }
    
    // Enhanced attendance handling
    async function handleAttendance() {
        if (!userLocation) {
            showError('Please enable location access to clock in/out');
            return;
        }
        
        if (!serverTimestamp || !serverHash) {
            showError('Server time synchronization required. Please wait.');
            return;
        }
        
        const button = document.getElementById('clockButton');
        button.classList.add('disabled');
        
        try {
            // Verify timestamp again before submitting
            const verifyResponse = await fetch(`/api/verify-timestamp/${encodeURIComponent(serverTimestamp)}/${encodeURIComponent(serverHash)}`);
            const verifyData = await verifyResponse.json();
            
            if (!verifyData.valid) {
                showError('Time verification failed. Please try again.');
                return;
            }
            
            const type = isClockIn ? 'in' : 'out';
            const attendanceData = {
                type: type,
                timestamp: serverTimestamp,
                hash: serverHash,
                location: userLocation,
                timezone: 'Asia/Manila'
            };
            
            // Send attendance data to server
            const response = await fetch('/api/attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(attendanceData)
            });
            
            if (!response.ok) throw new Error('Attendance submission failed');
            
            const result = await response.json();
            
            // Toggle button state on successful submission
            isClockIn = !isClockIn;
            updateButtonState();
            
            // Show success message
            const action = type.charAt(0).toUpperCase() + type.slice(1);
            showSuccess(`Clock ${action} recorded successfully!`);
        } catch (error) {
            console.error('Error recording attendance:', error);
            showError('Failed to record attendance. Please try again.');
        } finally {
            button.classList.remove('disabled');
        }
    }
    
    // Event listener for the clock button
    document.getElementById('clockButton').addEventListener('click', handleAttendance);
    
    // Initialize
    getServerTime();
    getUserLocation();
    updateButtonState();
    
    // Periodic server sync (every 5 minutes)
    setInterval(getServerTime, 300000);
});
</script>
@endpush

@endsection