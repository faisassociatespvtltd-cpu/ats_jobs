@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Available Jobs</h1>
    </div>

    <div class="filters-section">
        <form method="GET" action="{{ route('jobs.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $locationFilter }}" placeholder="City or country">
                </div>
                <div class="form-group">
                    <label>Skills</label>
                    <input type="text" name="skills" class="form-control" value="{{ $skillsFilter }}" placeholder="e.g., PHP, Laravel">
                </div>
                <div class="form-group">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="Full-time" {{ $jobTypeFilter == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="Part-time" {{ $jobTypeFilter == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="Contract" {{ $jobTypeFilter == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Freelance" {{ $jobTypeFilter == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Job Type</th>
                    <th>Salary</th>
                    <th>Posted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td>
                        <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                    </td>
                    <td>{{ $job->company_name }}</td>
                    <td>{{ $job->location }}</td>
                    <td>{{ $job->job_type }}</td>
                    <td>
                        @if($job->salary_min || $job->salary_max)
                            {{ $job->salary_min ? number_format($job->salary_min, 2) : 'N/A' }} - {{ $job->salary_max ? number_format($job->salary_max, 2) : 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $job->posted_date?->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No jobs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
