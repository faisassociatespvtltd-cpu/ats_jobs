<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reviewed_id' => 'required|exists:users,id',
            'job_posting_id' => 'nullable|exists:job_postings,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        $reviewer = Auth::user();
        
        if ($reviewer->id == $request->reviewed_id) {
             return back()->with('toast', ['type' => 'error', 'message' => 'You cannot rate yourself.']);
        }

        // Check if already rated for this specific job context (or general duplicate check logic)
        // For simplicity: One review per pair per job, or just one review per pair?
        // Let's allow multiple if job_id is different. If job_id is null, maybe just one?
        // Let's stick to: One review per job interaction.
        
        $existingReview = Review::where('reviewer_id', $reviewer->id)
                                ->where('reviewed_id', $request->reviewed_id)
                                ->where('job_posting_id', $request->job_posting_id)
                                ->first();

        if ($existingReview) {
             return back()->with('toast', ['type' => 'error', 'message' => 'You have already rated this user for this job.']);
        }

        Review::create([
            'reviewer_id' => $reviewer->id,
            'reviewed_id' => $request->reviewed_id,
            'job_posting_id' => $request->job_posting_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Review submitted successfully.']);
    }
}
