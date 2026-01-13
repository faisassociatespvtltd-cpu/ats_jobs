@extends('layouts.app')

@section('title', 'Scraped Jobs')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Job Scraping & Portal</h1>
        <div class="form-actions">
            <a href="{{ route('scraped-jobs.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Scraped</div>
            <div class="summary-card-value">{{ $totalScraped }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">WhatsApp</div>
            <div class="summary-card-value">{{ $whatsappJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">LinkedIn</div>
            <div class="summary-card-value">{{ $linkedinJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Facebook</div>
            <div class="summary-card-value">{{ $facebookJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Other</div>
            <div class="summary-card-value">{{ $otherJobs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Pending</div>
            <div class="summary-card-value">{{ $pendingJobs }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('scraped-jobs.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search title or company...">
                </div>
                <div class="form-group">
                    <label>Source</label>
                    <select name="source" class="form-control">
                        <option value="">All Sources</option>
                        <option value="whatsapp" {{ request('source') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="linkedin" {{ request('source') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                        <option value="facebook" {{ request('source') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="imported" {{ request('status') == 'imported' ? 'selected' : '' }}>Imported</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="scraped_at" {{ request('sort_by') == 'scraped_at' ? 'selected' : '' }}>Scraped Date</option>
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
                <a href="{{ route('scraped-jobs.index') }}" class="btn btn-secondary">Clear</a>
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
                    <th>Source</th>
                    <th>Status</th>
                    <th>Scraped Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scrapedJobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->company_name ?? 'N/A' }}</td>
                    <td>{{ $job->location ?? 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #605e5c; color: white;">
                            {{ ucfirst($job->source) }}
                        </span>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $job->status == 'imported' ? '#107c10' : ($job->status == 'pending' ? '#605e5c' : ($job->status == 'reviewed' ? '#0078D4' : '#d13438')) }}; color: white;">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td>{{ $job->scraped_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('scraped-jobs.show', $job) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('scraped-jobs.edit', $job) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('scraped-jobs.destroy', $job) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No scraped jobs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $scrapedJobs->links() }}
    </div>
</div>
@endsection

