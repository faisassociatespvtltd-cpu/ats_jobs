@extends('layouts.app')

@section('title', 'Job Applicants')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Applicants for: {{ $jobPosting->title }}</h1>
        <div class="form-actions">
            <a href="{{ route('employer.jobs') }}" class="btn btn-secondary">Back to Jobs</a>
        </div>
    </div>

    <div class="filters-section">
        <form method="GET" action="{{ route('employer.jobs.applicants', $jobPosting) }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, email, phone...">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                        <option value="offered" {{ request('status') == 'offered' ? 'selected' : '' }}>Offered</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
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
                    <th>Employee</th>
                    <th>Contact</th>
                    <th>CV</th>
                    <th>Profile Details</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applicants as $applicant)
                <tr>
                    <td>{{ trim($applicant->first_name . ' ' . $applicant->last_name) }}</td>
                    <td>
                        <div>{{ $applicant->email }}</div>
                        <div>{{ $applicant->phone ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @if($applicant->cv_path)
                            <a href="{{ asset('storage/' . $applicant->cv_path) }}" target="_blank">View CV</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($applicant->user?->employeeProfile)
                            <div><strong>Skills:</strong> {{ $applicant->user->employeeProfile->skills ?? 'N/A' }}</div>
                            <div><strong>Experience:</strong> {{ $applicant->user->employeeProfile->experience ?? 'N/A' }}</div>
                            <div><strong>Location:</strong> {{ $applicant->user->employeeProfile->location ?? $applicant->user->employeeProfile->city ?? 'N/A' }}</div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ ucfirst($applicant->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 24px;">No applicants found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $applicants->links() }}
    </div>
</div>
@endsection


