@extends('layouts.app')

@section('title', 'Scraped Jobs Management')

@section('content')
    <div class="form-header">
        <h1 class="form-title">Scraped Jobs Management</h1>
        <div class="form-actions">
            <a href="{{ route('superadmin.scraped-jobs.scrape') }}" class="btn btn-primary">
                + Scrape New Jobs
            </a>
        </div>
    </div>

    <div class="form-container">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Source</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Scraped At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scrapedJobs as $job)
                        <tr>
                            <td>{{ $job->id }}</td>
                            <td>
                                @if($job->source === 'whatsapp')
                                    <span class="badge bg-success">WhatsApp</span>
                                @elseif($job->source === 'linkedin')
                                    <span class="badge bg-primary">LinkedIn</span>
                                @elseif($job->source === 'facebook')
                                    <span class="badge bg-info">Facebook</span>
                                @elseif($job->source === 'instagram')
                                    <span class="badge bg-danger">Instagram</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($job->source) }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $job->title }}</strong><br>
                                <small class="text-muted">{{ Str::limit($job->description, 50) }}</small>
                            </td>
                            <td>{{ $job->company_name ?? 'N/A' }}</td>
                            <td>
                                @if($job->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($job->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($job->status === 'parsed')
                                    <span class="badge bg-info">Parsed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $job->scraped_at ? $job->scraped_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <div class="action-buttons">
                                    @if($job->status === 'pending' || !$job->parsed_data)
                                        <form method="POST" action="{{ route('superadmin.scraped-jobs.parse', $job->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Parse</button>
                                        </form>
                                    @endif

                                    @if($job->status !== 'approved')
                                        <form method="POST" action="{{ route('superadmin.scraped-jobs.approve', $job->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    @endif

                                    @if($job->status !== 'rejected')
                                        <form method="POST" action="{{ route('superadmin.scraped-jobs.reject', $job->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No scraped jobs found. Click "Scrape New Jobs" to
                                start.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $scrapedJobs->links() }}
        </div>
    </div>
@endsection