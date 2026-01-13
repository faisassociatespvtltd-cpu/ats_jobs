@extends('layouts.app')

@section('title', 'Job Postings')

@section('content')
<div class="form-container">
    {{-- Form Header: Title on left, Actions on right --}}
    <div class="form-header">
        <h1 class="form-title">Job Postings</h1>
        <div class="form-actions">
            <a href="{{ route('job-postings.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
            <a href="{{ route('job-postings.export-excel') }}" class="btn btn-secondary">Export Excel</a>
            <a href="{{ route('job-postings.export-pdf') }}" class="btn btn-secondary">Export PDF</a>
            <label for="import-excel" class="btn btn-secondary" style="cursor: pointer;">
                Import Excel
                <input type="file" id="import-excel" accept=".xlsx,.xls" style="display: none;" onchange="handleExcelImport(this)">
            </label>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Jobs</div>
            <div class="summary-card-value">{{ $totalJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Active Jobs</div>
            <div class="summary-card-value">{{ $activeJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Draft Jobs</div>
            <div class="summary-card-value">{{ $draftJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Closed Jobs</div>
            <div class="summary-card-value">{{ $closedJobs }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('job-postings.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search title, company, location...">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="Full-time" {{ request('job_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="Part-time" {{ request('job_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="Contract" {{ request('job_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Freelance" {{ request('job_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="posted_date" {{ request('sort_by') == 'posted_date' ? 'selected' : '' }}>Posted Date</option>
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
                <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Job Type</th>
                    <th>Status</th>
                    <th>Posted Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobPostings as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company_name }}</td>
                    <td>{{ $job->location }}</td>
                    <td>{{ $job->job_type }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $job->status == 'active' ? '#107c10' : ($job->status == 'draft' ? '#605e5c' : '#d13438') }}; color: white;">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td>{{ $job->posted_date->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('job-postings.show', $job) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('job-postings.edit', $job) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('job-postings.destroy', $job) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No job postings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $jobPostings->links() }}
    </div>
</div>

<script>
function handleExcelImport(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('file', input.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch('{{ route("job-postings.import-excel") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Excel imported successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Import failed'));
            }
        })
        .catch(error => {
            alert('Error importing Excel file');
            console.error(error);
        });
    }
}
</script>
@endsection
