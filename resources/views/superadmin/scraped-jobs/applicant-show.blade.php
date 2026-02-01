@extends('layouts.app')

@section('title', 'Applicant Profile')

@section('content')
    <div class="form-header">
        <h1 class="form-title">Applicant Profile: {{ $applicant->first_name }} {{ $applicant->last_name }}</h1>
        <div class="form-actions">
            <a href="{{ route('superadmin.scraped-jobs.applicants') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-container">
                <h3 class="form-title" style="font-size: 16px; margin-bottom: 20px;">Personal Information</h3>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Full Name</div>
                    <div class="col-md-8"><strong>{{ $applicant->first_name }} {{ $applicant->last_name }}</strong></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Email</div>
                    <div class="col-md-8">{{ $applicant->email }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Phone</div>
                    <div class="col-md-8">{{ $applicant->phone }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Applied Date</div>
                    <div class="col-md-8">{{ $applicant->created_at->toDayDateTimeString() }}</div>
                </div>

                <hr>

                <h3 class="form-title" style="font-size: 16px; margin-top: 20px; margin-bottom: 20px;">Professional Profile</h3>
                @if($applicant->user && $applicant->user->employeeProfile)
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Skills</div>
                        <div class="col-md-8">
                            @foreach(explode(',', $applicant->user->employeeProfile->skills) as $skill)
                                <span class="badge bg-light text-dark border">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($applicant->cv_path)
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Resume / CV</div>
                    <div class="col-md-8">
                        <a href="{{ asset('storage/' . $applicant->cv_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-download"></i> View Resume
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <div class="form-container">
                <h3 class="form-title" style="font-size: 16px; margin-bottom: 20px;">Job Context (Scraped Job)</h3>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Job Title</div>
                    <div class="col-md-8">
                        <strong>{{ $applicant->jobPosting->title }}</strong>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Company</div>
                    <div class="col-md-8">{{ $applicant->jobPosting->company_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Source Platform</div>
                    <div class="col-md-8"><span class="badge bg-secondary">{{ ucfirst($applicant->jobPosting->source) }}</span></div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('superadmin.scraped-jobs.show', $applicant->jobPosting->id) }}" class="btn btn-sm btn-link">View Original Scraped Data</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-container">
                <h3 class="form-title" style="font-size: 16px; margin-bottom: 20px;">Manage Application</h3>
                <form action="{{ route('superadmin.scraped-jobs.applicant-status', $applicant->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="status">Application Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="pending" {{ $applicant->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $applicant->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="contacted" {{ $applicant->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="interview" {{ $applicant->status === 'interview' ? 'selected' : '' }}>Interview Invited</option>
                            <option value="hired" {{ $applicant->status === 'hired' ? 'selected' : '' }}>Hired</option>
                            <option value="rejected" {{ $applicant->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="notes">Internal Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="5" placeholder="Add private notes about this candidate...">{{ $applicant->notes }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>

            <div class="alert alert-info mt-3" style="font-size: 13px;">
                <i class="fas fa-info-circle"></i> 
                <strong>System Note:</strong> 
                Since this job was scraped from <strong>{{ ucfirst($applicant->jobPosting->source) }}</strong>, 
                you may need to forward this candidate's profile to the actual employer at <strong>{{ $applicant->jobPosting->company_name }}</strong>.
            </div>
        </div>
    </div>
@endsection
