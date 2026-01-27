@extends('layouts.app')

@section('title', 'Create Forum Post')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Create Forum Post</h1>
        <div class="form-actions">
            <a href="{{ route('forums.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('forums.store') }}" style="max-width: 800px;">
        @csrf

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="e.g., General, Technical, Career Advice">
            @error('category') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Content *</label>
            <textarea name="content" class="form-control" rows="10" required>{{ old('content') }}</textarea>
            @error('content') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Create Post</button>
            <a href="{{ route('forums.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
