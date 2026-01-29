@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="main-container" style="padding: 0; max-width: 1000px; margin: 0 auto;">
        <div class="form-header"
            style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <h1 class="form-title">My Employee Profile</h1>
            <div style="display: flex; gap: 12px;">
                @if(auth()->user()->cv_path)
                    <form action="{{ route('employee.profile.refresh-cv') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="display: flex; align-items: center; gap: 8px;">
                            🔄 Refresh from CV
                        </button>
                    </form>
                @endif
                <a href="{{ route('employee.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>

        @if(session('success'))
            <div
                style="background: #e7f3ff; color: #0078d4; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px; border-left: 4px solid #0078d4; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                style="background: #fde7e9; color: #a4262c; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px; border-left: 4px solid #a4262c; font-weight: 500;">
                {{ session('error') }}
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 32px;">
            <!-- Left Sidebar -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="form-container" style="text-align: center; padding: 32px 24px;">
                    @if($profile->profile_photo_path)
                        <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="Profile Photo"
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #f3f2f1; margin: 0 auto 16px; display: block; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    @else
                        <div
                            style="width: 120px; height: 120px; border-radius: 50%; background: #f3f2f1; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 48px; color: #a19f9d;">
                            👤
                        </div>
                    @endif

                    <h2 style="font-size: 20px; font-weight: 700; color: #323130; margin-bottom: 4px;">
                        {{ !empty($profile->parsed_data['name']) ? $profile->parsed_data['name'] : $profile->name }}
                    </h2>
                    <p style="color: #605e5c; font-size: 14px; margin-bottom: 20px;">
                        {{ !empty($profile->parsed_data['email']) ? $profile->parsed_data['email'] : auth()->user()->email }}
                    </p>

                    @if(auth()->user()->cv_path)
                        <div style="padding-top: 20px; border-top: 1px solid #edebe9;">
                            <span
                                style="display: block; font-size: 12px; font-weight: 600; color: #605e5c; margin-bottom: 8px; text-transform: uppercase;">CV
                                File</span>
                            <a href="{{ asset('storage/' . auth()->user()->cv_path) }}" target="_blank"
                                class="btn btn-secondary btn-sm"
                                style="width: 100%; justify-content: center; text-decoration: none;">
                                📄 View CV File
                            </a>
                            <small style="display: block; margin-top: 8px; color: #a19f9d; font-size: 11px;">
                                Parsed using Smalot & Raw Scrape
                            </small>
                        </div>
                    @endif
                </div>

                <div class="form-container" style="padding: 24px;">
                    <h3
                        style="font-size: 14px; font-weight: 700; color: #323130; margin-bottom: 16px; text-transform: uppercase;">
                        Contact Details</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @php
                            $useCvPhone = !empty($profile->parsed_data['phone']);
                            $phone = $useCvPhone ? $profile->parsed_data['phone'] : $profile->phone_number;

                            $useCvAddress = !empty($profile->parsed_data['address']);
                            $address = $useCvAddress ? $profile->parsed_data['address'] : $profile->address;
                        @endphp

                        @if($phone)
                            <div style="font-size: 14px;">
                                <span
                                    style="color: #605e5c; display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                                    Phone
                                    {!! $useCvPhone ? '<span style="color: #0078d4;">(CV)</span>' : '<span style="color: #a19f9d;">(Manual)</span>' !!}
                                </span>
                                <span style="font-weight: 500; display: block; margin-top: 2px;">{{ $phone }}</span>
                            </div>
                        @endif

                        @if($profile->whatsapp_number)
                            <div style="font-size: 14px;">
                                <span
                                    style="color: #605e5c; display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">WhatsApp</span>
                                <span
                                    style="color: #107c10; font-weight: 600; display: block; margin-top: 2px;">{{ $profile->whatsapp_number }}</span>
                            </div>
                        @endif

                        @if($address)
                            <div style="font-size: 14px;">
                                <span
                                    style="color: #605e5c; display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                                    Address
                                    {!! $useCvAddress ? '<span style="color: #0078d4;">(CV)</span>' : '<span style="color: #a19f9d;">(Manual)</span>' !!}
                                </span>
                                <span style="display: block; margin-top: 2px; color: #323130;">{{ $address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Skills -->
                <div class="form-container" style="padding: 32px;">
                    @php
                        $useCvSkills = !empty($profile->parsed_data['skills']);
                        $skills = $useCvSkills ? $profile->parsed_data['skills'] : ($profile->skills ? explode(',', $profile->skills) : []);
                    @endphp
                    <h3
                        style="font-size: 18px; font-weight: 700; color: #323130; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <span>🛠️ Skills & Expertise</span>
                        <span
                            style="font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; border: 1px solid #edebe9; {{ $useCvSkills ? 'color: #0078d4; background: #e7f3ff;' : 'color: #605e5c; background: #f3f2f1;' }}">
                            {{ $useCvSkills ? 'Source: CV' : 'Source: Manual' }}
                        </span>
                    </h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @forelse($skills as $skill)
                            <span
                                style="background: #f3f2f1; color: #323130; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500; border: 1px solid #edebe9;">
                                {{ trim($skill) }}
                            </span>
                        @empty
                            <p style="color: #a19f9d; font-style: italic;">No skills listed</p>
                        @endforelse
                    </div>
                </div>

                <!-- Experience -->
                <div class="form-container" style="padding: 32px;">
                    @php
                        $useCvExp = !empty($profile->parsed_data['experience_items']);
                        $experience = $useCvExp ? $profile->parsed_data['experience_items'] : ($profile->experience ? explode("\n", $profile->experience) : []);
                    @endphp
                    <h3
                        style="font-size: 18px; font-weight: 700; color: #323130; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                        <span>💼 Professional Experience</span>
                        <span
                            style="font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; border: 1px solid #edebe9; {{ $useCvExp ? 'color: #0078d4; background: #e7f3ff;' : 'color: #605e5c; background: #f3f2f1;' }}">
                            {{ $useCvExp ? 'Source: CV' : 'Source: Manual' }}
                        </span>
                    </h3>

                    <div style="position: relative; padding-left: 20px; border-left: 2px solid #edebe9;">
                        @forelse($experience as $item)
                            @if(trim($item))
                                <div style="position: relative; margin-bottom: 24px;">
                                    <div
                                        style="position: absolute; left: -27px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: #0078d4; border: 3px solid white;">
                                    </div>
                                    <div style="font-size: 15px; color: #323130; line-height: 1.6; font-weight: 500;">
                                        {{ $item }}
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p style="color: #a19f9d; font-style: italic;">No experience details found</p>
                        @endforelse
                    </div>
                </div>

                <!-- Projects -->
                @php
                    $useCvProjects = !empty($profile->parsed_data['projects']);
                    $projects = $useCvProjects ? $profile->parsed_data['projects'] : [];
                @endphp
                @if(!empty($projects))
                    <div class="form-container" style="padding: 32px;">
                        <h3
                            style="font-size: 18px; font-weight: 700; color: #323130; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                            <span>📂 Key Projects</span>
                            <span
                                style="font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; border: 1px solid #edebe9; color: #0078d4; background: #e7f3ff;">
                                Source: CV
                            </span>
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($projects as $project)
                                <div
                                    style="font-size: 14px; color: #323130; padding-bottom: 12px; border-bottom: 1px dashed #edebe9; line-height: 1.5;">
                                    {{ $project }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Education -->
                @php
                    $useCvEdu = !empty($profile->parsed_data['education']);
                    $education = $useCvEdu ? $profile->parsed_data['education'] : ($profile->education_level ? [$profile->education_level] : []);
                @endphp
                <div class="form-container" style="padding: 32px;">
                    <h3
                        style="font-size: 18px; font-weight: 700; color: #323130; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                        <span>🎓 Education</span>
                        <span
                            style="font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; border: 1px solid #edebe9; {{ $useCvEdu ? 'color: #0078d4; background: #e7f3ff;' : 'color: #605e5c; background: #f3f2f1;' }}">
                            {{ $useCvEdu ? 'Source: CV' : 'Source: Manual' }}
                        </span>
                    </h3>
                    @if(!empty($education))
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($education as $edu)
                                <div
                                    style="font-size: 14px; color: #323130; padding-bottom: 12px; border-bottom: 1px dashed #edebe9;">
                                    {{ $edu }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #a19f9d; font-style: italic;">No education details listed</p>
                    @endif
                </div>

                <!-- Additional Details -->
                <div class="form-container" style="padding: 32px;">
                    <h3
                        style="font-size: 18px; font-weight: 700; color: #323130; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                        📝 Additional Details
                    </h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <div>
                            <span
                                style="color: #605e5c; display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Expected
                                Salary</span>
                            <span
                                style="font-size: 16px; font-weight: 600; color: #0078d4;">{{ $profile->expected_salary ? number_format((float) $profile->expected_salary, 2) : 'Not specified' }}</span>
                        </div>
                        <div>
                            <span
                                style="color: #605e5c; display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Gender</span>
                            <span
                                style="font-size: 16px; font-weight: 500;">{{ $profile->gender ? ucfirst($profile->gender) : 'Not specified' }}</span>
                        </div>
                        <div>
                            <span
                                style="color: #605e5c; display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Date
                                of Birth</span>
                            <span
                                style="font-size: 16px; font-weight: 500;">{{ $profile->date_of_birth ? ($profile->date_of_birth instanceof \DateTimeInterface ? $profile->date_of_birth->format('F d, Y') : \Carbon\Carbon::parse($profile->date_of_birth)->format('F d, Y')) : 'Not specified' }}</span>
                        </div>
                        <div>
                            <span
                                style="color: #605e5c; display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Location</span>
                            <span
                                style="font-size: 16px; font-weight: 500;">{{ $profile->location ?: ($profile->city ? $profile->city . ', ' . $profile->country : 'Not specified') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection