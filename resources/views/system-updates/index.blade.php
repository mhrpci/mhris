@extends('layouts.app')

@section('styles')
<style>
    .card-system-update {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }
    
    .card-system-update:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .update-inactive {
        border-left-color: #6c757d;
        opacity: 0.8;
    }
    
    .badge-published {
        background-color: #e4f0ff;
        color: #0056b3;
    }
    
    .system-update-description {
        max-height: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    
    @media (max-width: 767.98px) {
        .action-buttons .btn {
            padding: .25rem .5rem;
            font-size: .875rem;
            margin-bottom: 5px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">System Updates</h1>
            <p class="text-muted">Manage system updates and announcements</p>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('system-updates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> New Update
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">All System Updates</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover" id="system-updates-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Published</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($updates as $update)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $update->title }}</h6>
                                        <div class="small text-muted system-update-description">
                                            {{ Str::limit(strip_tags($update->description), 100) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-published">
                                    {{ $update->published_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td>{{ $update->author->name ?? 'Unknown' }}</td>
                            <td>
                                @if($update->is_active)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('system-updates.show', $update) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('system-updates.edit', $update) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('system-updates.destroy', $update) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this update?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                    <h5>No system updates found</h5>
                                    <p class="text-muted">Get started by creating your first system update</p>
                                    <a href="{{ route('system-updates.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus-circle mr-1"></i> Create Update
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#system-updates-table').DataTable({
            "order": [[1, "desc"]],
            "pageLength": 10,
            "language": {
                "paginate": {
                    "previous": "<i class='fas fa-angle-left'></i>",
                    "next": "<i class='fas fa-angle-right'></i>"
                }
            },
            "responsive": true
        });
    });
</script>
@endpush 