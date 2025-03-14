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
                    <button id="startTour" class="nav-link btn btn-link" data-tooltip="Start App Tour">
                        <i class="fas fa-route"></i>
                        <span class="d-none d-md-inline ml-1">Tour Guide</span>
                    </button>
                </li>
                @endif
                @if(auth()->user()->hasRole('HR Comben') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('HR Compliance'))
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


                @canany(['admin', 'super-admin', 'hrcomben', 'hrcompliance', 'hrpolicy'])
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
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="User Management">
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
</style>

<script>
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