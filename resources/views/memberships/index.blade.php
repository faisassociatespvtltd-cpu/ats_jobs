@extends('layouts.app')

@section('title', 'Memberships')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Memberships</h1>
        <div class="form-actions">
            <a href="{{ route('memberships.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Memberships</div>
            <div class="summary-card-value">{{ $totalMemberships }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Active</div>
            <div class="summary-card-value">{{ $activeMemberships }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Expired</div>
            <div class="summary-card-value">{{ $expiredMemberships }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Premium</div>
            <div class="summary-card-value">{{ $premiumMemberships }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('memberships.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by user name or email...">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Membership Type</label>
                    <select name="membership_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="basic" {{ request('membership_type') == 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="premium" {{ request('membership_type') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="enterprise" {{ request('membership_type') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="start_date" {{ request('sort_by') == 'start_date' ? 'selected' : '' }}>Start Date</option>
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
                <a href="{{ route('memberships.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Membership Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Referral Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($memberships as $membership)
                <tr>
                    <td>
                        @if($membership->user)
                            {{ $membership->user->name }}<br>
                            <small style="color: #605e5c;">{{ $membership->user->email }}</small>
                        @endif
                    </td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $membership->membership_type == 'premium' ? '#0078D4' : ($membership->membership_type == 'enterprise' ? '#107c10' : '#605e5c') }}; color: white;">
                            {{ ucfirst($membership->membership_type) }}
                        </span>
                    </td>
                    <td>{{ $membership->start_date->format('Y-m-d') }}</td>
                    <td>{{ $membership->end_date ? $membership->end_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $membership->status == 'active' ? '#107c10' : ($membership->status == 'expired' ? '#d13438' : '#605e5c') }}; color: white;">
                            {{ ucfirst($membership->status) }}
                        </span>
                    </td>
                    <td><code>{{ $membership->referral_code ?? 'N/A' }}</code></td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('memberships.show', $membership) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('memberships.edit', $membership) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('memberships.destroy', $membership) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No memberships found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $memberships->links() }}
    </div>
</div>
@endsection

