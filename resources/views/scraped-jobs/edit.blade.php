@extends('layouts.app')

@section('title', 'Edit Scraped Job')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Scraped Job</h1>
        <div class="form-actions">
            <a href="{{ route('scraped-jobs.show', $scrapedJob) }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <form method="POST" action="{{ route('scraped-jobs.update', $scrapedJob) }}" style="max-width: 800px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $scrapedJob->title) }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Company</label>
            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $scrapedJob->company_name) }}">
            @error('company_name') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $scrapedJob->location) }}">
            @error('location') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Source</label>
            <input type="text" name="source" class="form-control" value="{{ old('source', $scrapedJob->source) }}">
            @error('source') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Job URL</label>
            <input type="url" name="source_url" class="form-control" value="{{ old('source_url', $scrapedJob->source_url) }}">
            @error('source_url') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Salary</label>
            <input type="text" name="salary" class="form-control" value="{{ old('salary', $scrapedJob->salary) }}">
            @error('salary') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Job Type</label>
            <input type="text" name="job_type" class="form-control" value="{{ old('job_type', $scrapedJob->job_type) }}">
            @error('job_type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ old('status', $scrapedJob->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ old('status', $scrapedJob->status) == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="imported" {{ old('status', $scrapedJob->status) == 'imported' ? 'selected' : '' }}>Imported</option>
                <option value="rejected" {{ old('status', $scrapedJob->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Description *</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description', $scrapedJob->description) }}</textarea>
            @error('description') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection


