@extends('layouts.app')

@section('title', 'Membership Details')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Membership Details</h1>
        <div class="form-actions">
            <a href="{{ route('memberships.edit', $membership) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('memberships.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 800px;">
        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">User</label>
            <div style="font-size: 16px;">{{ $membership->user->name ?? 'N/A' }}</div>
            <div style="color: #605e5c;">{{ $membership->user->email ?? 'N/A' }}</div>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Membership Type</label>
            <div style="font-size: 16px;">{{ ucfirst($membership->membership_type) }}</div>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Status</label>
            <span style="padding: 4px 12px; border-radius: 2px; font-size: 14px; background-color: {{ $membership->status == 'active' ? '#107c10' : ($membership->status == 'expired' ? '#d13438' : '#605e5c') }}; color: white;">
                {{ ucfirst($membership->status) }}
            </span>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Referral Code</label>
            <code style="font-size: 16px; background: #f3f2f1; padding: 4px 8px; border-radius: 2px;">{{ $membership->referral_code ?? 'N/A' }}</code>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Start Date</label>
            <div style="font-size: 16px;">{{ $membership->start_date->format('Y-m-d') }}</div>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">End Date</label>
            <div style="font-size: 16px;">{{ $membership->end_date ? $membership->end_date->format('Y-m-d') : 'Lifetime / Continuous' }}</div>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Referred By</label>
            <div style="font-size: 16px;">{{ $membership->referredBy->name ?? 'None' }}</div>
        </div>

        <div class="details-group">
            <label style="font-weight: 600; color: #605e5c; display: block; margin-bottom: 4px;">Referral Count</label>
            <div style="font-size: 16px;">{{ $membership->referral_count }}</div>
        </div>
    </div>

    <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #edebe9;">
        <form action="{{ route('memberships.destroy', $membership) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this membership?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Membership</button>
        </form>
    </div>
</div>
@endsection
