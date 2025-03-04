@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Chat Rooms List -->
        <div class="col-md-4 col-lg-3 p-0 border-right">
            <div class="card h-100 rounded-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chats</h5>
                    <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#newChatModal">
                        <i class="fas fa-plus"></i> New Chat
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($chatRooms as $room)
                            <a href="{{ route('chat.show', $room) }}" 
                               class="list-group-item list-group-item-action {{ request()->route('chatRoom')?->id === $room->id ? 'active' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            @if($room->type === 'private')
                                                {{ $room->participants->where('user_id', '!=', auth()->id())->first()?->user->first_name }}
                                            @else
                                                {{ $room->name }}
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            {{ $room->lastMessage?->message ?? 'No messages yet' }}
                                        </small>
                                    </div>
                                    @if($room->unread_count > 0)
                                        <span class="badge badge-primary badge-pill">{{ $room->unread_count }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-8 col-lg-9 p-0">
            <div class="h-100 d-flex align-items-center justify-content-center bg-light">
                <p class="text-muted">Select a chat to start messaging</p>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1" role="dialog" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newChatModalLabel">New Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newChatForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="chatType">Chat Type</label>
                        <select class="form-control" id="chatType" name="type" required>
                            <option value="private">Private Chat</option>
                            <option value="group">Group Chat</option>
                        </select>
                    </div>
                    <div class="form-group" id="groupNameField" style="display: none;">
                        <label for="chatName">Group Name</label>
                        <input type="text" class="form-control" id="chatName" name="name">
                    </div>
                    <div class="form-group">
                        <label for="participants">Select Participants</label>
                        <select class="form-control select2" id="participants" name="participants[]" multiple required>
                            @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .list-group-item.active small {
        color: rgba(255, 255, 255, 0.8) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    $('#chatType').change(function() {
        if ($(this).val() === 'group') {
            $('#groupNameField').show();
            $('#chatName').prop('required', true);
        } else {
            $('#groupNameField').hide();
            $('#chatName').prop('required', false);
        }
    });

    $('#newChatForm').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route('chat.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.location.href = '{{ route('chat.show', '') }}/' + response.chat_room.id;
            },
            error: function(xhr) {
                alert('Error creating chat room');
            }
        });
    });
});
</script>
@endpush 