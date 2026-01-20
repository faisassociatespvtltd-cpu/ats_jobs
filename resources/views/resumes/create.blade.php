@extends('layouts.app')

@section('title', 'Upload Resume')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Upload Resume</h1>
        <div class="form-actions">
            <a href="{{ route('resumes.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('resumes.store') }}" enctype="multipart/form-data" style="max-width: 700px;">
        @csrf

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Applicant (optional)</label>
            <select name="applicant_id" class="form-control">
                <option value="">Select applicant</option>
                @foreach($applicants as $applicant)
                    <option value="{{ $applicant->id }}" {{ old('applicant_id') == $applicant->id ? 'selected' : '' }}>
                        {{ $applicant->first_name }} {{ $applicant->last_name }} ({{ $applicant->email }})
                    </option>
                @endforeach
            </select>
            @error('applicant_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Upload CV/Resume *</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
            @error('file') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Upload & Parse</button>
            <a href="{{ route('resumes.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

