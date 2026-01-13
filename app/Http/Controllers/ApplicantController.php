<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with('jobPosting');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $applicants = $query->paginate(30);
        
        // Summaries
        $totalApplicants = Applicant::count();
        $pendingApplicants = Applicant::where('status', 'pending')->count();
        $shortlistedApplicants = Applicant::where('status', 'shortlisted')->count();
        $hiredApplicants = Applicant::where('status', 'hired')->count();
        
        $jobPostings = JobPosting::all();
        
        return view('applicants.index', compact('applicants', 'totalApplicants', 'pendingApplicants', 'shortlistedApplicants', 'hiredApplicants', 'jobPostings'));
    }
    
    public function create()
    {
        $jobPostings = JobPosting::all();
        return view('applicants.create', compact('jobPostings'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'cover_letter' => 'nullable|string',
            'status' => 'required|in:pending,reviewed,shortlisted,interviewed,offered,rejected,hired',
            'application_date' => 'required|date',
        ]);
        
        $validated['application_date'] = now();
        
        Applicant::create($validated);
        
        return redirect()->route('applicants.index')->with('success', 'Applicant created successfully.');
    }
    
    public function show(Applicant $applicant)
    {
        $applicant->load('jobPosting', 'resume', 'interviews');
        return view('applicants.show', compact('applicant'));
    }
    
    public function edit(Applicant $applicant)
    {
        $jobPostings = JobPosting::all();
        return view('applicants.edit', compact('applicant', 'jobPostings'));
    }
    
    public function update(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'cover_letter' => 'nullable|string',
            'status' => 'required|in:pending,reviewed,shortlisted,interviewed,offered,rejected,hired',
            'notes' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);
        
        $applicant->update($validated);
        
        return redirect()->route('applicants.index')->with('success', 'Applicant updated successfully.');
    }
    
    public function destroy(Applicant $applicant)
    {
        $applicant->delete();
        return redirect()->route('applicants.index')->with('success', 'Applicant deleted successfully.');
    }
}
