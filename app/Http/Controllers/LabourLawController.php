<?php

namespace App\Http\Controllers;

use App\Models\LabourLaw;
use Illuminate\Http\Request;

class LabourLawController extends Controller
{
    public function index(Request $request)
    {
        $query = LabourLaw::query();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $labourLaws = $query->paginate(30);
        
        // Summaries
        $totalLaws = LabourLaw::count();
        $laws = LabourLaw::where('type', 'law')->count();
        $articles = LabourLaw::where('type', 'article')->count();
        $qa = LabourLaw::where('type', 'qa')->count();
        
        return view('labour-laws.index', compact('labourLaws', 'totalLaws', 'laws', 'articles', 'qa'));
    }
    
    public function create()
    {
        return view('labour-laws.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:law,article,book,qa',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'country' => 'nullable|string',
            'category' => 'nullable|string',
            'author' => 'nullable|string',
            'source' => 'nullable|string',
            'published_date' => 'nullable|date',
            'isbn' => 'nullable|string',
            'question' => 'nullable|string',
            'answer' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);
        
        $validated['created_by'] = auth()->id() ?? 1;
        $validated['is_featured'] = $request->has('is_featured');
        
        LabourLaw::create($validated);
        
        return redirect()->route('labour-laws.index')->with('success', 'Labour law resource created successfully.');
    }
    
    public function show(LabourLaw $labourLaw)
    {
        $labourLaw->load('creator');
        return view('labour-laws.show', compact('labourLaw'));
    }
    
    public function edit(LabourLaw $labourLaw)
    {
        return view('labour-laws.edit', compact('labourLaw'));
    }
    
    public function update(Request $request, LabourLaw $labourLaw)
    {
        $validated = $request->validate([
            'type' => 'required|in:law,article,book,qa',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'country' => 'nullable|string',
            'category' => 'nullable|string',
            'author' => 'nullable|string',
            'source' => 'nullable|string',
            'published_date' => 'nullable|date',
            'isbn' => 'nullable|string',
            'question' => 'nullable|string',
            'answer' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);
        
        $validated['is_featured'] = $request->has('is_featured');
        
        $labourLaw->update($validated);
        
        return redirect()->route('labour-laws.index')->with('success', 'Labour law resource updated successfully.');
    }
    
    public function destroy(LabourLaw $labourLaw)
    {
        $labourLaw->delete();
        return redirect()->route('labour-laws.index')->with('success', 'Labour law resource deleted successfully.');
    }
}
