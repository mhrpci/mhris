@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        border-color: #80bdff;
    }
    
    .note-editor.note-frame {
        border-color: #ced4da;
        border-radius: 0.25rem;
    }
    
    .note-editor.note-frame:focus {
        border-color: #80bdff;
    }
    
    .required-label::after {
        content: "*";
        color: #dc3545;
        margin-left: 4px;
    }
    
    .form-section {
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    @media (max-width: 767.98px) {
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">Edit System Update</h1>
            <p class="text-muted">Modify existing system update or announcement</p>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('system-updates.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
            <a href="{{ route('system-updates.show', $systemUpdate) }}" class="btn btn-info ml-2">
                <i class="fas fa-eye mr-1"></i> View
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Update Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('system-updates.update', $systemUpdate) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-section">
                            <div class="form-group">
                                <label for="title" class="required-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $systemUpdate->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter a clear, concise title for this update (max 255 characters)</small>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="form-group">
                                <label for="description" class="required-label">Description</label>
                                <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description', $systemUpdate->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Provide detailed information about this system update</small>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="published_at" class="required-label">Publish Date</label>
                                        <input type="date" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $systemUpdate->published_at->format('Y-m-d')) }}" required>
                                        @error('published_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="author_id" class="required-label">Author</label>
                                        <select class="form-control @error('author_id') is-invalid @enderror" id="author_id" name="author_id" required>
                                            <option value="">Select Author</option>
                                            @foreach(\App\Models\User::all() as $user)
                                                <option value="{{ $user->id }}" {{ (old('author_id', $systemUpdate->author_id) == $user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('author_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $systemUpdate->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active Status</label>
                                <small class="form-text text-muted d-block">Enable this to make the update visible to users</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between form-actions">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('system-updates.index') }}'">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Update
                                </button>
                                <a href="{{ route('system-updates.show', $systemUpdate) }}" class="btn btn-info ml-2">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Update Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Status</span>
                        <span>
                            @if($systemUpdate->is_active)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Created</span>
                        <span>{{ $systemUpdate->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Last Updated</span>
                        <span>{{ $systemUpdate->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Help & Guidelines</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-info-circle text-primary mr-2"></i>About System Updates</h6>
                        <p class="text-muted small">System updates are announcements about changes, improvements, or new features added to the system. These will be visible to users based on their active status.</p>
                    </div>
                    
                    <div class="alert alert-info small">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Fields marked with <span class="required-label"></span> are required.
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('system-updates.show', $systemUpdate) }}" class="btn btn-info btn-block">
                        <i class="fas fa-eye mr-1"></i> View Update
                    </a>
                    <form action="{{ route('system-updates.destroy', $systemUpdate) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this update? This action cannot be undone.')">
                            <i class="fas fa-trash mr-1"></i> Delete Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Enter detailed description here...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Initialize author select2
        $('#author_id').select2({
            theme: 'bootstrap4',
            placeholder: "Select an author",
            allowClear: true
        });
    });
</script>
@endpush 