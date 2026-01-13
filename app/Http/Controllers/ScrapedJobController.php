<?php

namespace App\Http\Controllers;

use App\Models\ScrapedJob;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class ScrapedJobController extends Controller
{
    public function index(Request $request)
    {
        $query = ScrapedJob::query();
        
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort_by', 'scraped_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $scrapedJobs = $query->paginate(30);
        
        // Summaries
        $totalScraped = ScrapedJob::count();
        $whatsappJobs = ScrapedJob::where('source', 'whatsapp')->count();
        $linkedinJobs = ScrapedJob::where('source', 'linkedin')->count();
        $facebookJobs = ScrapedJob::where('source', 'facebook')->count();
        $otherJobs = ScrapedJob::where('source', 'other')->count();
        $pendingJobs = ScrapedJob::where('status', 'pending')->count();
        
        return view('scraped-jobs.index', compact('scrapedJobs', 'totalScraped', 'whatsappJobs', 'linkedinJobs', 'facebookJobs', 'otherJobs', 'pendingJobs'));
    }
    
    public function create()
    {
        return view('scraped-jobs.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|in:whatsapp,linkedin,facebook,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'nullable|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|string',
            'job_type' => 'nullable|string',
            'source_url' => 'nullable|url',
            'status' => 'required|in:pending,reviewed,imported,rejected',
        ]);
        
        $validated['scraped_at'] = now();
        
        ScrapedJob::create($validated);
        
        return redirect()->route('scraped-jobs.index')->with('success', 'Scraped job created successfully.');
    }
    
    public function show(ScrapedJob $scrapedJob)
    {
        $scrapedJob->load('importedToJob');
        return view('scraped-jobs.show', compact('scrapedJob'));
    }
    
    public function edit(ScrapedJob $scrapedJob)
    {
        return view('scraped-jobs.edit', compact('scrapedJob'));
    }
    
    public function update(Request $request, ScrapedJob $scrapedJob)
    {
        $validated = $request->validate([
            'source' => 'required|in:whatsapp,linkedin,facebook,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'nullable|string',
            'location' => 'nullable|string',
            'salary' => 'nullable|string',
            'job_type' => 'nullable|string',
            'source_url' => 'nullable|url',
            'status' => 'required|in:pending,reviewed,imported,rejected',
        ]);
        
        $scrapedJob->update($validated);
        
        return redirect()->route('scraped-jobs.index')->with('success', 'Scraped job updated successfully.');
    }
    
    public function destroy(ScrapedJob $scrapedJob)
    {
        $scrapedJob->delete();
        return redirect()->route('scraped-jobs.index')->with('success', 'Scraped job deleted successfully.');
    }
}
