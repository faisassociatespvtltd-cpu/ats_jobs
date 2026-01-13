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
        $totalJobs = JobPosting::count();
        $totalApplicants = Applicant::count();
        $totalInterviews = Interview::count();
        $activeJobs = JobPosting::where('status', 'active')->count();
        
        return view('dashboard', compact('totalJobs', 'totalApplicants', 'totalInterviews', 'activeJobs'));
    }
}
