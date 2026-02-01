<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\JobPosting;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\ActivityLog;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_employees' => User::where('user_type', 'employee')->count(),
            'total_employers' => User::where('user_type', 'employer')->count(),
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'total_applications' => Applicant::count(),
            'total_interviews' => Interview::count(),
            'hires' => Applicant::where('status', 'hired')->count(),
            'rejected' => Applicant::where('status', 'rejected')->count(),
        ];

        $recentLogs = ActivityLog::with('user')->latest()->take(10)->get();

        return view('superadmin.dashboard', compact('stats', 'recentLogs'));
    }

    public function users()
    {
        $users = User::withCount(['employeeProfile', 'employerProfile'])
            ->latest()
            ->paginate(20);

        return view('superadmin.users.index', compact('users'));
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_blocked' => true]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'user_blocked',
            'description' => "Blocked user: {$user->email}",
            'confidence_score' => 100,
            'metadata' => ['user_id' => $user->id]
        ]);

        return redirect()->back()->with('success', 'User blocked successfully.');
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_blocked' => false]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'user_activated',
            'description' => "Activated user: {$user->email}",
            'confidence_score' => 100,
            'metadata' => ['user_id' => $user->id]
        ]);

        return redirect()->back()->with('success', 'User activated successfully.');
    }

    public function scrapedJobs()
    {
        $scrapedJobs = \App\Models\ScrapedJob::with('user')
            ->latest()
            ->paginate(20);

        return view('superadmin.scraped-jobs.index', compact('scrapedJobs'));
    }

    public function showScrapedJob($id)
    {
        $job = \App\Models\ScrapedJob::with('user')->findOrFail($id);
        return view('superadmin.scraped-jobs.show', compact('job'));
    }

    public function parseJob($id)
    {
        $job = \App\Models\ScrapedJob::findOrFail($id);

        // Use JobParserService to parse the job
        $parser = new \App\Services\JobParserService();
        $parsedData = $parser->parse($job->description ?? $job->title);

        // Update the scraped job with parsed data
        $job->update([
            'parsed_data' => $parsedData,
            'status' => 'parsed'
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'job_parse',
            'description' => "Parsed job: {$job->title}",
            'confidence_score' => 85,
            'metadata' => ['job_id' => $job->id, 'parsed_data' => $parsedData]
        ]);

        return redirect()->back()->with('success', 'Job parsed successfully.');
    }

    public function approveJob($id)
    {
        $job = \App\Models\ScrapedJob::findOrFail($id);
        
        // Prepare data for JobPosting
        // Use parsed_data if available, otherwise fall back to raw fields
        $data = $job->parsed_data ?? [];
        
        $jobPosting = \App\Models\JobPosting::create([
            'title' => $data['title'] ?? $job->title,
            'description' => $job->description,
            'company_name' => $job->company_name ?? 'Scraped Job',
            'location' => $data['location'] ?? ($job->location ?? 'N/A'),
            'job_type' => $data['job_type'] ?? ($job->job_type ?? 'Full-time'),
            'required_skills' => $data['required_skills'] ?? null,
            'responsibilities' => $data['responsibilities'] ?? null,
            'qualifications' => $data['qualifications'] ?? null,
            'salary_range' => $data['salary_range'] ?? ($job->salary ?? null),
            'status' => 'active',
            'source' => $job->source,
            'source_url' => $job->source_url,
            'posted_date' => now(),
            'posted_by' => auth()->id(),
            'hard_skills' => $data['hard_skills'] ?? null,
            'soft_skills' => $data['soft_skills'] ?? null,
        ]);

        $job->update([
            'status' => 'approved',
            'is_imported' => true,
            'imported_to_job_id' => $jobPosting->id
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'job_approved',
            'description' => "Approved scraped job: {$job->title} and created job posting #{$jobPosting->id}",
            'confidence_score' => 100,
            'metadata' => ['job_id' => $job->id, 'job_posting_id' => $jobPosting->id]
        ]);

        return redirect()->back()->with('success', 'Job approved and posted to the job board.');
    }

    public function rejectJob($id)
    {
        $job = \App\Models\ScrapedJob::findOrFail($id);
        $job->update(['status' => 'rejected']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'job_rejected',
            'description' => "Rejected scraped job: {$job->title}",
            'confidence_score' => 100,
            'metadata' => ['job_id' => $job->id]
        ]);

        return redirect()->back()->with('success', 'Job rejected successfully.');
    }

    public function scrapeFromSource()
    {
        return view('superadmin.scraped-jobs.scrape');
    }

    public function executeScrape(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'source' => 'required|in:whatsapp,linkedin,facebook,instagram,other',
            'url' => 'nullable|url',
            'pasted_data' => 'nullable|string'
        ]);

        if (!$request->url && !$request->pasted_data) {
            return redirect()->back()->with('error', 'Please provide either a URL or paste some data.');
        }

        $scrapingService = new \App\Services\ScrapingService();

        if ($request->filled('pasted_data')) {
            $results = $scrapingService->processPastedData($request->pasted_data, $request->source);
            
            foreach ($results as $jobData) {
                $jobData['user_id'] = auth()->id();
                \App\Models\ScrapedJob::create($jobData);
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'type' => 'job_scrape_paste',
                'description' => "Processed " . count($results) . " jobs from pasted {$request->source} data",
                'confidence_score' => 100,
                'metadata' => ['source' => $request->source, 'count' => count($results)]
            ]);

            return redirect()->route('superadmin.scraped-jobs')
                ->with('success', count($results) . ' jobs extracted from pasted data.');
        }

        // Fallback to URL-based placeholder
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'job_scrape_attempt',
            'description' => "Attempted to scrape jobs from {$request->source}",
            'confidence_score' => 0,
            'metadata' => ['source' => $request->source, 'url' => $request->url]
        ]);

        return redirect()->route('superadmin.scraped-jobs')
            ->with('info', 'Scraping initiated for ' . $request->url . '. Note: Automated scraping for ' . $request->source . ' requires API configuration.');
    }

    public function scrapedJobApplicants()
    {
        $applicants = \App\Models\Applicant::whereHas('jobPosting', function ($query) {
            $query->whereNotNull('source');
        })
        ->with('jobPosting', 'user')
        ->latest()
        ->paginate(20);

        return view('superadmin.scraped-jobs.applicants', compact('applicants'));
    }

    public function scrapedJobApplicantShow($id)
    {
        $applicant = \App\Models\Applicant::with('jobPosting', 'user.employeeProfile')->findOrFail($id);
        return view('superadmin.scraped-jobs.applicant-show', compact('applicant'));
    }

    public function updateApplicantStatus(\Illuminate\Http\Request $request, $id)
    {
        $applicant = \App\Models\Applicant::findOrFail($id);
        
        $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $applicant->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $applicant->notes
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'applicant_status_update',
            'description' => "Updated status for applicant {$applicant->first_name} to {$request->status}",
            'confidence_score' => 100,
            'metadata' => ['applicant_id' => $applicant->id, 'status' => $request->status]
        ]);

        return redirect()->back()->with('success', 'Applicant status updated successfully.');
    }
}
