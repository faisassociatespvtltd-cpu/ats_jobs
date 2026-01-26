@extends('layouts.app')

@section('title', 'Scraped Job Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">{{ $scrapedJob->title }}</h1>
        <div class="form-actions">
            <a href="{{ route('scraped-jobs.edit', $scrapedJob) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('scraped-jobs.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div style="display: grid; gap: 16px;">
        <div><strong>Source:</strong> {{ ucfirst($scrapedJob->source) }}</div>
        <div><strong>Company:</strong> {{ $scrapedJob->company_name ?? 'N/A' }}</div>
        <div><strong>Location:</strong> {{ $scrapedJob->location ?? 'N/A' }}</div>
        <div><strong>Job Type:</strong> {{ $scrapedJob->job_type ?? 'N/A' }}</div>
        <div><strong>Salary:</strong> {{ $scrapedJob->salary ?? 'N/A' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($scrapedJob->status) }}</div>
        <div>
            <strong>Description:</strong>
            <p style="margin: 8px 0;">{{ $scrapedJob->description }}</p>
        </div>
        @if($scrapedJob->source_url)
        <div><strong>Source URL:</strong> <a href="{{ $scrapedJob->source_url }}" target="_blank">{{ $scrapedJob->source_url }}</a></div>
        @endif
        @if($scrapedJob->scraped_at)
        <div><strong>Scraped At:</strong> {{ $scrapedJob->scraped_at->format('Y-m-d H:i') }}</div>
        @endif
    </div>
</div>
@endsection


