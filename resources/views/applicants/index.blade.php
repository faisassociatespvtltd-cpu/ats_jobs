@extends('layouts.app')

@section('title', 'Applicants')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Applicants</h1>
        <div class="form-actions">
            <a href="{{ route('applicants.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Applicants</div>
            <div class="summary-card-value">{{ $totalApplicants }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Pending</div>
            <div class="summary-card-value">{{ $pendingApplicants }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Shortlisted</div>
            <div class="summary-card-value">{{ $shortlistedApplicants }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Hired</div>
            <div class="summary-card-value">{{ $hiredApplicants }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('applicants.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name or email...">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
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
                    <label>Job Posting</label>
                    <select name="job_posting_id" class="form-control">
                        <option value="">All Jobs</option>
                        @foreach($jobPostings as $job)
                        <option value="{{ $job->id }}" {{ request('job_posting_id') == $job->id ? 'selected' : '' }}>
                            {{ $job->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                        <option value="application_date" {{ request('sort_by') == 'application_date' ? 'selected' : '' }}>Application Date</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Order</label>
                    <select name="sort_order" class="form-control">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('applicants.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Job Posting</th>
                    <th>Status</th>
                    <th>Application Date</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applicants as $applicant)
                <tr>
                    <td>{{ $applicant->first_name }} {{ $applicant->last_name }}</td>
                    <td>{{ $applicant->email }}</td>
                    <td>{{ $applicant->phone ?? 'N/A' }}</td>
                    <td>{{ $applicant->jobPosting->title ?? 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: 
                            @if($applicant->status == 'hired') #107c10
                            @elseif($applicant->status == 'shortlisted' || $applicant->status == 'offered') #0078D4
                            @elseif($applicant->status == 'rejected') #d13438
                            @else #605e5c
                            @endif; color: white;">
                            {{ ucfirst($applicant->status) }}
                        </span>
                    </td>
                    <td>{{ $applicant->application_date->format('Y-m-d') }}</td>
                    <td>
                        @if($applicant->rating)
                            {{ number_format($applicant->rating, 1) }}/5.0
                        @else
                            <span style="color: #605e5c;">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('applicants.edit', $applicant) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('applicants.destroy', $applicant) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px;">No applicants found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $applicants->links() }}
    </div>
</div>
@endsection

