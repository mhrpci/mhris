@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">System Update Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('system-updates.index') }}">System Updates</a></li>
        <li class="breadcrumb-item active">Details</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-eye me-1"></i>
                System Update Information
            </div>
            <div>
                <a href="{{ route('system-updates.edit', $systemUpdate->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('system-updates.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="mb-3">
                        <h2 class="mb-1">{{ $systemUpdate->title }}</h2>
                        <div class="small text-muted mb-2">
                            Published on {{ $systemUpdate->published_at->format('F d, Y \a\t h:i A') }} by {{ $systemUpdate->author->name ?? 'Unknown' }}
                            <span class="mx-2">|</span>
                            <span class="badge {{ $systemUpdate->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $systemUpdate->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-file-alt me-1"></i>
                            Description
                        </div>
                        <div class="card-body">
                            <div class="mb-0">
                                {!! nl2br(e($systemUpdate->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <form action="{{ route('system-updates.destroy', $systemUpdate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this system update?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 