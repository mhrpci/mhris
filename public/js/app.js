/**
 * MHRPCI-HRIS JavaScript Module
 * Contains all core functionality for the application
 */

// Wait for document to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Hide preloader when page is loaded
    const hidePreloader = () => {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        }
    };

    // Hide preloader
    window.addEventListener('load', hidePreloader);
    // Fallback in case the load event already fired
    setTimeout(hidePreloader, 1000);

    // Initialize Service Worker registration
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(reg => console.log("Service Worker Registered"))
            .catch(err => console.log("Service Worker Failed", err));
    }

    // Initialize Select2
    const initSelect2 = () => {
        if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') return;
        
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
    };

    // Initialize Select2
    initSelect2();

    // Theme Management System
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
    if (typeof $ !== 'undefined') {
        ThemeManager.init();
    }

    // Quick Actions Management
    const initQuickActions = () => {
        const quickActionsToggle = document.getElementById('quickActionsToggle');
        const quickActionsCard = document.getElementById('quickActionsCard');
        
        if (!quickActionsToggle || !quickActionsCard) return;

        // Toggle quick actions card
        quickActionsToggle.addEventListener('click', function() {
            quickActionsCard.classList.toggle('show');
            this.classList.toggle('active');
        });
        
        // Close quick actions when clicking outside
        document.addEventListener('click', function(event) {
            if (!quickActionsToggle || !quickActionsCard) return;
            
            const isClickInside = quickActionsToggle.contains(event.target) || 
                                quickActionsCard.contains(event.target);
            
            if (!isClickInside && quickActionsCard.classList.contains('show')) {
                quickActionsCard.classList.remove('show');
                quickActionsToggle.classList.remove('active');
            }
        });
        
        // Prevent closing when clicking inside the card
        if (quickActionsCard) {
            quickActionsCard.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        }
    };

    // Initialize Quick Actions
    initQuickActions();

    // Tour System Management
    const initTourSystem = () => {
        // Check if Shepherd.js is loaded
        if (typeof Shepherd === 'undefined') return;

        // Initialize tooltip if element exists
        const startTourBtn = document.getElementById('startTour');
        if (startTourBtn && typeof $ !== 'undefined') {
            $('#startTour').tooltip();
        }

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
        if (startTourBtn) {
            startTourBtn.addEventListener('click', function() {
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
        }

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
        if (startTourBtn && !localStorage.getItem('tourCompleted')) {
            startTourBtn.classList.add('pulse-animation');
        }
    };

    // Initialize Tour System
    initTourSystem();

    // System Updates Handler
    const initSystemUpdates = () => {
        if (typeof $ === 'undefined') return;

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
    };

    // Initialize System Updates
    initSystemUpdates();

    // Add any additional functionality here as needed

    // Check and show birthday celebrants
    const checkAndShowCelebrants = () => {
        if (typeof $ === 'undefined') return;
        
        // Check if already shown today
        const today = new Date().toDateString();
        const celebrantsShown = localStorage.getItem('celebrants_shown');
        
        if (celebrantsShown === today) {
            return;
        }
        
        // Fetch celebrants from API
        fetch('/api/celebrants', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.celebrants && data.celebrants.length > 0) {
                // Set localStorage to prevent showing again today
                localStorage.setItem('celebrants_shown', today);
                
                // Populate modal with celebrants
                const modalBody = document.getElementById('celebrantsModalBody');
                if (modalBody) {
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
            }
        })
        .catch(error => {
            console.error('Error fetching celebrants:', error);
        });
    };

    // Check for celebrants when page loads
    checkAndShowCelebrants();

    // Handle celebrants checkbox change
    const dontShowTodayCheckbox = document.getElementById('dontShowToday');
    if (dontShowTodayCheckbox) {
        dontShowTodayCheckbox.addEventListener('change', function(e) {
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
    }
}); 