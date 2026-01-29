@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
    <div class="form-header">
        <h1 class="form-title">Super Admin Dashboard</h1>
        <div class="form-actions">
            <span class="badge bg-primary">Real-time Analytics</span>
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Users</div>
            <div class="summary-card-value">{{ number_format($stats['total_users']) }}</div>
            <div style="font-size: 11px; margin-top: 8px;">
                <span style="color: var(--success-color)">●</span> {{ $stats['total_employees'] }} Employees<br>
                <span style="color: var(--primary-color)">●</span> {{ $stats['total_employers'] }} Employers
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Job Postings</div>
            <div class="summary-card-value">{{ number_format($stats['total_jobs']) }}</div>
            <div style="font-size: 11px; margin-top: 8px;">
                <span style="color: var(--success-color)">●</span> {{ $stats['active_jobs'] }} Active Jobs
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Applications</div>
            <div class="summary-card-value">{{ number_format($stats['total_applications']) }}</div>
            <div style="font-size: 11px; margin-top: 8px;">
                <span style="color: var(--primary-color)">●</span> {{ $stats['total_interviews'] }} Interviews
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Success Rate</div>
            <div class="summary-card-value">
                @if($stats['total_applications'] > 0)
                    {{ round(($stats['hires'] / $stats['total_applications']) * 100, 1) }}%
                @else
                    0%
                @endif
            </div>
            <div style="font-size: 11px; margin-top: 8px;">
                <span style="color: var(--success-color)">●</span> {{ $stats['hires'] }} Hired
                <span style="color: var(--error-color); margin-left: 8px;">●</span> {{ $stats['rejected'] }} Rejected
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-container">
                <h3 class="form-title" style="font-size: 16px; margin-bottom: 16px;">System Activity</h3>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Status</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td>
                                        <strong>{{ ucwords(str_replace('_', ' ', $log->type)) }}</strong><br>
                                        <small class="text-muted">{{ $log->description }}</small>
                                    </td>
                                    <td>
                                        @if($log->confidence_score >= 80)
                                            <span class="badge bg-success">High ({{ $log->confidence_score }}%)</span>
                                        @elseif($log->confidence_score >= 50)
                                            <span class="badge bg-warning">Med ({{ $log->confidence_score }}%)</span>
                                        @else
                                            <span class="badge bg-danger">Low ({{ $log->confidence_score }}%)</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No recent activity logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-container">
                <h3 class="form-title" style="font-size: 16px; margin-bottom: 16px;">Quick Controls</h3>
                <div class="d-grid gap-2">
                    <a href="{{ route('superadmin.users') }}" class="btn btn-primary">
                        Manage Users
                    </a>
                    <a href="{{ route('superadmin.scraped-jobs') }}" class="btn btn-secondary">
                        Manage Scraped Jobs
                    </a>
                    <a href="{{ route('superadmin.scraped-jobs.scrape') }}" class="btn btn-success">
                        Scrape New Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection