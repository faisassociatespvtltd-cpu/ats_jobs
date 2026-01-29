@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="form-header">
        <h1 class="form-title">User Management</h1>
        <div class="form-actions">
            <span class="badge bg-info">{{ $users->total() }} Total Users</span>
        </div>
    </div>

    <div class="form-container">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->email }}</strong><br>
                                <small class="text-muted">{{ $user->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($user->user_type === 'employee')
                                    <span class="badge bg-success">Employee</span>
                                @elseif($user->user_type === 'employer')
                                    <span class="badge bg-primary">Employer</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($user->user_type) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_blocked)
                                    <span class="badge bg-danger">Blocked</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    @if($user->is_blocked)
                                        <form method="POST" action="{{ route('superadmin.users.activate', $user->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('superadmin.users.block', $user->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Block</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $users->links() }}
        </div>
    </div>
@endsection