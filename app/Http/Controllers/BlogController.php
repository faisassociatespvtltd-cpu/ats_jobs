<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('author');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $blogs = $query->paginate(30);
        
        // Summaries
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $draftBlogs = Blog::where('status', 'draft')->count();
        $featuredBlogs = Blog::where('is_featured', true)->count();
        
        return view('blogs.index', compact('blogs', 'totalBlogs', 'publishedBlogs', 'draftBlogs', 'featuredBlogs'));
    }
    
    public function create()
    {
        return view('blogs.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);
        
        $validated['author_id'] = auth()->id() ?? 1;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        
        if ($validated['status'] == 'published') {
            $validated['published_at'] = now();
        }
        
        Blog::create($validated);
        
        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }
    
    public function show(Blog $blog)
    {
        $blog->load('author');
        $blog->increment('views');
        return view('blogs.show', compact('blog'));
    }
    
    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }
    
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        
        if ($validated['status'] == 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }
        
        $blog->update($validated);
        
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }
    
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
