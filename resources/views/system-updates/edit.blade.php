@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Edit System Update</h1>
                <div>
                    <a href="{{ route('system-updates.show', $systemUpdate->id) }}" class="d-none d-sm-inline-block btn btn-info shadow-sm me-2">
                        <i class="fas fa-eye fa-sm text-white-50 mr-1"></i> View
                    </a>
                    <a href="{{ route('system-updates.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Back to List
                    </a>
                </div>
            </div>
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('system-updates.index') }}" class="text-decoration-none">System Updates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit me-2"></i>
                        <h6 class="m-0 font-weight-bold">Edit System Update: {{ $systemUpdate->title }}</h6>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <h5 class="alert-heading fw-bold mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i> Please check the form
                            </h5>
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('system-updates.update', $systemUpdate->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-1 text-primary"></i> Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                        class="form-control form-control-lg shadow-sm @error('title') is-invalid @enderror" 
                                        id="title" 
                                        name="title" 
                                        value="{{ old('title', $systemUpdate->title) }}" 
                                        placeholder="Enter update title"
                                        maxlength="255"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-1 text-primary"></i> Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        class="form-control shadow-sm @error('description') is-invalid @enderror" 
                                        id="description" 
                                        name="description" 
                                        rows="10" 
                                        placeholder="Enter detailed information about the update"
                                        required>{{ old('description', $systemUpdate->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-cog me-1"></i> Settings
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="published_at" class="form-label fw-bold">
                                                <i class="fas fa-calendar-alt me-1 text-primary"></i> Publish Date
                                            </label>
                                            <input 
                                                type="datetime-local" 
                                                class="form-control shadow-sm @error('published_at') is-invalid @enderror" 
                                                id="published_at" 
                                                name="published_at" 
                                                value="{{ old('published_at', $systemUpdate->published_at->format('Y-m-d\TH:i')) }}">
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i> When this update was or will be published
                                            </small>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-4">
                                            <input 
                                                type="checkbox" 
                                                class="form-check-input" 
                                                id="is_active" 
                                                name="is_active" 
                                                value="1" 
                                                role="switch"
                                                {{ old('is_active', $systemUpdate->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                <i class="fas fa-toggle-on me-1 text-primary"></i> Active Status
                                            </label>
                                            <div class="form-text">
                                                Enable to make this update visible to users
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info shadow-sm">
                                            <i class="fas fa-user-edit me-1"></i> Originally authored by <strong>{{ $systemUpdate->author->first_name }} {{ $systemUpdate->author->last_name }}</strong>
                                            <hr>
                                            <small><i class="fas fa-clock me-1"></i> Created: {{ $systemUpdate->created_at->format('M d, Y h:i A') }}</small><br>
                                            <small><i class="fas fa-edit me-1"></i> Last Updated: {{ $systemUpdate->updated_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex mt-4 border-top pt-4">
                            <a href="{{ route('system-updates.index') }}" class="btn btn-light border shadow-sm me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add rich text editor to description field
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('description', {
                height: 400,
                removePlugins: 'elementspath',
                resize_enabled: false,
                allowedContent: true, // Allow all HTML tags and attributes
                extraAllowedContent: '*(*)[*]{*}', // Allow all classes, attributes, and styles
                removeDialogTabs: 'image:advanced;link:advanced',
                toolbar: [
                    { name: 'document', items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                    { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
                    '/',
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
                    { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                    { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                    '/',
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                    { name: 'about', items: [ 'About' ] }
                ],
                filebrowserUploadUrl: '{{ route("upload.image") }}',
                filebrowserUploadMethod: 'form',
                extraPlugins: 'colorbutton,font,justify,uploadimage',
                removeButtons: 'Save,NewPage,Preview,Print,Templates,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt,Language,Flash,Smiley,SpecialChar,PageBreak,Iframe,About',
                contentsCss: [
                    'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.5; }',
                    'h1, h2, h3, h4, h5, h6 { margin-top: 1em; margin-bottom: 0.5em; }',
                    'p { margin-bottom: 1em; }',
                    'ul, ol { margin-bottom: 1em; padding-left: 2em; }',
                    'table { border-collapse: collapse; width: 100%; margin-bottom: 1em; }',
                    'table, th, td { border: 1px solid #ddd; }',
                    'th, td { padding: 8px; text-align: left; }',
                    'th { background-color: #f8f9fa; }',
                    'img { max-width: 100%; height: auto; }',
                    'pre { background-color: #f8f9fa; padding: 1em; border-radius: 4px; overflow-x: auto; }',
                    'code { background-color: #f8f9fa; padding: 0.2em 0.4em; border-radius: 3px; }',
                    'blockquote { border-left: 4px solid #ddd; margin: 1em 0; padding-left: 1em; color: #666; }'
                ]
            });
        }
        
        // Form validation
        (function () {
            'use strict'
            
            // Fetch all forms we want to apply custom validation to
            var forms = document.querySelectorAll('.needs-validation')
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    });
</script>
@endsection 