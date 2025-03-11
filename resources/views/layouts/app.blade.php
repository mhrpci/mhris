<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MHR Property Conglomerates, Inc.</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/ICON_APP.png') }}">

    <!-- PWA Manifest -->
<link rel="manifest" href="{{ asset('manifest.json') }}">

<!-- Service Worker Registration -->
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(reg => console.log("Service Worker Registered"))
            .catch(err => console.log("Service Worker Failed", err));
    }
</script>


    @yield('styles')
    <style>
    /* Global Responsive Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-size: 16px;
        line-height: 1.5;
        overflow-x: hidden;
    }

    /* Responsive Typography */
    @media (max-width: 1200px) {
        body {
            font-size: 15px;
        }
    }

    @media (max-width: 992px) {
        body {
            font-size: 14px;
        }
    }

    @media (max-width: 768px) {
        body {
            font-size: 13px;
        }
    }

    /* Responsive Container */
    .container {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    @media (min-width: 576px) {
        .container {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .container {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .container {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }

    /* Responsive Navigation */
    .contribution-nav {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        flex-wrap: wrap;
        padding: 10px;
    }

    @media (max-width: 768px) {
        .contribution-nav {
            gap: 5px;
        }

        .contribution-link {
            width: calc(50% - 5px);
            padding: 8px;
        }

        .contribution-link .icon-wrapper {
            width: 30px;
            height: 30px;
        }

        .contribution-link .title {
            font-size: 0.9rem;
        }

        .contribution-link .description {
            font-size: 0.7rem;
        }
    }

    @media (max-width: 576px) {
        .contribution-link {
            width: 100%;
        }
    }

    /* Responsive Images */
    img {
        max-width: 100%;
        height: auto;
    }

    .profile-img {
        width: 150px;
        height: 150px;
    }

    @media (max-width: 768px) {
        .profile-img {
            width: 120px;
            height: 120px;
        }
    }

    /* Responsive Tables */
    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Responsive Forms */
    input, select, textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
    }

    /* Responsive Sidebar */
    @media (max-width: 992px) {
        .control-sidebar {
            width: 200px;
        }
    }

    @media (max-width: 768px) {
        .control-sidebar {
            width: 100%;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 1050;
        }
    }

    /* Toast Responsive Positioning */
    @media (max-width: 576px) {
        .toast-container {
            right: 0;
            left: 0;
            bottom: 0;
            margin: 0.5rem;
        }
    }

    /* Loader Responsive Adjustments */
    @media (max-width: 576px) {
        .mhr-loader {
            width: 80px;
            height: 80px;
        }

        .mhr-text {
            font-size: 18px;
        }
    }

    /* Card and Panel Responsive Design */
    .card, .panel {
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .card, .panel {
            margin-bottom: 0.5rem;
        }
    }

    /* Button Responsive Styles */
    .btn {
        padding: 0.375rem 0.75rem;
    }

    @media (max-width: 576px) {
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    /* Grid System */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    [class*="col-"] {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    @media (max-width: 768px) {
        [class*="col-"] {
            padding-right: 10px;
            padding-left: 10px;
        }
    }

    /* Loader */
    .loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-out;
    }

    .loader-content {
        text-align: center;
    }

    /* MHR Loader */
    .mhr-loader {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .spinner {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #8e44ad;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .mhr-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        font-weight: bold;
        color: #8e44ad;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .contribution-nav {
        display: flex;
        gap: 15px;
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    .contribution-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    .contribution-link:hover {
        background-color: #e9ecef;
        text-decoration: none;
        color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .contribution-link.active {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    .contribution-link .icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.1);
        margin-right: 10px;
    }
    .contribution-link.active .icon-wrapper {
        background-color: rgba(255,255,255,0.2);
    }
    .contribution-link .icon-wrapper i {
        font-size: 1.2rem;
    }
    .contribution-link .text-wrapper {
        display: flex;
        flex-direction: column;
    }
    .contribution-link .title {
        font-weight: bold;
        font-size: 1rem;
    }
    .contribution-link .description {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    .contribution-link.active .description {
        opacity: 0.9;
    }
    /*toast*/
    .toast-container {
    z-index: 1050;
    position: fixed;
    bottom: 1rem;
    right: 1rem;
}

.toast {
    background-color: #226304;
    color: white;
}
.status-active {
        color: #fff;
        border: 1px solid green;
        background-color: green;
        padding: 2px 4px;
        border-radius: 5px;
    }

    .status-inactive {
        color: #fff;
        border: 1px solid red;
        background-color: red;
        padding: 2px 4px;
        border-radius: 5px;
    }

    /* Profile Image Styling */
    .profile-img {
        border: 3px solid #8e44ad; /* Purple border for a professional look */
        padding: 5px;
        background-color: #fff;
        object-fit: cover;
        width: 200px;
        height: 200px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .text-primary {
        font-size: 1.8rem;
        font-weight: bold;
    }

    .theme-option {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        margin-right: 5px;
        margin-bottom: 5px;
        opacity: 0.8;
        position: relative;
    }

    .theme-option:hover {
        opacity: 1;
    }

    .theme-option.active::after {
        content: '\2714';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 16px;
    }

    /* Right Sidebar Styles */
    .control-sidebar {
        width: 250px;
    }
    .control-sidebar-dark {
        background-color: #343a40;
    }
    .control-sidebar-content {
        padding: 1rem;
    }
    .theme-option-wrapper {
        margin-bottom: 1rem;
    }
    .theme-option-wrapper label {
        display: block;
        margin-bottom: 0.5rem;
        color: #ced4da;
        font-weight: 600;
    }
    .theme-select {
        width: 100%;
        background-color: #454d55;
        color: #fff;
        border: 1px solid #6c757d;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .theme-select option {
        background-color: #454d55;
        color: #fff;
    }
    .theme-select:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
    }

    .theme-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .theme-option {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        margin-right: 5px;
        margin-bottom: 5px;
        opacity: 0.8;
        position: relative;
    }

    .theme-option:hover {
        opacity: 1;
    }

    .theme-option.active::after {
        content: '\2714';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 16px;
    }
    /*my-contribution css*/
    .container-fluid {
        padding: 20px;
    }

    .info-box {
    position: relative;
    overflow: hidden;
    height: 120px;
    border-radius: .25rem;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    background-color: #f4f6f9;
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-box-icon {
    font-size: 2.5rem;
    color: #fff;
    margin-right: 1rem;
}

.info-box-content {
    position: relative; /* Added to ensure content overlays on top of the background */
    z-index: 1; /* Ensure content is above the overlay */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.info-box-text {
    font-size: 1rem;
    font-weight: bold;
}

.info-box-number {
    font-size: 1.5rem;
    font-weight: bold;
}

.info-box-overlay {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 100px; /* Adjusted size for better visibility */
    height: auto; /* Auto height to maintain aspect ratio */
    opacity: 0.15; /* Slightly increased opacity for subtle effect */
    z-index: 0; /* Positioned behind the content */
}

.info-box-overlay img {
    width: 100%; /* Ensure the image takes the full width of the container */
    height: auto; /* Maintain aspect ratio */
}

.theme-option-wrapper small {
    color: #6c757d !important;
}

.theme-option-wrapper small {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* Global Search Styles */
.global-search-container {
    position: relative;
    min-width: 300px;
    margin-right: 1rem;
}

.global-search {
    border-radius: 20px;
    padding-left: 1rem;
    padding-right: 1rem;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.global-search:focus {
    box-shadow: 0 0 0 0.2rem rgba(142, 68, 173, 0.25);
    border-color: #8e44ad;
}

.search-results-container {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 0.5rem;
    z-index: 1050;
    max-height: 500px;
    overflow-y: auto;
}

.search-results-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.search-results-body {
    padding: 0.5rem 0;
}

.search-results-footer {
    padding: 0.5rem 1rem;
    border-top: 1px solid #dee2e6;
    text-align: center;
}

.search-result-item {
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    transition: background-color 0.2s ease;
    text-decoration: none;
    color: inherit;
}

.search-result-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: inherit;
}

.search-result-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #8e44ad;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.search-result-content {
    flex: 1;
}

.search-result-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.search-result-subtitle {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.search-result-description {
    font-size: 0.875rem;
    color: #495057;
}

.search-result-type {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    background-color: #e9ecef;
    color: #495057;
    margin-left: 0.5rem;
}

/* Leave Request specific styles */
.search-result-item .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    margin-left: 0.5rem;
}

.search-result-item .badge-success {
    background-color: #28a745;
    color: white;
}

.search-result-item .badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.search-result-item .badge-danger {
    background-color: #dc3545;
    color: white;
}

.search-result-item .badge-secondary {
    background-color: #6c757d;
    color: white;
}

.search-result-meta i {
    margin-right: 0.25rem;
}

/* User Dropdown - Updated Styling */
.user-menu .dropdown-menu {
    padding: 0;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border-radius: 8px;
    min-width: 250px;
    margin-top: 0.5rem;
}

.user-header {
    background: linear-gradient(-45deg, #8e44ad, #9b59b6, #2ecc71, #3498db);
    background-size: 400% 400%;
    animation: gradient 15s ease infinite;
    padding: 1rem;
    border-radius: 8px 8px 0 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.user-header::before,
.user-header::after {
    content: '';
    position: absolute;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s infinite;
}

.user-header::before {
    left: 10%;
    animation-delay: -2s;
}

.user-header::after {
    right: 10%;
    animation-delay: -4s;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) scale(1);
        opacity: 0.8;
    }
    50% {
        transform: translateY(-20px) scale(1.2);
        opacity: 0.3;
    }
}

.user-header .img-circle {
    width: 60px;
    height: 60px;
    border: 2px solid rgba(255,255,255,0.9);
    padding: 0;
    margin-bottom: 0.5rem;
}

.user-header .user-info {
    color: #fff;
    text-align: center;
}

.user-header .user-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #fff;
}

.user-header .user-role {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.9);
    background: rgba(0,0,0,0.1);
    padding: 0.2rem 0.8rem;
    border-radius: 12px;
    display: inline-block;
}

.dropdown-menu-content {
    padding: 0.5rem 0;
}

.user-menu .dropdown-item {
    padding: 0.6rem 1.2rem;
    display: flex;
    align-items: center;
    color: #444;
    transition: all 0.2s ease;
}

.user-menu .dropdown-item:hover {
    background-color: #f8f9fa;
    color: #8e44ad;
}

.user-menu .dropdown-item i {
    width: 1.2rem;
    margin-right: 0.8rem;
    font-size: 1rem;
    color: #666;
}

.user-menu .dropdown-item:hover i {
    color: #8e44ad;
}

.user-menu .dropdown-divider {
    margin: 0.25rem 0;
    border-top: 1px solid #f1f1f1;
}

.user-menu .logout-item {
    color: #dc3545;
}

.user-menu .logout-item:hover {
    background-color: #fff5f5;
    color: #dc3545;
}

.user-menu .logout-item i {
    color: #dc3545;
}

.user-status {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #28a745;
    margin-right: 0.5rem;
    box-shadow: 0 0 0 2px rgba(255,255,255,0.8);
}

.user-role {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background-color: rgba(255,255,255,0.1);
    border-radius: 12px;
    font-size: 0.75rem;
    color: #fff;
    margin-top: 0.5rem;
}

.toast {
    background-color: #28a745 !important; /* Success green background */
    color: #ffffff !important; /* White text */
    border: none !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    opacity: 1 !important;
}

.toast-header {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 8px 8px 0 0 !important;
    padding: 0.75rem 1rem !important;
}

.toast-header strong {
    color: #ffffff !important;
}

.toast-header .close {
    color: #ffffff !important;
    text-shadow: none !important;
    opacity: 0.8 !important;
}

.toast-header .close:hover {
    opacity: 1 !important;
}

.toast-body {
    color: #ffffff !important;
    padding: 1rem !important;
}

.toast-icon {
    color: #ffffff !important;
}

.toast-progress {
    height: 3px !important;
    background: rgba(255, 255, 255, 0.3) !important;
    width: 100% !important;
    position: absolute !important;
    bottom: 0 !important;
    left: 0 !important;
    border-radius: 0 0 8px 8px !important;
    animation: toast-progress 8s linear forwards !important;
}

/* Toast container positioning */
.toast-container {
    position: fixed !important;
    bottom: 20px !important;
    right: 20px !important;
    z-index: 9999 !important;
    min-width: 350px !important;
    max-width: 400px !important;
}

/* Toast animation */
@keyframes toastFadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.toast.show {
    animation: toastFadeIn 0.3s ease-out !important;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .toast-container {
        padding: 0.5rem !important;
        min-width: auto !important;
        width: calc(100% - 2rem) !important;
        right: 1rem !important;
        left: 1rem !important;
    }
    
    .toast {
        margin: 0.5rem !important;
        font-size: 0.9rem !important;
    }
}

.navbar-badge {
    font-size: 0.6rem;
    padding: 2px 4px;
    right: 5px;
    top: 9px;
}

#notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.dropdown-item:active {
    background-color: #e9ecef;
    color: #1e2125;
}

/* Enhanced Notification Styles */
.notification-scroll {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #8e44ad #f1f1f1;
}

.notification-scroll::-webkit-scrollbar {
    width: 6px;
}

.notification-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-scroll::-webkit-scrollbar-thumb {
    background: #8e44ad;
    border-radius: 3px;
}

#notification-list {
    width: 350px;
    padding: 0;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
}

#notification-list .dropdown-header {
    border-radius: 0.5rem 0.5rem 0 0;
}

.notification-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.2s ease;
    cursor: pointer;
    display: flex;
    align-items: flex-start;
    text-decoration: none;
    color: inherit;
}

.notification-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: inherit;
}

.notification-item.unread {
    background-color: #f0f4ff;
}

.notification-item.unread:hover {
    background-color: #e5ebff;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.notification-icon.leave {
    background-color: #8e44ad;
    color: white;
}

.notification-icon.cash-advance {
    background-color: #2ecc71;
    color: white;
}

.notification-content {
    flex-grow: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.notification-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.notification-time {
    font-size: 0.75rem;
    color: #adb5bd;
}

.empty-notifications {
    padding: 2rem;
    text-align: center;
    color: #6c757d;
}

.empty-notifications i {
    font-size: 2.5rem;
    color: #adb5bd;
    margin-bottom: 1rem;
}

.dropdown-footer {
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 0.5rem 0.5rem;
}

/* Select2 AdminLTE Compatibility Styles */
.select2-container--bootstrap4 .select2-selection {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.select2-container--bootstrap4.select2-container--focus .select2-selection {
    border-color: #ced4da; /* Changed from #80bdff */
    box-shadow: none; /* Removed blue box shadow */
}

.select2-container--bootstrap4 .select2-selection--single {
    height: calc(2.25rem + 2px) !important;
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
    color: #6c757d;
    line-height: 2.25rem;
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
    position: absolute;
    top: 50%;
    right: 3px;
    width: 20px;
    transform: translateY(-50%);
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
    top: 60%;
    border-color: #6c757d transparent transparent transparent;
    border-style: solid;
    border-width: 5px 4px 0 4px;
    width: 0;
    height: 0;
    left: 50%;
    margin-left: -4px;
    margin-top: -2px;
    position: absolute;
}

.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: 2.25rem;
}

.select2-container--bootstrap4 .select2-selection--multiple {
    min-height: calc(2.25rem + 2px) !important;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
    margin: 0;
    padding: 0;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-search--inline .select2-search__field {
    margin-top: 7px;
    line-height: 1.5;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: none;
    color: #fff;
    padding: 0 8px;
    margin-top: 6px;
    line-height: 1.5;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 4px;
}

.select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #fff;
    opacity: 0.8;
}

.select2-container--bootstrap4 .select2-dropdown {
    border-color: #ced4da;
    border-radius: 0.25rem;
}

.select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
    background-color: #e9ecef !important; /* Changed from #007bff */
    color: #1e2125 !important; /* Changed from #fff */
}

.select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
    background-color: #e9ecef;
    color: #1e2125;
}

/* Dark theme compatibility */
.dark-mode .select2-container--bootstrap4 .select2-selection {
    background-color: #343a40;
    border-color: #6c757d;
    color: #fff;
}

.dark-mode .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    color: #fff;
}

.dark-mode .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
    color: #adb5bd;
}

.dark-mode .select2-container--bootstrap4 .select2-dropdown {
    background-color: #343a40;
    border-color: #6c757d;
}

.dark-mode .select2-container--bootstrap4 .select2-results__option {
    color: #fff;
}

.dark-mode .select2-container--bootstrap4 .select2-results__option[aria-selected=true] {
    background-color: #495057;
    color: #fff;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    #notification-list {
        position: fixed !important; /* Force position */
        top: 56px !important; /* Height of navbar */
        left: 0 !important;
        right: 0 !important;
        width: 100% !important; /* Full width on mobile */
        margin: 0 !important;
        border-radius: 0 !important;
        max-height: calc(100vh - 56px); /* Viewport height minus navbar */
    }

    .notification-scroll {
        max-height: calc(100vh - 160px); /* Adjust for header and footer */
    }

    .dropdown-header, .dropdown-footer {
        position: sticky; /* Keep header/footer visible */
        background: inherit;
        z-index: 1;
    }

    .dropdown-header {
        top: 0;
    }

    .dropdown-footer {
        bottom: 0;
        border-top: 1px solid #dee2e6;
    }

    .notification-item {
        padding: 1rem !important;
    }

    .notification-icon {
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
    }

    .notification-content {
        width: 100%;
    }
}

/* Medium devices */
@media (min-width: 577px) and (max-width: 992px) {
    #notification-list {
        width: 320px;
    }
}

/* Ensure dropdown stays within viewport */
@media (min-width: 993px) {
    .dropdown-menu-xl {
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
}

/* Animation for dropdown */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-menu.show {
    animation: slideIn 0.2s ease-out;
}

/* Improve touch interaction on mobile */
@media (hover: none) and (pointer: coarse) {
    .notification-item {
        cursor: pointer;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }

    .notification-item:active {
        background-color: #f8f9fa;
    }
}

/* Notification Styles */
.notifications-menu {
    width: 300px;
    padding: 0;
}

.notifications-list {
    max-height: 400px;
    overflow-y: auto;
}

.dropdown-header {
    padding: 0.5rem 1rem;
    font-weight: bold;
    background-color: #f8f9fa;
}

.notification-item {
    padding: 0.5rem 1rem;
    border-bottom: 1px solid #dee2e6;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-count {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 0.75rem;
}

/* Dark mode support */
.dark-mode .notifications-menu {
    background-color: #343a40;
    color: #fff;
}

.dark-mode .dropdown-header {
    background-color: #2c3136;
    color: #fff;
}

.dark-mode .notification-item:hover {
    background-color: #3f474e;
}

/* Replace the existing toast styles with these enhanced styles */
.toast-container {
    position: fixed !important;
    top: 20px !important; /* Changed from bottom to top */
    right: 20px !important;
    z-index: 9999 !important;
    min-width: 350px !important;
    max-width: 400px !important;
}

.toast {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    opacity: 1 !important;
    overflow: hidden !important;
    margin-bottom: 1rem !important;
}

.toast-header {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    padding: 0.8rem 1rem !important;
    position: relative !important;
}

.toast-header strong {
    color: #ffffff !important;
    font-size: 1.1rem !important;
    font-weight: 600 !important;
}

.toast-header .close {
    color: #ffffff !important;
    text-shadow: none !important;
    opacity: 0.8 !important;
    font-size: 1.2rem !important;
    padding: 0.5rem !important;
    margin: -0.5rem -0.5rem -0.5rem auto !important;
}

.toast-header .close:hover {
    opacity: 1 !important;
}

.toast-body {
    padding: 1rem !important;
    font-size: 0.95rem !important;
    line-height: 1.5 !important;
}

.toast-progress {
    position: absolute !important;
    bottom: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 4px !important;
    background: rgba(255, 255, 255, 0.3) !important;
    z-index: 1 !important;
}

.toast-progress::after {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    height: 100% !important;
    width: 100% !important;
    background: rgba(255, 255, 255, 0.7) !important;
    animation: progress 5s linear forwards !important;
}

@keyframes progress {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.toast.showing {
    animation: slideInRight 0.3s ease-out !important;
}

.toast.hide {
    animation: slideOutRight 0.3s ease-out !important;
}

/* Toast variations */
.toast.success {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
}

.toast.warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
}

.toast.error {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.toast.info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .toast-container {
        top: 10px !important;
        right: 10px !important;
        left: 10px !important;
        min-width: auto !important;
    }
    
    .toast {
        margin: 0 0 0.5rem 0 !important;
        font-size: 0.9rem !important;
    }
}

/* Toast Container Styles */
.toast-container {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    z-index: 9999 !important;
    min-width: 350px !important;
    max-width: 400px !important;
    pointer-events: none; /* Allows clicking through the container */
}

.toast {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    opacity: 1 !important;
    overflow: hidden !important;
    margin-bottom: 1rem !important;
    pointer-events: auto; /* Re-enable pointer events for individual toasts */
    max-height: 200px !important; /* Prevent oversized toasts */
    transition: all 0.3s ease !important;
}

/* Toast Types */
.toast.success {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
}

.toast.error {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.toast.warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
}

.toast.info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

/* Toast Components */
.toast-header {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    padding: 0.8rem 1rem !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

.toast-body {
    padding: 1rem !important;
    font-size: 0.95rem !important;
    line-height: 1.5 !important;
    word-wrap: break-word !important;
    max-height: 150px !important;
    overflow-y: auto !important;
}

/* Progress Bar */
.toast-progress {
    position: absolute !important;
    bottom: 0 !important;
    left: 0 !important;
    width: 0 !important; /* Start at 0 width */
    height: 4px !important;
    background: rgba(255, 255, 255, 0.3) !important;
    transition: width linear !important;
}

/* Quick Actions Styling */
.quick-action-item {
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.quick-action-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
}

.quick-action-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
}

.quick-action-item .media {
    align-items: center;
}

.quick-action-item .dropdown-item-title {
    font-size: 1rem;
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
}

.quick-action-item .text-sm {
    color: #6c757d;
    margin: 0;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .quick-action-item .media {
        flex-direction: column;
        text-align: center;
    }

    .quick-action-icon {
        margin: 0 auto 0.5rem;
    }

    .quick-action-item .text-sm {
        display: none;
    }
}

/* Dark Mode Support */
.dark-mode .quick-action-item:hover {
    background-color: #343a40;
}

.dark-mode .quick-action-item .dropdown-item-title {
    color: #fff;
}

.dark-mode .quick-action-item .text-sm {
    color: #adb5bd;
}

/* Animation */
.dropdown-menu.show {
    animation: slideIn 0.2s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Add these styles in the existing <style> section */
.quick-actions-fab {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1040;
}

.quick-actions-button {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    /* Remove hardcoded background color - will be set by JS */
}

.quick-actions-button:hover {
    background-color: #732d91;
    transform: scale(1.1);
}

.quick-actions-button i {
    font-size: 24px;
    transition: transform 0.3s ease;
}

.quick-actions-button.active i {
    transform: rotate(45deg);
}

.quick-actions-card {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 300px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(20px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1039;
}

.quick-actions-card.show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.quick-actions-header {
    padding: 15px;
    color: white;
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: center;
    /* Remove hardcoded background color - will be set by JS */
}

.quick-actions-header i {
    margin-right: 10px;
}

.quick-actions-content {
    padding: 15px;
}

.quick-action-item {
    display: flex;
    align-items: center;
    padding: 12px;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease;
    border-radius: 6px;
    margin-bottom: 8px;
}

.quick-action-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
    text-decoration: none;
}

.quick-action-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: white;
}

.quick-action-text {
    flex: 1;
}

.quick-action-title {
    font-weight: 600;
    margin-bottom: 2px;
    color: #333;
}

.quick-action-description {
    font-size: 0.8rem;
    color: #666;
}

/* Theme Customization Styles */
.theme-option-wrapper {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.theme-option-wrapper label {
    color: #fff;
    font-weight: 600;
    margin-bottom: 1rem;
    display: block;
}

.theme-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.theme-option {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.theme-option:hover {
    transform: scale(1.05);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.theme-option.active {
    border-color: #fff;
}

.theme-option.active::after {
    content: '\2714';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 1.2rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

/* Theme Colors */
.theme-primary { background: #007bff; }
.theme-secondary { background: #6c757d; }
.theme-success { background: #28a745; }
.theme-danger { background: #dc3545; }
.theme-warning { background: #ffc107; }
.theme-info { background: #17a2b8; }
.theme-dark { background: #343a40; }
.theme-purple { background: #6f42c1; }
.theme-indigo { background: #6610f2; }
.theme-pink { background: #e83e8c; }

/* ... existing code ... */

/* Enhanced Quick Actions Button Styles */
.quick-actions-button {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quick-actions-button i {
    font-size: 24px;
    color: #ffffff;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.quick-actions-button.active i {
    transform: rotate(45deg);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .quick-actions-button {
        width: 48px;
        height: 48px;
    }

    .quick-actions-button i {
        font-size: 20px;
    }
}

@media (max-width: 576px) {
    .quick-actions-fab {
        bottom: 15px;
        right: 15px;
    }

    .quick-actions-button {
        width: 42px;
        height: 42px;
    }

    .quick-actions-button i {
        font-size: 18px;
    }
}

/* Add hover effect for better visibility */
.quick-actions-button:hover i {
    transform: scale(1.1);
}

.quick-actions-button.active:hover i {
    transform: rotate(45deg) scale(1.1);
}

/* Ensure icon visibility on light backgrounds */
.quick-actions-button i {
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
}
    </style>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    @stack('styles')

    <!-- Initialize Select2 -->
    <script>
        window.addEventListener('load', function() {
            if (typeof jQuery !== 'undefined') {
                // Initialize Select2 with custom configuration
                $('select').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Select an option',
                    allowClear: true,
                    containerCssClass: ':all:',
                    dropdownCssClass: function() {
                        // Check if dark mode is active
                        return document.body.classList.contains('dark-mode') ? 'select2-dropdown-dark' : '';
                    }
                });

                // Update Select2 dropdown theme when switching between light/dark mode
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            $('select').each(function() {
                                $(this).select2('destroy');
                                $(this).select2({
                                    theme: 'bootstrap4',
                                    width: '100%',
                                    dropdownAutoWidth: true,
                                    placeholder: 'Select an option',
                                    allowClear: true,
                                    containerCssClass: ':all:',
                                    dropdownCssClass: document.body.classList.contains('dark-mode') ? 'select2-dropdown-dark' : ''
                                });
                            });
                        }
                    });
                });

                observer.observe(document.body, {
                    attributes: true
                });
            }
        });
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="app-env" content="{{ config('app.env') }}">

    <!-- Add this in the head section after other CSS links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/10.0.1/css/shepherd.css"/>

    <!-- Add SweetAlert2 CSS and JS in the head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Preloader -->
    <div id="loader" class="loader">
        <div class="loader-content">
            <div class="mhr-loader">
                <div class="spinner"></div>
                <div class="mhr-text">MHR</div>
            </div>
            <h4 class="mt-4 text-dark">Loading...</h4>
        </div>
    </div>

    <!-- Account Switch Toast Container -->
    <div class="toast-container">
        @if(session('toast'))
            <div class="toast account-switch-toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                <div class="toast-header bg-primary text-white">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    <strong class="mr-auto">{{ session('toast')['title'] }}</strong>
                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Switched from <strong>{{ session('toast')['from'] }}</strong> to <strong>{{ session('toast')['to'] }}</strong>
                </div>
                <div class="toast-progress"></div>
            </div>
        @endif
    </div>

    <div class="wrapper">

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3 control-sidebar-content">
                <h5 class="text-light mb-3">Customize Theme</h5>
                <hr class="mb-2">
                <div class="theme-option-wrapper">
                    <label>Select Theme Color</label>
                    <div class="theme-options">
                        <div class="theme-option bg-primary" data-theme="primary"></div>
                        <div class="theme-option bg-secondary" data-theme="secondary"></div>
                        <div class="theme-option bg-info" data-theme="info"></div>
                        <div class="theme-option bg-success" data-theme="success"></div>
                        <div class="theme-option bg-danger" data-theme="danger"></div>
                        <div class="theme-option bg-indigo" data-theme="indigo"></div>
                        <div class="theme-option bg-purple" data-theme="purple"></div>
                        <div class="theme-option bg-pink" data-theme="pink"></div>
                        <div class="theme-option bg-dark" data-theme="dark"></div>
                    </div>
                </div>
                 <!-- New navbar position options -->
                <div class="navbar-position-wrapper mb-4">
                    <label for="navbar-position-select" class="d-block mb-2">Navbar Position</label>
                    <select id="navbar-position-select" class="form-control bg-dark text-light border-secondary">
                        <option value="static">Static (Default)</option>
                        <option value="fixed">Fixed Top</option>
                        <option value="sticky">Sticky Top</option>
                    </select>
                </div>
            </div>
        </aside>
        <!-- /.control-sidebar -->

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
                 @if(!auth()->user()->hasRole('Employee'))
                <li class="nav-item">
                    <button id="startTour" class="nav-link btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Start App Tour">
                        <i class="fas fa-route"></i>
                        <span class="d-none d-md-inline ml-1">Tour Guide</span>
                    </button>
                </li>
                @endif

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
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" id="notifications-dropdown">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge" id="notifications-count">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification-list">
                        <span class="dropdown-header">
                            <i class="fas fa-bell mr-2"></i>Notifications
                            <span class="float-right">
                                <a href="#" class="text-sm mark-all-read">Mark all as read</a>
                            </span>
                        </span>
                        <div class="dropdown-divider"></div>
                        <div class="notification-scroll" id="notifications-container">
                            <!-- Notifications will be dynamically inserted here -->
                            <div class="empty-notifications text-center p-3">
                                <i class="fas fa-bell-slash mb-3"></i>
                                <p class="text-muted">No new notifications</p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('notifications.all') }}" class="dropdown-footer dropdown-item text-center">
                            See All Notifications
                        </a>
                    </div>
                </li>
            
                @canany(['admin', 'super-admin', 'hrcomben', 'hrcompliance', 'hrpolicy'])
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-bullhorn"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @canany(['super-admin'])
                        <a href="{{ url('types') }}" class="dropdown-item">
                            <i class="fas fa-folder mr-2"></i> Leave Type
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcompliance', 'hrpolicy'])
                        <a href="{{ url('posts') }}" class="dropdown-item">
                            <i class="fas fa-bullhorn mr-2"></i> Announcement
                        </a>
                        @endcanany
                        @can('admin')
                        <a href="{{ url('tasks') }}" class="dropdown-item">
                            <i class="fas fa-tasks mr-2"></i> Send Task
                        </a>
                        @endcan
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

                @canany(['admin', 'super-admin', 'supervisor'])
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-users"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @canany(['admin', 'super-admin'])
                        <a href="{{ url('users') }}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> User Management
                        </a>
                        @endcanany
                        @if(auth()->user()->hasRole('Supervisor'))
                        <a href="{{ route('activity-logs.index') }}" class="dropdown-item">
                            <i class="fas fa-history mr-2"></i> Departmental User Activity
                        </a>
                        @endif
                        @can('super-admin')
                        <a href="{{ url('/user-activity') }}" class="dropdown-item">
                            <i class="fas fa-history mr-2"></i> User General Logs
                        </a>
                        @endcan
                        
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
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
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
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                @endguest
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/home') }}" class="brand-link">
                <img src="{{ asset('vendor/adminlte/dist/img/whiteICON_APP.png') }}" alt="Task List Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">MHRPCI-HRIS</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('/home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @canany(['admin', 'super-admin', 'hrcompliance','finance'])
                        <li class="nav-item">
                            <a href="{{ url('/employees') }}" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Employee Management</p>
                            </a>
                        </li>
                        @endcanany

                        @auth
                            @if(auth()->user()->hasRole('Employee'))
                        <li class="nav-item">
                            <a href="{{ url('/my-tasks') }}" class="nav-link {{ Request::is('my-tasks') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>My Task</p>
                            </a>
                        </li>
                        @endif
                      @endauth
                        @canany(['admin', 'super-admin', 'hrcomben', 'normal-employee','supervisor'])
                        <li class="nav-item has-treeview {{ Request::is('attendances*', 'timesheets*', 'my-timesheet', 'attendance') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('attendances*', 'timesheets*', 'my-timesheet', 'attendance') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>
                                    Attendance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                            @canany(['admin', 'super-admin', 'hrcomben','supervisor'])
                                <li class="nav-item">
                                    <a href="{{ url('/attendances') }}" class="nav-link {{ Request::is('attendances*') || Request::is('timesheets*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list nav-icon"></i>
                                        <p>Attendance</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                <li class="nav-item">
                                    <a href="{{ route('attendances.attendance') }}" class="nav-link {{ Request::is('attendance') ? 'active' : '' }}">
                                        <i class="fas fa-clock nav-icon"></i>
                                        <p>Clock In/Out</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/my-timesheet') }}" class="nav-link {{ Request::is('my-timesheet') ? 'active' : '' }}">
                                        <i class="fas fa-user-clock nav-icon"></i>
                                        <p>My Timesheet</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </li>
                    @endcanany
                    @canany(['admin', 'super-admin', 'hrcomben','normal-employee','supervisor'])
                    <li class="nav-item has-treeview {{ Request::is('leaves*') || Request::is('leaves-employees*') || Request::is('my-leave-sheet*') || Request::is('my-leave-detail*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('leaves*') || Request::is('leaves-employees*') || Request::is('my-leave-sheet*') || Request::is('my-leave-detail*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>
                                    Leave Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin', 'hrcomben','supervisor'])
                                <li class="nav-item">
                                    <a href="{{ url('/leaves') }}" class="nav-link {{ Request::is('leaves') || request()->routeIs('leaves.show*') ? 'active' : '' }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Leave List</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                <li class="nav-item">
                                    <a href="{{ url('/leaves/create') }}" class="nav-link {{ Request::is('leaves/create') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check nav-icon"></i>
                                        <p>Apply Leave</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                                @canany(['admin', 'super-admin', 'hrcomben'])
                                <li class="nav-item">
                                    <a href="{{ url('/leaves-employees') }}" class="nav-link {{ Request::is('leaves-employees*') ? 'active' : '' }}">
                                        <i class="fas fa-file nav-icon"></i>
                                        <p>Leave Sheet</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                <li class="nav-item">
                                    <a href="{{ route('leaves.my_leave_sheet') }}" class="nav-link {{ request()->routeIs('leaves.my_leave_sheet') || request()->routeIs('leaves.myLeaveDetail') ? 'active' : '' }}">
                                        <i class="fas fa-print nav-icon"></i>
                                        <p>My Leaves</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </li>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcomben','finance','normal-employee'])
                        <li class="nav-item has-treeview {{ Request::is('payroll*', 'overtime*', 'my-payrolls*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('payroll*', 'overtime*', 'my-payrolls*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>
                                    Payroll Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin','hrcomben','finance'])
                                <li class="nav-item">
                                    <a href="{{ url('/payroll') }}" class="nav-link {{ Request::is('payroll*', 'overtime*') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-wave nav-icon"></i>
                                        <p>Payroll</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee'))
                                <li class="nav-item">
                                    <a href="{{ url('/my-payrolls') }}" class="nav-link {{ Request::is('my-payrolls*') ? 'active' : '' }}">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>My Payroll</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </li>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcomben', 'finance', 'normal-employee', 'supervisor'])
                        <li class="nav-item has-treeview {{ Request::is('sss*', 'philhealth*', 'pagibig*', 'loan_sss*','loan_pagibig*', 'cash_advances*', 'my-contributions*', 'my-loans*', 'contributions-employees-list*', 'loans-employees-list*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('sss*', 'philhealth*', 'pagibig*', 'loan_sss*', 'loan_pagibig*', 'cash_advances*', 'my-contributions*', 'my-loans*', 'contributions-employees-list*', 'loans-employees-list*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hands-helping"></i>
                                <p>
                                    Loans & Contributions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin', 'hrcomben', 'finance'])
                                <li class="nav-item">
                                    <a href="{{ url('/sss') }}" class="nav-link {{ Request::is('sss*', 'philhealth*', 'pagibig*','contributions-employees-list') ? 'active' : '' }}">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>Contributions</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/loan_sss') }}" class="nav-link {{ Request::is('loan_sss*','loan_pagibig*', 'cash_advances*', 'loans-employees-list*') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-alt nav-icon"></i>
                                        <p>Loans</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                    <li class="nav-item">
                                        <a href="{{ route('cash_advances.create') }}" class="nav-link {{ Request::is('cash_advances/create') ? 'active' : '' }}">
                                            <i class="fas fa-money-bill-wave nav-icon"></i>
                                            <p>Apply Company Loan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/my-contributions') }}" class="nav-link {{ Request::is('my-contributions*') ? 'active' : '' }}">
                                            <i class="fas fa-solid fa-gift nav-icon"></i>
                                            <p>My Contribution</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/my-loans') }}" class="nav-link {{ Request::is('my-loans*') || Request::is('cash_advances/*/ledger') ? 'active' : '' }}">
                                            <i class="fas fa-hand-holding-usd nav-icon"></i>
                                            <p>My Loan</p>
                                        </a>
                                    </li>
                                    @endif
                                @endauth
                            </ul>
                        </li>
                        @endcanany
                        @can('hrhiring')
                        <li class="nav-item">
                            <a href="{{ url('/hirings') }}" class="nav-link {{ Request::is('hirings*', 'all-careers*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Hiring Management</p>
                            </a>
                        </li>
                        @endcan

                        @canany(['admin', 'super-admin', 'hrcompliance', 'it-staff', 'hrpolicy'])
                        <li class="nav-item has-treeview {{ Request::is('accountabilities*', 'credentials*', 'inventory*', 'properties*', 'subsidiaries*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('accountabilities*', 'credentials*', 'inventory*', 'properties*', 'subsidiaries*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Others
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('hrcompliance')
                                <li class="nav-item">
                                    <a href="{{ url('/accountabilities') }}" class="nav-link {{ Request::is('accountabilities*') ? 'active' : '' }}">
                                        <i class="fas fa-check-circle nav-icon"></i>
                                        <p>Employee Accountability</p>
                                    </a>
                                </li>
                                @endcan
                                @can('hrpolicy')
                                <li class="nav-item">
                                    <a href="{{ url('/credentials') }}" class="nav-link {{ Request::is('credentials*') ? 'active' : '' }}">
                                        <i class="fas fa-phone nav-icon"></i>
                                        <p>Contacts and Emails</p>
                                    </a>
                                </li>
                                @endcan
                                @canany(['hrcompliance', 'it-staff'])
                                <li class="nav-item">
                                    <a href="{{ url('/inventory') }}" class="nav-link {{ Request::is('inventory*') ? 'active' : '' }}">
                                        <i class="fas fa-cubes nav-icon"></i>
                                        <p>Inventory</p>
                                    </a>
                                </li>
                                @endcanany
                            </ul>
                        </li>
                        @endcanany
                        @auth
                            @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                        <li class="nav-item">
                            <a href="{{ url('/my-profile') }}" class="nav-link {{ Request::is('my-profile*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>My Profile</p>
                            </a>
                        </li>
                        @endif
                        @endauth
                        <li class="nav-item">
                            <a href="{{ url('/birthdays') }}" class="nav-link {{ Request::is('birthdays*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-birthday-cake"></i>
                                <p>Birthdays</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('holidays.calendar') }}" class="nav-link {{ Request::is('holidays-calendar') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>MHR Calendar</p>
                            </a>
                        </li>
                        @canany(['product-manager', 'super-admin'])
                        <li class="nav-item">
                            <a href="{{ route('analytics.dashboard') }}" class="nav-link {{ Request::is('analytics*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pills"></i>
                                <p>MHRHCI Management</p>
                            </a>
                        </li>
                        @endcanany
                        @can('super-admin')
                        <li class="nav-item">
                            <a href="{{ url('/reports') }}" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('controller.analysis') }}" class="nav-link {{ request()->routeIs('controller.analysis*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sitemap"></i>
                                <p>System Routes Reports</p>
                            </a>
                        </li>
                    </ul>
                    @endcanany
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0"></h1>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/home') }}">MHR Property Conglomerates, Inc</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

    @stack('scripts')

    <script>
        $(document).ready(function() {
            // Preloader
            $(window).on('load', function() {
                $('#loader').fadeOut('slow', function() {
                    $(this).remove();
                });
            });

            // If the page takes too long to load, hide the preloader after 1 second
            setTimeout(function() {
                $('#loader').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 1000);

            // Theme customization
            function applyTheme(navbarClass, sidebarClass, brandClass) {
                // Apply navbar theme
                $('.main-header').attr('class', 'main-header navbar navbar-expand ' + navbarClass);

                // Apply sidebar theme
                $('.main-sidebar').attr('class', 'main-sidebar ' + sidebarClass);

                // Apply brand theme
                $('.brand-link').attr('class', 'brand-link ' + brandClass);

                // Update active links color in sidebar
                $('.nav-sidebar .nav-link.active').css('background-color', getComputedStyle(document.documentElement).getPropertyValue('--' + sidebarClass.split('-')[2] + '-color'));

                // Update navbar text and icon colors
                updateNavbarColors(navbarClass);

                // Save theme preferences
                localStorage.setItem('navbarVariant', navbarClass);
                localStorage.setItem('sidebarVariant', sidebarClass);
                localStorage.setItem('brandVariant', brandClass);

                // Update select values
                $('#theme-select').val(navbarClass.split('-')[1]);
            }

            // Function to update navbar text and icon colors
            function updateNavbarColors(navbarClass) {
                var isDark = navbarClass.includes('navbar-dark');
                var textColor = isDark ? '#ffffff' : '#000000';
                var iconColor = isDark ? '#ffffff' : '#000000';

                $('.main-header .nav-link').css('color', textColor);
                $('.main-header .nav-link i').css('color', iconColor);

                // Adjust dropdown text colors
                $('.main-header .dropdown-menu a').css('color', '#212529');

                // Adjust navbar brand text color
                $('.main-header .navbar-brand').css('color', textColor);
            }

            // Theme change event handler
            $('.theme-option').on('click', function() {
                var selectedTheme = $(this).data('theme');
                var navbarClass = 'navbar-' + selectedTheme + ' ' + (isLightColor(selectedTheme) ? 'navbar-light' : 'navbar-dark');
                var sidebarClass = 'sidebar-dark-' + selectedTheme;
                var brandClass = 'bg-' + selectedTheme;

                applyTheme(navbarClass, sidebarClass, brandClass);

                // Update active state
                $('.theme-option').removeClass('active');
                $(this).addClass('active');
            });

            // Function to determine if a color is light
            function isLightColor(color) {
                var lightColors = ['light', 'warning', 'white', 'orange', 'lime', 'teal', 'cyan'];
                return lightColors.includes(color);
            }

            // Load saved theme
            function loadSavedTheme() {
                var navbarVariant = localStorage.getItem('navbarVariant') || 'navbar-dark navbar-primary';
                var sidebarVariant = localStorage.getItem('sidebarVariant') || 'sidebar-dark-primary';
                var brandVariant = localStorage.getItem('brandVariant') || 'bg-primary';

                applyTheme(navbarVariant, sidebarVariant, brandVariant);

                // Set active state on the correct theme option
                var activeTheme = navbarVariant.split('-')[2] || 'primary';
                $('.theme-option[data-theme="' + activeTheme + '"]').addClass('active');
            }

            // Call this function on page load
            loadSavedTheme();

            // Navbar Position Functionality
            function applyNavbarPosition(position) {
                const $body = $('body');
                const $navbar = $('.main-header');

                // Remove existing classes
                $body.removeClass('layout-navbar-fixed layout-navbar-not-fixed');
                $navbar.removeClass('fixed-top sticky-top');

                switch (position) {
                    case 'fixed':
                        $body.addClass('layout-navbar-fixed');
                        $navbar.addClass('fixed-top');
                        break;
                    case 'sticky':
                        $navbar.addClass('sticky-top');
                        break;
                    default: // 'static'
                        $body.addClass('layout-navbar-not-fixed');
                        break;
                }

                // Save preference
                localStorage.setItem('navbarPosition', position);
            }

            // Navbar position change event handler
            $('#navbar-position-select').on('change', function() {
                const selectedPosition = $(this).val();
                applyNavbarPosition(selectedPosition);
            });

            // Load saved navbar position
            function loadSavedNavbarPosition() {
                const savedPosition = localStorage.getItem('navbarPosition') || 'static';
                $('#navbar-position-select').val(savedPosition);
                applyNavbarPosition(savedPosition);
            }

            // Call this function on page load
            loadSavedNavbarPosition();

            // Notifications handling
            function loadNotifications() {
                $.ajax({
                    url: '/notifications/get',
                    method: 'GET',
                    success: function(response) {
                        updateNotifications(response);
                    },
                    error: function(xhr) {
                        console.error('Error loading notifications:', xhr);
                    }
                });
            }

            function updateNotifications(data) {
                $('.notification-count').text(data.count);
                $('.notifications-list').html(data.notifications);
                
                if (data.toast && data.toast.message) {
                    showToast(data.toast);
                }
            }

            function showToast(toastData) {
                const toast = `
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                        <div class="toast-header">
                            <i class="${toastData.icon} mr-2"></i>
                            <strong class="mr-auto">${toastData.title}</strong>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            ${toastData.message}
                        </div>
                        <div class="toast-progress"></div>
                    </div>
                `;

                // Create toast container if it doesn't exist
                if (!$('.toast-container').length) {
                    $('body').append('<div class="toast-container"></div>');
                }

                // Add toast to container and show it
                const $toast = $(toast);
                $('.toast-container').append($toast);
                $toast.toast('show');

                // Remove toast after it's hidden
                $toast.on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // Load notifications on page load
            loadNotifications();

            // Set up Echo to listen for new notifications
            window.Echo.channel('notifications')
                .listen('NewNotification', (e) => {
                    updateNotifications(e);
                });

            // No need for setInterval as we're using real-time updates
        });
    </script>

    @yield('js')

    <!-- Add this modal structure before the closing body tag -->
    <div class="modal fade" id="celebrantsModal" tabindex="-1" role="dialog" aria-labelledby="celebrantsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="celebrantsModalLabel">
                        <i class="fas fa-birthday-cake mr-2"></i>Today's Celebrants
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="celebrantsModalBody">
                    <!-- Celebrants will be loaded here -->
                </div>
                <div class="modal-footer">
                    <div class="form-check mr-auto">
                        <input type="checkbox" class="form-check-input" id="dontShowToday">
                        <label class="form-check-label" for="dontShowToday">Don't show this to me today</label>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this script before the closing body tag -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function checkAndShowCelebrants() {
            fetch('/api/today-celebrants', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.celebrants && data.celebrants.length > 0 && !data.userDismissed) {
                    // Update modal content
                    const modalBody = document.getElementById('celebrantsModalBody');
                    modalBody.innerHTML = data.celebrants.map(celebrant => `
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                ${celebrant.profile_picture ? 
                                    `<img src="${celebrant.profile_picture}" class="rounded-circle" width="50" height="50" alt="${celebrant.name}">` :
                                    `<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px">
                                        ${celebrant.name.split(' ').map(n => n[0]).join('')}
                                    </div>`
                                }
                            </div>
                            <div>
                                <h6 class="mb-0">${celebrant.name}</h6>
                                <small class="text-muted">${celebrant.department}</small>
                            </div>
                        </div>
                    `).join('');

                    // Show modal
                    $('#celebrantsModal').modal('show');
                }
            })
            .catch(error => {
                console.error('Error fetching celebrants:', error);
            });
        }

        // Handle checkbox change
        document.getElementById('dontShowToday').addEventListener('change', function(e) {
            if (e.target.checked) {
                fetch('/api/dismiss-celebrants', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .catch(error => {
                    console.error('Error dismissing celebrants:', error);
                });
            }
        });

        // Check for celebrants when page loads
        checkAndShowCelebrants();
    });
    </script>

    <!-- Remove the existing Quick Actions dropdown from the navbar -->

    <!-- Add this before the closing </body> tag -->
@canany(['admin', 'super-admin', 'hrcomben', 'hrcompliance', 'hrpolicy', 'normal-employee', 'supervisor', 'finance'])
    <div class="quick-actions-fab">
        <button class="quick-actions-button" id="quickActionsToggle" title="Quick Actions">
            <i class="fas fa-cog"></i>
        </button>
        
        <div class="quick-actions-card" id="quickActionsCard">
            <div class="quick-actions-header">
                <i class="fas fa-bolt"></i>
                <span>Quick Actions</span>
            </div>
        @if(Auth::user()->hasRole('Employee') || Auth::user()->hasRole('Supervisor'))
            <div class="quick-actions-content">
                <a href="{{ route('leaves.create') }}" class="quick-action-item">
                    <div class="quick-action-icon bg-success">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="quick-action-text">
                        <div class="quick-action-title">Apply Leave</div>
                        <div class="quick-action-description">Request time off work</div>
                    </div>
                </a>
                
                <a href="{{ route('cash_advances.create') }}" class="quick-action-item">
                    <div class="quick-action-icon bg-info">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="quick-action-text">
                        <div class="quick-action-title">Apply Company Loan</div>
                        <div class="quick-action-description">Request financial assistance</div>
                    </div>
                </a>
            @endif
                <a href="https://t.me/edmarcrescencio" target="_blank" class="quick-action-item" id="helpAction">
                    <div class="quick-action-icon bg-warning">
                        <i class="fab fa-telegram"></i>
                    </div>
                    <div class="quick-action-text">
                        <div class="quick-action-title">IT Support</div>
                        <div class="quick-action-description">Contact IT via Telegram</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endcanany
    <!-- Add this script before the closing </body> tag -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const quickActionsToggle = document.getElementById('quickActionsToggle');
        const quickActionsCard = document.getElementById('quickActionsCard');
        
        // Toggle quick actions card
        quickActionsToggle.addEventListener('click', function() {
            quickActionsCard.classList.toggle('show');
            this.classList.toggle('active');
        });
        
        // Close quick actions when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = quickActionsToggle.contains(event.target) || 
                                quickActionsCard.contains(event.target);
            
            if (!isClickInside && quickActionsCard.classList.contains('show')) {
                quickActionsCard.classList.remove('show');
                quickActionsToggle.classList.remove('active');
            }
        });
        
        // Prevent closing when clicking inside the card
        quickActionsCard.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
    </script>

    <!-- Theme Management System -->
    <script>
    const ThemeManager = {
        themes: {
            primary: {
                navbar: 'navbar-dark navbar-primary',
                sidebar: 'sidebar-dark-primary',
                brand: 'bg-primary'
            },
            secondary: {
                navbar: 'navbar-dark navbar-secondary',
                sidebar: 'sidebar-dark-secondary',
                brand: 'bg-secondary'
            },
            success: {
                navbar: 'navbar-dark navbar-success',
                sidebar: 'sidebar-dark-success',
                brand: 'bg-success'
            },
            danger: {
                navbar: 'navbar-dark navbar-danger',
                sidebar: 'sidebar-dark-danger',
                brand: 'bg-danger'
            },
            warning: {
                navbar: 'navbar-light navbar-warning',
                sidebar: 'sidebar-dark-warning',
                brand: 'bg-warning'
            },
            info: {
                navbar: 'navbar-dark navbar-info',
                sidebar: 'sidebar-dark-info',
                brand: 'bg-info'
            },
            dark: {
                navbar: 'navbar-dark navbar-dark',
                sidebar: 'sidebar-dark-dark',
                brand: 'bg-dark'
            },
            purple: {
                navbar: 'navbar-dark navbar-purple',
                sidebar: 'sidebar-dark-purple',
                brand: 'bg-purple'
            },
            indigo: {
                navbar: 'navbar-dark navbar-indigo',
                sidebar: 'sidebar-dark-indigo',
                brand: 'bg-indigo'
            },
            pink: {
                navbar: 'navbar-dark navbar-pink',
                sidebar: 'sidebar-dark-pink',
                brand: 'bg-pink'
            }
        },

        themeColors: {
            primary: '#007bff',
            secondary: '#6c757d',
            success: '#28a745',
            danger: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8',
            dark: '#343a40',
            purple: '#6f42c1',
            indigo: '#6610f2',
            pink: '#e83e8c'
        },

        init() {
            this.loadSavedTheme();
            this.bindEvents();
        },

        bindEvents() {
            $('.theme-option').on('click', (e) => {
                const theme = $(e.currentTarget).data('theme');
                this.applyTheme(theme);
                this.saveTheme(theme);
                this.updateActiveState(e.currentTarget);
            });
        },

        applyTheme(themeName) {
            const theme = this.themes[themeName];
            const themeColor = this.themeColors[themeName];
            if (!theme) return;

            // Existing theme applications
            $('.main-header')
                .removeClass(Object.values(this.themes).map(t => t.navbar).join(' '))
                .addClass(theme.navbar);

            $('.main-sidebar')
                .removeClass(Object.values(this.themes).map(t => t.sidebar).join(' '))
                .addClass(theme.sidebar);

            $('.brand-link')
                .removeClass(Object.values(this.themes).map(t => t.brand).join(' '))
                .addClass(theme.brand);

            // Apply theme to Quick Actions
            $('.quick-actions-button').css('background-color', themeColor);
            $('.quick-actions-header').css('background-color', themeColor);

            // Update hover effect for quick actions button
            const darkerColor = this.adjustColor(themeColor, -20); // Darken by 20%
            const style = document.createElement('style');
            style.textContent = `
                .quick-actions-button:hover {
                    background-color: ${darkerColor} !important;
                    transform: scale(1.1);
                }
            `;
            // Remove any previous dynamic styles
            document.querySelectorAll('style[data-theme-style]').forEach(el => el.remove());
            style.setAttribute('data-theme-style', 'true');
            document.head.appendChild(style);

            // Update navbar colors
            this.updateNavbarColors(theme.navbar);
        },

        updateNavbarColors(navbarClass) {
            const isDark = navbarClass.includes('navbar-dark');
            const textColor = isDark ? '#ffffff' : '#000000';
            
            $('.main-header .nav-link').css('color', textColor);
            $('.main-header .navbar-brand').css('color', textColor);
            
            // Preserve dropdown text colors
            $('.main-header .dropdown-menu a').css('color', '#212529');
        },

        saveTheme(theme) {
            localStorage.setItem('selectedTheme', theme);
        },

        loadSavedTheme() {
            const savedTheme = localStorage.getItem('selectedTheme') || 'primary';
            this.applyTheme(savedTheme);
            this.updateActiveState($(`.theme-option[data-theme="${savedTheme}"]`));
        },

        updateActiveState(element) {
            $('.theme-option').removeClass('active');
            $(element).addClass('active');
        },

        // Helper function to darken/lighten colors
        adjustColor(color, percent) {
            const num = parseInt(color.replace('#', ''), 16);
            const amt = Math.round(2.55 * percent);
            const R = (num >> 16) + amt;
            const G = (num >> 8 & 0x00FF) + amt;
            const B = (num & 0x0000FF) + amt;
            return '#' + (
                0x1000000 +
                (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 +
                (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 +
                (B < 255 ? (B < 1 ? 0 : B) : 255)
            ).toString(16).slice(1);
        }
    };

    // Initialize Theme Manager
    $(document).ready(() => {
        ThemeManager.init();
    });
    </script>

    <!-- Add this before closing body tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/10.0.1/js/shepherd.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltip
        $('#startTour').tooltip();

        // Configure tour
        const tour = new Shepherd.Tour({
            useModalOverlay: true,
            defaultStepOptions: {
                classes: 'shadow-md bg-purple-dark',
                scrollTo: true,
                cancelIcon: {
                    enabled: true
                }
            },
            tourName: 'app-tour'
        });

        // Define tour steps
        tour.addStep({
            id: 'welcome',
            text: `
                <div class="text-center">
                    <h3 class="font-weight-bold mb-3">Welcome to MHRPCI-HRIS! 👋</h3>
                    <p>Let's take a quick tour of the main features.</p>
                </div>
            `,
            buttons: [
                {
                    text: 'Skip Tour',
                    action: tour.complete,
                    classes: 'btn btn-secondary'
                },
                {
                    text: 'Start Tour',
                    action: tour.next,
                    classes: 'btn btn-primary'
                }
            ]
        });

        // Sidebar navigation
        tour.addStep({
            id: 'sidebar',
            text: 'The sidebar contains all main navigation items. Click items to access different sections.',
            attachTo: {
                element: '.main-sidebar',
                on: 'right'
            },
            buttons: [
                {
                    text: 'Back',
                    action: tour.back,
                    classes: 'btn btn-secondary'
                },
                {
                    text: 'Next',
                    action: tour.next,
                    classes: 'btn btn-primary'
                }
            ]
        });

        // Quick actions
        if (document.querySelector('.quick-actions-fab')) {
            tour.addStep({
                id: 'quick-actions',
                text: 'Access common actions quickly from this floating button.',
                attachTo: {
                    element: '.quick-actions-fab',
                    on: 'left'
                },
                buttons: [
                    {
                        text: 'Back',
                        action: tour.back,
                        classes: 'btn btn-secondary'
                    },
                    {
                        text: 'Next',
                        action: tour.next,
                        classes: 'btn btn-primary'
                    }
                ]
            });
        }

        // Notifications
        tour.addStep({
            id: 'notifications',
            text: 'Check your notifications here. The badge shows unread notifications.',
            attachTo: {
                element: '#notifications-dropdown',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Back',
                    action: tour.back,
                    classes: 'btn btn-secondary'
                },
                {
                    text: 'Next',
                    action: tour.next,
                    classes: 'btn btn-primary'
                }
            ]
        });

        // Theme customization
        tour.addStep({
            id: 'theme',
            text: 'Customize the app appearance using the theme settings.',
            attachTo: {
                element: '[data-widget="control-sidebar"]',
                on: 'left'
            },
            buttons: [
                {
                    text: 'Back',
                    action: tour.back,
                    classes: 'btn btn-secondary'
                },
                {
                    text: 'Next',
                    action: tour.next,
                    classes: 'btn btn-primary'
                }
            ]
        });

        // User menu
        tour.addStep({
            id: 'user-menu',
            text: 'Access your profile and account settings here.',
            attachTo: {
                element: '.user-menu',
                on: 'bottom'
            },
            buttons: [
                {
                    text: 'Back',
                    action: tour.back,
                    classes: 'btn btn-secondary'
                },
                {
                    text: 'Finish',
                    action: tour.complete,
                    classes: 'btn btn-primary'
                }
            ]
        });

        // Handle tour button click
        document.getElementById('startTour').addEventListener('click', function() {
            // Check if tour was completed before
            const tourCompleted = localStorage.getItem('tourCompleted');
            
            if (!tourCompleted) {
                tour.start();
            } else {
                // Ask if user wants to take the tour again
                if (confirm('You have already completed the tour. Would you like to take it again?')) {
                    tour.start();
                }
            }
        });

        // Mark tour as completed when finished
        tour.on('complete', function() {
            localStorage.setItem('tourCompleted', 'true');
        });

        // Add responsive handling
        function updateTourAttachment() {
            if (window.innerWidth < 768) {
                // Adjust attachments for mobile
                tour.steps.forEach(step => {
                    if (step.options.attachTo) {
                        step.options.attachTo.on = 'bottom';
                    }
                });
            }
        }

        // Update on resize
        window.addEventListener('resize', updateTourAttachment);
        updateTourAttachment();

        // Add tour button animation
        const tourButton = document.getElementById('startTour');
        if (!localStorage.getItem('tourCompleted')) {
            tourButton.classList.add('pulse-animation');
        }
    });
    </script>

    <style>
    /* Tour button styles */
    #startTour {
        transition: all 0.3s ease;
        position: relative;
    }

    #startTour:hover {
        transform: scale(1.1);
    }

    /* Pulse animation for new users */
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(142, 68, 173, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(142, 68, 173, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(142, 68, 173, 0);
        }
    }

    /* Custom tour styles */
    .shepherd-content {
        border-radius: 8px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }

    .shepherd-text {
        font-size: 1rem !important;
        padding: 1.5rem !important;
    }

    .shepherd-footer {
        padding: 0.75rem !important;
        border-top: 1px solid rgba(0, 0, 0, 0.1) !important;
    }

    .shepherd-button {
        margin: 0 0.5rem !important;
        padding: 0.5rem 1rem !important;
        border-radius: 4px !important;
        font-weight: 500 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .shepherd-text {
            font-size: 0.9rem !important;
            padding: 1rem !important;
        }

        .shepherd-button {
            padding: 0.4rem 0.8rem !important;
            font-size: 0.9rem !important;
        }
    }
    </style>

    <!-- Link Account Modal -->
    <div class="modal fade" id="linkAccountModal" tabindex="-1" role="dialog" aria-labelledby="linkAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkAccountModalLabel">Link Another Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="linkAccountForm" action="{{ route('account.link') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" id="linkAccountError" style="display: none;"></div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="linkAccountBtn">
                            <span class="normal-text">Link Account</span>
                            <span class="loading-text" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Linking...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle link account form submission
            $('#linkAccountForm').on('submit', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $submitBtn = $('#linkAccountBtn');
                const $error = $('#linkAccountError');
                
                // Show loading state
                $submitBtn.prop('disabled', true);
                $submitBtn.find('.normal-text').hide();
                $submitBtn.find('.loading-text').show();
                $error.hide();

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        // Hide modal
                        $('#linkAccountModal').modal('hide');
                        
                        // Show success Swal
                        Swal.fire({
                            title: 'Success!',
                            text: 'Account linked successfully!',
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
                                    Account linked successfully!
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
                        // Show error message
                        const message = xhr.responseJSON?.message || 'An error occurred while linking the account.';
                        $error.html(message).show();
                        
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
                    },
                    complete: function() {
                        // Reset button state
                        $submitBtn.prop('disabled', false);
                        $submitBtn.find('.loading-text').hide();
                        $submitBtn.find('.normal-text').show();
                    }
                });
            });

            // Remove toasts when hidden
            $(document).on('hidden.bs.toast', '.toast', function() {
                $(this).remove();
            });

            // Reset form when modal is closed
            $('#linkAccountModal').on('hidden.bs.modal', function() {
                $('#linkAccountForm')[0].reset();
                $('#linkAccountError').hide();
            });
        });
    </script>

    <style>
        /* Link Account Modal Styles */
        #linkAccountError {
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }

        #linkAccountBtn {
            min-width: 120px;
            position: relative;
        }

        #linkAccountBtn:disabled {
            cursor: not-allowed;
        }

        .loading-text i {
            margin-right: 0.5rem;
        }

        /* Toast Enhancements */
        .toast {
            min-width: 300px;
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .toast.success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }

        .toast.error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .toast .close {
            text-shadow: none;
            opacity: 0.8;
        }

        .toast .close:hover {
            opacity: 1;
        }

        .toast-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .toast-body {
            padding: 1rem;
            font-size: 0.95rem;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast.show {
            animation: slideIn 0.3s ease-out;
        }
    </style>

    <!-- Add this JavaScript after the existing Link Account Modal script -->
    <script>
        $(document).ready(function() {
            // Password toggle functionality
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                
                // Toggle password visibility
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Reset password visibility when modal is closed
            $('#linkAccountModal').on('hidden.bs.modal', function() {
                $('#password').attr('type', 'password');
                $('#togglePassword i').removeClass('fa-eye-slash').addClass('fa-eye');
            });
        });
    </script>

    <!-- Add these styles -->
    <style>
        #togglePassword {
            cursor: pointer;
        }
        
        #togglePassword:focus {
            outline: none;
            box-shadow: none;
        }
        
        .input-group-append .btn {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
    </style>

    <style>
        /* Account Switch Toast Specific Styles */
        .account-switch-toast {
            min-width: 300px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 1;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #e9ecef;
        }

        .toast-progress::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: #007bff;
            animation: progress 5s linear forwards;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }

        @media (max-width: 576px) {
            .account-switch-toast {
                min-width: auto;
                width: calc(100vw - 2rem);
                margin: 0 1rem;
            }
        }
    </style>

    @yield('scripts')

    <!-- Add this script section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accountSwitchToast = document.querySelector('.account-switch-toast');
            if (accountSwitchToast) {
                $(accountSwitchToast).toast('show');
            }
        });
    </script>

    <!-- System Updates Handler -->
    <script>
    $(document).ready(function() {
        // Initialize tooltip for system updates icon
        $('.system-updates-icon').tooltip();

        // Handle system updates icon click
        $('.system-updates-icon').on('click', function(e) {
            const today = new Date().toDateString();
            const updatesModalShown = localStorage.getItem('updates_modal_shown');

            // Check if updates should be shown
            if (!updatesModalShown || updatesModalShown !== today) {
                $('#systemUpdatesModal').modal('show');
            }
        });

        // Handle "Don't show again" checkbox
        $('#dontShowUpdatesAgain').on('change', function() {
            const today = new Date().toDateString();
            const confirmationDiv = $(this).closest('.dont-show-again').find('.confirmation-message');
            
            if (this.checked) {
                localStorage.setItem('updates_modal_shown', today);
                confirmationDiv.text("Update notifications won't show again today.").show();
            } else {
                localStorage.removeItem('updates_modal_shown');
                confirmationDiv.hide();
            }
        });

        // Theme-aware updates icon
        function updateSystemUpdatesIconTheme() {
            const isDarkMode = $('body').hasClass('dark-mode');
            const $icon = $('.system-updates-icon');
            
            if (isDarkMode) {
                $icon.addClass('text-light').removeClass('text-dark');
            } else {
                $icon.addClass('text-dark').removeClass('text-light');
            }
        }

        // Update theme when dark mode changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    updateSystemUpdatesIconTheme();
                }
            });
        });

        observer.observe(document.body, {
            attributes: true
        });

        // Initial theme setup
        updateSystemUpdatesIconTheme();
    });
    </script>

    </body>
</html>
