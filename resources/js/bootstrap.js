import 'bootstrap';
import axios from 'axios';
import jQuery from 'jquery';
import toastr from 'toastr';

window.$ = window.jQuery = jQuery;
window.axios = axios;
window.toastr = toastr;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen for notification events
window.Echo.channel('notifications')
    .listen('NewNotification', (e) => {
        // Update your notification UI here
        updateNotificationUI(e.notification);
    });

function updateNotificationUI(notification) {
    // Update the notification count
    document.querySelector('#notification-badge').textContent = notification.label;

    // Update the dropdown content
    document.querySelector('#notification-dropdown').innerHTML = notification.dropdown;

    // You might want to show a toast or some other visual indicator of new notifications
}
