@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12 col-sm-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-gradient rounded-circle p-2 me-3 text-white">
                            <i class="fas fa-share-alt fs-4"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-primary">{{ __('Share Credentials') }}</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger mb-4 animate__animated animate__fadeIn shadow-sm border-start border-danger border-4">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle mt-1 me-2"></i>
                            <div>
                                <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('credentials.generate-share') }}" id="shareForm" novalidate>
                        @csrf

                        <div class="row g-3">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group mb-4">
                                    <label for="description" class="form-label fw-bold">{{ __('Description (optional)') }}</label>
                                    <div class="input-group has-validation shadow-sm rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-comment text-primary"></i></span>
                                        <input type="text" class="form-control border-0 ps-0" id="description" name="description" 
                                            placeholder="What is this shared link for?" value="{{ old('description') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top" 
                                            title="Add a brief note about this share">
                                    </div>
                                    <small class="text-muted mt-1">A brief description helps others understand the purpose of this shared link.</small>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group mb-4">
                                    <label for="expiration_time" class="form-label fw-bold">{{ __('Expiration Time') }} <span class="text-danger">*</span></label>
                                    <div class="input-group has-validation shadow-sm rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-clock text-primary"></i></span>
                                        <select class="form-select border-0 ps-0 @error('expiration_time') is-invalid @enderror" id="expiration_time" name="expiration_time" required
                                               data-bs-toggle="tooltip" data-bs-placement="top" 
                                               title="How long this link will be valid">
                                            <option value="10" {{ old('expiration_time') == '10' ? 'selected' : '' }}>10 minutes</option>
                                            <option value="20" {{ old('expiration_time') == '20' ? 'selected' : '' }}>20 minutes</option>
                                            <option value="30" {{ old('expiration_time') == '30' ? 'selected' : '' }}>30 minutes</option>
                                            <option value="40" {{ old('expiration_time') == '40' ? 'selected' : '' }}>40 minutes</option>
                                            <option value="50" {{ old('expiration_time') == '50' ? 'selected' : '' }}>50 minutes</option>
                                            <option value="60" {{ old('expiration_time') == '60' ? 'selected' : '' }}>1 hour</option>
                                        </select>
                                        @error('expiration_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted mt-1">Select how long this shared link will be accessible.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">{{ __('Select Credentials to Share') }} <span class="text-danger">*</span></label>
                            <div class="alert alert-info d-flex align-items-center shadow-sm border-start border-info border-4">
                                <i class="fas fa-info-circle me-2 fs-5 text-info"></i> 
                                <span>The shareable link will expire based on your selected time after creation.</span>
                            </div>
                            
                            <div class="card mt-3 shadow-sm border-0">
                                <div class="card-header bg-light py-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="select-all" role="switch">
                                                <label class="form-check-label fw-bold" for="select-all">Select All</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12">
                                            <div class="d-flex flex-wrap align-items-center justify-content-md-end">
                                                <div class="me-2 mb-md-0 mb-2">
                                                    <span class="badge bg-primary rounded-pill selected-count">0</span> credentials selected
                                                </div>
                                                <div class="input-group input-group-sm search-group shadow-sm rounded">
                                                    <span class="input-group-text bg-white border-0"><i class="fas fa-search text-primary"></i></span>
                                                    <input type="text" class="form-control border-0 ps-0" id="credentialSearch" placeholder="Search credentials..." 
                                                           aria-label="Search credentials">
                                                    <button type="button" class="btn btn-outline-secondary border-0 clear-search" style="display: none;"
                                                           data-bs-toggle="tooltip" data-bs-placement="top" title="Clear search">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0" id="credentialsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%" class="text-center">#</th>
                                                    <th width="30%">Employee</th>
                                                    <th width="35%">Company Email</th>
                                                    <th width="30%">Company Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($credentials as $credential)
                                                    <tr class="credential-row animate__animated animate__fadeIn" data-id="{{ $credential->id }}">
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input credential-checkbox" 
                                                                    name="credentials[]" value="{{ $credential->id }}" 
                                                                    id="credential-{{ $credential->id }}"
                                                                    {{ (is_array(old('credentials')) && in_array($credential->id, old('credentials'))) ? 'checked' : '' }}
                                                                    aria-label="Select {{ $credential->employee->first_name ?? '' }} {{ $credential->employee->last_name ?? '' }}">
                                                                <label class="form-check-label d-none" for="credential-{{ $credential->id }}">Select</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label for="credential-{{ $credential->id }}" class="cursor-pointer mb-0 d-block">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center">
                                                                        <i class="fas fa-user"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $credential->employee->first_name ?? '' }} {{ $credential->employee->last_name ?? '' }}</strong>
                                                                        @if($credential->employee && $credential->employee->position)
                                                                            <div><small class="text-muted">{{ $credential->employee->position->name ?? '' }}</small></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill me-2 p-2">
                                                                    <i class="fas fa-envelope"></i>
                                                                </span>
                                                                <span class="email-cell text-truncate">{{ $credential->company_email }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill me-2 p-2">
                                                                    <i class="fas fa-phone"></i>
                                                                </span>
                                                                <span>{{ $credential->company_number }}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-5 text-muted">
                                                            <div class="empty-state py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3 text-secondary"></i>
                                                                <p class="mb-0">No credentials found</p>
                                                                <small class="text-muted">Try again later or contact administrator</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @error('credentials')
                                    <div class="card-footer bg-danger-subtle text-danger py-2">
                                        <small><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div id="validation-feedback"></div>

                        <div class="form-group d-flex flex-wrap justify-content-between gap-3 mt-4">
                            <a href="{{ route('credentials.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-primary position-relative px-4" id="submitBtn">
                                <i class="fas fa-share-alt me-1"></i> {{ __('Generate Shareable Link') }}
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger submit-counter" style="display: none;">
                                    0 <span class="visually-hidden">selected credentials</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    /* Base styles */
    .cursor-pointer {
        cursor: pointer;
    }
    
    .form-check-input[type="checkbox"] {
        cursor: pointer;
        width: 1.2em;
        height: 1.2em;
    }
    
    /* Card styling */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        border: none;
        transition: all 0.3s ease;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-header {
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
    }
    
    /* Form elements styling */
    .form-control, .form-select, .input-group-text {
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        transition: all 0.2s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus, .form-check-input:focus, .btn:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Avatar circle for employee */
    .avatar-circle {
        width: 35px;
        height: 35px;
        min-width: 35px;
    }
    
    /* Button styling */
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.25s ease-in-out;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
    }
    
    /* Table styling */
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        padding: 0.75rem 1rem;
    }
    
    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    
    .credential-row {
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }
    
    .table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
    
    .table tbody tr.selected {
        background-color: rgba(13, 110, 253, 0.12) !important;
    }
    
    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .selected-count {
        font-size: 0.875rem;
        min-width: 28px;
        text-align: center;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 0.5rem;
        border: none;
    }
    
    .alert-info {
        background-color: rgba(13, 202, 240, 0.1);
        color: #055160;
    }
    
    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #842029;
    }
    
    /* Empty state styling */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    /* Search input */
    .search-group {
        width: 250px;
        margin-left: auto;
        border-radius: 50rem !important;
        overflow: hidden;
    }
    
    .search-group .input-group-text {
        border-right: none;
    }
    
    .search-group .form-control {
        border-left: none;
    }
    
    .search-group .form-control:focus {
        border-color: #dee2e6;
    }
    
    /* Form switch styling */
    .form-check-input.form-switch {
        height: 1.5em !important;
    }
    
    /* Animations */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .pulse {
        animation: pulse 1s infinite;
    }
    
    /* Loading animation for button */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading:after {
        content: "";
        position: absolute;
        width: 1.25rem;
        height: 1.25rem;
        top: calc(50% - 0.625rem);
        left: calc(50% - 0.625rem);
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-top-color: white;
        border-radius: 50%;
        animation: spinner .6s linear infinite;
    }
    
    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
    
    /* Tooltip styles */
    .tooltip {
        font-size: 0.875rem;
    }
    
    .tooltip-inner {
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
    }
    
    /* Row selection highlight effect */
    .credential-row.row-highlight {
        animation: highlight 1s;
    }
    
    @keyframes highlight {
        0% { background-color: rgba(13, 110, 253, 0.3); }
        100% { background-color: transparent; }
    }
    
    /* Animate.css classes */
    .animate__animated {
        animation-duration: 0.4s;
    }
    
    /* Custom submit counter badge */
    .submit-counter {
        font-size: 0.75rem;
        transform: translate(-50%, -50%) !important;
    }
    
    /* Toast styling */
    .toast {
        background-color: white;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .toast-header {
        border-bottom: none;
        padding: 0.75rem 1rem;
    }
    
    .toast-body {
        padding: 0.75rem 1rem;
    }
    
    /* Focus visible improvements for accessibility */
    .form-control:focus-visible,
    .form-select:focus-visible,
    .form-check-input:focus-visible,
    .btn:focus-visible {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .card-body {
            padding: 1.25rem !important;
        }
        
        .search-group {
            width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .table th, .table td {
            padding: 0.625rem;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .form-group {
            margin-bottom: 1.5rem !important;
        }
        
        /* Improved mobile selection */
        .form-check-input {
            width: 1.4em !important;
            height: 1.4em !important;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .table thead {
            display: none;
        }
        
        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100%;
        }
        
        .table tr {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            position: relative;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            background-color: #fff;
            overflow: hidden;
        }
        
        .table td {
            position: relative;
            padding: 0.75rem 1rem;
            padding-left: 45%;
            text-align: left;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,.05);
        }
        
        .table td:last-child {
            border-bottom: none;
        }
        
        .table td:before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 40%;
            font-weight: 600;
            color: #495057;
        }
        
        .table td:first-child {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,.05);
            background-color: rgba(0,0,0,.02);
        }
        
        .table td:first-child .form-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .table td:first-child .form-check-label {
            display: inline-block !important;
            margin-left: 0.5rem;
            font-weight: 600;
        }
        
        .email-cell {
            word-break: break-all;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .form-group.d-flex {
            flex-direction: column;
        }
        
        /* Mobile card styling improvements */
        .card {
            margin-bottom: 1rem;
        }
        
        /* Hide visual labels on mobile */
        .credential-row:after {
            display: none;
        }
        
        /* Add selection indicator on mobile */
        .credential-row.selected {
            border-left: 4px solid #0d6efd;
        }
        
        /* Improve avatar display on mobile */
        .avatar-circle {
            width: 30px;
            height: 30px;
            min-width: 30px;
        }
    }
    
    @media (prefers-reduced-motion: reduce) {
        .animate__animated {
            animation: none !important;
        }
        
        .btn:hover {
            transform: none !important;
        }
        
        * {
            transition: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body // Helps with positioning on mobile
            });
        });
    
        // Initialize Select2 for all select elements
        if ($.fn.select2) {
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%',
                // Add animation when opening dropdown
                dropdownCssClass: 'animate__animated animate__fadeIn'
            }).on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });
        }
        
        // Add data-label attributes for responsive tables
        function addTableAttributes() {
            if ($(window).width() <= 576) {
                $('#credentialsTable tbody tr').each(function() {
                    $(this).find('td:eq(0) .form-check-label').removeClass('d-none').text('Select');
                    $(this).find('td:eq(1)').attr('data-label', 'Employee');
                    $(this).find('td:eq(2)').attr('data-label', 'Company Email');
                    $(this).find('td:eq(3)').attr('data-label', 'Company Number');
                });
            } else {
                $('#credentialsTable tbody tr').each(function() {
                    $(this).find('td:eq(0) .form-check-label').addClass('d-none');
                });
            }
        }
        
        // Call on page load
        addTableAttributes();
        
        // Call on window resize with debounce
        let resizeTimer;
        $(window).resize(function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                addTableAttributes();
            }, 250);
        });
        
        // Handle select all checkbox
        $('#select-all').change(function() {
            $('.credential-checkbox').prop('checked', $(this).prop('checked'));
            updateRowHighlighting();
            updateSelectedCount();
            updateSubmitCounter();
            
            // Add visual feedback
            if ($(this).prop('checked')) {
                $('#credentialsTable tbody tr').addClass('row-highlight');
                setTimeout(function() {
                    $('#credentialsTable tbody tr').removeClass('row-highlight');
                }, 1000);
            }
        });
        
        // Update select all if all checkboxes are selected
        $('.credential-checkbox').change(function() {
            updateSelectAllState();
            updateRowHighlighting();
            updateSelectedCount();
            updateSubmitCounter();
            
            // Add visual feedback
            $(this).closest('tr').addClass('row-highlight');
            setTimeout(function() {
                $('.row-highlight').removeClass('row-highlight');
            }, 1000);
        });
        
        // Make row clickable to toggle checkbox
        $('.credential-row').click(function(e) {
            if (!$(e.target).hasClass('form-check-input') && $(e.target).closest('.form-check').length === 0) {
                var checkbox = $(this).find('.credential-checkbox');
                checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            }
        });
        
        // Search functionality with debounce
        let searchTimer;
        $('#credentialSearch').on('input', function() {
            clearTimeout(searchTimer);
            const $this = $(this);
            
            // Show/hide clear button immediately
            if ($this.val()) {
                $('.clear-search').show();
            } else {
                $('.clear-search').hide();
            }
            
            searchTimer = setTimeout(function() {
                const value = $this.val().toLowerCase();
                
                // Filter table rows
                let visibleRows = 0;
                $('#credentialsTable tbody tr:not(.no-results)').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    const isVisible = rowText.indexOf(value) > -1;
                    $(this).toggle(isVisible);
                    if (isVisible) visibleRows++;
                });
                
                // Show no results message
                if (visibleRows === 0 && value !== '' && $('#credentialsTable .no-results').length === 0) {
                    $('#credentialsTable tbody').append(
                        '<tr class="no-results animate__animated animate__fadeIn">' +
                        '<td colspan="4" class="text-center py-4 text-muted">' +
                        '<div class="py-3">' +
                        '<i class="fas fa-search fa-2x mb-2"></i>' +
                        '<p class="mb-0">No matching credentials found</p>' +
                        '<small>Try different search terms</small>' +
                        '</div>' +
                        '</td>' +
                        '</tr>'
                    );
                } else if (visibleRows > 0 || value === '') {
                    $('#credentialsTable .no-results').remove();
                }
            }, 300);
        });
        
        // Clear search
        $('.clear-search').click(function() {
            $('#credentialSearch').val('');
            $('#credentialSearch').trigger('input');
            $(this).hide();
            $('#credentialSearch').focus();
        });
        
        // Form validation before submit
        $('#shareForm').submit(function(e) {
            if ($('.credential-checkbox:checked').length === 0) {
                e.preventDefault();
                
                // Show alert if no credentials selected
                if ($('#validation-feedback .alert-validation').length === 0) {
                    $('#validation-feedback').html(
                        '<div class="alert alert-danger alert-validation mb-3 animate__animated animate__fadeIn shadow-sm border-start border-danger border-4">' +
                        '<i class="fas fa-exclamation-circle me-2"></i> ' +
                        'Please select at least one credential to share.' +
                        '</div>'
                    );
                        
                    // Scroll to the error with smooth behavior
                    $('html, body').animate({
                        scrollTop: $('#validation-feedback').offset().top - 100
                    }, 300);
                    
                    // Pulse the table for attention
                    $('#credentialsTable').addClass('pulse');
                    setTimeout(function() {
                        $('#credentialsTable').removeClass('pulse');
                    }, 1000);
                }
                return false;
            }
            
            // Add loading state to button
            $('#submitBtn').addClass('btn-loading')
                .prop('disabled', true)
                .html('<span class="visually-hidden">Generating shareable link...</span>');
            
            // Disable all form inputs while submitting
            $('#shareForm input, #shareForm select, #shareForm button').prop('disabled', true);
            
            return true;
        });
        
        // Helper functions
        function updateSelectAllState() {
            var totalCheckboxes = $('.credential-checkbox').length;
            var checkedCheckboxes = $('.credential-checkbox:checked').length;
            $('#select-all').prop('checked', totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes);
            
            // Update the "Select All" text for better UX
            if (totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes) {
                $('#select-all').parent().find('label').text('Deselect All');
            } else {
                $('#select-all').parent().find('label').text('Select All');
            }
        }
        
        function updateRowHighlighting() {
            $('.credential-checkbox').each(function() {
                if ($(this).prop('checked')) {
                    $(this).closest('tr').addClass('selected');
                } else {
                    $(this).closest('tr').removeClass('selected');
                }
            });
        }
        
        function updateSelectedCount() {
            var count = $('.credential-checkbox:checked').length;
            $('.selected-count').text(count);
            
            // Animate counter if changed
            $('.selected-count').addClass('animate__animated animate__heartBeat');
            setTimeout(function() {
                $('.selected-count').removeClass('animate__animated animate__heartBeat');
            }, 1000);
        }
        
        function updateSubmitCounter() {
            var count = $('.credential-checkbox:checked').length;
            if (count > 0) {
                $('.submit-counter').text(count).show();
            } else {
                $('.submit-counter').hide();
            }
        }
        
        // Initialize UI state
        updateRowHighlighting();
        updateSelectAllState();
        updateSelectedCount();
        updateSubmitCounter();
        
        // Add keyboard navigation for improved accessibility
        $('#credentialsTable tbody tr').attr('tabindex', '0');
        $('#credentialsTable tbody tr').keypress(function(e) {
            if (e.which === 13 || e.which === 32) { // Enter or Space key
                e.preventDefault();
                var checkbox = $(this).find('.credential-checkbox');
                checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            }
        });
        
        // Show a small success message when a row is selected
        $('.credential-checkbox').change(function() {
            var isChecked = $(this).prop('checked');
            var employeeName = $(this).closest('tr').find('td:eq(1) strong').text().trim();
            
            // Remove any existing toasts
            $('.selection-toast').remove();
            
            // Create and append toast
            var toastHtml = '<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">' +
                '<div class="toast selection-toast animate__animated animate__fadeInUp" role="alert" aria-live="assertive" aria-atomic="true">' +
                '<div class="toast-header bg-' + (isChecked ? 'primary' : 'secondary') + ' text-white">' +
                '<i class="fas fa-' + (isChecked ? 'check-circle' : 'times-circle') + ' me-2"></i>' +
                '<strong class="me-auto">' + (isChecked ? 'Selected' : 'Deselected') + '</strong>' +
                '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>' +
                '</div>' +
                '<div class="toast-body">' +
                employeeName + ' ' + (isChecked ? 'added to' : 'removed from') + ' selection' +
                '</div>' +
                '</div>' +
                '</div>';
            
            $('body').append(toastHtml);
            
            // Initialize and show the toast
            var toastElement = document.querySelector('.selection-toast');
            var toast = new bootstrap.Toast(toastElement, { delay: 2000 });
            toast.show();
            
            // Remove toast after it's hidden
            $(toastElement).on('hidden.bs.toast', function() {
                $(this).parent().remove();
            });
        });
        
        // Prevent zooming on mobile devices when focusing inputs
        document.addEventListener('gesturestart', function(e) {
            e.preventDefault();
        });
        
        // Improve mobile experience - auto close dropdown when item selected
        if ($(window).width() <= 768) {
            $('select').on('select2:select', function(e) {
                $(this).select2('close');
            });
        }
    });
</script>
@endsection
@endsection 