@extends('layouts.app')

@section('title', 'Edit Applicant')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Applicant</h1>
        <div class="form-actions">
            <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-secondary">View</a>
            <a href="{{ route('applicants.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('applicants.update', $applicant) }}" style="max-width: 800px;">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label>Job Posting *</label>
            <select name="job_posting_id" class="form-control" required>
                <option value="">Select a job posting...</option>
                @foreach($jobPostings as $job)
                <option value="{{ $job->id }}" {{ old('job_posting_id', $applicant->job_posting_id) == $job->id ? 'selected' : '' }}>
                    {{ $job->title }} - {{ $job->company_name }}
                </option>
                @endforeach
            </select>
            @error('job_posting_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $applicant->first_name) }}" required>
                @error('first_name') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $applicant->last_name) }}" required>
                @error('last_name') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $applicant->email) }}" required>
                @error('email') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $applicant->phone) }}">
                @error('phone') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label>Cover Letter</label>
            <textarea name="cover_letter" class="form-control" rows="6">{{ old('cover_letter', $applicant->cover_letter) }}</textarea>
            @error('cover_letter') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Status *</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status', $applicant->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ old('status', $applicant->status) == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="shortlisted" {{ old('status', $applicant->status) == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interviewed" {{ old('status', $applicant->status) == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                    <option value="offered" {{ old('status', $applicant->status) == 'offered' ? 'selected' : '' }}>Offered</option>
                    <option value="rejected" {{ old('status', $applicant->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="hired" {{ old('status', $applicant->status) == 'hired' ? 'selected' : '' }}>Hired</option>
                </select>
                @error('status') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Rating (0-5)</label>
                <input type="number" name="rating" class="form-control" value="{{ old('rating', $applicant->rating) }}" step="0.1" min="0" max="5">
                @error('rating') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="4">{{ old('notes', $applicant->notes) }}</textarea>
            @error('notes') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        
        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update Applicant</button>
            <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

