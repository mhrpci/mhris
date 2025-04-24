// resources/js/bootstrap.js

import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events. Echo and event broadcasting allows your team to easily build
 * robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Added for backward compatibility - get values from the window object if available
if (!import.meta.env.VITE_PUSHER_APP_KEY && window.Laravel && window.Laravel.pusherKey) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: window.Laravel.pusherKey,
        cluster: window.Laravel.pusherCluster || 'mt1',
        forceTLS: true
    });
}

// Listen for the NewNotification event
window.Echo.channel('notifications')
    .listen('NewNotification', (data) => {
        // Update notification UI
        updateNotificationCount(data.count);
        updateNotificationDropdown(data.notifications);
        
        // Show toast for new notifications
        if (data.toast.message) {
            showToast(data.toast.title, data.toast.message, data.toast.icon);
        }
    });

// Add these functions to your main JS file
// resources/js/notifications.js

// Function to update the notification count in the UI
function updateNotificationCount(count) {
    const $notificationCounter = $('.notification-counter');
    
    if (count > 0) {
        $notificationCounter.text(count).show();
    } else {
        $notificationCounter.text('0').hide();
    }
}

// Function to update the notification dropdown content
function updateNotificationDropdown(html) {
    const $notificationDropdown = $('.notifications-dropdown-menu');
    
    if (html) {
        $notificationDropdown.html(html);
    }
}

// Function to show a toast notification
function showToast(title, message, icon) {
    // You can use any toast library here (Toastr, SweetAlert2, etc.)
    // Example with Toastr:
    toastr.info(message, title, {
        closeButton: true,
        progressBar: true,
        timeOut: 5000,
        extendedTimeOut: 2000,
        positionClass: 'toast-top-right',
        iconClass: icon || 'fas fa-bell'
    });
}

// Function to fetch notifications manually
function fetchNotifications() {
    $.ajax({
        url: '/notifications/data',
        method: 'GET',
        success: function(response) {
            updateNotificationCount(response.count);
            updateNotificationDropdown(response.notifications);
        },
        error: function(xhr) {
            console.error('Error fetching notifications:', xhr.responseText);
        }
    });
}

// Trigger a notification update every 60 seconds as a fallback
setInterval(fetchNotifications, 60000);

// Initialize notifications on page load
$(document).ready(function() {
    fetchNotifications();
    
    // Toggle notification dropdown
    $('.notification-bell').on('click', function(e) {
        e.preventDefault();
        $('.notifications-dropdown-menu').toggleClass('show');
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.notification-bell, .notifications-dropdown-menu').length) {
            $('.notifications-dropdown-menu').removeClass('show');
        }
    });
});