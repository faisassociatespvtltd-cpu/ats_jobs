@extends('layouts.app')

@section('title', 'Scraped Job Applicants')

@section('content')
    <div class="form-header">
        <h1 class="form-title">Scraped Job Applicants</h1>
        <div class="form-actions">
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="form-container">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Job Applied For</th>
                        <th>Source</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applicants as $applicant)
                        <tr>
                            <td>
                                <strong>{{ $applicant->first_name }} {{ $applicant->last_name }}</strong><br>
                                <small class="text-muted">{{ $applicant->email }}</small>
                            </td>
                            <td>
                                <a href="{{ route('superadmin.scraped-jobs.show', $applicant->jobPosting->id) }}">
                                    {{ $applicant->jobPosting->title }}
                                </a><br>
                                <small class="text-muted">{{ $applicant->jobPosting->company_name }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($applicant->jobPosting->source) }}</span>
                            </td>
                            <td>{{ $applicant->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge {{ 
                                    $applicant->status === 'hired' ? 'bg-success' : 
                                    ($applicant->status === 'rejected' ? 'bg-danger' : 
                                    ($applicant->status === 'interview' ? 'bg-info' : 'bg-warning')) 
                                }}">
                                    {{ ucfirst($applicant->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('superadmin.scraped-jobs.applicant-show', $applicant->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No applications found for scraped jobs.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $applicants->links() }}
        </div>
    </div>
@endsection
