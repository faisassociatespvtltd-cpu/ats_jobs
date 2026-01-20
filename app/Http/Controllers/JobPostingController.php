<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\JobParserService;

class JobPostingController extends Controller
{
    protected function ensureEmployer()
    {
        if (!auth()->check() || !auth()->user()->isEmployer()) {
            abort(403, 'Only employers can perform this action.');
        }
    }

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
        $this->ensureEmployer();
        return view('job-postings.create');
    }
    
    public function store(Request $request)
    {
        $this->ensureEmployer();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'required_skills' => 'required|string|max:1000',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'experience_level' => 'nullable|string',
            'education_required' => 'nullable|string|max:255',
            'experience_required' => 'nullable|string|max:255',
            'closing_date' => 'required|date|after_or_equal:today',
            'status' => 'nullable|in:draft,active,closed,cancelled',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
        ]);
        
        $validated['posted_by'] = auth()->id();
        $validated['posted_date'] = now();
        $validated['status'] = $validated['status'] ?? 'active';
        
        $parser = new JobParserService();
        $parsed = $parser->parse($validated['description']);

        $validated['required_skills'] = $validated['required_skills'] ?: ($parsed['required_skills'] ?? null);
        $validated['responsibilities'] = $validated['responsibilities'] ?? $parsed['responsibilities'] ?? null;
        $validated['qualifications'] = $validated['qualifications'] ?? $parsed['qualifications'] ?? null;
        $validated['salary_range'] = $validated['salary_range'] ?? $parsed['salary_range'] ?? null;
        $validated['hard_skills'] = $parsed['hard_skills'] ?? null;
        $validated['soft_skills'] = $parsed['soft_skills'] ?? null;
        $validated['parsed_at'] = now();

        if (!empty($parsed['closing_date']) && empty($validated['closing_date'])) {
            $validated['closing_date'] = $parsed['closing_date'];
        }

        JobPosting::create($validated);
        
        return redirect()->route('employer.jobs')->with('toast', [
            'type' => 'success',
            'message' => 'Job successfully parsed and posted! You can view your posted jobs in your dashboard.',
        ]);
    }
    
    public function show(JobPosting $jobPosting)
    {
        return view('job-postings.show', compact('jobPosting'));
    }
    
    public function edit(JobPosting $jobPosting)
    {
        $this->ensureEmployer();
        return view('job-postings.edit', compact('jobPosting'));
    }
    
    public function update(Request $request, JobPosting $jobPosting)
    {
        $this->ensureEmployer();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'required_skills' => 'required|string|max:1000',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'experience_level' => 'nullable|string',
            'education_required' => 'nullable|string|max:255',
            'experience_required' => 'nullable|string|max:255',
            'closing_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:draft,active,closed,cancelled',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
        ]);
        
        $jobPosting->update($validated);
        
        return redirect()->route('employer.jobs')->with('toast', [
            'type' => 'success',
            'message' => 'Job posting updated successfully.',
        ]);
    }
    
    public function destroy(JobPosting $jobPosting)
    {
        $this->ensureEmployer();
        $jobPosting->delete();
        return redirect()->route('employer.jobs')->with('toast', [
            'type' => 'success',
            'message' => 'Job posting deleted successfully.',
        ]);
    }

    public function employeeIndex(Request $request)
    {
        $query = JobPosting::where('status', 'active');
        $profile = auth()->user()?->employeeProfile;

        $locationFilter = $request->get('location');
        $skillsFilter = $request->get('skills');
        $jobTypeFilter = $request->get('job_type');

        if (!$request->hasAny(['location', 'skills', 'job_type']) && $profile) {
            $locationFilter = $profile->location ?: $profile->city;
            $skillsFilter = $profile->skills;
        }

        if ($locationFilter) {
            $query->where('location', 'like', '%' . $locationFilter . '%');
        }

        if ($jobTypeFilter) {
            $query->where('job_type', $jobTypeFilter);
        }

        if ($skillsFilter) {
            $query->where('required_skills', 'like', '%' . $skillsFilter . '%');
        }

        $jobs = $query->orderByDesc('posted_date')->paginate(20);

        if ($profile && !$request->hasAny(['location', 'skills', 'job_type'])) {
            $profileSkills = array_filter(array_map('trim', explode(',', (string) $profile->skills)));
            $matched = $jobs->filter(function ($job) use ($profileSkills) {
                if (empty($profileSkills)) {
                    return false;
                }
                $jobSkills = array_map('trim', explode(',', (string) $job->required_skills));
                return count(array_intersect(array_map('strtolower', $profileSkills), array_map('strtolower', $jobSkills))) > 0;
            });

            if ($matched->count() > 0) {
                session()->flash('toast', [
                    'type' => 'success',
                    'message' => 'New job opportunities found that match your skills!',
                ]);
            }
        }

        return view('jobs.index', [
            'jobs' => $jobs,
            'locationFilter' => $locationFilter,
            'skillsFilter' => $skillsFilter,
            'jobTypeFilter' => $jobTypeFilter,
        ]);
    }

    public function employeeShow(JobPosting $jobPosting)
    {
        $hasApplied = false;
        if (auth()->check() && auth()->user()->isEmployee()) {
            $hasApplied = Applicant::where('job_posting_id', $jobPosting->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('jobs.show', compact('jobPosting', 'hasApplied'));
    }

    public function apply(JobPosting $jobPosting)
    {
        if (!auth()->check() || !auth()->user()->isEmployee()) {
            abort(403, 'Only employees can apply for jobs.');
        }

        $user = auth()->user();
        $profile = $user->employeeProfile;

        if (!$profile) {
            return redirect()->route('employee.profile.complete')->with('toast', [
                'type' => 'error',
                'message' => 'Please complete your profile before applying.',
            ]);
        }

        if (!$user->cv_path) {
            return redirect()->route('employee.profile.edit')->with('toast', [
                'type' => 'error',
                'message' => 'Please upload your CV before applying.',
            ]);
        }

        $alreadyApplied = Applicant::where('job_posting_id', $jobPosting->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()->route('jobs.show', $jobPosting)->with('toast', [
                'type' => 'info',
                'message' => 'You have already applied for this job.',
            ]);
        }

        $nameParts = preg_split('/\s+/', trim($profile->name));
        $firstName = $nameParts[0] ?? $profile->name;
        $lastName = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : null;

        Applicant::create([
            'job_posting_id' => $jobPosting->id,
            'user_id' => $user->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $user->email,
            'phone' => $profile->phone_number,
            'cv_path' => $user->cv_path,
            'application_date' => now(),
            'status' => 'pending',
        ]);

        return redirect()->route('jobs.show', $jobPosting)->with('toast', [
            'type' => 'success',
            'message' => 'Your application has been submitted successfully! We will notify you once the employer reviews it.',
        ]);
    }

    public function employerIndex()
    {
        $this->ensureEmployer();
        $jobs = JobPosting::where('posted_by', auth()->id())
            ->withCount('applicants')
            ->orderByDesc('posted_date')
            ->paginate(20);

        return view('employer.jobs.index', compact('jobs'));
    }

    public function applicants(JobPosting $jobPosting, Request $request)
    {
        $this->ensureEmployer();

        if ($jobPosting->posted_by !== auth()->id()) {
            abort(403, 'You can only view applicants for your own jobs.');
        }

        $query = $jobPosting->applicants()->with('user.employeeProfile');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $sortBy = $request->get('sort_by', 'application_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $applicants = $query->paginate(20);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Applicant list loaded successfully! View and manage all applicants for your job post.',
        ]);

        return view('job-postings.applicants', compact('jobPosting', 'applicants'));
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
