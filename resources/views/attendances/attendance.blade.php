@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Attendance System</h4>
                </div>
                <div class="card-body">
                    <!-- Real-time Clock -->
                    <div class="text-center mb-4">
                        <h2 id="current-time" class="display-4 mb-2">00:00:00</h2>
                        <p id="current-date" class="h5 text-muted"></p>
                    </div>

                    <!-- Clock In/Out Button -->
                    <div class="text-center mb-4">
                        <button id="attendance-btn" class="btn btn-lg btn-primary px-5">
                            Clock In
                        </button>
                    </div>

                    <!-- Location Information -->
                    <div class="location-info p-3 bg-light rounded">
                        <h5 class="mb-3">
                            <i class="fas fa-map-marker-alt text-danger"></i> 
                            Current Location
                        </h5>
                        <div id="location-status" class="alert alert-info">
                            Requesting location access...
                        </div>
                        <p id="current-location" class="mb-0">Fetching location...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .location-info {
        border: 1px solid #dee2e6;
    }
    #attendance-btn {
        transition: all 0.3s ease;
    }
    #attendance-btn:hover {
        transform: scale(1.05);
    }
    #current-time {
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update time every second
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
        document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // Attendance button functionality
    const attendanceBtn = document.getElementById('attendance-btn');
    let isClockIn = true;

    attendanceBtn.addEventListener('click', function() {
        if (isClockIn) {
            attendanceBtn.textContent = 'Clock Out';
            attendanceBtn.classList.remove('btn-primary');
            attendanceBtn.classList.add('btn-danger');
        } else {
            attendanceBtn.textContent = 'Clock In';
            attendanceBtn.classList.remove('btn-danger');
            attendanceBtn.classList.add('btn-primary');
        }
        isClockIn = !isClockIn;
        
        // Here you can add AJAX call to your backend to record the attendance
    });

    // Location tracking
    const locationStatus = document.getElementById('location-status');
    const currentLocation = document.getElementById('current-location');

    if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition(
            function(position) {
                locationStatus.className = 'alert alert-success';
                locationStatus.textContent = 'Location access granted';

                // Get address from coordinates using reverse geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        currentLocation.innerHTML = `
                            <strong>Address:</strong> ${data.display_name}<br>
                            <strong>Coordinates:</strong> ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}
                        `;
                    })
                    .catch(error => {
                        currentLocation.textContent = `Latitude: ${position.coords.latitude.toFixed(6)}, Longitude: ${position.coords.longitude.toFixed(6)}`;
                    });
            },
            function(error) {
                locationStatus.className = 'alert alert-danger';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        locationStatus.textContent = "Location access denied. Please enable location services.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        locationStatus.textContent = "Location information unavailable.";
                        break;
                    case error.TIMEOUT:
                        locationStatus.textContent = "Location request timed out.";
                        break;
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    } else {
        locationStatus.className = 'alert alert-danger';
        locationStatus.textContent = "Geolocation is not supported by your browser.";
    }
});
</script>
@endpush
@endsection
