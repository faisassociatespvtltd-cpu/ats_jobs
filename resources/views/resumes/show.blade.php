@extends('layouts.app')

@section('title', 'Resume Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Resume Details</h1>
        <div class="form-actions">
            <a href="{{ route('resumes.print', $resume) }}" class="btn btn-primary" target="_blank">Print Report</a>
            <a href="{{ route('resumes.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div style="display: grid; gap: 16px;">
        <div><strong>File:</strong> {{ $resume->file_name }}</div>
        <div><strong>Status:</strong> {{ ucfirst($resume->parsing_status) }}</div>
        @if(!empty($resume->parsed_content['error']))
        <div style="color: #d13438;">
            <strong>Parsing Error:</strong> {{ $resume->parsed_content['error'] }}
        </div>
        @endif
        <div><strong>File Type:</strong> {{ strtoupper($resume->file_type) }}</div>
        <div><strong>File Size:</strong> {{ number_format($resume->file_size / 1024, 2) }} KB</div>
        <div>
            <strong>Name:</strong>
            {{ $resume->parsed_content['name'] ?? 'N/A' }}
        </div>
        <div>
            <strong>Email:</strong>
            {{ $resume->parsed_content['email'] ?? 'N/A' }}
        </div>
        <div>
            <strong>Phone:</strong>
            {{ $resume->parsed_content['phone'] ?? 'N/A' }}
        </div>
        <div>
            <strong>Address:</strong>
            {{ $resume->parsed_content['address'] ?? 'N/A' }}
        </div>
        <div>
            <strong>LinkedIn:</strong>
            {{ $resume->parsed_content['linkedin'] ?? 'N/A' }}
        </div>
        <div>
            <strong>GitHub:</strong>
            {{ $resume->parsed_content['github'] ?? 'N/A' }}
        </div>
        <div>
            <strong>Website:</strong>
            {{ $resume->parsed_content['website'] ?? 'N/A' }}
        </div>
        <div>
            <strong>Download:</strong>
            <a href="{{ asset('storage/' . $resume->file_path) }}" target="_blank">View File</a>
        </div>
        <div>
            <strong>Skills:</strong>
            @if(!empty($resume->skills))
                {{ implode(', ', $resume->skills) }}
            @else
                N/A
            @endif
        </div>
        <div>
            <strong>Experience:</strong>
            @if(!empty($resume->experience))
                <ul style="margin: 8px 0 0 16px;">
                    @foreach($resume->experience as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                N/A
            @endif
        </div>
        <div>
            <strong>Education:</strong>
            @if(!empty($resume->education))
                <ul style="margin: 8px 0 0 16px;">
                    @foreach($resume->education as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                N/A
            @endif
        </div>
        @if(!empty($resume->parsed_content['timeline']))
        <div>
            <strong>Timeline:</strong>
            {{ implode(', ', $resume->parsed_content['timeline']) }}
        </div>
        @endif
        @if($resume->parsed_content && !empty($resume->parsed_content['text']))
        <div>
            <strong>Parsed Text (preview):</strong>
            <p style="margin: 8px 0; white-space: pre-wrap;">{{ \Illuminate\Support\Str::limit($resume->parsed_content['text'], 1000) }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

