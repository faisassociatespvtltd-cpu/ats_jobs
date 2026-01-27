@extends('layouts.app')

@section('title', 'Edit Labour Law Resource')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Labour Law Resource</h1>
        <div class="form-actions">
            <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('labour-laws.update', $labourLaw) }}" style="max-width: 800px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Type *</label>
            <select name="type" class="form-control" required>
                <option value="law" {{ old('type', $labourLaw->type) == 'law' ? 'selected' : '' }}>Law</option>
                <option value="article" {{ old('type', $labourLaw->type) == 'article' ? 'selected' : '' }}>Article</option>
                <option value="book" {{ old('type', $labourLaw->type) == 'book' ? 'selected' : '' }}>Book</option>
                <option value="qa" {{ old('type', $labourLaw->type) == 'qa' ? 'selected' : '' }}>Q&A</option>
            </select>
            @error('type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $labourLaw->title) }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Country</label>
            <input type="text" name="country" class="form-control" value="{{ old('country', $labourLaw->country) }}">
            @error('country') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $labourLaw->category) }}">
            @error('category') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Content *</label>
            <textarea name="content" class="form-control" rows="10" required>{{ old('content', $labourLaw->content) }}</textarea>
            @error('content') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $labourLaw->is_featured) ? 'checked' : '' }}>
                <span>Is Featured?</span>
            </label>
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update Resource</button>
            <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
