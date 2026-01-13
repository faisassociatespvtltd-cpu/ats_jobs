@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <div class="summary-card">
        <div class="summary-card-title">Total Jobs</div>
        <div class="summary-card-value">{{ $totalJobs }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-title">Total Applicants</div>
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

<h2 style="margin-bottom: 16px;">Welcome to ATS Job Site</h2>
<p>Use the navigation menu above to access different modules.</p>
@endsection

