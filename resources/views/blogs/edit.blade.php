@extends('layouts.app')

@section('title', 'Edit Blog')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Blog</h1>
        <div class="form-actions">
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('blogs.update', $blog) }}" style="max-width: 800px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Excerpt</label>
            <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
            @error('excerpt') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Content *</label>
            <textarea name="content" class="form-control" rows="10" required>{{ old('content', $blog->content) }}</textarea>
            @error('content') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ old('status', $blog->status) == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            @error('status') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}>
                <span>Is Featured?</span>
            </label>
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update Blog</button>
            <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
