@extends('layouts.app')

@section('title', 'Edit Membership')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Edit Membership</h1>
        <div class="form-actions">
            <a href="{{ route('memberships.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <form method="POST" action="{{ route('memberships.update', $membership) }}" style="max-width: 800px;">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 16px;">
            <label>User *</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $membership->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Membership Type *</label>
            <select name="membership_type" class="form-control" required>
                <option value="basic" {{ old('membership_type', $membership->membership_type) == 'basic' ? 'selected' : '' }}>Basic</option>
                <option value="premium" {{ old('membership_type', $membership->membership_type) == 'premium' ? 'selected' : '' }}>Premium</option>
                <option value="enterprise" {{ old('membership_type', $membership->membership_type) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
            </select>
            @error('membership_type') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Start Date *</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $membership->start_date->format('Y-m-d')) }}" required>
            @error('start_date') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $membership->end_date ? $membership->end_date->format('Y-m-d') : '') }}">
            @error('end_date') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ old('status', $membership->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ old('status', $membership->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="cancelled" {{ old('status', $membership->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label>Referred By</label>
            <select name="referred_by" class="form-control">
                <option value="">None</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('referred_by', $membership->referred_by) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('referred_by') <div style="color: #d13438; font-size: 12px; margin-top: 4px;">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom: 16px;">
            <button type="submit" class="btn btn-primary">Update Membership</button>
            <a href="{{ route('memberships.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
