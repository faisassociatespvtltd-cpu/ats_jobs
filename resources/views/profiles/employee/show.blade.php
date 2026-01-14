@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">My Employee Profile</h1>
        <div class="form-actions">
            <a href="{{ route('employee.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 30px; margin-bottom: 30px;">
        <div>
            @if(auth()->user()->cv_path)
                <div style="background: #faf9f8; border: 1px solid #edebe9; border-radius: 8px; padding: 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 12px;">📄</div>
                    <a href="{{ asset('storage/' . auth()->user()->cv_path) }}" target="_blank" class="btn btn-sm" style="display: block; text-align: center;">View CV</a>
                </div>
            @endif
        </div>
        
        <div>
            <h2 style="font-size: 24px; margin-bottom: 20px; color: #323130;">{{ $profile->name }}</h2>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Email:</strong>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                
                @if($profile->address)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Address:</strong>
                    <span>{{ $profile->address }}</span>
                </div>
                @endif
                
                @if($profile->phone_number)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Phone Number:</strong>
                    <span>{{ $profile->phone_number }}</span>
                </div>
                @endif
                
                @if($profile->whatsapp_number)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">WhatsApp Number:</strong>
                    <span>{{ $profile->whatsapp_number }}</span>
                </div>
                @endif
                
                @if($profile->cnic)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">CNIC (National ID):</strong>
                    <span>{{ $profile->cnic }}</span>
                </div>
                @endif
                
                @if($profile->city || $profile->state || $profile->country)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Location:</strong>
                    <span>
                        @if($profile->city){{ $profile->city }}@endif
                        @if($profile->state){{ $profile->city ? ', ' : '' }}{{ $profile->state }}@endif
                        @if($profile->country){{ ($profile->city || $profile->state) ? ', ' : '' }}{{ $profile->country }}@endif
                    </span>
                </div>
                @elseif($profile->location)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Location:</strong>
                    <span>{{ $profile->location }}</span>
                </div>
                @endif
                
                @if($profile->expected_salary)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Expected Salary:</strong>
                    <span>{{ number_format($profile->expected_salary, 2) }}</span>
                </div>
                @endif
                
                @if($profile->date_of_birth)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Date of Birth:</strong>
                    <span>{{ $profile->date_of_birth->format('F d, Y') }}</span>
                </div>
                @endif
                
                @if($profile->gender)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Gender:</strong>
                    <span>{{ ucfirst($profile->gender) }}</span>
                </div>
                @endif
                
                @if($profile->bio)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Bio:</strong>
                    <p style="margin: 0;">{{ $profile->bio }}</p>
                </div>
                @endif
                
                @if($profile->education_level)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Education Level:</strong>
                    <span>{{ $profile->education_level }}</span>
                </div>
                @endif
                
                @if($profile->skills)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Skills:</strong>
                    <p style="margin: 0;">{{ $profile->skills }}</p>
                </div>
                @endif
                
                @if($profile->experience)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Experience:</strong>
                    <p style="margin: 0;">{{ $profile->experience }}</p>
                </div>
                @endif
                
                @if($profile->linkedin_url)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">LinkedIn:</strong>
                    <a href="{{ $profile->linkedin_url }}" target="_blank">{{ $profile->linkedin_url }}</a>
                </div>
                @endif
                
                @if($profile->portfolio_url)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Portfolio:</strong>
                    <a href="{{ $profile->portfolio_url }}" target="_blank">{{ $profile->portfolio_url }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

