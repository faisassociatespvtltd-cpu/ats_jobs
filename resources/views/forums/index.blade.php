@extends('layouts.app')

@section('title', 'Discussion Forums')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Discussion Forums</h1>
        <div class="form-actions">
            <a href="{{ route('forums.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Forums</div>
            <div class="summary-card-value">{{ $totalForums }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Total Replies</div>
            <div class="summary-card-value">{{ $totalReplies }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Pinned</div>
            <div class="summary-card-value">{{ $pinnedForums }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('forums.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search title or content...">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" value="{{ request('category') }}" placeholder="Category...">
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="last_reply_at" {{ request('sort_by') == 'last_reply_at' ? 'selected' : '' }}>Last Reply</option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="replies_count" {{ request('sort_by') == 'replies_count' ? 'selected' : '' }}>Replies</option>
                        <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Views</option>
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
                <a href="{{ route('forums.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Replies</th>
                    <th>Views</th>
                    <th>Last Reply</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forums as $forum)
                <tr>
                    <td>
                        @if($forum->is_pinned)
                        <span style="color: #ffaa44;">📌</span>
                        @endif
                        {{ $forum->title }}
                        @if($forum->is_locked)
                        <span style="color: #d13438;">🔒</span>
                        @endif
                    </td>
                    <td>{{ $forum->user->name ?? 'N/A' }}</td>
                    <td>{{ $forum->category ?? 'N/A' }}</td>
                    <td>{{ $forum->replies_count }}</td>
                    <td>{{ $forum->views }}</td>
                    <td>{{ $forum->last_reply_at ? $forum->last_reply_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        @if($forum->is_pinned)
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #ffaa44; color: white;">Pinned</span>
                        @elseif($forum->is_locked)
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #d13438; color: white;">Locked</span>
                        @else
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #107c10; color: white;">Active</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('forums.show', $forum) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('forums.edit', $forum) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('forums.destroy', $forum) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px;">No forum posts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $forums->links() }}
    </div>
</div>
@endsection

