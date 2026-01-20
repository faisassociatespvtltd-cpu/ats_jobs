<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ResumeParserService;

class ResumeController extends Controller
{
    public function index(Request $request)
    {
        $query = Resume::with('applicant');
        
        if ($request->filled('search')) {
            $query->whereHas('applicant', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('parsing_status')) {
            $query->where('parsing_status', $request->parsing_status);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $resumes = $query->paginate(30);
        
        // Summaries
        $totalResumes = Resume::count();
        $parsedResumes = Resume::where('parsing_status', 'completed')->count();
        $pendingResumes = Resume::where('parsing_status', 'pending')->count();
        $failedResumes = Resume::where('parsing_status', 'failed')->count();
        
        return view('resumes.index', compact('resumes', 'totalResumes', 'parsedResumes', 'pendingResumes', 'failedResumes'));
    }
    
    public function create()
    {
        $applicants = Applicant::all();
        return view('resumes.create', compact('applicants'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_id' => 'nullable|exists:applicants,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('resumes', $fileName, 'public');
            
            $resume = Resume::create([
                'applicant_id' => $validated['applicant_id'] ?? null,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'parsing_status' => 'pending',
            ]);

            $parser = new ResumeParserService();
            $parsed = $parser->parseFromStorage($filePath);

            $resume->update([
                'parsed_content' => [
                    'text' => $parsed['text'] ?? '',
                    'error' => $parsed['error'] ?? null,
                    'name' => $parsed['name'] ?? null,
                    'email' => $parsed['email'] ?? null,
                    'phone' => $parsed['phone'] ?? null,
                    'address' => $parsed['address'] ?? null,
                    'linkedin' => $parsed['linkedin'] ?? null,
                    'github' => $parsed['github'] ?? null,
                    'website' => $parsed['website'] ?? null,
                    'education' => $parsed['education'] ?? [],
                    'experience' => $parsed['experience_items'] ?? [],
                    'timeline' => $parsed['timeline'] ?? [],
                ],
                'skills' => $parsed['skills'] ?? null,
                'experience' => $parsed['experience_items'] ?? null,
                'education' => $parsed['education'] ?? null,
                'parsing_status' => empty($parsed['text']) ? 'failed' : 'completed',
            ]);
        }
        
        return redirect()->route('resumes.index')->with('success', 'Resume uploaded and parsed successfully.');
    }
    
    public function show(Resume $resume)
    {
        $resume->load('applicant');
        return view('resumes.show', compact('resume'));
    }
    
    public function edit(Resume $resume)
    {
        $applicants = Applicant::all();
        return view('resumes.edit', compact('resume', 'applicants'));
    }
    
    public function update(Request $request, Resume $resume)
    {
        $validated = $request->validate([
            'applicant_id' => 'nullable|exists:applicants,id',
            'parsing_status' => 'required|in:pending,completed,failed',
            'skills' => 'nullable|array',
            'experience' => 'nullable|array',
            'education' => 'nullable|array',
        ]);
        
        $resume->update($validated);
        
        return redirect()->route('resumes.index')->with('success', 'Resume updated successfully.');
    }
    
    public function destroy(Resume $resume)
    {
        if ($resume->file_path && Storage::disk('public')->exists($resume->file_path)) {
            Storage::disk('public')->delete($resume->file_path);
        }
        
        $resume->delete();
        return redirect()->route('resumes.index')->with('success', 'Resume deleted successfully.');
    }
    
    public function download(Resume $resume)
    {
        if ($resume->file_path && Storage::disk('public')->exists($resume->file_path)) {
            return Storage::disk('public')->download($resume->file_path, $resume->file_name);
        }
        
        return redirect()->back()->with('error', 'File not found.');
    }

    public function print(Resume $resume)
    {
        $resume->load('applicant');
        return view('resumes.print', compact('resume'));
    }
}
