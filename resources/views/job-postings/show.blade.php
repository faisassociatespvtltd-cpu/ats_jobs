@extends('layouts.app')

@section('title', 'Job Posting Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">{{ $jobPosting->title }}</h1>
        <div class="form-actions">
            <a href="{{ route('job-postings.edit', $jobPosting) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div style="display: grid; gap: 16px;">
        <div><strong>Company:</strong> {{ $jobPosting->company_name }}</div>
        <div><strong>Location:</strong> {{ $jobPosting->location }}</div>
        <div><strong>Job Type:</strong> {{ $jobPosting->job_type }}</div>
        <div><strong>Required Skills:</strong> {{ $jobPosting->required_skills ?? 'N/A' }}</div>
        @if($jobPosting->education_required)
        <div><strong>Education Required:</strong> {{ $jobPosting->education_required }}</div>
        @endif
        @if($jobPosting->experience_required)
        <div><strong>Experience Required:</strong> {{ $jobPosting->experience_required }}</div>
        @endif
        @if($jobPosting->responsibilities)
        <div>
            <strong>Responsibilities:</strong>
            <p style="margin: 8px 0;">{{ $jobPosting->responsibilities }}</p>
        </div>
        @endif
        @if($jobPosting->qualifications)
        <div>
            <strong>Qualifications:</strong>
            <p style="margin: 8px 0;">{{ $jobPosting->qualifications }}</p>
        </div>
        @endif
        @if($jobPosting->salary_range)
        <div><strong>Salary Range:</strong> {{ $jobPosting->salary_range }}</div>
        @endif
        <div><strong>Application Deadline:</strong> {{ $jobPosting->closing_date?->format('Y-m-d') }}</div>
        <div><strong>Status:</strong> {{ ucfirst($jobPosting->status) }}</div>
        <div>
            <strong>Description:</strong>
            <p style="margin: 8px 0;">{{ $jobPosting->description }}</p>
        </div>
        @if($jobPosting->other_details)
        <div>
            <strong>Other Details:</strong>
            <p style="margin: 8px 0;">{{ $jobPosting->other_details }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

