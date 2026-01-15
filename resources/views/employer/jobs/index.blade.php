@extends('layouts.app')

@section('title', 'My Job Postings')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">My Job Postings</h1>
        <div class="form-actions">
            <a href="{{ route('job-postings.create') }}" class="btn btn-primary">Post a Job</a>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
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
                    <td>{{ $job->title }}</td>
                    <td>{{ $job->location }}</td>
                    <td>{{ ucfirst($job->status) }}</td>
                    <td>{{ $job->applicants_count }}</td>
                    <td>{{ $job->posted_date?->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('job-postings.edit', $job) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="{{ route('employer.jobs.applicants', $job) }}" class="btn btn-primary btn-sm">Applicants</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 24px;">No jobs posted yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $jobs->links() }}
    </div>
</div>
@endsection

