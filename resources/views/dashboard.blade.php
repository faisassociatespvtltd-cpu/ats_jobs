@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <div class="summary-card">
        <div class="summary-card-title">Total Jobs</div>
        <div class="summary-card-value">{{ $totalJobs }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-title">
            @if(auth()->user()->isEmployer())
                Total Applicants
            @else
                My Applications
            @endif
        </div>
        <div class="summary-card-value">{{ $totalApplicants }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-title">Total Interviews</div>
        <div class="summary-card-value">{{ $totalInterviews }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-title">Active Jobs</div>
        <div class="summary-card-value">{{ $activeJobs }}</div>
    </div>
</div>

@if(auth()->user()->isEmployer())
    <h2 style="margin-bottom: 16px;">Employer Dashboard</h2>
    <p style="margin-bottom: 16px;">Post jobs and review applicants for your openings.</p>
    <div class="form-actions" style="gap: 12px; display: flex;">
        <a href="{{ route('job-postings.create') }}" class="btn btn-primary">Post a Job</a>
        <a href="{{ route('employer.jobs') }}" class="btn btn-secondary">My Job Postings</a>
    </div>
@else
    <h2 style="margin-bottom: 16px;">Employee Dashboard</h2>
    <p style="margin-bottom: 16px;">Browse jobs and apply with one click.</p>
    <div class="form-actions" style="gap: 12px; display: flex;">
        <a href="{{ route('jobs.index') }}" class="btn btn-primary">Job Board</a>
        <a href="{{ route('employee.profile') }}" class="btn btn-secondary">My Profile</a>
    </div>
@endif
@endsection

