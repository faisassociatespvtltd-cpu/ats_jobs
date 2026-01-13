<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $query = Forum::with('user')->whereNull('parent_id');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $sortBy = $request->get('sort_by', 'last_reply_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy == 'last_reply_at') {
            $query->orderBy('is_pinned', 'desc')->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $forums = $query->paginate(30);
        
        // Summaries
        $totalForums = Forum::whereNull('parent_id')->count();
        $totalReplies = Forum::whereNotNull('parent_id')->count();
        $pinnedForums = Forum::whereNull('parent_id')->where('is_pinned', true)->count();
        
        return view('forums.index', compact('forums', 'totalForums', 'totalReplies', 'pinnedForums'));
    }
    
    public function create()
    {
        return view('forums.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string',
        ]);
        
        $validated['user_id'] = auth()->id() ?? 1;
        
        Forum::create($validated);
        
        return redirect()->route('forums.index')->with('success', 'Forum post created successfully.');
    }
    
    public function show(Forum $forum)
    {
        $forum->load('user', 'replies.user');
        $forum->increment('views');
        return view('forums.show', compact('forum'));
    }
    
    public function edit(Forum $forum)
    {
        return view('forums.edit', compact('forum'));
    }
    
    public function update(Request $request, Forum $forum)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string',
        ]);
        
        $forum->update($validated);
        
        return redirect()->route('forums.show', $forum)->with('success', 'Forum post updated successfully.');
    }
    
    public function destroy(Forum $forum)
    {
        $forum->delete();
        return redirect()->route('forums.index')->with('success', 'Forum post deleted successfully.');
    }
}
