<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Applicant;
use App\Models\Interview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->isEmployer()) {
            $totalJobs = JobPosting::where('posted_by', $user->id)->count();
            $activeJobs = JobPosting::where('posted_by', $user->id)->where('status', 'active')->count();
            $totalApplicants = Applicant::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('posted_by', $user->id);
            })->count();
            $totalInterviews = Interview::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('posted_by', $user->id);
            })->count();
        } else {
            $totalJobs = JobPosting::where('status', 'active')->count();
            $activeJobs = $totalJobs;
            $totalApplicants = Applicant::where('user_id', $user?->id)->count();
            $totalInterviews = Interview::whereHas('applicant', function ($q) use ($user) {
                $q->where('user_id', $user?->id);
            })->count();
        }

        return view('dashboard', compact('totalJobs', 'totalApplicants', 'totalInterviews', 'activeJobs'));
    }
}
