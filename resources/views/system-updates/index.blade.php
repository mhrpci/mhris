@extends('layouts.app')

@section('title', 'System Updates')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">
                    <i class="fas fa-sync-alt me-2 text-primary"></i>
                    System Updates
                </h5>
            </div>
            <div class="col text-end">
                <a href="{{ route('system-updates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> New Update
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-heading me-2"></i>Title</th>
                        <th><i class="fas fa-align-left me-2"></i>Description</th>
                        <th><i class="fas fa-toggle-on me-2"></i>Status</th>
                        <th><i class="fas fa-calendar-alt me-2"></i>Published At</th>
                        <th><i class="fas fa-cogs me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($updates as $update)
                        <tr>
                            <td>{{ $update->title }}</td>
                            <td>{{ Str::limit($update->description, 100) }}</td>
                            <td>
                                @if($update->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-calendar-day me-1 text-muted"></i>
                                @if($update->published_at)
                                    {{ $update->published_at instanceof \Carbon\Carbon 
                                        ? $update->published_at->format('M d, Y') 
                                        : \Carbon\Carbon::parse($update->published_at)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('system-updates.show', $update) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('system-updates.edit', $update) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Edit Update">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('system-updates.destroy', $update) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this update?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                title="Delete Update">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                    No system updates found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($updates->hasPages())
        <div class="card-footer bg-white">
            {{ $updates->links() }}
        </div>
    @endif
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