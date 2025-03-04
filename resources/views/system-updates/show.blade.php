@extends('layouts.app')

@section('title', $systemUpdate->title)

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    System Update Details
                </h5>
            </div>
            <div class="col text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('system-updates.edit', $systemUpdate) }}" 
                       class="btn btn-primary"
                       data-bs-toggle="tooltip"
                       title="Edit Update">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('system-updates.destroy', $systemUpdate) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this update?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger"
                                data-bs-toggle="tooltip"
                                title="Delete Update">
                            <i class="fas fa-trash-alt me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-1">
                    <i class="fas fa-heading me-2"></i>Title
                </h6>
                <p class="mb-0">{{ $systemUpdate->title }}</p>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">
                    <i class="fas fa-toggle-on me-2"></i>Status
                </h6>
                <p class="mb-0">
                    @if($systemUpdate->is_active)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Active
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            <i class="fas fa-times-circle me-1"></i>Inactive
                        </span>
                    @endif
                </p>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted mb-1">
                    <i class="fas fa-calendar-alt me-2"></i>Published At
                </h6>
                <p class="mb-0">{{ $systemUpdate->published_at->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-1">
                    <i class="fas fa-user me-2"></i>Author
                </h6>
                <p class="mb-0">{{ $systemUpdate->author->first_name }} {{ $systemUpdate->author->last_name }}</p>
            </div>
        </div>

        <div class="mb-4">
            <h6 class="text-muted mb-1">
                <i class="fas fa-align-left me-2"></i>Description
            </h6>
            <div class="card bg-light">
                <div class="card-body">
                    {!! nl2br(e($systemUpdate->description)) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted mb-1">
                    <i class="fas fa-clock me-2"></i>Created At
                </h6>
                <p class="mb-0">{{ $systemUpdate->created_at->format('M d, Y H:i:s') }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-1">
                    <i class="fas fa-history me-2"></i>Last Updated
                </h6>
                <p class="mb-0">{{ $systemUpdate->updated_at->format('M d, Y H:i:s') }}</p>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('system-updates.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush
@endsection 