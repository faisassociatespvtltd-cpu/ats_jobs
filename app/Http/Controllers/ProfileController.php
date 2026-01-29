<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProfile;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ResumeParserService;

class ProfileController extends Controller
{
    /**
     * Show employee profile.
     */
    public function showEmployeeProfile()
    {
        $user = Auth::user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard');
        }

        $profile = $user->employeeProfile;

        if (!$profile) {
            return redirect()->route('employee.profile.complete');
        }

        // Trigger parsing if missing
        if ($user->cv_path && !$profile->parsed_data) {
            try {
                $parser = new ResumeParserService();
                $parsed = $parser->parseFromStorage($user->cv_path);

                $profile->update([
                    'parsed_data' => $parsed,
                    'profile_photo_path' => $parsed['profile_photo'] ?? $profile->profile_photo_path,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to parse resume on profile view', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        return view('profiles.employee.show', compact('profile'));
    }

    /**
     * Show employee profile edit form.
     */
    public function editEmployeeProfile()
    {
        $user = Auth::user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard');
        }

        $profile = $user->employeeProfile;

        if (!$profile) {
            return redirect()->route('employee.profile.complete');
        }

        return view('profiles.employee.edit', compact('profile'));
    }

    /**
     * Update employee profile.
     */
    public function updateEmployeeProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEmployee()) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|max:20',
            'expected_salary' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string',
            'education_level' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'linkedin_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $profile = $user->employeeProfile;

        if (!$profile) {
            return redirect()->route('employee.profile.complete');
        }

        // Update CV if provided
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($user->cv_path) {
                Storage::disk('public')->delete($user->cv_path);
            }

            $cvPath = $request->file('cv')->store('cvs', 'public');
            $user->cv_path = $cvPath;
            $user->save();

            // Store new parsed data
            try {
                $parser = new ResumeParserService();
                $parsed = $parser->parseFromStorage($cvPath);
                $profile->update([
                    'parsed_data' => $parsed,
                    'profile_photo_path' => $parsed['profile_photo'] ?? $profile->profile_photo_path,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to parse resume after update', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        // Update profile
        $profile->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'cnic' => $request->cnic,
            'expected_salary' => $request->expected_salary,
            'location' => $request->location,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'bio' => $request->bio,
            'education_level' => $request->education_level,
            'skills' => $request->skills,
            'experience' => $request->experience,
            'linkedin_url' => $request->linkedin_url,
            'portfolio_url' => $request->portfolio_url,
        ]);

        $user->name = $request->name;
        $user->save();

        return redirect()->route('employee.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show employer profile.
     */
    public function showEmployerProfile()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            return redirect()->route('dashboard');
        }

        $profile = $user->employerProfile;

        if (!$profile) {
            return redirect()->route('employer.profile.complete');
        }

        return view('profiles.employer.show', compact('profile'));
    }

    /**
     * Show employer profile edit form.
     */
    public function editEmployerProfile()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            return redirect()->route('dashboard');
        }

        $profile = $user->employerProfile;

        if (!$profile) {
            return redirect()->route('employer.profile.complete');
        }

        return view('profiles.employer.edit', compact('profile'));
    }

    /**
     * Update employer profile.
     */
    public function updateEmployerProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'contact_person_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'website_url' => 'nullable|url',
            'company_description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'company_type' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'linkedin_url' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = $user->employerProfile;

        if (!$profile) {
            return redirect()->route('employer.profile.complete');
        }

        // Update logo if provided
        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            if ($user->company_logo_path) {
                Storage::disk('public')->delete($user->company_logo_path);
            }

            $logoPath = $request->file('company_logo')->store('company-logos', 'public');
            $user->company_logo_path = $logoPath;
            $user->save();
        }

        // Update profile
        $profile->update([
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'contact_person_name' => $request->contact_person_name,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'website_url' => $request->website_url,
            'company_description' => $request->company_description,
            'industry' => $request->industry,
            'company_size' => $request->company_size,
            'license_number' => $request->license_number,
            'registration_number' => $request->registration_number,
            'tax_number' => $request->tax_number,
            'company_type' => $request->company_type,
            'founded_year' => $request->founded_year,
            'linkedin_url' => $request->linkedin_url,
        ]);

        $user->name = $request->company_name;
        $user->save();

        return redirect()->route('employer.profile')->with('success', 'Profile updated successfully.');
    }

    public function refreshFromCv(Request $request)
    {
        $user = auth()->user();
        $profile = $user->employeeProfile;

        if (!$user->cv_path) {
            return redirect()->back()->with('error', 'No CV found to refresh from.');
        }

        try {
            $parser = new ResumeParserService();
            $parsed = $parser->parseFromStorage($user->cv_path);

            $profile->update([
                'parsed_data' => $parsed,
                'profile_photo_path' => $parsed['profile_photo'] ?? $profile->profile_photo_path,
            ]);

            return redirect()->back()->with('success', 'Profile data refreshed from CV successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to refresh profile from CV', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to refresh data from CV: ' . $e->getMessage());
        }
    }
}
