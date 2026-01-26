@extends('layouts.app')

@section('title', 'Edit Resume')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Resume</h1>
        <div class="form-actions">
            <a href="{{ route('resumes.show', $resume) }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <form method="POST" action="{{ route('resumes.update', $resume) }}" style="max-width: 700px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Applicant (optional)</label>
            <select name="applicant_id" class="form-control">
                <option value="">Select applicant</option>
                @foreach($applicants as $applicant)
                    <option value="{{ $applicant->id }}" {{ old('applicant_id', $resume->applicant_id) == $applicant->id ? 'selected' : '' }}>
                        {{ $applicant->first_name }} {{ $applicant->last_name }} ({{ $applicant->email }})
                    </option>
                @endforeach
            </select>
            @error('applicant_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Parsing Status *</label>
            <select name="parsing_status" class="form-control" required>
                <option value="pending" {{ old('parsing_status', $resume->parsing_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ old('parsing_status', $resume->parsing_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ old('parsing_status', $resume->parsing_status) == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            @error('parsing_status') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection


