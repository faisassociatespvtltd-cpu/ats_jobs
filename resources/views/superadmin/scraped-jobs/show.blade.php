@extends('layouts.app')

@section('title', 'Scraped Job Details')

@section('content')
    <div class="form-header">
        <h1 class="form-title">Job Details: {{ $job->title }}</h1>
        <div class="form-actions">
            <a href="{{ route('superadmin.scraped-jobs') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            @if($job->status === 'pending' || !$job->parsed_data)
                <form method="POST" action="{{ route('superadmin.scraped-jobs.parse', $job->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Parse Job</button>
                </form>
            @endif
            @if($job->status !== 'approved')
                <form method="POST" action="{{ route('superadmin.scraped-jobs.approve', $job->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            @endif
            @if($job->status !== 'rejected')
                <form method="POST" action="{{ route('superadmin.scraped-jobs.reject', $job->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            @endif
        </div>
    </div>

    <div class="form-container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Job Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Title:</strong></div>
                            <div class="col-sm-9">{{ $job->title }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Company:</strong></div>
                            <div class="col-sm-9">{{ $job->company_name ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Location:</strong></div>
                            <div class="col-sm-9">{{ $job->location ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Salary:</strong></div>
                            <div class="col-sm-9">{{ $job->salary ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Job Type:</strong></div>
                            <div class="col-sm-9">{{ $job->job_type ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Source:</strong></div>
                            <div class="col-sm-9">
                                <span class="badge {{ $job->source === 'whatsapp' ? 'bg-success' : ($job->source === 'linkedin' ? 'bg-primary' : 'bg-secondary') }}">
                                    {{ ucfirst($job->source) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Status:</strong></div>
                            <div class="col-sm-9">
                                <span class="badge {{ $job->status === 'approved' ? 'bg-success' : ($job->status === 'rejected' ? 'bg-danger' : ($job->status === 'parsed' ? 'bg-info' : 'bg-warning')) }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                        </div>
                        @if($job->source_url)
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Source URL:</strong></div>
                            <div class="col-sm-9">
                                <a href="{{ $job->source_url }}" target="_blank">{{ $job->source_url }}</a>
                            </div>
                        </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Scraped At:</strong></div>
                            <div class="col-sm-9">{{ $job->scraped_at ? $job->scraped_at->format('M d, Y H:i') : 'N/A' }}</div>
                        </div>
                        <div class="mt-4">
                            <h5>Description</h5>
                            <div class="p-3 bg-light border rounded">
                                {!! nl2br(e($job->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Parsed Data</h5>
                    </div>
                    <div class="card-body">
                        @if($job->parsed_data)
                            <pre class="bg-light p-2 border rounded" style="white-space: pre-wrap; font-size: 0.85rem;">{{ json_encode($job->parsed_data, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <p class="text-muted">No parsed data available yet. Click "Parse Job" to extract structured information.</p>
                        @endif
                    </div>
                </div>
                
                @if($job->user)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Scraped By</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>User:</strong> {{ $job->user->name }}</p>
                        <p><strong>Email:</strong> {{ $job->user->email }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
