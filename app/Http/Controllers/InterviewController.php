<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Applicant;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Interview::with('applicant', 'jobPosting', 'interviewer');
        
        if ($request->filled('search')) {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort_by', 'scheduled_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $interviews = $query->paginate(30);
        
        // Summaries
        $totalInterviews = Interview::count();
        $scheduledInterviews = Interview::where('status', 'scheduled')->count();
        $completedInterviews = Interview::where('status', 'completed')->count();
        $cancelledInterviews = Interview::where('status', 'cancelled')->count();
        
        return view('interviews.index', compact('interviews', 'totalInterviews', 'scheduledInterviews', 'completedInterviews', 'cancelledInterviews'));
    }
    
    public function create()
    {
        $applicants = Applicant::all();
        $jobPostings = JobPosting::all();
        $interviewers = User::all();
        return view('interviews.create', compact('applicants', 'jobPostings', 'interviewers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'job_posting_id' => 'required|exists:job_postings,id',
            'scheduled_at' => 'required|date',
            'interview_type' => 'required|string',
            'location' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'interviewer_id' => 'nullable|exists:users,id',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
        ]);
        
        Interview::create($validated);
        
        return redirect()->route('interviews.index')->with('success', 'Interview scheduled successfully.');
    }
    
    public function show(Interview $interview)
    {
        $interview->load('applicant', 'jobPosting', 'interviewer');
        return view('interviews.show', compact('interview'));
    }
    
    public function edit(Interview $interview)
    {
        $applicants = Applicant::all();
        $jobPostings = JobPosting::all();
        $interviewers = User::all();
        return view('interviews.edit', compact('interview', 'applicants', 'jobPostings', 'interviewers'));
    }
    
    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'job_posting_id' => 'required|exists:job_postings,id',
            'scheduled_at' => 'required|date',
            'interview_type' => 'required|string',
            'location' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'interviewer_id' => 'nullable|exists:users,id',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'notes' => 'nullable|string',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);
        
        $interview->update($validated);
        
        return redirect()->route('interviews.index')->with('success', 'Interview updated successfully.');
    }
    
    public function destroy(Interview $interview)
    {
        $interview->delete();
        return redirect()->route('interviews.index')->with('success', 'Interview deleted successfully.');
    }
}
