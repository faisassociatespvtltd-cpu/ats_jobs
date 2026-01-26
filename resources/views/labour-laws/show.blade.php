@extends('layouts.app')

@section('title', 'Labour Law Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">{{ $labourLaw->title }}</h1>
        <div class="form-actions">
            <a href="{{ route('labour-laws.edit', $labourLaw) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div style="display: grid; gap: 16px;">
        <div><strong>Type:</strong> {{ ucfirst($labourLaw->type) }}</div>
        <div><strong>Country:</strong> {{ $labourLaw->country }}</div>
        @if($labourLaw->summary)
        <div>
            <strong>Summary:</strong>
            <p style="margin: 8px 0;">{{ $labourLaw->summary }}</p>
        </div>
        @endif
        <div>
            <strong>Content:</strong>
            <p style="margin: 8px 0; white-space: pre-wrap;">{{ $labourLaw->content }}</p>
        </div>
    </div>
</div>
@endsection


