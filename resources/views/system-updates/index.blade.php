@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">System Updates</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">System Updates</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            System Updates Management
            <div class="float-end">
                <a class="btn btn-success btn-sm" href="{{ route('system-updates.create') }}">
                    <i class="fas fa-plus"></i> Create New Update
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Published At</th>
                            <th>Status</th>
                            <th>Author</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($updates as $update)
                        <tr>
                            <td>{{ $update->title }}</td>
                            <td>{{ Str::limit($update->description, 50) }}</td>
                            <td>{{ $update->published_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($update->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $update->author->name ?? 'Unknown' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a class="btn btn-info btn-sm me-1" href="{{ route('system-updates.show', $update->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-primary btn-sm me-1" href="{{ route('system-updates.edit', $update->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('system-updates.destroy', $update->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this update?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $updates->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            "order": [[ 2, "desc" ]],
            "pageLength": 25,
            "searching": true,
            "dom": '<"top"f>rt<"bottom"ip><"clear">',
        });
    });
</script>
@endsection 