@extends('layouts.app')

@section('title', 'Create Job Posting')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Create Job Posting</h1>
        <div class="form-actions">
            <a href="{{ route('employer.jobs') }}" class="btn btn-secondary">Back to Jobs</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('job-postings.store') }}" style="max-width: 800px;">
        @csrf
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label>Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label>Description *</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
            @error('description') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', auth()->user()->employerProfile->company_name ?? '') }}" required>
                @error('company_name') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
                @error('location') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Required Skills *</label>
            <input type="text" name="required_skills" class="form-control" value="{{ old('required_skills') }}" placeholder="e.g., PHP, Laravel, MySQL" required>
            @error('required_skills') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Job Type *</label>
                <select name="job_type" class="form-control" required>
                    <option value="">Select...</option>
                    <option value="Full-time" {{ old('job_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('job_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('job_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Freelance" {{ old('job_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                </select>
                @error('job_type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Experience Level</label>
                <select name="experience_level" class="form-control">
                    <option value="">Select...</option>
                    <option value="Entry" {{ old('experience_level') == 'Entry' ? 'selected' : '' }}>Entry</option>
                    <option value="Mid" {{ old('experience_level') == 'Mid' ? 'selected' : '' }}>Mid</option>
                    <option value="Senior" {{ old('experience_level') == 'Senior' ? 'selected' : '' }}>Senior</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Application Deadline *</label>
                <input type="date" name="closing_date" class="form-control" value="{{ old('closing_date') }}" required>
                @error('closing_date') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="form-group">
                <label>Salary Min</label>
                <input type="number" name="salary_min" class="form-control" value="{{ old('salary_min') }}" step="0.01">
            </div>
            
            <div class="form-group">
                <label>Salary Max</label>
                <input type="number" name="salary_max" class="form-control" value="{{ old('salary_max') }}" step="0.01">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Other Relevant Details</label>
            <textarea name="other_details" class="form-control" rows="4" placeholder="Any additional information about the role...">{{ old('other_details') }}</textarea>
        </div>

        <input type="hidden" name="status" value="active">
        
        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Create Job Posting</button>
            <a href="{{ route('employer.jobs') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

