@extends('layouts.app')

@section('title', 'My Job Postings')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">My Jobs</h1>
        <div class="form-actions">
            <a href="{{ route('job-postings.create') }}" class="btn btn-primary">Add New Job</a>
        </div>
    </div>
    <div class="filters-section mb-4">
        <form method="GET" action="{{ route('employer.jobs') }}">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label class="small text-muted">Search Jobs</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Title, location...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="small text-muted">Status</label>
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label class="small text-muted">Job Type</label>
                        <select name="job_type" class="form-control">
                            <option value="">All Types</option>
                            <option value="Full-time" {{ request('job_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ request('job_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ request('job_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Freelance" {{ request('job_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-container">
        <form id="bulk-action-form" action="{{ route('employer.jobs.bulk-status') }}" method="POST">
            @csrf
            <input type="hidden" name="status" id="bulk-status-input" value="">
            
            <div class="mb-3 d-flex gap-2 align-items-center" id="bulk-actions-bar" style="display: none !important;">
                <span class="text-muted small"><span id="selected-count">0</span> jobs selected:</span>
                <button type="button" class="btn btn-sm btn-success bulk-btn" data-status="active">Make Active</button>
                <button type="button" class="btn btn-sm btn-warning bulk-btn" data-status="inactive">Make Inactive</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="select-all-jobs">
                        </th>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Applicants</th>
                        <th>Posted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr>
                        <td>
                            <input type="checkbox" name="job_ids[]" value="{{ $job->id }}" class="job-checkbox">
                        </td>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->location }}</td>
                        <td>
                            <span class="badge {{ $job->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td>{{ $job->applicants_count }}</td>
                        <td>{{ $job->posted_date?->format('Y-m-d') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('job-postings.show', $job) }}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{ route('job-postings.edit', $job) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <a href="{{ route('employer.jobs.applicants', $job) }}" class="btn btn-primary btn-sm">Applicants</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this job posting?')) document.getElementById('delete-form-{{ $job->id }}').submit();">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px;">No jobs posted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    @foreach($jobs as $job)
        <form id="delete-form-{{ $job->id }}" action="{{ route('job-postings.destroy', $job) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <div class="pagination-container">
        {{ $jobs->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all-jobs');
    const checkboxes = document.querySelectorAll('.job-checkbox');
    const bulkBar = document.getElementById('bulk-actions-bar');
    const selectedCount = document.getElementById('selected-count');
    const bulkBtns = document.querySelectorAll('.bulk-btn');
    const statusInput = document.getElementById('bulk-status-input');
    const bulkForm = document.getElementById('bulk-action-form');

    function updateBulkBar() {
        const checkedCount = document.querySelectorAll('.job-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
        
        if (checkedCount > 0) {
            bulkBar.style.setProperty('display', 'flex', 'important');
        } else {
            bulkBar.style.setProperty('display', 'none', 'important');
        }
    }

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });
        updateBulkBar();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    bulkBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const status = this.dataset.status;
            if (confirm(`Are you sure you want to make the selected jobs ${status}?`)) {
                statusInput.value = status;
                bulkForm.submit();
            }
        });
    });
});
</script>
@endpush
@endsection




