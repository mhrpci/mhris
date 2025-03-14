<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MHR Property Conglomerates, Inc.</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/ICON_APP.png') }}">
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => console.log("Service Worker Registered"))
                .catch(err => console.log("Service Worker Failed", err));
        }
    </script>


    @yield('styles')
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
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="app-env" content="{{ config('app.env') }}">

    <!-- Add this in the head section after other CSS links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/10.0.1/css/shepherd.css"/>

    <!-- Add SweetAlert2 CSS and JS in the head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/10.0.1/js/shepherd.min.js"></script>

    @stack('styles')
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

        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
        @include('layouts.partials.rightsidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.partials.footer')
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

    @yield('scripts')
    @include('layouts.partials.script')
    </body>
</html>