<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Membership::with('user', 'referredBy');
        
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('membership_type')) {
            $query->where('membership_type', $request->membership_type);
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $memberships = $query->paginate(30);
        
        // Summaries
        $totalMemberships = Membership::count();
        $activeMemberships = Membership::where('status', 'active')->count();
        $expiredMemberships = Membership::where('status', 'expired')->count();
        $premiumMemberships = Membership::where('membership_type', 'premium')->where('status', 'active')->count();
        
        return view('memberships.index', compact('memberships', 'totalMemberships', 'activeMemberships', 'expiredMemberships', 'premiumMemberships'));
    }
    
    public function create()
    {
        $users = User::all();
        return view('memberships.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'membership_type' => 'required|in:basic,premium,enterprise',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:active,expired,cancelled',
            'referred_by' => 'nullable|exists:users,id',
        ]);
        
        // Generate referral code
        $validated['referral_code'] = strtoupper(substr(md5(uniqid()), 0, 8));
        
        Membership::create($validated);
        
        return redirect()->route('memberships.index')->with('success', 'Membership created successfully.');
    }
    
    public function show(Membership $membership)
    {
        $membership->load('user', 'referredBy');
        return view('memberships.show', compact('membership'));
    }
    
    public function edit(Membership $membership)
    {
        $users = User::all();
        return view('memberships.edit', compact('membership', 'users'));
    }
    
    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'membership_type' => 'required|in:basic,premium,enterprise',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:active,expired,cancelled',
            'referred_by' => 'nullable|exists:users,id',
        ]);
        
        $membership->update($validated);
        
        return redirect()->route('memberships.index')->with('success', 'Membership updated successfully.');
    }
    
    public function destroy(Membership $membership)
    {
        $membership->delete();
        return redirect()->route('memberships.index')->with('success', 'Membership deleted successfully.');
    }
    
    public function referrals(Request $request)
    {
        $query = Membership::with('user', 'referredBy')
            ->whereNotNull('referred_by');
        
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $referrals = $query->paginate(30);
        
        // Summaries
        $totalReferrals = Membership::whereNotNull('referred_by')->count();
        $totalReferralCount = Membership::sum('referral_count');
        
        return view('memberships.referrals', compact('referrals', 'totalReferrals', 'totalReferralCount'));
    }
}
