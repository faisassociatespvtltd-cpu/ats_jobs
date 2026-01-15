@extends('layouts.app')

@section('title', 'Job Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">{{ $jobPosting->title }}</h1>
        <div class="form-actions">
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Back to Jobs</a>
        </div>
    </div>

    <div style="display: grid; gap: 16px;">
        <div>
            <strong>Company:</strong> {{ $jobPosting->company_name }}
        </div>
        <div>
            <strong>Location:</strong> {{ $jobPosting->location }}
        </div>
        <div>
            <strong>Job Type:</strong> {{ $jobPosting->job_type }}
        </div>
        <div>
            <strong>Salary/Compensation:</strong>
            @if($jobPosting->salary_min || $jobPosting->salary_max)
                {{ $jobPosting->salary_min ? number_format($jobPosting->salary_min, 2) : 'N/A' }} - {{ $jobPosting->salary_max ? number_format($jobPosting->salary_max, 2) : 'N/A' }}
            @else
                N/A
            @endif
        </div>
        <div>
            <strong>Application Deadline:</strong> {{ $jobPosting->closing_date?->format('Y-m-d') }}
        </div>
        <div>
            <strong>Required Skills:</strong> {{ $jobPosting->required_skills ?? 'N/A' }}
        </div>
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

    <div style="margin-top: 24px;">
        @auth
            @if(auth()->user()->isEmployee())
                @if($hasApplied)
                    <button class="btn btn-secondary" disabled>Already Applied</button>
                @else
                    <form method="POST" action="{{ route('jobs.apply', $jobPosting) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Easy Apply</button>
                    </form>
                @endif
            @else
                <div style="color: #605e5c;">Only employees can apply to jobs.</div>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login to Apply</a>
        @endauth
    </div>
</div>
@endsection

