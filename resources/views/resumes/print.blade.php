<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Parsing Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 24px;
        }
        h1, h2 {
            margin: 0 0 8px 0;
        }
        .section {
            margin-top: 16px;
        }
        .label {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .box {
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 4px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 16px;">
        <button onclick="window.print()">Print</button>
    </div>

    <h1>Resume Parsing Report</h1>
    <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>

    @if(!empty($resume->parsed_content['error']))
    <div class="section">
        <div class="label">Parsing Error</div>
        <div class="box">{{ $resume->parsed_content['error'] }}</div>
    </div>
    @endif

    <div class="section">
        <div class="label">File Information</div>
        <div class="box">
            <div>File Name: {{ $resume->file_name }}</div>
            <div>File Type: {{ strtoupper($resume->file_type) }}</div>
            <div>File Size: {{ number_format($resume->file_size / 1024, 2) }} KB</div>
            <div>Status: {{ ucfirst($resume->parsing_status) }}</div>
        </div>
    </div>

    <div class="section">
        <div class="label">Applicant</div>
        <div class="box">
            @if($resume->applicant)
                <div>Name: {{ $resume->applicant->first_name }} {{ $resume->applicant->last_name }}</div>
                <div>Email: {{ $resume->applicant->email }}</div>
                <div>Phone: {{ $resume->applicant->phone ?? 'N/A' }}</div>
            @else
                <div>Name: {{ $resume->parsed_content['name'] ?? 'N/A' }}</div>
                <div>Email: {{ $resume->parsed_content['email'] ?? 'N/A' }}</div>
                <div>Phone: {{ $resume->parsed_content['phone'] ?? 'N/A' }}</div>
            @endif
            <div>Address: {{ $resume->parsed_content['address'] ?? 'N/A' }}</div>
            <div>LinkedIn: {{ $resume->parsed_content['linkedin'] ?? 'N/A' }}</div>
            <div>GitHub: {{ $resume->parsed_content['github'] ?? 'N/A' }}</div>
            <div>Website: {{ $resume->parsed_content['website'] ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="label">Skills</div>
        <div class="box">
            @if(!empty($resume->skills))
                {{ implode(', ', $resume->skills) }}
            @else
                N/A
            @endif
        </div>
    </div>

    <div class="section">
        <div class="label">Experience</div>
        <div class="box">
            @if(!empty($resume->experience))
                <ul>
                    @foreach($resume->experience as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                N/A
            @endif
        </div>
    </div>

    <div class="section">
        <div class="label">Education</div>
        <div class="box">
            @if(!empty($resume->education))
                <ul>
                    @foreach($resume->education as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                N/A
            @endif
        </div>
    </div>

    @if(!empty($resume->parsed_content['timeline']))
    <div class="section">
        <div class="label">Timeline</div>
        <div class="box">
            {{ implode(', ', $resume->parsed_content['timeline']) }}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="label">Parsed Text</div>
        <div class="box" style="white-space: pre-wrap;">
            {{ $resume->parsed_content['text'] ?? 'N/A' }}
        </div>
    </div>
</body>
</html>

