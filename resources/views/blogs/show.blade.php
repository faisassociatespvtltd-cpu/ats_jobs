@extends('layouts.app')

@section('title', $blog->title)

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Blog Post</h1>
        <div class="form-actions">
            <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary">Edit Post</a>
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <article style="max-width: 900px;">
        <header style="margin-bottom: 32px; border-bottom: 1px solid #edebe9; padding-bottom: 24px;">
            <h1 style="font-size: 32px; margin-bottom: 16px; color: #323130;">{{ $blog->title }}</h1>
            
            <div style="display: flex; align-items: center; gap: 16px; color: #605e5c; font-size: 14px;">
                <span>By <strong>{{ $blog->author->name ?? 'N/A' }}</strong></span>
                <span>•</span>
                <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Draft' }}</span>
                <span>•</span>
                <span>{{ $blog->views }} views</span>
                @if($blog->is_featured)
                    <span style="background: #ffaa44; color: white; padding: 2px 8px; border-radius: 2px; font-size: 12px;">Featured</span>
                @endif
            </div>
        </header>

        @if($blog->excerpt)
            <div style="font-size: 18px; color: #605e5c; line-height: 1.6; margin-bottom: 24px; font-style: italic;">
                {{ $blog->excerpt }}
            </div>
        @endif

        <div style="font-size: 16px; line-height: 1.8; color: #323130;">
            {!! nl2br(e($blog->content)) !!}
        </div>

        <footer style="margin-top: 48px; padding-top: 24px; border-top: 1px solid #edebe9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 8px;">
                <span style="background: #f3f2f1; padding: 4px 12px; border-radius: 16px; font-size: 12px;">{{ ucfirst($blog->status) }}</span>
            </div>
            
            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete Post</button>
            </form>
        </footer>
    </article>
</div>
@endsection
