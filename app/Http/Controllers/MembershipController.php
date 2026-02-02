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
        // For Super Admin: Full list
        if (auth()->user()->isSuperAdmin()) {
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
            
            $totalReferrals = Membership::whereNotNull('referred_by')->count();
            $totalReferralCount = Membership::sum('referral_count');
            
            return view('memberships.referrals', compact('referrals', 'totalReferrals', 'totalReferralCount'));
        }

        // For Regular Users: Their own referrals
        $user = auth()->user();
        $membership = $user->membership;

        if (!$membership) {
            // Create a basic membership if not exists
            $membership = Membership::create([
                'user_id' => $user->id,
                'membership_type' => 'basic',
                'start_date' => now(),
                'status' => 'active',
                'referral_code' => strtoupper(substr(md5(uniqid()), 0, 8)),
            ]);
        }

        $referrals = Membership::with('user')
            ->where('referred_by', $user->id)
            ->latest()
            ->paginate(10);

        return view('memberships.my-referrals', compact('membership', 'referrals'));
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = auth()->user();
        
        // Ensure membership exists
        if (!$user->membership) {
             return back()->with('error', 'You need an active membership to refer friends.');
        }

        $referralCode = $user->membership->referral_code;
        $link = url('/welcome?ref=' . $referralCode);

        try {
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\InviteFriendMail($user->name, $link));
            return back()->with('success', 'Invitation sent successfully to ' . $request->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send invitation: ' . $e->getMessage());
        }
    }
}
