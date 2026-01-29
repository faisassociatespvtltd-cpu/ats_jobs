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
        $job->update(['status' => 'approved']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'job_approved',
            'description' => "Approved scraped job: {$job->title}",
            'confidence_score' => 100,
            'metadata' => ['job_id' => $job->id]
        ]);

        return redirect()->back()->with('success', 'Job approved successfully.');
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
            'url' => 'required|url'
        ]);

        $scrapingService = new \App\Services\ScrapingService();

        // Log the scraping attempt
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => 'job_scrape_attempt',
            'description' => "Attempted to scrape jobs from {$request->source}",
            'confidence_score' => 0,
            'metadata' => ['source' => $request->source, 'url' => $request->url]
        ]);

        return redirect()->route('superadmin.scraped-jobs')
            ->with('info', 'Scraping initiated. This feature requires API integration for ' . $request->source);
    }
}
