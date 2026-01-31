@extends('layouts.app')

@section('title', 'Ask a Legal Question')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Ask a Legal Question</h1>
        <div class="form-actions">
            <a href="{{ route('labour-laws.index') }}?type=qa" class="btn btn-secondary">
                Back to Q&A
            </a>
        </div>
    </div>

    <form action="{{ route('labour-laws.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="qa">
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="title">Question Subject / Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g., Termination Notice Period" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}" placeholder="e.g., Saudi Arabia">
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                        <option value="">Select Category</option>
                        <option value="Employment Contract" {{ old('category') == 'Employment Contract' ? 'selected' : '' }}>Employment Contract</option>
                        <option value="Termination" {{ old('category') == 'Termination' ? 'selected' : '' }}>Termination</option>
                        <option value="Wages & Benefits" {{ old('category') == 'Wages & Benefits' ? 'selected' : '' }}>Wages & Benefits</option>
                        <option value="Work Hours & Leave" {{ old('category') == 'Work Hours & Leave' ? 'selected' : '' }}>Work Hours & Leave</option>
                        <option value="Health & Safety" {{ old('category') == 'Health & Safety' ? 'selected' : '' }}>Health & Safety</option>
                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="content">Detailed Question</label>
                    <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" placeholder="Provide as much detail as possible about your legal query..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">Submit Question</button>
            <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
