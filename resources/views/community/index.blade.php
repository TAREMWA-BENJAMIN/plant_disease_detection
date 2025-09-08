@extends('layouts.app')

@section('content')
<style>
    .forum-bg {
        background: linear-gradient(120deg, #e8f5e9 0%, #f1f8e9 100%);
        min-height: 100vh;
        padding: 80px 0 16px 0;
    }
    .forum-card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(46, 125, 50, 0.08);
        border: none;
        margin-bottom: 32px;
        background: #fff;
        transition: box-shadow 0.2s;
    }
    .forum-card:hover {
        box-shadow: 0 8px 32px rgba(46, 125, 50, 0.15);
    }
    .forum-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #a5d6a7;
        margin-right: 16px;
    }
    .forum-header {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .forum-user {
        font-weight: 600;
        color: #388e3c;
    }
    .forum-time {
        color: #8bc34a;
        font-size: 0.95em;
        margin-left: 12px;
    }
    .forum-content {
        font-size: 1.1em;
        margin-bottom: 12px;
    }
    .forum-image {
        max-width: 320px;
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid #c8e6c9;
    }
    .forum-replies {
        background: #f9fbe7;
        border-radius: 12px;
        padding: 16px;
        margin-top: 18px;
    }
    .forum-reply {
        border-left: 3px solid #a5d6a7;
        padding-left: 12px;
        margin-bottom: 12px;
    }
    .forum-reply-user {
        color: #558b2f;
        font-weight: 500;
    }
    .forum-reply-time {
        color: #b2b2b2;
        font-size: 0.9em;
        margin-left: 8px;
    }
    .plant-icon {
        color: #66bb6a;
        margin-right: 8px;
        font-size: 1.3em;
    }
    .floating-action-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }
    .forum-heading {
        margin-top: 12px;
    }
</style>
<div class="forum-bg">
    <div class="container">
        @forelse($posts as $chat)
            <div class="card forum-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="forum-header">
                            @php
                                $creatorPhoto = $chat->creator->photo ?? null;
                                $photoUrl = $creatorPhoto 
                                    ? route('images.show', ['folder' => 'profile-photos', 'filename' => $creatorPhoto])
                                    : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']);
                            @endphp
                            <img src="{{ $photoUrl }}" class="forum-avatar" alt="{{ $chat->creator->first_name ?? $chat->creator->name }}" title="User: {{ $chat->creator->first_name ?? $chat->creator->name }} | Photo: {{ $creatorPhoto ?? 'No photo' }}">
                            <div>
                                <span class="forum-user">{{ $chat->creator->first_name ?? $chat->creator->name }} {{ $chat->creator->last_name ?? '' }}</span><br>
                                <span class="forum-time">{{ $chat->chat_created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <form action="{{ route('community.destroy', $chat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                    <div class="forum-content mt-3">
                        <strong>Topic:</strong> {{ $chat->chat_topic }}
                        @if($chat->attachment_url)
                            <br>
                            <img src="{{ route('images.show', ['folder' => 'chat_attachments', 'filename' => $chat->attachment_url]) }}" class="forum-image mt-2">
                        @endif
                    </div>

                    <div class="forum-replies mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <strong>Replies</strong>
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $chat->id }}" aria-expanded="false" aria-controls="replyForm{{ $chat->id }}">
                                Add Reply
                            </button>
                        </div>
                        <div class="collapse mb-3" id="replyForm{{ $chat->id }}">
                            <form action="{{ route('community.reply', $chat->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="content" class="form-control" rows="2" placeholder="Write your reply..." required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="replyAttachment{{ $chat->id }}" class="form-label">Attachment (Optional)</label>
                                    <input type="file" class="form-control" id="replyAttachment{{ $chat->id }}" name="attachment">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Submit Reply</button>
                            </form>
                        </div>
                        @forelse($chat->replies as $reply)
                            <div class="forum-reply">
                                @php
                                    $replyUserPhoto = $reply->user->photo ?? null;
                                    $replyPhotoUrl = $replyUserPhoto 
                                        ? route('images.show', ['folder' => 'profile-photos', 'filename' => $replyUserPhoto])
                                        : route('images.show', ['folder' => 'default-avatar', 'filename' => 'default-avatar.png']);
                                @endphp
                                <span class="forum-reply-user">{{ $reply->user->first_name ?? $reply->user->name }}</span>
                                <span class="forum-reply-time">{{ $reply->created_at->diffForHumans() }}</span><br>
                                {{ $reply->content }}
                                @if($reply->attachment_url)
                                    <br><img src="{{ route('images.show', ['folder' => 'chat_reply_attachments', 'filename' => $reply->attachment_url]) }}" class="forum-image mt-2">
                                @endif
                            </div>
                        @empty
                            <div class="text-muted">No replies yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No forum topics yet. Be the first to create one!</div>
        @endforelse
        <!-- Floating Action Button for New Post -->
        <button class="btn btn-success rounded-circle floating-action-button" data-bs-toggle="modal" data-bs-target="#newChatModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>
<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newChatModalLabel">Create New Topic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="chatTopic" class="form-label">Topic Title</label>
                        <input type="text" class="form-control" id="chatTopic" name="chat_topic" required>
                    </div>
                    <div class="mb-3">
                        <label for="chatAttachment" class="form-label">Attachment (Optional)</label>
                        <input type="file" class="form-control" id="chatAttachment" name="attachment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Topic</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 