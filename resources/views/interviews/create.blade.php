@extends('layouts.app')

@section('title', 'Schedule Interview')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Schedule Interview</h1>
        <div class="form-actions">
            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('interviews.store') }}" style="max-width: 800px;">
        @csrf

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Applicant *</label>
            <select name="applicant_id" class="form-control" required>
                <option value="">Select Applicant</option>
                @foreach($applicants as $applicant)
                    <option value="{{ $applicant->id }}" {{ old('applicant_id') == $applicant->id ? 'selected' : '' }}>
                        {{ $applicant->first_name }} {{ $applicant->last_name }} ({{ $applicant->email }})
                    </option>
                @endforeach
            </select>
            @error('applicant_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Job Posting *</label>
            <select name="job_posting_id" class="form-control" required>
                <option value="">Select Job</option>
                @foreach($jobPostings as $job)
                    <option value="{{ $job->id }}" {{ old('job_posting_id') == $job->id ? 'selected' : '' }}>
                        {{ $job->title }}
                    </option>
                @endforeach
            </select>
            @error('job_posting_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Scheduled At *</label>
            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}" required>
            @error('scheduled_at') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Interview Type *</label>
            <input type="text" name="interview_type" class="form-control" value="{{ old('interview_type') }}" placeholder="e.g., Technical, HR, Video Call" required>
            @error('interview_type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Office Address or Online">
            @error('location') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Meeting Link</label>
            <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}" placeholder="https://zoom.us/j/...">
            @error('meeting_link') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Interviewer</label>
            <select name="interviewer_id" class="form-control">
                <option value="">Select Interviewer</option>
                @foreach($interviewers as $interviewer)
                    <option value="{{ $interviewer->id }}" {{ old('interviewer_id') == $interviewer->id ? 'selected' : '' }}>
                        {{ $interviewer->name }}
                    </option>
                @endforeach
            </select>
            @error('interviewer_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="rescheduled" {{ old('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
            </select>
            @error('status') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Schedule Interview</button>
            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
