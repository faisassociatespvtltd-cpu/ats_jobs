<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function apply(JobPosting $jobPosting, Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->isEmployee()) {
            return redirect()->route('login');
        }

        if ($jobPosting->status !== 'active') {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'This job is not accepting applications.',
            ]);
        }

        if ($jobPosting->closing_date && now()->startOfDay()->gt($jobPosting->closing_date)) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'The application deadline has passed.',
            ]);
        }

        if (!$user->cv_path) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Please upload your CV before applying.',
            ]);
        }

        if (Applicant::where('job_posting_id', $jobPosting->id)->where('user_id', $user->id)->exists()) {
            return back()->with('toast', [
                'type' => 'info',
                'message' => 'You have already applied for this job.',
            ]);
        }

        $profile = $user->employeeProfile;
        $fullName = $profile?->name ?? $user->name ?? '';
        $nameParts = preg_split('/\s+/', trim($fullName));
        $firstName = $nameParts[0] ?? '';
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        Applicant::create([
            'job_posting_id' => $jobPosting->id,
            'user_id' => $user->id,
            'first_name' => $firstName ?: 'Employee',
            'last_name' => $lastName ?: '',
            'email' => $user->email,
            'phone' => $profile?->phone_number,
            'status' => 'pending',
            'application_date' => now()->toDateString(),
            'cv_path' => $user->cv_path,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Your application has been submitted successfully! We will notify you once the employer reviews it.',
        ]);
    }
}
