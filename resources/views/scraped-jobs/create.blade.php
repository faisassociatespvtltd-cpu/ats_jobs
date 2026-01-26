@extends('layouts.app')

@section('title', 'Create Scraped Job')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Create Scraped Job</h1>
        <div class="form-actions">
            <a href="{{ route('scraped-jobs.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('scraped-jobs.store') }}" style="max-width: 800px;">
        @csrf

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Company</label>
            <input type="text" name="company" class="form-control" value="{{ old('company') }}">
            @error('company') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            @error('location') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Source</label>
            <input type="text" name="source" class="form-control" value="{{ old('source') }}" placeholder="e.g., LinkedIn, WhatsApp, Facebook">
            @error('source') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Job URL</label>
            <input type="url" name="url" class="form-control" value="{{ old('url') }}">
            @error('url') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Description *</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
            @error('description') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('scraped-jobs.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


