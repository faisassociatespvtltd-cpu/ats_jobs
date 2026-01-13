@extends('layouts.app')

@section('title', 'Referral Invites')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Referral Invites</h1>
        <div class="form-actions">
            <a href="{{ route('memberships.index') }}" class="btn btn-secondary">Back to Memberships</a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Referrals</div>
            <div class="summary-card-value">{{ $totalReferrals }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Total Referral Count</div>
            <div class="summary-card-value">{{ $totalReferralCount }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('memberships.referrals') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by user name or email...">
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="referral_count" {{ request('sort_by') == 'referral_count' ? 'selected' : '' }}>Referral Count</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Order</label>
                    <select name="sort_order" class="form-control">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('memberships.referrals') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Referral Code</th>
                    <th>Referred By</th>
                    <th>Referral Count</th>
                    <th>Membership Type</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $membership)
                <tr>
                    <td>
                        @if($membership->user)
                            {{ $membership->user->name }}<br>
                            <small style="color: #605e5c;">{{ $membership->user->email }}</small>
                        @endif
                    </td>
                    <td><code>{{ $membership->referral_code }}</code></td>
                    <td>
                        @if($membership->referredBy)
                            {{ $membership->referredBy->name }}
                        @else
                            <span style="color: #605e5c;">N/A</span>
                        @endif
                    </td>
                    <td>{{ $membership->referral_count }}</td>
                    <td>{{ ucfirst($membership->membership_type) }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $membership->status == 'active' ? '#107c10' : ($membership->status == 'expired' ? '#d13438' : '#605e5c') }}; color: white;">
                            {{ ucfirst($membership->status) }}
                        </span>
                    </td>
                    <td>{{ $membership->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('memberships.show', $membership) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('memberships.edit', $membership) }}" class="btn btn-secondary btn-sm">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px;">No referrals found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $referrals->links() }}
    </div>
</div>
@endsection

