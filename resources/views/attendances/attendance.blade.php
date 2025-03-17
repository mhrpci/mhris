@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">MHR Employee Attendance</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div id="clock" class="display-4 mb-2">--:--:-- --</div>
                        <div id="date" class="h5 text-muted mb-3">Loading...</div>
                        <div id="location" class="text-muted small mb-4">
                            <i class="fas fa-map-marker-alt"></i> 
                            <span id="address">Fetching location...</span>
                        </div>
                        
                        <div class="d-grid gap-3 col-md-8 mx-auto">
                            <button id="clockButton" class="btn btn-lg">
                                <i class="fas"></i> <span>Loading...</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="alert alert-info text-center small" role="alert">
                        <i class="fas fa-info-circle"></i> 
                        Your attendance is being recorded with secure timestamp verification
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        padding: 1.5rem;
    }
    
    #clock {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-clock-in {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        border: none;
        color: white;
    }
    
    .btn-clock-out {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border: none;
        color: white;
    }
    
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
        
        .h5 {
            font-size: 1.1rem;
        }
    }

    .disabled {
        opacity: 0.65;
        pointer-events: none;
    }

    /* Button state transition animation */
    #clockButton {
        transition: all 0.3s ease-in-out;
    }

    #clockButton i {
        margin-right: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let serverTimestamp = '';
    let serverHash = '';
    let userLocation = null;
    let lastVerifiedTime = null;
    let isClockIn = true; // Track button state
    
    // Function to update button state
    function updateButtonState() {
        const button = document.getElementById('clockButton');
        const icon = button.querySelector('i');
        const text = button.querySelector('span');
        
        if (isClockIn) {
            button.className = 'btn btn-lg btn-clock-in';
            icon.className = 'fas fa-sign-in-alt';
            text.textContent = 'Clock In';
        } else {
            button.className = 'btn btn-lg btn-clock-out';
            icon.className = 'fas fa-sign-out-alt';
            text.textContent = 'Clock Out';
        }
    }
    
    // Function to get server time
    async function getServerTime() {
        try {
            const response = await fetch('/api/server-time');
            if (!response.ok) throw new Error('Server time fetch failed');
            
            const data = await response.json();
            serverTimestamp = data.timestamp;
            serverHash = data.hash;
            
            // Verify timestamp before updating display
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
            updateTimeDisplay(data.formatted);
        } catch (error) {
            console.error('Error with server time:', error);
            showError('Unable to sync with server time. Please refresh the page.');
            disableAttendanceButton();
        }
    }
    
    // Function to update time display
    function updateTimeDisplay(formatted) {
        if (!formatted || !formatted.full) return;
        
        const date = new Date(formatted.full);
        const timeString = date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true,
            timeZone: 'Asia/Manila'
        });
        document.getElementById('clock').textContent = timeString;
        
        const dateString = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timeZone: 'Asia/Manila'
        });
        document.getElementById('date').textContent = dateString;
    }
    
    // Function to disable attendance button
    function disableAttendanceButton() {
        document.getElementById('clockButton').classList.add('disabled');
    }
    
    // Function to show error message
    function showError(message) {
        const alertDiv = document.querySelector('.alert');
        alertDiv.classList.remove('alert-info');
        alertDiv.classList.add('alert-danger');
        alertDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
    }
    
    // Function to get user's location
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
                document.getElementById('address').textContent = data.display_name;
            } catch (error) {
                document.getElementById('address').textContent = 'Location access denied';
                console.error('Error getting location:', error);
                disableAttendanceButton();
            }
        } else {
            document.getElementById('address').textContent = 'Geolocation not supported';
            disableAttendanceButton();
        }
    }
    
    // Clock In/Out functionality
    async function handleAttendance() {
        if (!userLocation) {
            alert('Please enable location access to clock in/out');
            return;
        }
        
        if (!serverTimestamp || !serverHash) {
            alert('Server time synchronization required. Please wait.');
            return;
        }
        
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
            alert(`Clock ${action} successful!`);
        } catch (error) {
            console.error('Error recording attendance:', error);
            showError('Failed to record attendance. Please try again.');
        }
    }
    
    // Event listener for the clock button
    document.getElementById('clockButton').addEventListener('click', handleAttendance);
    
    // Initialize
    getServerTime();
    getUserLocation();
    updateButtonState(); // Set initial button state
    
    // Update server time more frequently (every 30 seconds) for better accuracy
    setInterval(getServerTime, 30000);
});
</script>
@endpush

@endsection