<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    {{-- <a href="{{ url('/') }}" class="nav-link">Home</a> --}}
                </li>
                <!-- Add more nav items here -->
            </ul>

            <!-- Right navbar links --> 
            <ul class="navbar-nav ml-auto">
                <!-- Add the tour guide button before notifications -->
                 @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('VP Finance'))
                <li class="nav-item">
                    <button id="startTour" class="nav-link btn btn-link" data-tooltip="Start App Tour">
                        <i class="fas fa-route"></i>
                        <span class="d-none d-md-inline ml-1">Tour Guide</span>
                    </button>
                </li>
                @endif
                @if(auth()->user()->hasRole('HR Comben') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR Compliance') || auth()->user()->hasRole('VP Finance'))
                <!-- Search Icon and Popup -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="search-toggle" data-tooltip="Search">
                        <i class="fas fa-search"></i>
                    </a>
                    <div id="search-popup" class="search-popup" style="display: none;">
                        <div class="search-content">
                            <div class="search-header">
                                <h5 class="mb-0">Search</h5>
                                <button type="button" class="close" id="search-close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <input type="text" id="search-input" class="form-control" placeholder="Search...">
                        </div>
                    </div>
                </li>
                @endif

                <!-- Notifications Dropdown -->
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="Notifications" id="notifications-dropdown-toggle">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-danger navbar-badge notification-count">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notifications-dropdown">
                        <span class="dropdown-header"><span id="notification-header-count">0</span> Notifications</span>
                        <div class="dropdown-divider"></div>
                        
                        <!-- Notification Items Container -->
                        <div class="notifications-container">
                            <!-- Loading indicator -->
                            <div class="loading-notifications text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="text-muted mt-2">Loading notifications...</p>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item dropdown-actions">
                            <button class="btn btn-sm btn-outline-primary mark-all-read">
                                <i class="fas fa-check-double mr-1"></i> Mark All as Read
                            </button>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('notifications.all') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                @endauth

                <!-- Web Push Notification Permission Button -->
                @auth
                <li class="nav-item">
                    <button id="enableNotifications" class="nav-link btn btn-link" onclick="requestNotificationPermission()" style="display: none;" data-tooltip="Enable Notifications">
                        <i class="fas fa-bell"></i>
                    </button>
                </li>
                @endauth

                <!-- Toast Container for Notifications -->
                <div class="toast-container position-fixed bottom-0 left-0 p-3"></div>

                <style>
                    .search-popup {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        z-index: 9999;
                        display: flex;
                        justify-content: center;
                        align-items: flex-start;
                        padding-top: 100px;
                    }
                    
                    /* Notification Dropdown Styles */
                    .notifications-dropdown {
                        padding: 0;
                        width: 320px;
                        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                        border: none;
                        overflow: hidden;
                    }
                    
                    .dropdown-header {
                        background-color: #f8f9fa;
                        padding: 12px 15px;
                        color: #495057;
                        font-weight: 600;
                        border-bottom: 1px solid #eee;
                    }
                    
                    .notifications-container {
                        max-height: 350px;
                        overflow-y: auto;
                    }
                    
                    .notification-item {
                        padding: 12px 15px;
                        display: flex;
                        align-items: flex-start;
                        transition: background-color 0.3s;
                        position: relative;
                    }
                    
                    .notification-item.unread {
                        background-color: rgba(0, 123, 255, 0.05);
                    }
                    
                    .notification-item.unread:before {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 3px;
                        background-color: #007bff;
                    }
                    
                    .notification-item:hover {
                        background-color: #f8f9fa;
                    }
                    
                    .notification-icon {
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 12px;
                        flex-shrink: 0;
                    }
                    
                    .notification-icon i {
                        color: white;
                        font-size: 14px;
                    }
                    
                    .notification-content {
                        flex-grow: 1;
                        padding-right: 5px;
                    }
                    
                    .notification-text {
                        margin-bottom: 4px;
                        color: #333;
                        font-size: 14px;
                        line-height: 1.4;
                    }
                    
                    .notification-time {
                        color: #888;
                        font-size: 12px;
                        margin-bottom: 0;
                    }
                    
                    .notification-action {
                        margin-left: 5px;
                        margin-top: 5px;
                        flex-shrink: 0;
                    }
                    
                    .dropdown-actions {
                        padding: 8px 15px;
                        text-align: center;
                    }
                    
                    .dropdown-actions .btn {
                        width: 100%;
                    }
                    
                    .dropdown-footer {
                        padding: 10px 15px;
                        text-align: center;
                        background-color: #f8f9fa;
                        font-weight: 500;
                        color: #007bff;
                        border-top: 1px solid #eee;
                    }
                    
                    @media (max-width: 576px) {
                        .notifications-dropdown {
                            width: 290px;
                        }
                        
                        .notification-text {
                            font-size: 13px;
                        }
                    }
                    
                    .search-content {
                        width: 80%;
                        max-width: 600px;
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        animation: slideDown 0.3s ease-out;
                    }
                    .search-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 15px;
                    }
                    .search-header h5 {
                        color: #333;
                        font-weight: 600;
                    }
                    .search-header .close {
                        background: none;
                        border: none;
                        font-size: 24px;
                        color: #666;
                        cursor: pointer;
                        padding: 0;
                        line-height: 1;
                        transition: color 0.3s;
                    }
                    .search-header .close:hover {
                        color: #333;
                    }
                    @keyframes slideDown {
                        from {
                            transform: translateY(-20px);
                            opacity: 0;
                        }
                        to {
                            transform: translateY(0);
                            opacity: 1;
                        }
                    }
                    #search-input {
                        width: 100%;
                        padding: 12px 20px;
                        font-size: 16px;
                        border: 2px solid #ddd;
                        border-radius: 4px;
                        outline: none;
                        transition: border-color 0.3s;
                    }
                    #search-input:focus {
                        border-color: #007bff;
                    }
                </style>

                <script>
                    $(document).ready(function() {
                        // Show search popup
                        $('#search-toggle').click(function(e) {
                            e.preventDefault();
                            $('#search-popup').fadeIn(200);
                            $('#search-input').focus();
                        });

                        // Close search popup when clicking close button
                        $('#search-close').click(function() {
                            $('#search-popup').fadeOut(200);
                        });

                        // Close search popup when clicking outside
                        $(document).click(function(e) {
                            if (!$(e.target).closest('#search-popup, #search-toggle').length) {
                                $('#search-popup').fadeOut(200);
                            }
                        });

                        // Handle search input
                        $('#search-input').on('keyup', function(e) {
                            if (e.key === 'Enter') {
                                const searchTerm = $(this).val();
                                // Add your search logic here
                                console.log('Searching for:', searchTerm);
                                // You can redirect to a search results page or handle the search as needed
                            }
                        });

                        // Close on escape key
                        $(document).keyup(function(e) {
                            if (e.key === "Escape") {
                                $('#search-popup').fadeOut(200);
                            }
                        });
                        
                        // Initialize tooltips with custom configuration
                        $('[data-tooltip]').tooltip({
                            trigger: 'hover',
                            placement: 'bottom',
                            container: 'body',
                            boundary: 'window',
                            title: function() {
                                return $(this).data('tooltip');
                            },
                            delay: {
                                show: 200,
                                hide: 0
                            }
                        });
                        
                        // Notification dropdown functionality
                        $('.nav-link[data-tooltip="Notifications"]').on('click', function() {
                            // Here you can add code to fetch real-time notifications
                            console.log('Fetching notifications...');
                        });
                        
                        // Keep track of the latest notification timestamp
                        let lastNotificationTimestamp = 0;
                        
                        // Initial loading of notifications
                        loadNotifications();
                        
                        // Function to load notifications via AJAX
                        function loadNotifications() {
                            $.ajax({
                                url: '{{ route("notifications.get") }}',
                                type: 'GET',
                                dataType: 'json',
                                success: function(response) {
                                    // Update notification count
                                    updateNotificationCount(response.unread_count);
                                    
                                    // Update notification content
                                    renderNotifications(response.notifications);
                                    
                                    // Store the timestamp of the latest notification
                                    if (response.notifications && response.notifications.length > 0) {
                                        const timestamps = response.notifications.map(n => new Date(n.created_at).getTime() / 1000);
                                        lastNotificationTimestamp = Math.max(...timestamps);
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Error loading notifications:', xhr.responseText);
                                }
                            });
                        }
                        
                        // Function to update notification count badge
                        function updateNotificationCount(count) {
                            const $badge = $('.notification-count');
                            const $headerCount = $('.dropdown-header');
                            
                            if (count > 0) {
                                $badge.text(count).show();
                                $headerCount.text(count + ' Notifications');
                            } else {
                                $badge.hide();
                                $headerCount.text('No Notifications');
                            }
                        }
                        
                        // Function to render notifications in the dropdown
                        function renderNotifications(notifications) {
                            const $container = $('.notifications-container');
                            
                            // Clear the container
                            $container.empty();
                            
                            if (!notifications || notifications.length === 0) {
                                $container.html(`
                                    <div class="empty-notifications text-center p-3">
                                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No new notifications</p>
                                    </div>
                                `);
                                return;
                            }
                            
                            // Add each notification
                            notifications.forEach(function(notification, index) {
                                let iconClass = 'bg-primary';
                                let iconHtml = `<i class="${notification.icon}"></i>`;
                                let badgeHtml = '';
                                
                                // Set icon and badge based on notification type
                                if (notification.type.includes('leave')) {
                                    if (notification.details.status === 'Approved') {
                                        iconClass = 'bg-success';
                                        badgeHtml = '<span class="badge badge-success">Approved</span>';
                                    } else if (notification.details.status === 'Rejected') {
                                        iconClass = 'bg-danger';
                                        badgeHtml = '<span class="badge badge-danger">Rejected</span>';
                                    } else if (notification.type.includes('validated')) {
                                        iconClass = 'bg-info';
                                        badgeHtml = '<span class="badge badge-info">Validated</span>';
                                    } else {
                                        iconClass = 'bg-warning';
                                        badgeHtml = '<span class="badge badge-warning">Pending</span>';
                                    }
                                } else if (notification.type.includes('cash_advance')) {
                                    if (notification.details.status === 'Active') {
                                        iconClass = 'bg-success';
                                        badgeHtml = '<span class="badge badge-success">Approved</span>';
                                    } else if (notification.details.status === 'Declined') {
                                        iconClass = 'bg-danger';
                                        badgeHtml = '<span class="badge badge-danger">Declined</span>';
                                    } else {
                                        iconClass = 'bg-warning';
                                        badgeHtml = '<span class="badge badge-warning">Pending</span>';
                                    }
                                } else if (notification.type.includes('night_premium')) {
                                    if (notification.details.status === 'Approved') {
                                        iconClass = 'bg-success';
                                        badgeHtml = '<span class="badge badge-success">Approved</span>';
                                    } else if (notification.details.status === 'Rejected') {
                                        iconClass = 'bg-danger';
                                        badgeHtml = '<span class="badge badge-danger">Rejected</span>';
                                    } else {
                                        iconClass = 'bg-warning';
                                        badgeHtml = '<span class="badge badge-warning">Pending</span>';
                                    }
                                }
                                
                                const notificationHtml = `
                                    <a href="#" class="dropdown-item notification-item unread" data-id="${notification.id}" data-type="${notification.type}">
                                        <div class="notification-icon ${iconClass}">
                                            ${iconHtml}
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-text">${notification.message}</p>
                                            <p class="notification-time"><i class="far fa-clock mr-1"></i>${timeAgo(notification.created_at)}</p>
                                        </div>
                                        <div class="notification-action">
                                            ${badgeHtml}
                                        </div>
                                    </a>
                                `;
                                
                                $container.append(notificationHtml);
                                
                                // Add divider if not the last item
                                if (index < notifications.length - 1) {
                                    $container.append('<div class="dropdown-divider"></div>');
                                }
                            });
                        }
                        
                        // Convert ISO date to "time ago" format
                        function timeAgo(dateString) {
                            const date = new Date(dateString);
                            const now = new Date();
                            const diffInSeconds = Math.floor((now - date) / 1000);
                            
                            if (diffInSeconds < 60) {
                                return 'just now';
                            }
                            
                            const diffInMinutes = Math.floor(diffInSeconds / 60);
                            if (diffInMinutes < 60) {
                                return `${diffInMinutes} ${diffInMinutes === 1 ? 'minute' : 'minutes'} ago`;
                            }
                            
                            const diffInHours = Math.floor(diffInMinutes / 60);
                            if (diffInHours < 24) {
                                return `${diffInHours} ${diffInHours === 1 ? 'hour' : 'hours'} ago`;
                            }
                            
                            const diffInDays = Math.floor(diffInHours / 24);
                            if (diffInDays < 30) {
                                return `${diffInDays} ${diffInDays === 1 ? 'day' : 'days'} ago`;
                            }
                            
                            const diffInMonths = Math.floor(diffInDays / 30);
                            if (diffInMonths < 12) {
                                return `${diffInMonths} ${diffInMonths === 1 ? 'month' : 'months'} ago`;
                            }
                            
                            const diffInYears = Math.floor(diffInMonths / 12);
                            return `${diffInYears} ${diffInYears === 1 ? 'year' : 'years'} ago`;
                        }
                        
                        // Show toast notification
                        function showToast(type, title, message, icon, details = null) {
                            // Generate unique ID for the toast
                            const toastId = 'toast-' + Date.now();
                            
                            // Determine toast classes based on type
                            let bgClass = 'bg-primary';
                            if (type === 'success') bgClass = 'bg-success';
                            if (type === 'warning') bgClass = 'bg-warning';
                            if (type === 'danger') bgClass = 'bg-danger';
                            if (type === 'info') bgClass = 'bg-info';
                            
                            // Create the toast HTML
                            let toastHtml = `
                                <div id="${toastId}" class="toast ${bgClass} text-white" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                                    <div class="toast-header ${bgClass} text-white">
                                        <i class="${icon} mr-2"></i>
                                        <strong class="mr-auto">${title}</strong>
                                        <small>${timeAgo(new Date())}</small>
                                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="toast-body">
                                        ${message}
                            `;
                            
                            // Add details if provided
                            if (details) {
                                toastHtml += `
                                    <div class="mt-2 pt-2 border-top">
                                        <small>${details}</small>
                                    </div>
                                `;
                            }
                            
                            // Close the toast body and toast divs
                            toastHtml += `
                                    </div>
                                    <div class="toast-progress"></div>
                                </div>
                            `;
                            
                            // Append the toast to the container
                            $('.toast-container').append(toastHtml);
                            
                            // Show the toast
                            $(`#${toastId}`).toast('show');
                            
                            // Remove toast when hidden
                            $(`#${toastId}`).on('hidden.bs.toast', function() {
                                $(this).remove();
                            });
                        }
                        
                        // Click handler for marking a notification as read
                        $(document).on('click', '.notification-item', function(e) {
                            e.preventDefault();
                            
                            const notificationId = $(this).data('id');
                            const notificationType = $(this).data('type');
                            
                            // Make AJAX request to mark notification as read
                            $.ajax({
                                url: '{{ route("notifications.mark-read") }}',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    id: notificationId,
                                    type: notificationType,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Remove the unread class
                                        $(e.currentTarget).removeClass('unread');
                                        
                                        // Update the count
                                        loadNotifications();
                                    }
                                }
                            });
                            
                            // Handle navigation to the related page
                            let url = '#';
                            
                            if (notificationType.includes('leave')) {
                                const leaveId = notificationId.split('_').pop();
                                url = '{{ url("/leaves") }}/' + leaveId;
                            } else if (notificationType.includes('cash_advance')) {
                                const cashAdvanceId = notificationId.split('_').pop();
                                url = '{{ url("/cash_advances") }}/' + cashAdvanceId;
                            } else if (notificationType.includes('overtime')) {
                                // For overtime notifications, extract the ID
                                const overtimeId = notificationId.split('_').pop();
                                // Set route based on user role - we're using a JS variable populated by Blade
                                const isEmployee = "{{ Auth::user()->hasRole('Employee') ? 'true' : 'false' }}" === "true";
                                
                                if (isEmployee) {
                                    url = "{{ url('/overtime') }}/" + overtimeId;
                                } else {
                                    url = "{{ route('overtime.show', ['overtime' => ':id']) }}".replace(':id', overtimeId);
                                }
                            } else if (notificationType.includes('night_premium')) {
                                // For night premium notifications, extract the ID
                                const nightPremiumId = notificationId.split('_').pop();
                                // Set route based on user role
                                const isEmployee = "{{ Auth::user()->hasRole('Employee') ? 'true' : 'false' }}" === "true";
                                
                                if (isEmployee) {
                                    url = "{{ url('/night-premium') }}/" + nightPremiumId;
                                } else {
                                    url = "{{ route('night-premium.show', ['night_premium' => ':id']) }}".replace(':id', nightPremiumId);
                                }
                            }
                            
                            window.location.href = url;
                        });
                        
                        // Mark all as read functionality
                        $('.mark-all-read').click(function(e) {
                            e.preventDefault();
                            e.stopPropagation(); // Prevent dropdown from closing
                            
                            $.ajax({
                                url: '{{ route("notifications.mark-all-read") }}',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Update UI
                                        $('.notification-count').hide();
                                        $('.notification-item').removeClass('unread');
                                        
                                        // Show success toast
                                        showToast(
                                            'success',
                                            'Success',
                                            'All notifications marked as read',
                                            'fas fa-check-circle'
                                        );
                                        
                                        // Reload notifications
                                        loadNotifications();
                                    }
                                },
                                error: function(xhr) {
                                    showToast(
                                        'danger',
                                        'Error',
                                        'Failed to mark notifications as read',
                                        'fas fa-exclamation-circle'
                                    );
                                }
                            });
                        });
                        
                        // Polling for new notifications every 15 seconds
                        setInterval(function() {
                            // Check if the document has focus to reduce unnecessary requests when tab is inactive
                            if (document.hasFocus()) {
                                $.ajax({
                                    url: '{{ route("notifications.check-updates") }}',
                                    type: 'GET',
                                    data: {
                                        timestamp: lastNotificationTimestamp
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.hasUpdates) {
                                            // Update the notification count
                                            updateNotificationCount(response.count);
                                            
                                            // If there are new notifications, show toast for each (max 3)
                                            if (response.notifications && response.notifications.length > 0) {
                                                // Limit to showing 3 notifications max to avoid toast spam
                                                const notificationsToShow = response.notifications.slice(0, 3);
                                                
                                                // Show toast for each new notification
                                                notificationsToShow.forEach(function(notification) {
                                                    let toastType = 'info';
                                                    let toastIcon = notification.icon || 'fas fa-bell';
                                                    let toastTitle = notification.title || 'Notification';
                                                    let detailsText = '';
                                                    
                                                    // Customize the toast based on notification type
                                                    if (notification.type.includes('leave')) {
                                                        if (notification.type.includes('approved')) {
                                                            toastType = 'success';
                                                            toastIcon = 'fas fa-check-circle';
                                                        } else if (notification.type.includes('rejected')) {
                                                            toastType = 'danger';
                                                            toastIcon = 'fas fa-times-circle';
                                                        } else {
                                                            toastType = 'warning';
                                                        }
                                                        
                                                        // Add details for leave notifications
                                                        if (notification.details) {
                                                            detailsText = `
                                                                ${notification.details.reason ? 'Reason: ' + notification.details.reason : ''}
                                                                ${notification.details.start_date ? ' • Period: ' + notification.details.start_date : ''}
                                                                ${notification.details.end_date ? ' to ' + notification.details.end_date : ''}
                                                            `;
                                                        }
                                                    } else if (notification.type.includes('cash_advance')) {
                                                        if (notification.type.includes('approved')) {
                                                            toastType = 'success';
                                                            toastIcon = 'fas fa-check-circle';
                                                        } else if (notification.type.includes('declined')) {
                                                            toastType = 'danger';
                                                            toastIcon = 'fas fa-times-circle';
                                                        } else {
                                                            toastType = 'warning';
                                                        }
                                                        
                                                        // Add details for cash advance notifications
                                                        if (notification.details && notification.details.amount) {
                                                            detailsText = `Amount: ₱${notification.details.amount.toLocaleString()}`;
                                                            if (notification.details.reason) {
                                                                detailsText += ` • Reason: ${notification.details.reason}`;
                                                            }
                                                        }
                                                    } else if (notification.type.includes('night_premium')) {
                                                        if (notification.type.includes('approved')) {
                                                            toastType = 'success';
                                                            toastIcon = 'fas fa-check-circle';
                                                        } else if (notification.type.includes('rejected')) {
                                                            toastType = 'danger';
                                                            toastIcon = 'fas fa-times-circle';
                                                        } else {
                                                            toastType = 'warning';
                                                        }
                                                        
                                                        // Add details for night premium notifications
                                                        if (notification.details) {
                                                            detailsText = `Date: ${notification.details.date || 'N/A'}`;
                                                            if (notification.details.night_hours) {
                                                                detailsText += ` • Hours: ${notification.details.night_hours}`;
                                                            }
                                                            if (notification.details.night_premium_pay) {
                                                                detailsText += ` • Amount: ₱${parseFloat(notification.details.night_premium_pay).toLocaleString()}`;
                                                            }
                                                            if (notification.details.reason) {
                                                                detailsText += ` • Reason: ${notification.details.reason}`;
                                                            }
                                                        }
                                                    }
                                                    
                                                    showToast(
                                                        toastType,
                                                        toastTitle,
                                                        notification.message,
                                                        toastIcon,
                                                        detailsText
                                                    );
                                                });
                                                
                                                // If there are more notifications than we showed
                                                if (response.notifications.length > 3) {
                                                    const remaining = response.notifications.length - 3;
                                                    showToast(
                                                        'info',
                                                        'More Notifications',
                                                        `You have ${remaining} more new notification${remaining > 1 ? 's' : ''}`,
                                                        'fas fa-bell'
                                                    );
                                                }
                                                
                                                // Reload notifications if dropdown is open
                                                if ($('.notifications-dropdown').hasClass('show')) {
                                                    loadNotifications();
                                                }
                                            }
                                            
                                            // Update the timestamp
                                            lastNotificationTimestamp = response.timestamp;
                                            
                                            // Play notification sound if enabled
                                            playNotificationSound();
                                        }
                                    }
                                });
                            }
                        }, 15000);  // Check every 15 seconds
                        
                        // Play notification sound
                        function playNotificationSound() {
                            // Check if notification sounds are enabled in user preferences
                            const soundEnabled = localStorage.getItem('notification_sound_enabled') !== 'false';
                            
                            if (soundEnabled) {
                                // Create audio element if it doesn't exist
                                if (!window.notificationAudio) {
                                    window.notificationAudio = new Audio('{{ asset("sounds/notification.mp3") }}');
                                }
                                
                                // Play the sound
                                window.notificationAudio.play().catch(function(error) {
                                    // Autoplay might be blocked by browser
                                    console.log('Could not play notification sound:', error);
                                });
                            }

                        }
                        
                        // Reload notifications when dropdown is opened
                        $('#notifications-dropdown-toggle').on('click', function() {
                            // Only load if dropdown isn't already open
                            if (!$(this).parent().hasClass('show')) {
                                loadNotifications();
                            }
                        });
                    });
                </script>

                <!-- System Updates Icon -->
                @if(isset($systemUpdates))
                <li class="nav-item">
                    <a class="nav-link system-updates-icon" href="#" data-toggle="modal" data-target="#systemUpdatesModal" title="System Updates">
                        <i class="fas fa-sync-alt"></i>
                        @if($systemUpdates['hasUnreadUpdates'])
                            <span class="badge badge-danger navbar-badge updates-count">{{ $systemUpdates['updates']->count() }}</span>
                        @endif
                    </a>
                </li>
                @endif


                @canany(['admin', 'super-admin', 'hrcomben', 'hrcompliance', 'hrpolicy','vpfinance-admin'])
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="Announcements and Holidays">
                        <i class="fas fa-bullhorn"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @canany(['super-admin'])
                        <a href="{{ url('types') }}" class="dropdown-item">
                            <i class="fas fa-folder mr-2"></i> Leave Type
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcompliance', 'hrpolicy','vpfinance-admin'])
                        <a href="{{ url('posts') }}" class="dropdown-item">
                            <i class="fas fa-bullhorn mr-2"></i> Announcement
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'supervisor','vpfinance-admin'])
                        <a href="{{ url('tasks') }}" class="dropdown-item">
                            <i class="fas fa-tasks mr-2"></i> Send Task
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcomben'])
                        <a href="{{ url('holidays') }}" class="dropdown-item">
                            <i class="fas fa-calendar-alt mr-2"></i> Holiday
                        </a>
                        @endcanany
                        @can('system-admin')
                        <a href="{{ url('system-updates') }}" class="dropdown-item">
                            <i class="fas fa-sync-alt mr-2"></i> System Updates
                        </a>
                        @endcan
                    </div>
                </li>
                @endcanany

                @canany(['admin', 'super-admin', 'hrcomben'])
                <li class="nav-item">
                    <a class="nav-link contribution-notify-btn" href="#" data-toggle="modal" data-target="#contributeNotifyModal" data-tooltip="Send Contribution Notifications">
                        <i class="fas fa-money-bill-wave"></i>
                        <span class="badge badge-warning navbar-badge notification-badge"><i class="fas fa-bell fa-xs"></i></span>
                    </a>
                </li>
                @endcanany

                @canany(['admin', 'super-admin', 'supervisor'])
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="User Management">
                        <i class="fas fa-users"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @canany(['admin', 'super-admin', 'vpfinance-admin'])
                        <a href="{{ url('users') }}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> User Management
                        </a>
                        @endcanany
                        @if(auth()->user()->hasRole('Supervisor'))
                        <a href="{{ route('activity-logs.index') }}" class="dropdown-item">
                            <i class="fas fa-history mr-2"></i> Departmental User Activity
                        </a>
                        @endif
                        @canany(['admin', 'super-admin', 'vpfinance-admin'])
                        <a href="{{ url('/user-activity') }}" class="dropdown-item">
                            <i class="fas fa-history mr-2"></i> User General Logs
                        </a>
                        @endcanany
                        @canany(['super-admin'])
                        <a href="{{ route('database.backups') }}" class="dropdown-item">
                            <i class="fas fa-database mr-2"></i> Database Backups
                        </a>
                        @endcanany
                    </div>
                </li>
                @endcanany


                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" data-tooltip="Profile Management">
                            @if(Auth::user()->adminlte_image())
                                <img src="{{ Auth::user()->adminlte_image() }}" class="user-image img-circle elevation-1" alt="User Image">
                                {{Auth::user()->first_name}} {{Auth::user()->last_name}}
                            @else
                                <div class="user-image img-circle elevation-1 d-flex justify-content-center align-items-center">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </a>
                        <div class="dropdown-menu">
                            <div class="user-header">
                                @if(Auth::user()->adminlte_image())
                                    <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle elevation-2" alt="User Image">
                                @else
                                    <div class="img-circle elevation-2 d-flex justify-content-center align-items-center mx-auto">
                                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="user-info">
                                    <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                    <span class="user-role">{{ Auth::user()->roles->first()->name ?? 'User' }}</span>
                                </div>
                            </div>
                            
                            <div class="dropdown-menu-content">
                                <a href="/profile/details" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>

                                <!-- Account Management Section -->
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Account Management</h6>
                                
                                <!-- Linked Accounts -->
                                <div class="linked-accounts px-3 py-2">
                                    @foreach(Auth::user()->linkedAccounts as $linkedAccount)
                                        <div class="linked-account d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <i class="fas fa-user-circle"></i>
                                                {{ Str::limit($linkedAccount->email, 15) }}
                                            </div>
                                            <div class="btn-group">
                                                <form action="{{ route('account.switch', $linkedAccount->id) }}" method="POST" class="d-inline switch-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('account.unlink', $linkedAccount->id) }}" method="POST" class="d-inline unlink-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger unlink-btn">
                                                        <i class="fas fa-unlink"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        // Handle unlink button click
                                        $('.unlink-btn').on('click', function(e) {
                                            e.preventDefault();
                                            const $form = $(this).closest('form');
                                            const email = $(this).closest('.linked-account').find('.email-text').text().trim();

                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "This will unlink the account. This action cannot be undone!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#dc3545',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Yes, unlink it!',
                                                cancelButtonText: 'Cancel',
                                                customClass: {
                                                    popup: 'animated fadeInDown faster'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $.ajax({
                                                        url: $form.attr('action'),
                                                        method: 'POST',
                                                        data: $form.serialize(),
                                                        success: function(response) {
                                                            // Show success Swal
                                                            Swal.fire({
                                                                title: 'Unlinked!',
                                                                text: 'The account has been unlinked successfully.',
                                                                icon: 'success',
                                                                timer: 2000,
                                                                timerProgressBar: true,
                                                                showConfirmButton: false,
                                                                customClass: {
                                                                    popup: 'animated fadeInDown faster'
                                                                }
                                                            });

                                                            // Show success toast
                                                            const toast = `
                                                                <div class="toast success" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                                                                    <div class="toast-header bg-success text-white">
                                                                        <i class="fas fa-check-circle mr-2"></i>
                                                                        <strong class="mr-auto">Success</strong>
                                                                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="toast-body">
                                                                        Account unlinked successfully!
                                                                    </div>
                                                                    <div class="toast-progress"></div>
                                                                </div>
                                                            `;
                                                            
                                                            $('.toast-container').append(toast);
                                                            $('.toast').toast('show');

                                                            // Reload page after short delay
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 2000);
                                                        },
                                                        error: function(xhr) {
                                                            const message = xhr.responseJSON?.message || 'An error occurred while unlinking the account.';
                                                            
                                                            // Show error Swal
                                                            Swal.fire({
                                                                title: 'Error!',
                                                                text: message,
                                                                icon: 'error',
                                                                confirmButtonText: 'OK',
                                                                customClass: {
                                                                    popup: 'animated shake faster'
                                                                }
                                                            });

                                                            // Show error toast
                                                            const toast = `
                                                                <div class="toast error" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                                                                    <div class="toast-header bg-danger text-white">
                                                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                                                        <strong class="mr-auto">Error</strong>
                                                                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="toast-body">
                                                                        ${message}
                                                                    </div>
                                                                    <div class="toast-progress"></div>
                                                                </div>
                                                            `;
                                                            
                                                            $('.toast-container').append(toast);
                                                            $('.toast').toast('show');
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    });
                                </script>

                                <!-- Link New Account -->
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#linkAccountModal">
                                    <i class="fas fa-link"></i>
                                    Link Another Account
                                </a>
                                @if(auth()->user()->hasRole('Super Admin'))
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('route-management.index') }}" class="dropdown-item">
                                    <i class="fas fa-route"></i>
                                    Route Management
                                </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('login.history') }}" class="dropdown-item logout-item">
                                    <i class="fas fa-history"></i>
                                    Recent Logins
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-item">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" data-tooltip="Settings">
                            <i class="fas fa-cog"></i>
                        </a>
                    </li>
                @endguest
            </ul>
        </nav>

<style>
    /* Custom tooltip styles */
    .tooltip {
        z-index: 9999;
        pointer-events: none;
    }
    
    .tooltip .tooltip-inner {
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        max-width: 200px;
    }
    
    .tooltip.bs-tooltip-top .arrow::before {
        border-top-color: rgba(0, 0, 0, 0.8);
    }
    
    .tooltip.bs-tooltip-bottom .arrow::before {
        border-bottom-color: rgba(0, 0, 0, 0.8);
    }
    
    /* Toast notification styles */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        min-width: 250px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        opacity: 1 !important;
    }
    
    .toast .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.7);
        width: 0;
        animation: toast-progress 3s linear forwards;
    }
    
    @keyframes toast-progress {
        from { width: 100%; }
        to { width: 0%; }
    }
</style><script>
    $(document).ready(function() {
        // Initialize tooltips with custom configuration
        $('[data-tooltip]').tooltip({
            trigger: 'hover',
            placement: 'bottom',
            container: 'body',
            boundary: 'window',
            title: function() {
                return $(this).data('tooltip');
            },
            delay: {
                show: 200,
                hide: 0
            }
        });

        // Hide tooltips when dropdown menus are shown
        $('.dropdown').on('show.bs.dropdown', function () {
            $('[data-tooltip]').tooltip('hide');
        });

        // Hide tooltips when search popup is shown
        $('#search-toggle').on('click', function() {
            $('[data-tooltip]').tooltip('hide');
        });

        // Destroy tooltips before showing modals
        $('[data-toggle="modal"]').on('click', function() {
            $('[data-tooltip]').tooltip('hide');
        });
        
        // Cleanup tooltips when elements are removed from DOM
        $(document).on('remove', '[data-tooltip]', function() {
            $(this).tooltip('dispose');
        });
    });
</script>


