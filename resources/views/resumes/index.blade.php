@extends('layouts.app')

@section('title', 'Resume Parsing')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Resume Parsing</h1>
        <div class="form-actions">
            <a href="{{ route('resumes.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Resumes</div>
            <div class="summary-card-value">{{ $totalResumes }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Parsed</div>
            <div class="summary-card-value">{{ $parsedResumes }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Pending</div>
            <div class="summary-card-value">{{ $pendingResumes }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Failed</div>
            <div class="summary-card-value">{{ $failedResumes }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('resumes.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by applicant name or email...">
                </div>
                <div class="form-group">
                    <label>Parsing Status</label>
                    <select name="parsing_status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('parsing_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('parsing_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('parsing_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="file_name" {{ request('sort_by') == 'file_name' ? 'selected' : '' }}>File Name</option>
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
                <a href="{{ route('resumes.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Applicant</th>
                    <th>File Type</th>
                    <th>File Size</th>
                    <th>Parsing Status</th>
                    <th>Upload Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resumes as $resume)
                <tr>
                    <td>{{ $resume->file_name }}</td>
                    <td>
                        @if($resume->applicant)
                            {{ $resume->applicant->first_name }} {{ $resume->applicant->last_name }}
                        @else
                            <span style="color: #605e5c;">Not assigned</span>
                        @endif
                    </td>
                    <td>{{ strtoupper($resume->file_type) }}</td>
                    <td>{{ number_format($resume->file_size / 1024, 2) }} KB</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $resume->parsing_status == 'completed' ? '#107c10' : ($resume->parsing_status == 'pending' ? '#605e5c' : '#d13438') }}; color: white;">
                            {{ ucfirst($resume->parsing_status) }}
                        </span>
                    </td>
                    <td>{{ $resume->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('resumes.show', $resume) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('resumes.edit', $resume) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('resumes.destroy', $resume) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No resumes found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $resumes->links() }}
    </div>
</div>
@endsection

