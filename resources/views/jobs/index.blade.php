@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')
@section('content')
<style>
    .jobs-page-container {
        width: 100%;
        padding: 20px 40px;
    }
    
    .page-header {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        color: white;
        padding: 40px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .page-subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .filters-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .jobs-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table th {
        background: #f8f9fa;
        padding: 15px 20px;
        font-weight: 600;
        color: #495057;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
    }

    .custom-table td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        color: #212529;
    }

    .custom-table tr:hover td {
        background-color: #f8f9fa;
    }

    .job-title {
        font-weight: 600;
        color: #2b3445;
        text-decoration: none;
        font-size: 1.05rem;
        display: block;
        margin-bottom: 4px;
    }

    .job-company {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }

    .badge-full-time { background: #e3f2fd; color: #1976d2; }
    .badge-part-time { background: #fff3e0; color: #f57c00; }
    .badge-contract { background: #e8f5e9; color: #388e3c; }
    .badge-freelance { background: #f3e5f5; color: #7b1fa2; }

    .action-btn {
        background: #000DFF;
        color: white;
        padding: 8px 20px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .action-btn:hover {
        background: #0006b3;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
</style>

<div class="jobs-page-container">
    <div class="page-header">
        <h1 class="page-title">Explore Opportunities</h1>
        <p class="page-subtitle">Find your next dream job from our curated listings</p>
    </div>

    <div class="filters-card">
        <form method="GET" action="{{ route('jobs.index') }}">
            <div class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label font-weight-bold">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $locationFilter }}" placeholder="City or Country">
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label font-weight-bold">Skills</label>
                    <input type="text" name="skills" class="form-control" value="{{ $skillsFilter }}" placeholder="e.g. PHP, Design">
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="form-label font-weight-bold">Experience</label>
                    <select name="experience" class="form-control">
                        <option value="">All Levels</option>
                        <option value="Fresher / Entry Level" {{ $experienceFilter == 'Fresher / Entry Level' ? 'selected' : '' }}>Fresher / Entry Level</option>
                        <option value="Junior (1-2 Years)" {{ $experienceFilter == 'Junior (1-2 Years)' ? 'selected' : '' }}>Junior (1-2 Years)</option>
                        <option value="Mid Level (3-5 Years)" {{ $experienceFilter == 'Mid Level (3-5 Years)' ? 'selected' : '' }}>Mid Level (3-5 Years)</option>
                        <option value="Senior (5+ Years)" {{ $experienceFilter == 'Senior (5+ Years)' ? 'selected' : '' }}>Senior (5+ Years)</option>
                        <option value="Expert" {{ $experienceFilter == 'Expert' ? 'selected' : '' }}>Expert</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="form-label font-weight-bold">Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="Full-time" {{ $jobTypeFilter == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="Part-time" {{ $jobTypeFilter == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="Contract" {{ $jobTypeFilter == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Freelance" {{ $jobTypeFilter == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block" style="background: #000DFF; border: none; height: 38px;">
                        Filter Jobs
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="jobs-table-card">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Job Role</th>
                        <th>Location</th>
                        <th>Experience</th>
                        <th>Type</th>
                        <th>Salary Details</th>
                        <th>Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr>
                        <td>
                            <a href="{{ route('jobs.show', $job) }}" class="job-title">{{ $job->title }}</a>
                            <span class="job-company">{{ $job->company_name }}</span>
                        </td>
                        <td>
                            <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                            {{ $job->location }}
                        </td>
                        <td>
                            @if($job->experience_level || $job->experience_required)
                                {{ $job->experience_level ?? $job->experience_required }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-custom badge-{{ Str::slug($job->job_type) }}">
                                {{ $job->job_type }}
                            </span>
                        </td>
                        <td>
                            @if($job->salary_min || $job->salary_max)
                                <span class="font-weight-bold text-dark">
                                    {{ $job->salary_min ? number_format($job->salary_min) : '' }} 
                                    {{ $job->salary_min && $job->salary_max ? '-' : '' }}
                                    {{ $job->salary_max ? number_format($job->salary_max) : '' }}
                                </span>
                            @else
                                <span class="text-muted">Not Negotiable</span>
                            @endif
                        </td>
                        <td>
                            @if($job->closing_date)
                                <span class="{{ $job->closing_date->isPast() ? 'text-danger' : 'text-success' }}">
                                    {{ $job->closing_date->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Open</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('jobs.show', $job) }}" class="action-btn">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" alt="No jobs" style="width: 80px; opacity: 0.5; margin-bottom: 20px;">
                                <h4>No Jobs Found</h4>
                                <p>We couldn't find any jobs matching your criteria. Try adjusting your filters.</p>
                                <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary mt-3">Clear All Filters</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-container mt-4">
        {{ $jobs->withQueryString()->links() }}
    </div>
</div>
@endsection





