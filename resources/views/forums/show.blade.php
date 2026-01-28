@extends('layouts.app')

@section('title', $forum->title)

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Forum Discussion</h1>
        <div class="form-actions">
            <a href="{{ route('forums.edit', $forum) }}" class="btn btn-primary">Edit Post</a>
            <a href="{{ route('forums.index') }}" class="btn btn-secondary">Back to Forums</a>
        </div>
    </div>

    <div style="max-width: 1000px;">
        {{-- Original Post --}}
        <div style="background: white; border: 1px solid #edebe9; border-radius: 4px; margin-bottom: 24px;">
            <div style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                    <div>
                        <h1 style="font-size: 24px; color: #323130; margin-bottom: 8px;">{{ $forum->title }}</h1>
                        <div style="color: #605e5c; font-size: 14px;">
                            Posted by <strong>{{ $forum->user->name ?? 'N/A' }}</strong>
                            • {{ $forum->created_at->diffForHumans() }}
                            • {{ $forum->views }} views
                            @if($forum->category)
                                • <span style="background: #f3f2f1; padding: 2px 8px; border-radius: 12px;">{{ $forum->category }}</span>
                            @endif
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        @if($forum->is_pinned) <span title="Pinned">📌</span> @endif
                        @if($forum->is_locked) <span title="Locked">🔒</span> @endif
                    </div>
                </div>
                <div style="font-size: 16px; line-height: 1.6; color: #323130; white-space: pre-wrap;">{{ $forum->content }}</div>
            </div>
        </div>

        {{-- Replies --}}
        <h2 style="font-size: 18px; color: #323130; margin: 32px 0 16px;">{{ $forum->replies->count() }} Replies</h2>
        
        @foreach($forum->replies as $reply)
            <div style="background: #faf9f8; border: 1px solid #edebe9; border-radius: 4px; margin-bottom: 16px; margin-left: 24px;">
                <div style="padding: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: #605e5c;">
                        <span><strong>{{ $reply->user->name ?? 'N/A' }}</strong> replied {{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="font-size: 15px; line-height: 1.5; color: #323130; white-space: pre-wrap;">{{ $reply->content }}</div>
                </div>
            </div>
        @endforeach

        {{-- Add Reply (Conceptual for now as there's no reply controller visible) --}}
        <div style="margin-top: 32px; padding: 24px; border-top: 1px solid #edebe9;">
            <p style="color: #605e5c; font-size: 14px;">Note: To reply, please use the discussion interface.</p>
        </div>
    </div>
</div>
@endsection
