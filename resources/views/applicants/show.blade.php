@extends('layouts.app')

@section('title', 'View Applicant')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">View Applicant</h1>
        <div class="form-actions">
            <a href="{{ route('applicants.edit', $applicant) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('applicants.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    
    <div style="max-width: 800px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div>
                <h3 style="margin-bottom: 16px; font-size: 16px; color: #323130;">Personal Information</h3>
                <div style="background-color: #faf9f8; padding: 16px; border-radius: 2px;">
                    <div style="margin-bottom: 12px;">
                        <strong>Name:</strong><br>
                        {{ $applicant->first_name }} {{ $applicant->last_name }}
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Email:</strong><br>
                        {{ $applicant->email }}
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Phone:</strong><br>
                        {{ $applicant->phone ?? 'N/A' }}
                    </div>
                    <div>
                        <strong>Application Date:</strong><br>
                        {{ $applicant->application_date->format('Y-m-d') }}
                    </div>
                </div>
            </div>
            
            <div>
                <h3 style="margin-bottom: 16px; font-size: 16px; color: #323130;">Application Details</h3>
                <div style="background-color: #faf9f8; padding: 16px; border-radius: 2px;">
                    <div style="margin-bottom: 12px;">
                        <strong>Job Posting:</strong><br>
                        {{ $applicant->jobPosting->title ?? 'N/A' }}
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Status:</strong><br>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #605e5c; color: white;">
                            {{ ucfirst($applicant->status) }}
                        </span>
                    </div>
                    <div style="margin-bottom: 12px;">
                        <strong>Rating:</strong><br>
                        @if($applicant->rating)
                            {{ number_format($applicant->rating, 1) }}/5.0
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        @if($applicant->cover_letter)
        <div style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 16px; font-size: 16px; color: #323130;">Cover Letter</h3>
            <div style="background-color: #faf9f8; padding: 16px; border-radius: 2px; white-space: pre-wrap;">
                {{ $applicant->cover_letter }}
            </div>
        </div>
        @endif
        
        @if($applicant->notes)
        <div style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 16px; font-size: 16px; color: #323130;">Notes</h3>
            <div style="background-color: #faf9f8; padding: 16px; border-radius: 2px; white-space: pre-wrap;">
                {{ $applicant->notes }}
            </div>
        </div>
        @endif
        
        @if($applicant->resume)
        <div style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 16px; font-size: 16px; color: #323130;">Resume</h3>
            <div style="background-color: #faf9f8; padding: 16px; border-radius: 2px;">
                <a href="{{ route('resumes.show', $applicant->resume) }}" class="btn btn-primary btn-sm">View Resume</a>
            </div>
        </div>
        @endif
        
        @if($applicant->interviews->count() > 0)
        <div style="margin-bottom: 24px;">
            <h3 style="margin-bottom: 16px; font-size: 16px; color: #323130;">Interviews</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Scheduled Date</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applicant->interviews as $interview)
                        <tr>
                            <td>{{ $interview->scheduled_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $interview->interview_type }}</td>
                            <td>{{ ucfirst($interview->status) }}</td>
                            <td>
                                <a href="{{ route('interviews.show', $interview) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

