@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Current Time and Attendance Actions in one card -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <!-- Date and Time -->
                    <div class="text-center mb-4">
                        <h4 id="currentDate" class="mb-2 text-muted">{{ date('l, F j, Y') }}</h4>
                        <div class="time-display py-3 rounded">
                            <h1 id="currentTime" class="display-4 font-weight-bold text-primary mb-0">00:00:00</h1>
                        </div>
                    </div>
                    
                    <!-- Location Display -->
                    <div class="location-display mb-4 rounded p-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-wrapper text-primary mr-2">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="text-muted small">Current Location</div>
                            <div class="ml-auto">
                                <button id="refreshLocation" class="btn btn-sm btn-link p-0">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div id="locationAddress" class="font-weight-medium">
                            <div class="location-placeholder d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span>Requesting location permission...</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Info -->
                    <div class="row align-items-center mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center p-3 rounded bg-light">
                                <div class="icon-wrapper text-primary mr-3">
                                    <i class="fas fa-sign-in-alt fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Clock In</div>
                                    <div class="font-weight-bold" id="clockInTime">--:--:--</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded bg-light">
                                <div class="icon-wrapper text-danger mr-3">
                                    <i class="fas fa-sign-out-alt fa-lg"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Clock Out</div>
                                    <div class="font-weight-bold" id="clockOutTime">--:--:--</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Status Alert - Simplified -->
                    <div class="alert alert-light border text-center mb-4" id="attendanceStatus">
                        <span class="status-indicator bg-info mr-2"></span>
                        You have not clocked in today
                    </div>

                    <!-- Clock In/Out Buttons - Cleaner design -->
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <button id="clockInBtn" class="btn btn-outline-primary btn-block py-3">
                                <i class="fas fa-sign-in-alt mr-2"></i>Clock In
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button id="clockOutBtn" class="btn btn-outline-danger btn-block py-3" disabled>
                                <i class="fas fa-sign-out-alt mr-2"></i>Clock Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Summary - Simplified -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0">
                    <h6 class="mb-0 text-muted">
                        <i class="fas fa-history mr-2"></i>Recent Activity
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Clock In</th>
                                    <th class="border-0">Clock Out</th>
                                    <th class="border-0 d-none d-md-table-cell">Hours</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('M d', strtotime('-1 day')) }}</td>
                                    <td>08:02</td>
                                    <td>17:01</td>
                                    <td class="d-none d-md-table-cell">8.98</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-2 day')) }}</td>
                                    <td>08:05</td>
                                    <td>17:15</td>
                                    <td class="d-none d-md-table-cell">9.17</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-3 day')) }}</td>
                                    <td>--:--</td>
                                    <td>--:--</td>
                                    <td class="d-none d-md-table-cell">0.00</td>
                                    <td><span class="badge badge-soft-danger">Absent</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-4 day')) }}</td>
                                    <td>08:10</td>
                                    <td>17:05</td>
                                    <td class="d-none d-md-table-cell">8.92</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                                <tr>
                                    <td>{{ date('M d', strtotime('-5 day')) }}</td>
                                    <td>08:00</td>
                                    <td>17:30</td>
                                    <td class="d-none d-md-table-cell">9.49</td>
                                    <td><span class="badge badge-soft-success">Present</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Camera Capture Component -->
@include('attendances.camera-capture')
@endsection

@push('styles')
<style>
    /* Minimalist styling */
    .time-display {
        background-color: #f9f9f9;
        border-radius: 8px;
    }
    
    #currentTime {
        font-size: 3rem;
        color: #333;
    }
    
    .icon-wrapper {
        width: 40px;
        text-align: center;
    }
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-outline-primary:hover, .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .badge-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        font-weight: 500;
    }
    
    .badge-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        font-weight: 500;
    }
    
    .table th {
        font-weight: 500;
        color: #6c757d;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }

    .location-display {
        border-radius: 8px;
    }

    .font-weight-medium {
        font-weight: 500;
    }

    #refreshLocation {
        color: #6c757d;
        transition: all 0.2s;
    }

    #refreshLocation:hover {
        color: #3b82f6;
        transform: rotate(90deg);
    }
    
    @media (max-width: 576px) {
        #currentTime {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Update current time every second
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            $('#currentTime').text(`${hours}:${minutes}:${seconds}`);
        }
        
        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
        
        // Location variables
        let userCoordinates = null;
        const locationIqApiKey = 'pk.e5dff6366eb119dd6b5fc023775923c9'; // Replace with your LocationIQ API key
        
        // Get user location
        function getUserLocation() {
            $('#locationAddress').html(`
                <div class="location-placeholder d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <span>Fetching your location...</span>
                </div>
            `);
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success callback
                    function(position) {
                        userCoordinates = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        getAddressFromCoordinates(userCoordinates);
                    },
                    // Error callback
                    function(error) {
                        let errorMessage = 'Unable to retrieve your location';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location permission denied. Please enable location services.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information is unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Location request timed out.';
                                break;
                        }
                        
                        $('#locationAddress').html(`
                            <div class="text-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i> ${errorMessage}
                            </div>
                        `);
                    },
                    // Options
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                $('#locationAddress').html(`
                    <div class="text-danger">
                        <i class="fas fa-exclamation-circle mr-1"></i> Geolocation is not supported by this browser.
                    </div>
                `);
            }
        }
        
        // Get address from coordinates using LocationIQ API
        function getAddressFromCoordinates(coordinates) {
            // Using reverse geocoding endpoint instead of the matching/driving endpoint
            const apiUrl = `https://us1.locationiq.com/v1/reverse.php?key=${locationIqApiKey}&lat=${coordinates.lat}&lon=${coordinates.lng}&format=json`;
            
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.display_name) {
                        const address = response.display_name;
                        $('#locationAddress').html(`
                            <div>
                                <i class="fas fa-map-marker-alt text-primary mr-1"></i> ${address}
                            </div>
                        `);
                        
                        // Store location for clock in/out
                        window.currentLocationAddress = address;
                    } else {
                        $('#locationAddress').html(`
                            <div class="text-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Could not determine exact address.
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    $('#locationAddress').html(`
                        <div class="text-danger">
                            <i class="fas fa-exclamation-circle mr-1"></i> Error fetching location: ${error}
                        </div>
                    `);
                    console.error('LocationIQ API Error:', error);
                }
            });
        }
        
        // Initialize location detection
        getUserLocation();
        
        // Allow manual refresh of location
        $('#refreshLocation').click(function() {
            getUserLocation();
            $(this).addClass('fa-spin');
            setTimeout(() => {
                $(this).removeClass('fa-spin');
            }, 1000);
        });
        
        // Clock In button - Now opens camera
        $('#clockInBtn').click(function() {
            // Check if we have location first
            if (!userCoordinates) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Location Required',
                    text: 'Please allow location access to clock in',
                    confirmButtonText: 'Try Again',
                    customClass: {
                        popup: 'swal-minimalist'
                    }
                }).then(() => {
                    getUserLocation();
                });
                return;
            }
            
            // Open camera for clock in
            window.openCameraForAction('clock-in');
        });
        
        // Clock Out button - Now opens camera
        $('#clockOutBtn').click(function() {
            // Check if we have location first
            if (!userCoordinates) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Location Required',
                    text: 'Please allow location access to clock out',
                    confirmButtonText: 'Try Again',
                    customClass: {
                        popup: 'swal-minimalist'
                    }
                }).then(() => {
                    getUserLocation();
                });
                return;
            }
            
            // Open camera for clock out
            window.openCameraForAction('clock-out');
        });
        
        // Handler for completing clock in (called from camera component)
        window.completeClockIn = function(timeString, photoData) {
            $('#clockInTime').text(timeString);
            $('#attendanceStatus').removeClass('alert-light').addClass('alert-success');
            $('#attendanceStatus').html('<span class="status-indicator bg-success mr-2"></span>You are currently clocked in');
            
            // Enable clock out button and disable clock in
            $('#clockInBtn').prop('disabled', true);
            $('#clockOutBtn').prop('disabled', false);
            
            // Show success message with photo and location
            Swal.fire({
                icon: 'success',
                title: 'Clocked In',
                html: `
                    <div class="text-center mb-3">
                        <img src="${photoData}" alt="Verification Photo" class="img-fluid rounded" style="max-height: 120px;">
                    </div>
                    <div class="text-left">
                        <p class="mb-1"><strong>Time:</strong> ${timeString}</p>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-map-marker-alt mr-1"></i> 
                            ${window.currentLocationAddress || 'Location recorded'}
                        </p>
                    </div>
                `,
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'swal-minimalist'
                }
            });
            
            // Here you would typically send the data to the server
            // sendAttendanceData('clock-in', timeString, photoData, userCoordinates);
        };
        
        // Handler for completing clock out (called from camera component)
        window.completeClockOut = function(timeString, photoData) {
            $('#clockOutTime').text(timeString);
            $('#attendanceStatus').removeClass('alert-success').addClass('alert-light');
            $('#attendanceStatus').html('<span class="status-indicator bg-secondary mr-2"></span>You have completed your shift today');
            
            // Disable clock out button and enable clock in
            $('#clockOutBtn').prop('disabled', true);
            $('#clockInBtn').prop('disabled', true);
            
            // Show success message with photo and location
            Swal.fire({
                icon: 'success',
                title: 'Clocked Out',
                html: `
                    <div class="text-center mb-3">
                        <img src="${photoData}" alt="Verification Photo" class="img-fluid rounded" style="max-height: 120px;">
                    </div>
                    <div class="text-left">
                        <p class="mb-1"><strong>Time:</strong> ${timeString}</p>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-map-marker-alt mr-1"></i> 
                            ${window.currentLocationAddress || 'Location recorded'}
                        </p>
                    </div>
                `,
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'swal-minimalist'
                }
            });
            
            // Here you would typically send the data to the server
            // sendAttendanceData('clock-out', timeString, photoData, userCoordinates);
        };
        
        // Function to send attendance data to server (placeholder)
        function sendAttendanceData(action, time, photo, coordinates) {
            // This function would handle sending the data to your backend
            console.log('Sending attendance data:', {
                action: action,
                time: time,
                photo: photo ? 'Photo captured' : 'No photo',
                coordinates: coordinates
            });
            
            // Example AJAX call (commented out)
            /*
            $.ajax({
                url: '/api/attendance',
                method: 'POST',
                data: {
                    action: action,
                    time: time,
                    photo: photo,
                    latitude: coordinates.lat,
                    longitude: coordinates.lng,
                    address: window.currentLocationAddress
                },
                success: function(response) {
                    console.log('Attendance recorded successfully', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error recording attendance:', error);
                    // Handle error
                }
            });
            */
        }
        
        // Add custom style for SweetAlert
        $('<style>.swal-minimalist{width:360px !important; padding:1.5rem !important;}</style>').appendTo('head');
    });
</script>
@endpush


