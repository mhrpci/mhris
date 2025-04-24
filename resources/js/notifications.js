import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import axios from 'axios';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

class NotificationHandler {
    constructor() {
        this.initializeNotifications();
    }

    initializeNotifications() {
        if (window.Laravel.user) {
            this.listenForNotifications();
            this.loadExistingNotifications();
            this.setupEventListeners();
        }
    }

    listenForNotifications() {
        window.Echo.private(`App.Models.User.${window.Laravel.user.id}`)
            .notification((notification) => {
                this.handleNewNotification(notification);
            });
    }

    async loadExistingNotifications() {
        try {
            const response = await fetch('/notifications/get');
            const data = await response.json();
            this.updateNotificationCount(data.unread_count);
            this.renderNotifications(data.notifications);
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    setupEventListeners() {
        // Toggle notification dropdown
        $(document).on('click', '#notificationsDropdown', (e) => {
            e.preventDefault();
            $('#notificationsMenu').toggleClass('show');
        });

        // Close dropdown when clicking outside
        $(document).on('click', (e) => {
            if (!$(e.target).closest('#notificationsDropdown, #notificationsMenu').length) {
                $('#notificationsMenu').removeClass('show');
            }
        });

        // Mark all as read
        $(document).on('click', '.mark-all-read', async (e) => {
            e.preventDefault();
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                if (response.ok) {
                    $('.notification-item').removeClass('unread');
                    $('.notification-count').hide();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        });

        // Mark individual notification as read
        $(document).on('click', '.notification-item', async function() {
            const notificationId = $(this).data('notification-id');
            try {
                const response = await fetch(`/notifications/mark-as-read/${notificationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                if (response.ok) {
                    $(this).removeClass('unread');
                    const unreadCount = $('.notification-item.unread').length;
                    if (unreadCount === 0) {
                        $('.notification-count').hide();
                    } else {
                        $('.notification-count').text(unreadCount);
                    }
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        });
    }

    updateNotificationCount(count) {
        const badge = $('.notification-count');
        if (count > 0) {
            badge.text(count).show();
        } else {
            badge.hide();
        }
    }

    renderNotifications(notifications) {
        const container = $('.notifications-list');
        container.empty();

        if (notifications.length === 0) {
            container.html(`
                <div class="dropdown-item text-center text-muted">
                    <i class="fas fa-bell-slash mr-2"></i>No notifications
                </div>
            `);
            return;
        }

        notifications.forEach(notification => {
            const timeAgo = this.timeAgo(new Date(notification.created_at));
            const html = `
                <div class="notification-item ${notification.read_at ? '' : 'unread'}" 
                     data-notification-id="${notification.id}">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-text">${notification.message}</div>
                    <div class="notification-time">${timeAgo}</div>
                </div>
            `;
            container.append(html);
        });
    }

    handleNewNotification(notification) {
        // Update notification count
        const currentCount = parseInt($('.notification-count').text()) || 0;
        this.updateNotificationCount(currentCount + 1);

        // Add new notification to list
        const timeAgo = this.timeAgo(new Date(notification.created_at));
        const html = `
            <div class="notification-item unread" data-notification-id="${notification.id}">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-text">${notification.message}</div>
                <div class="notification-time">${timeAgo}</div>
            </div>
        `;
        $('.notifications-list').prepend(html);

        // Show toast notification
        this.showToast(notification);
    }

    showToast(notification) {
        const toast = $(`
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                <div class="toast-header">
                    <strong class="mr-auto">${notification.title}</strong>
                    <small class="text-muted">just now</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    ${notification.message}
                </div>
            </div>
        `);
        
        $('.toast-container').append(toast);
        toast.toast('show');
    }

    timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        
        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) return interval + ' year' + (interval === 1 ? '' : 's') + ' ago';
        
        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) return interval + ' month' + (interval === 1 ? '' : 's') + ' ago';
        
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + ' day' + (interval === 1 ? '' : 's') + ' ago';
        
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + ' hour' + (interval === 1 ? '' : 's') + ' ago';
        
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + ' minute' + (interval === 1 ? '' : 's') + ' ago';
        
        return 'just now';
    }
}

// Initialize notifications
new NotificationHandler();

// Request notification permission
if (Notification.permission === 'default') {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            console.log('Notification permission granted.');
        } else {
            console.log('Notification permission denied.');
        }
    });
} else if (Notification.permission === 'denied') {
    alert('Notifications are blocked. Please enable them in your browser settings.');
} else {
    console.log('Notification permission already granted.');
}

window.Echo.private(`notifications`)
    .listen('RealTimeNotification', (e) => {
        // Update notification badge
        updateNotificationBadge(e.notification);

        // Show desktop notification if permitted
        if (Notification.permission === 'granted') {
            showDesktopNotification(e.notification);
        }

        // Play notification sound
        playNotificationSound();
    });

function updateNotificationBadge(notification) {
    const badge = document.getElementById('notification-badge');
    const count = parseInt(badge.textContent || '0');
    badge.textContent = count + 1;

    // Add notification to dropdown
    addNotificationToDropdown(notification);
}

function showDesktopNotification(notification) {
    const options = {
        body: notification.text,
        icon: '/path/to/icon.png',
        badge: '/path/to/badge.png',
        vibrate: [200, 100, 200]
    };

    new Notification(notification.title, options);
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification system
    initializeNotifications();

    // Set up polling for notifications
    setInterval(fetchNotifications, 30000); // Poll every 30 seconds
});

function initializeNotifications() {
    // Set up CSRF token for AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

    // Initialize Pusher
    const pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
    });

    // Subscribe to the notifications channel
    const channel = pusher.subscribe('notifications');
    
    // Listen for new notifications
    channel.bind('new-notification', function(data) {
        updateNotificationUI(data);
        playNotificationSound();
    });
}

function fetchNotifications() {
    axios.get('/notifications/data')
        .then(response => {
            updateNotificationUI(response.data);
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
}

function updateNotificationUI(data) {
    // Update notification counter
    const counter = document.getElementById('notification-counter');
    if (counter) {
        counter.textContent = data.count;
        counter.style.display = data.count > 0 ? 'inline-block' : 'none';
    }

    // Update notification dropdown
    const dropdown = document.getElementById('notification-dropdown');
    if (dropdown) {
        dropdown.innerHTML = data.notifications;
    }

    // Show toast notification if there's a new notification
    if (data.toast && data.toast.message) {
        showToast(data.toast);
    }
}

function showToast(toast) {
    // Create toast element
    const toastElement = document.createElement('div');
    toastElement.className = 'toast';
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');
    
    toastElement.innerHTML = `
        <div class="toast-header">
            <i class="${toast.icon} mr-2"></i>
            <strong class="mr-auto">${toast.title}</strong>
            <small class="text-muted">just now</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            ${toast.message}
        </div>
    `;

    // Add toast to container
    const toastContainer = document.querySelector('.toast-container');
    if (toastContainer) {
        toastContainer.appendChild(toastElement);
        
        // Initialize Bootstrap toast
        const bsToast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
        
        bsToast.show();

        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }
}

function playNotificationSound() {
    const audio = new Audio('/notification-sound.mp3');
    audio.play().catch(error => {
        console.error('Error playing notification sound:', error);
    });
}

// Mark notification as read
function markAsRead(notificationId) {
    axios.post(`/notifications/${notificationId}/read`)
        .then(response => {
            // Update UI to reflect the read status
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('unread');
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
}

// Clear all notifications
function clearAllNotifications() {
    axios.post('/notifications/clear-all')
        .then(response => {
            // Update UI to show no notifications
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown) {
                dropdown.innerHTML = `
                    <div class="empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>No new notifications</p>
                    </div>
                `;
            }
            
            // Hide the counter
            const counter = document.getElementById('notification-counter');
            if (counter) {
                counter.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error clearing notifications:', error);
        });
}

/**
 * Real-time notifications setup with Pusher
 */

// Get user ID from the page
const userId = document.querySelector('meta[name="user-id"]')?.content;

/**
 * Initialize real-time notifications
 */
function initRealTimeNotifications() {
    if (!userId || !window.Echo) {
        console.error('Echo or user ID not available for real-time notifications');
        return;
    }

    // Listen for private notifications channel
    window.Echo.private(`notifications.${userId}`)
        .listen('.new-notification', (e) => {
            console.log('New notification received via Pusher:', e);
            
            // Update notification UI
            updateNotificationBadge();
            
            // Add to notification list
            addNotificationToList(e.notification);
            
            // Play sound if applicable
            if (shouldPlaySound()) {
                playNotificationSound();
            }
            
            // Send to service worker if page not focused
            if (document.visibilityState !== 'visible') {
                sendToServiceWorker(e.notification);
            } else {
                // Show in-app toast notification
                showToastNotification(e.notification);
            }
        });
        
    console.log('Real-time notifications initialized with Pusher');
}

/**
 * Send notification to service worker for background display
 * @param {Object} notification - The notification data
 */
function sendToServiceWorker(notification) {
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
        navigator.serviceWorker.controller.postMessage({
            type: 'pusher:notification',
            data: notification,
            inFocus: document.visibilityState === 'visible'
        });
    }
}

/**
 * Update the notification badge count
 */
function updateNotificationBadge() {
    fetch('/notifications/get')
        .then(response => response.json())
        .then(data => {
            const badges = document.querySelectorAll('.notification-count');
            badges.forEach(badge => {
                badge.textContent = data.unread_count;
                if (data.unread_count > 0) {
                    badge.classList.remove('d-none');
                }
            });
        })
        .catch(error => console.error('Error updating notification badge:', error));
}

/**
 * Add a notification to the dropdown list
 * @param {Object} notification - The notification data
 */
function addNotificationToList(notification) {
    const notificationsList = document.querySelector('.notifications-list');
    if (!notificationsList) return;

    const notificationItem = document.createElement('a');
    notificationItem.href = notification.url || '#';
    notificationItem.className = 'notification-item unread';
    notificationItem.dataset.id = notification.id;
    
    // Generate notification HTML based on type
    const iconClass = getIconClass(notification.type);
    
    notificationItem.innerHTML = `
        <div class="notification-icon ${iconClass}">
            <i class="${notification.icon || 'fas fa-bell'}"></i>
        </div>
        <div class="notification-content">
            <div class="notification-title">${notification.title || 'New Notification'}</div>
            <div class="notification-text">${notification.body || notification.text || ''}</div>
            <div class="notification-time">
                <i class="far fa-clock mr-1"></i>Just now
            </div>
        </div>
    `;
    
    // Add to the top of the list
    if (notificationsList.firstChild) {
        notificationsList.insertBefore(notificationItem, notificationsList.firstChild);
    } else {
        notificationsList.appendChild(notificationItem);
    }
    
    // If empty notification placeholder exists, remove it
    const emptyNotification = notificationsList.querySelector('.empty-notifications');
    if (emptyNotification) {
        emptyNotification.remove();
    }
}

/**
 * Show a toast notification in the app
 * @param {Object} notification - The notification data
 */
function showToastNotification(notification) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: getToastIcon(notification.type),
            title: notification.title || 'New Notification',
            text: notification.body || notification.text || '',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('click', () => {
                    if (notification.url) {
                        window.location.href = notification.url;
                    }
                });
            }
        });
    } else if (typeof toastr !== 'undefined') {
        // Fallback to toastr if SweetAlert2 is not available
        toastr.info(
            notification.body || notification.text || '',
            notification.title || 'New Notification',
            { timeOut: 5000, progressBar: true }
        );
    }
}

/**
 * Get icon class based on notification type
 * @param {string} type - The notification type
 * @returns {string} The icon class
 */
function getIconClass(type) {
    if (!type) return 'default';
    
    if (type.includes('leave')) return 'leave';
    if (type.includes('cash_advance')) return 'cash-advance';
    if (type.includes('overtime')) return 'overtime';
    if (type.includes('night_premium')) return 'night-premium';
    
    return 'default';
}

/**
 * Get toast icon based on notification type
 * @param {string} type - The notification type
 * @returns {string} The SweetAlert2 icon type
 */
function getToastIcon(type) {
    if (!type) return 'info';
    
    if (type.includes('approved')) return 'success';
    if (type.includes('rejected') || type.includes('declined')) return 'error';
    if (type.includes('validated')) return 'success';
    if (type.includes('pending')) return 'warning';
    
    return 'info';
}

/**
 * Play notification sound
 */
function playNotificationSound() {
    const soundUrl = '/sounds/notification.mp3';
    const audio = new Audio(soundUrl);
    audio.play().catch(error => {
        console.error('Error playing notification sound:', error);
    });
}

/**
 * Check if notification sound should be played
 * @returns {boolean} True if sound should be played
 */
function shouldPlaySound() {
    return localStorage.getItem('notification_sound') !== 'disabled';
}

/**
 * Document ready handler
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize real-time notifications
    initRealTimeNotifications();
    
    // Mark notification as read when clicked
    document.addEventListener('click', (e) => {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem && notificationItem.dataset.id) {
            markNotificationAsRead(notificationItem.dataset.id, notificationItem.dataset.type || '');
        }
    });
    
    // Setup visibility change listener to update notifications
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            updateNotificationBadge();
        }
    });
});

/**
 * Mark a notification as read
 * @param {string} id - The notification ID
 * @param {string} type - The notification type
 */
function markNotificationAsRead(id, type) {
    fetch('/web-notifications/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({ id, type })
    })
    .then(response => {
        if (response.ok) {
            updateNotificationBadge();
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

// Export the functions for use in other files
export {
    initRealTimeNotifications,
    updateNotificationBadge,
    playNotificationSound
};

