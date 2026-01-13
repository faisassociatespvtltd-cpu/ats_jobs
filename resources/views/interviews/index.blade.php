@extends('layouts.app')

@section('title', 'Interviews')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Interviews</h1>
        <div class="form-actions">
            <a href="{{ route('interviews.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Interviews</div>
            <div class="summary-card-value">{{ $totalInterviews }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Scheduled</div>
            <div class="summary-card-value">{{ $scheduledInterviews }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Completed</div>
            <div class="summary-card-value">{{ $completedInterviews }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Cancelled</div>
            <div class="summary-card-value">{{ $cancelledInterviews }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('interviews.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by applicant name...">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="scheduled_at" {{ request('sort_by') == 'scheduled_at' ? 'selected' : '' }}>Scheduled Date</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
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
                <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Job Posting</th>
                    <th>Scheduled At</th>
                    <th>Interview Type</th>
                    <th>Interviewer</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($interviews as $interview)
                <tr>
                    <td>{{ $interview->applicant->first_name ?? 'N/A' }} {{ $interview->applicant->last_name ?? '' }}</td>
                    <td>{{ $interview->jobPosting->title ?? 'N/A' }}</td>
                    <td>{{ $interview->scheduled_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $interview->interview_type }}</td>
                    <td>{{ $interview->interviewer->name ?? 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $interview->status == 'completed' ? '#107c10' : ($interview->status == 'scheduled' ? '#0078D4' : ($interview->status == 'cancelled' ? '#d13438' : '#605e5c')) }}; color: white;">
                            {{ ucfirst($interview->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('interviews.show', $interview) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('interviews.destroy', $interview) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No interviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $interviews->links() }}
    </div>
</div>
@endsection

