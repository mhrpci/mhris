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
                    <div id="location-status" class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Requesting location access...
                        </div>
                    </div>
                    
                    <div class="location-info">
                        <div class="mb-3">
                            <label class="text-muted small">Current Address</label>
                            <p id="current-location" class="mb-0 fw-bold">Fetching location...</p>
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
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Today's Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Action</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="activity-log">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No activity recorded today</td>
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update time every second
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
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
    const lastAction = document.getElementById('last-action');
    let isClockIn = true;

    attendanceBtn.addEventListener('click', function() {
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
        updateActivityLog(isClockIn ? 'Clock Out' : 'Clock In');
    });

    // Activity Log Update
    function updateActivityLog(action) {
        const tbody = document.getElementById('activity-log');
        const now = new Date();
        const row = document.createElement('tr');
        
        if (tbody.firstElementChild.getElementsByTagName('td')[0].colSpan) {
            tbody.innerHTML = '';
        }

        row.innerHTML = `
            <td>${now.toLocaleTimeString()}</td>
            <td><span class="badge bg-${action === 'Clock In' ? 'success' : 'danger'}">${action}</span></td>
            <td id="log-location">Fetching location...</td>
            <td><span class="badge bg-success">Successful</span></td>
        `;
        tbody.insertBefore(row, tbody.firstChild);
    }

    // Location tracking
    const locationStatus = document.getElementById('location-status');
    const currentLocation = document.getElementById('current-location');
    const coordinatesInfo = document.getElementById('coordinates-info');
    const coordinates = document.getElementById('coordinates');

    if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition(
            function(position) {
                locationStatus.className = 'alert alert-success';
                locationStatus.innerHTML = '<i class="fas fa-check-circle me-2"></i>Location access granted';

                // Get address from coordinates using reverse geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        currentLocation.textContent = data.display_name;
                        coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                        coordinatesInfo.classList.remove('d-none');
                    })
                    .catch(error => {
                        currentLocation.textContent = 'Unable to fetch address';
                        coordinates.textContent = `${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                        coordinatesInfo.classList.remove('d-none');
                    });
            },
            function(error) {
                locationStatus.className = 'alert alert-danger';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location access denied. Please enable location services.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Location request timed out.';
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
        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Geolocation is not supported by your browser.';
    }
});
</script>
@endpush
@endsection
