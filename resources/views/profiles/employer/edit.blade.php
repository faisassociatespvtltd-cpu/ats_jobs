@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Employer Profile</h1>
        <div class="form-actions">
            <a href="{{ route('employer.profile') }}" class="btn btn-secondary">Back to Profile</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('employer.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; gap: 20px;">
            <div class="form-group">
                <label for="company_name">Company Name <span style="color: #d13438;">*</span></label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', $profile->company_name) }}" required>
                @error('company_name')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="company_logo">Company Logo (JPG, PNG, GIF - Max 2MB)</label>
                @if(auth()->user()->company_logo_path)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset('storage/' . auth()->user()->company_logo_path) }}" alt="Current Logo" style="max-width: 150px; border-radius: 4px; border: 1px solid #edebe9;">
                    </div>
                @endif
                <input type="file" id="company_logo" name="company_logo" class="form-control" accept="image/*">
                @error('company_logo')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="company_address">Company Address</label>
                <textarea id="company_address" name="company_address" class="form-control" rows="3">{{ old('company_address', $profile->company_address) }}</textarea>
                @error('company_address')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="contact_person_name">Contact Person Name</label>
                <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" value="{{ old('contact_person_name', $profile->contact_person_name) }}">
                @error('contact_person_name')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $profile->phone_number) }}">
                    @error('phone_number')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="whatsapp_number">WhatsApp Number</label>
                    <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}">
                    @error('whatsapp_number')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="website_url">Website URL</label>
                <input type="url" id="website_url" name="website_url" class="form-control" value="{{ old('website_url', $profile->website_url) }}" placeholder="https://example.com">
                @error('website_url')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" id="industry" name="industry" class="form-control" value="{{ old('industry', $profile->industry) }}">
                    @error('industry')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="company_size">Company Size</label>
                    <input type="text" id="company_size" name="company_size" class="form-control" value="{{ old('company_size', $profile->company_size) }}" placeholder="e.g., 50-100 employees">
                    @error('company_size')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="license_number">License Number</label>
                    <input type="text" id="license_number" name="license_number" class="form-control" value="{{ old('license_number', $profile->license_number) }}">
                    @error('license_number')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="registration_number">Registration Number</label>
                    <input type="text" id="registration_number" name="registration_number" class="form-control" value="{{ old('registration_number', $profile->registration_number) }}">
                    @error('registration_number')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="tax_number">Tax Number</label>
                    <input type="text" id="tax_number" name="tax_number" class="form-control" value="{{ old('tax_number', $profile->tax_number) }}">
                    @error('tax_number')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="company_type">Company Type</label>
                    <input type="text" id="company_type" name="company_type" class="form-control" value="{{ old('company_type', $profile->company_type) }}" placeholder="e.g., Pvt Ltd, LLC">
                    @error('company_type')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="founded_year">Founded Year</label>
                <input type="number" id="founded_year" name="founded_year" class="form-control" value="{{ old('founded_year', $profile->founded_year) }}" min="1800" max="{{ date('Y') }}" placeholder="e.g., 2012">
                @error('founded_year')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="company_description">Company Description</label>
                <textarea id="company_description" name="company_description" class="form-control" rows="4">{{ old('company_description', $profile->company_description) }}</textarea>
                @error('company_description')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $profile->linkedin_url) }}">
                @error('linkedin_url')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </form>
</div>
@endsection

