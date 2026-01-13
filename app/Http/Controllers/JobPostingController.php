<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::query();
        
        // Filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $jobPostings = $query->paginate(30);
        
        // Summaries
        $totalJobs = JobPosting::count();
        $activeJobs = JobPosting::where('status', 'active')->count();
        $draftJobs = JobPosting::where('status', 'draft')->count();
        $closedJobs = JobPosting::where('status', 'closed')->count();
        
        return view('job-postings.index', compact('jobPostings', 'totalJobs', 'activeJobs', 'draftJobs', 'closedJobs'));
    }
    
    public function create()
    {
        return view('job-postings.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'experience_level' => 'nullable|string',
            'posted_date' => 'required|date',
            'closing_date' => 'nullable|date',
            'status' => 'required|in:draft,active,closed,cancelled',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
        ]);
        
        $validated['posted_by'] = auth()->id();
        $validated['posted_date'] = now();
        
        JobPosting::create($validated);
        
        return redirect()->route('job-postings.index')->with('success', 'Job posting created successfully.');
    }
    
    public function show(JobPosting $jobPosting)
    {
        return view('job-postings.show', compact('jobPosting'));
    }
    
    public function edit(JobPosting $jobPosting)
    {
        return view('job-postings.edit', compact('jobPosting'));
    }
    
    public function update(Request $request, JobPosting $jobPosting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'experience_level' => 'nullable|string',
            'posted_date' => 'required|date',
            'closing_date' => 'nullable|date',
            'status' => 'required|in:draft,active,closed,cancelled',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
        ]);
        
        $jobPosting->update($validated);
        
        return redirect()->route('job-postings.index')->with('success', 'Job posting updated successfully.');
    }
    
    public function destroy(JobPosting $jobPosting)
    {
        $jobPosting->delete();
        return redirect()->route('job-postings.index')->with('success', 'Job posting deleted successfully.');
    }
    
    public function exportExcel()
    {
        // Excel export will be implemented with maatwebsite/excel package
        return response()->download(storage_path('app/exports/job-postings.xlsx'));
    }
    
    public function exportPdf()
    {
        // PDF export will be implemented with dompdf package
        return response()->download(storage_path('app/exports/job-postings.pdf'));
    }
    
    public function importExcel(Request $request)
    {
        // Excel import will be implemented with maatwebsite/excel package
        return response()->json(['success' => true, 'message' => 'Import functionality will be implemented']);
    }
}
