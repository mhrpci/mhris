@extends('layouts.app')

@section('styles')
<style>
    .system-update-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
        padding: 2rem 0;
    }
    
    .update-meta {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .update-meta-item {
        display: flex;
        align-items: center;
        margin-right: 1.5rem;
        color: #6c757d;
    }
    
    .update-meta-item i {
        margin-right: 0.5rem;
        font-size: 0.9rem;
    }
    
    .update-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .update-badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
    }
    
    .badge-published {
        background-color: #e4f0ff;
        color: #0056b3;
    }
    
    @media (max-width: 767.98px) {
        .system-update-header {
            padding: 1.5rem 0;
        }
        
        .update-meta {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .update-meta-item {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
@if(!auth()->check() || !auth()->user()->hasRole('Super Admin'))
<div class="container-fluid py-4">
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <strong>Access Denied!</strong> You don't have permission to access this page.
    </div>
</div>
@else
<div class="system-update-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2 font-weight-bold">{{ $systemUpdate->title }}</h1>
                <div class="update-meta">
                    <div class="update-meta-item">
                        <i class="far fa-calendar-alt"></i>
                        <span>Published: {{ $systemUpdate->published_at->format('M d, Y') }}</span>
                    </div>
                    <div class="update-meta-item">
                        <i class="far fa-user"></i>
                        <span>By: {{ $systemUpdate->author->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="update-meta-item">
                        <i class="far fa-check-circle"></i>
                        <span>Status: 
                            @if($systemUpdate->is_active)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('system-updates.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <a href="{{ route('system-updates.edit', $systemUpdate) }}" class="btn btn-primary ml-2">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Update Details</h5>
                </div>
                <div class="card-body">
                    <div class="update-content">
                        {!! $systemUpdate->description !!}
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <span class="text-muted small">
                        <i class="far fa-clock mr-1"></i> Last updated: {{ $systemUpdate->updated_at->diffForHumans() }}
                    </span>
                    
                    <form action="{{ route('system-updates.destroy', $systemUpdate) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this update?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
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
                        <span class="text-muted">Published Date</span>
                        <span>{{ $systemUpdate->published_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Created</span>
                        <span>{{ $systemUpdate->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Last Updated</span>
                        <span>{{ $systemUpdate->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Author</span>
                        <span>{{ $systemUpdate->author->name ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('system-updates.edit', $systemUpdate) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit mr-1"></i> Edit Update
                    </a>
                    <a href="{{ route('system-updates.index') }}" class="btn btn-outline-secondary btn-block mt-2">
                        <i class="fas fa-list mr-1"></i> View All Updates
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection 