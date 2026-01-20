@extends('layouts.app')

@section('title', 'Create Labour Law')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Create Labour Law</h1>
        <div class="form-actions">
            <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('labour-laws.store') }}" style="max-width: 800px;">
        @csrf

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Type *</label>
            <select name="type" class="form-control" required>
                <option value="">Select...</option>
                <option value="law" {{ old('type') == 'law' ? 'selected' : '' }}>Country Law</option>
                <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}>Article & Book</option>
                <option value="qa" {{ old('type') == 'qa' ? 'selected' : '' }}>Legal Q&A</option>
            </select>
            @error('type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Country *</label>
            <input type="text" name="country" class="form-control" value="{{ old('country') }}" required>
            @error('country') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Summary</label>
            <textarea name="summary" class="form-control" rows="3">{{ old('summary') }}</textarea>
            @error('summary') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Content *</label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
            @error('content') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

