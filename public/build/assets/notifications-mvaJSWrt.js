import{P as e,E as s}from"./pusher-DHLPHH5Z.js";var a={};window.Pusher=e;window.Echo=new s({broadcaster:"pusher",key:a.MIX_PUSHER_APP_KEY,cluster:a.MIX_PUSHER_APP_CLUSTER,forceTLS:!0});class c{constructor(){this.notificationCount=0,this.lastTimestamp=Date.now()/1e3,this.notificationsContainer=$(".notifications-container"),this.notificationCountBadge=$(".notification-count"),this.notificationHeaderCount=$("#notification-header-count"),this.initializeListeners(),this.loadInitialNotifications(),this.setupPolling()}initializeListeners(){window.Echo.channel("notifications").listen("NewNotification",t=>{this.updateNotificationUI(t),this.playNotificationSound(),this.showToastNotification(t.toast)}),$(document).on("click",".mark-as-read",t=>{t.preventDefault();const i=$(t.currentTarget).data("id");this.markAsRead(i)}),$(".mark-all-read").on("click",t=>{t.preventDefault(),this.markAllAsRead()}),$("#notifications-dropdown-toggle").on("click",()=>{this.notificationsContainer.find(".notification-item").length===0&&this.loadInitialNotifications()})}loadInitialNotifications(){this.notificationsContainer.html(`
            <div class="loading-notifications text-center p-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading notifications...</p>
            </div>
        `),$.ajax({url:"/notifications/data",type:"GET",success:t=>{this.renderNotifications(t),this.updateNotificationCount(t.count),this.lastTimestamp=Math.floor(Date.now()/1e3)},error:t=>{this.notificationsContainer.html(`
                    <div class="text-center p-3">
                        <i class="fas fa-exclamation-circle text-danger fa-2x mb-3"></i>
                        <p class="text-muted">Failed to load notifications.</p>
                    </div>
                `),console.error("Error loading notifications:",t.responseText)}})}setupPolling(){setInterval(()=>{this.checkForUpdates()},3e4)}checkForUpdates(){$.ajax({url:"/notifications/check-updates",type:"POST",data:{timestamp:this.lastTimestamp,_token:$('meta[name="csrf-token"]').attr("content")},success:t=>{t.has_new&&(this.lastTimestamp=Math.floor(Date.now()/1e3))},error:t=>{console.error("Error checking for notification updates:",t.responseText)}})}updateNotificationUI(t){this.updateNotificationCount(t.count),t.notifications&&t.notifications.length>0&&(this.notificationsContainer.find(".loading-notifications").remove(),t.notifications.forEach(i=>{const o=this.createNotificationHtml(i);this.notificationsContainer.prepend(o);const n=this.notificationsContainer.find(".notification-item").first();n.addClass("notification-highlight"),setTimeout(()=>{n.removeClass("notification-highlight")},3e3)}))}createNotificationHtml(t){return`
            <a href="${t.url}" class="notification-item ${t.is_read?"":"unread"}" data-id="${t.id}">
                <div class="notification-icon ${t.icon_class}">
                    <i class="${t.icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-text">${t.text}</div>
                    <div class="notification-time">${t.time}</div>
                </div>
                <div class="notification-action">
                    <button class="btn btn-sm btn-link text-muted mark-as-read" data-id="${t.id}" title="Mark as read">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </a>
        `}updateNotificationCount(t){this.notificationCount=t,this.notificationCountBadge.text(t),this.notificationHeaderCount.text(t),t>0?this.notificationCountBadge.show():this.notificationCountBadge.hide()}renderNotifications(t){this.notificationsContainer.empty(),t.notifications&&t.notifications.length>0?t.notifications.forEach(i=>{const o=this.createNotificationHtml(i);this.notificationsContainer.append(o)}):this.notificationsContainer.html(`
                <div class="text-center p-3">
                    <i class="far fa-bell-slash text-muted fa-2x mb-3"></i>
                    <p class="text-muted">No notifications yet.</p>
                </div>
            `)}markAsRead(t){$.ajax({url:"/notifications/mark-as-read",type:"POST",data:{id:t,_token:$('meta[name="csrf-token"]').attr("content")},success:i=>{i.success&&($(`.notification-item[data-id="${t}"]`).removeClass("unread"),this.updateNotificationCount(i.count))},error:i=>{console.error("Error marking notification as read:",i.responseText)}})}markAllAsRead(){$.ajax({url:"/notifications/mark-all-as-read",type:"POST",data:{_token:$('meta[name="csrf-token"]').attr("content")},success:t=>{t.success&&($(".notification-item").removeClass("unread"),this.updateNotificationCount(0),this.showToastNotification({title:"Success",message:"All notifications marked as read",icon:"fas fa-check-circle"}))},error:t=>{console.error("Error marking all notifications as read:",t.responseText)}})}playNotificationSound(){window.notificationSound||(window.notificationSound=new Audio("/sounds/notification.mp3")),window.notificationSound&&window.notificationSound.play().catch(t=>{console.log("Could not play notification sound:",t)})}showToastNotification(t){t&&Swal.fire({title:t.title,text:t.message,icon:"info",toast:!0,position:"bottom-end",showConfirmButton:!1,timer:5e3,timerProgressBar:!0})}}$(document).ready(()=>{new c});
