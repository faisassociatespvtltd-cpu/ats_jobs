@extends('layouts.app')

@section('title', 'Interview Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Interview Details</h1>
        <div class="form-actions">
            <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-primary">Edit Interview</a>
            <a href="{{ route('interviews.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px; max-width: 900px;">
        <div class="details-section">
            <h2 style="font-size: 18px; color: #323130; margin-bottom: 16px; border-bottom: 1px solid #edebe9; padding-bottom: 8px;">Schedule Information</h2>
            
            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Date & Time</label>
                <div style="font-size: 16px;">{{ $interview->scheduled_at ? $interview->scheduled_at->format('M d, Y - h:i A') : 'N/A' }}</div>
            </div>

            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Type</label>
                <div style="font-size: 16px;">{{ $interview->interview_type }}</div>
            </div>

            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Status</label>
                <span style="padding: 4px 12px; border-radius: 2px; font-size: 14px; background-color: {{ $interview->status == 'completed' ? '#107c10' : ($interview->status == 'scheduled' ? '#0078D4' : '#d13438') }}; color: white;">
                    {{ ucfirst($interview->status) }}
                </span>
            </div>

            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Location / Link</label>
                @if($interview->meeting_link)
                    <a href="{{ $interview->meeting_link }}" target="_blank" class="btn btn-sm btn-primary">Join Meeting</a>
                @else
                    <div style="font-size: 16px;">{{ $interview->location ?? 'N/A' }}</div>
                @endif
            </div>
        </div>

        <div class="details-section">
            <h2 style="font-size: 18px; color: #323130; margin-bottom: 16px; border-bottom: 1px solid #edebe9; padding-bottom: 8px;">Participants</h2>
            
            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Applicant</label>
                <div style="font-size: 16px;">{{ $interview->applicant->first_name ?? 'N/A' }} {{ $interview->applicant->last_name ?? '' }}</div>
                <div style="color: #605e5c; font-size: 14px;">{{ $interview->applicant->email ?? '' }}</div>
            </div>

            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Job Posting</label>
                <div style="font-size: 16px;">{{ $interview->jobPosting->title ?? 'N/A' }}</div>
            </div>

            <div class="details-group" style="margin-bottom: 16px;">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Interviewer</label>
                <div style="font-size: 16px;">{{ $interview->interviewer->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($interview->notes || $interview->feedback)
    <div style="margin-top: 32px; max-width: 900px;">
        <h2 style="font-size: 18px; color: #323130; margin-bottom: 16px; border-bottom: 1px solid #edebe9; padding-bottom: 8px;">Assessment</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
            <div class="details-group">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Internal Notes</label>
                <div style="background: #faf9f8; padding: 12px; border-radius: 4px; font-size: 15px; min-height: 100px;">{{ $interview->notes ?? 'No notes added.' }}</div>
            </div>

            <div class="details-group">
                <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Feedback</label>
                <div style="background: #faf9f8; padding: 12px; border-radius: 4px; font-size: 15px; min-height: 100px;">{{ $interview->feedback ?? 'No feedback recorded.' }}</div>
                @if($interview->rating)
                    <div style="margin-top: 12px; font-weight: 600;">Rating: {{ $interview->rating }} / 5.0</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div style="margin-top: 48px; padding-top: 16px; border-top: 1px solid #edebe9;">
        <form action="{{ route('interviews.destroy', $interview) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this interview record?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete Record</button>
        </form>
    </div>
</div>
@endsection
