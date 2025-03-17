@extends('layouts.app')

@section('title', 'Create System Update')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<style>
    /* Animation Keyframes */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Container Animation */
    .animate-container {
        animation: slideIn 0.5s ease-out;
    }

    /* Card Enhancements */
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,.08);
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .card:hover {
        box-shadow: 0 0 30px rgba(0,0,0,.12);
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 1px solid rgba(0,0,0,.05);
        padding: 1.5rem;
        border-radius: 12px 12px 0 0 !important;
    }

    .card-header h5 {
        font-size: 1.25rem;
        color: #344767;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header h5 i {
        font-size: 1.1em;
        color: #007bff;
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: #344767;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    .form-control {
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        background-color: #f8f9fa;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.1);
        background-color: #ffffff;
    }

    .form-control:hover {
        border-color: #007bff;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    textarea.form-control {
        min-height: 120px;
        line-height: 1.6;
    }

    .input-group-text {
        border: 2px solid #e9ecef;
        background-color: #f8f9fa;
        color: #007bff;
        transition: all 0.3s ease;
    }

    .input-group:focus-within .input-group-text {
        border-color: #007bff;
        color: #0056b3;
    }

    /* Field Groups */
    .field-group {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 10px;
        padding: 1.75rem;
        margin-bottom: 1.75rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .field-group:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,.05);
        transform: translateY(-2px);
    }

    .field-group-title {
        color: #344767;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .field-group-title i {
        color: #007bff;
        font-size: 0.9em;
    }

    /* Toggle Switch Enhancements */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 52px;
        height: 26px;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        transition: .3s ease-in-out;
        border-radius: 26px;
        border: 2px solid #dee2e6;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 2px;
        bottom: 2px;
        background: #ffffff;
        transition: .3s ease-in-out;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }

    input:checked + .toggle-slider {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        border-color: #28a745;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    .toggle-label {
        margin-left: 15px;
        font-weight: 500;
        color: #344767;
        display: flex;
        align-items: center;
    }

    .status-text {
        font-size: 0.875rem;
        padding: 0.35rem 1rem;
        border-radius: 6px;
        margin-left: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .status-active {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.2) 100%);
        color: #28a745;
    }

    .status-inactive {
        background: linear-gradient(135deg, rgba(108, 117, 125, 0.1) 0%, rgba(108, 117, 125, 0.2) 100%);
        color: #6c757d;
    }

    /* Button Enhancements */
    .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .btn i {
        font-size: 1.1em;
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        box-shadow: 0 4px 6px rgba(0,123,255,.11), 0 1px 3px rgba(0,0,0,.08);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 14px rgba(0,123,255,.1), 0 3px 6px rgba(0,0,0,.08);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #344767;
        border: 1px solid #e9ecef;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        border-color: #dde1e5;
        transform: translateY(-1px);
    }

    /* Responsive Enhancements */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }

        .field-group {
            padding: 1.25rem;
        }

        .form-label {
            font-size: 0.8125rem;
        }

        .btn {
            padding: 0.625rem 1.25rem;
        }

        .toggle-switch {
            width: 46px;
            height: 23px;
        }

        .toggle-slider:before {
            height: 15px;
            width: 15px;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(23px);
        }
    }

    @media (max-width: 576px) {
        .card-header {
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .field-group {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .field-group-title {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-end {
            flex-direction: column-reverse;
        }

        .status-text {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }
    }

    /* Loading States */
    .form-control:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    /* Required Field Enhancement */
    .required-field::after {
        content: "*";
        color: #dc3545;
        margin-left: 4px;
        animation: fadeIn 0.3s ease-out;
    }

    /* Error State Enhancements */
    .invalid-feedback {
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        color: #dc3545;
        animation: fadeIn 0.3s ease-out;
    }

    .invalid-feedback:before {
        content: "⚠";
        margin-right: 0.5rem;
        font-size: 1em;
    }

    /* Flatpickr Customization */
    .flatpickr-calendar {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,.1);
        border: none;
        background: #ffffff;
    }

    .flatpickr-day {
        border-radius: 6px;
    }

    .flatpickr-day.selected {
        background: #007bff;
        border-color: #007bff;
    }

    .flatpickr-day:hover {
        background: #f0f2f5;
    }
</style>
@endpush

@section('content')
<div class="container py-4 animate-container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i>
                    Create System Update
                </h5>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('system-updates.store') }}" 
                  method="POST"
                  class="needs-validation"
                  novalidate>
                @csrf
                <input type="hidden" name="author_id" value="{{ auth()->id() }}">

                <div class="field-group">
                    <div class="field-group-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </div>
                    <div class="mb-4">
                        <label for="title" class="form-label required-field">Title</label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', '') }}" 
                               placeholder="Enter update title"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label required-field">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Enter detailed description"
                                  required>{{ old('description', '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="field-group">
                    <div class="field-group-title">
                        <i class="fas fa-cog"></i>
                        Publishing Settings
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="published_at" class="form-label required-field">Publish Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="text" 
                                       class="form-control flatpickr @error('published_at') is-invalid @enderror" 
                                       id="published_at" 
                                       name="published_at" 
                                       placeholder="Select date"
                                       value="{{ old('published_at', '') }}"
                                       required>
                            </div>
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block">Status</label>
                            <div class="d-flex align-items-center">
                                <label class="toggle-switch">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1" 
                                           id="is_active"
                                           class="@error('is_active') is-invalid @enderror"
                                           {{ old('is_active', false) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <label class="toggle-label" for="is_active">
                                    <span class="status-text" id="statusText">
                                        {{ old('is_active', false) ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Author</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}" 
                                   readonly 
                                   disabled>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('system-updates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Flatpickr with animation
        try {
            const flatpickrInstance = flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                allowInput: true,
                disableMobile: true,
                altInput: true,
                altFormat: 'F j, Y',
                minDate: 'today',
                animate: true,
                onOpen: function(selectedDates, dateStr, instance) {
                    instance.calendarContainer.classList.add('animate-calendar');
                },
                onParseConfig: function(config) {
                    return config;
                },
                errorHandler: function(error) {
                    console.warn('Flatpickr error:', error);
                }
            });
            
            console.log('Flatpickr initialized successfully');
        } catch (e) {
            console.error('Error initializing Flatpickr:', e);
            
            // Fallback to standard date input if Flatpickr fails
            const dateInput = document.getElementById('published_at');
            if (dateInput) {
                dateInput.type = 'date';
            }
        }

        // Toggle switch functionality with animation
        try {
            const toggleSwitch = document.getElementById('is_active');
            const statusText = document.getElementById('statusText');
            
            if (toggleSwitch && statusText) {
                function updateStatus(checked) {
                    statusText.style.opacity = '0';
                    setTimeout(() => {
                        statusText.textContent = checked ? 'Active' : 'Inactive';
                        statusText.className = 'status-text ' + (checked ? 'status-active' : 'status-inactive');
                        statusText.style.opacity = '1';
                    }, 200);
                }
                
                toggleSwitch.addEventListener('change', function() {
                    updateStatus(this.checked);
                });
                
                // Initialize status
                updateStatus(toggleSwitch.checked);
            } else {
                console.warn('Toggle switch elements not found');
            }
        } catch (e) {
            console.error('Error initializing toggle switch:', e);
        }

        // Enhanced form validation
        try {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        // Scroll to first error
                        const firstError = form.querySelector(':invalid');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.focus();
                        }
                    }
                    form.classList.add('was-validated');
                });
            } else {
                console.warn('Form element not found');
            }
        } catch (e) {
            console.error('Error initializing form validation:', e);
        }

        // Input animations
        try {
            const inputs = document.querySelectorAll('.form-control');
            if (inputs.length > 0) {
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        const label = this.closest('.mb-4')?.querySelector('.form-label');
                        if (label) {
                            label.style.color = '#007bff';
                        }
                    });
                    
                    input.addEventListener('blur', function() {
                        const label = this.closest('.mb-4')?.querySelector('.form-label');
                        if (label) {
                            label.style.color = '#344767';
                        }
                    });
                });
            } else {
                console.warn('No form controls found for animations');
            }
        } catch (e) {
            console.error('Error initializing input animations:', e);
        }
    });
</script>
@endpush 