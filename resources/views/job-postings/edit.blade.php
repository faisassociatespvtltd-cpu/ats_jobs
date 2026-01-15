@extends('layouts.app')

@section('title', 'Edit Job Posting')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Job Posting</h1>
        <div class="form-actions">
            <a href="{{ route('job-postings.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <form method="POST" action="{{ route('job-postings.update', $jobPosting) }}" style="max-width: 800px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $jobPosting->title) }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Description *</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description', $jobPosting->description) }}</textarea>
            @error('description') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $jobPosting->company_name) }}" required>
                @error('company_name') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $jobPosting->location) }}" required>
                @error('location') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Required Skills *</label>
            <input type="text" name="required_skills" class="form-control" value="{{ old('required_skills', $jobPosting->required_skills) }}" placeholder="e.g., PHP, Laravel, MySQL" required>
            @error('required_skills') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Job Type *</label>
                <select name="job_type" class="form-control" required>
                    <option value="">Select...</option>
                    <option value="Full-time" {{ old('job_type', $jobPosting->job_type) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('job_type', $jobPosting->job_type) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('job_type', $jobPosting->job_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Freelance" {{ old('job_type', $jobPosting->job_type) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                </select>
                @error('job_type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Experience Level</label>
                <select name="experience_level" class="form-control">
                    <option value="">Select...</option>
                    <option value="Entry" {{ old('experience_level', $jobPosting->experience_level) == 'Entry' ? 'selected' : '' }}>Entry</option>
                    <option value="Mid" {{ old('experience_level', $jobPosting->experience_level) == 'Mid' ? 'selected' : '' }}>Mid</option>
                    <option value="Senior" {{ old('experience_level', $jobPosting->experience_level) == 'Senior' ? 'selected' : '' }}>Senior</option>
                </select>
            </div>

            <div class="form-group">
                <label>Application Deadline *</label>
                <input type="date" name="closing_date" class="form-control" value="{{ old('closing_date', $jobPosting->closing_date?->format('Y-m-d')) }}" required>
                @error('closing_date') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Salary Min</label>
                <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min', $jobPosting->salary_min) }}" step="0.01">
            </div>
            
            <div class="form-group">
                <label>Salary Max</label>
                <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max', $jobPosting->salary_max) }}" step="0.01">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                <option value="draft" {{ old('status', $jobPosting->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="active" {{ old('status', $jobPosting->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="closed" {{ old('status', $jobPosting->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="cancelled" {{ old('status', $jobPosting->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Other Relevant Details</label>
            <textarea name="other_details" class="form-control" rows="4" placeholder="Any additional information about the role...">{{ old('other_details', $jobPosting->other_details) }}</textarea>
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update Job Posting</button>
        </div>
    </form>
</div>
@endsection

