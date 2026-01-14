@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Employee Profile</h1>
        <div class="form-actions">
            <a href="{{ route('employee.profile') }}" class="btn btn-secondary">Back to Profile</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('employee.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; gap: 20px;">
            <div class="form-group">
                <label for="name">Full Name <span style="color: #d13438;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $profile->name) }}" required>
                @error('name')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="cv">CV (PDF, DOC, DOCX - Max 10MB)</label>
                @if(auth()->user()->cv_path)
                    <div style="margin-bottom: 8px;">
                        <a href="{{ asset('storage/' . auth()->user()->cv_path) }}" target="_blank">Current CV</a>
                    </div>
                @endif
                <input type="file" id="cv" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                @error('cv')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $profile->address) }}</textarea>
                @error('address')
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
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}">
                    @error('date_of_birth')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $profile->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" class="form-control" rows="4">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="education_level">Education Level</label>
                <input type="text" id="education_level" name="education_level" class="form-control" value="{{ old('education_level', $profile->education_level) }}">
                @error('education_level')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="skills">Skills</label>
                <textarea id="skills" name="skills" class="form-control" rows="3">{{ old('skills', $profile->skills) }}</textarea>
                @error('skills')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="experience">Experience</label>
                <textarea id="experience" name="experience" class="form-control" rows="4">{{ old('experience', $profile->experience) }}</textarea>
                @error('experience')
                    <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label for="linkedin_url">LinkedIn URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $profile->linkedin_url) }}">
                    @error('linkedin_url')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="portfolio_url">Portfolio URL</label>
                    <input type="url" id="portfolio_url" name="portfolio_url" class="form-control" value="{{ old('portfolio_url', $profile->portfolio_url) }}">
                    @error('portfolio_url')
                        <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </form>
</div>
@endsection

