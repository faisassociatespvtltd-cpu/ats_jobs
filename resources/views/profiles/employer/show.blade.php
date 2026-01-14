@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">My Employer Profile</h1>
        <div class="form-actions">
            <a href="{{ route('employer.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 30px; margin-bottom: 30px;">
        <div>
            @if(auth()->user()->company_logo_path)
                <div style="background: #faf9f8; border: 1px solid #edebe9; border-radius: 8px; padding: 20px; text-align: center;">
                    <img src="{{ asset('storage/' . auth()->user()->company_logo_path) }}" alt="Company Logo" style="max-width: 100%; border-radius: 4px;">
                </div>
            @endif
        </div>
        
        <div>
            <h2 style="font-size: 24px; margin-bottom: 20px; color: #323130;">{{ $profile->company_name }}</h2>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Email:</strong>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                
                @if($profile->company_address)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Company Address:</strong>
                    <span>{{ $profile->company_address }}</span>
                </div>
                @endif
                
                @if($profile->contact_person_name)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Contact Person:</strong>
                    <span>{{ $profile->contact_person_name }}</span>
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
                
                @if($profile->website_url)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Website:</strong>
                    <a href="{{ $profile->website_url }}" target="_blank">{{ $profile->website_url }}</a>
                </div>
                @endif
                
                @if($profile->company_description)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Company Description:</strong>
                    <p style="margin: 0;">{{ $profile->company_description }}</p>
                </div>
                @endif
                
                @if($profile->industry)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Industry:</strong>
                    <span>{{ $profile->industry }}</span>
                </div>
                @endif
                
                @if($profile->company_size)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">Company Size:</strong>
                    <span>{{ $profile->company_size }}</span>
                </div>
                @endif
                
                @if($profile->linkedin_url)
                <div>
                    <strong style="color: #605e5c; display: block; margin-bottom: 4px;">LinkedIn:</strong>
                    <a href="{{ $profile->linkedin_url }}" target="_blank">{{ $profile->linkedin_url }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

