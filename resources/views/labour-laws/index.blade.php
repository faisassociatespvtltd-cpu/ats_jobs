@extends('layouts.app')

@section('title', 'Labour Laws')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Labour Laws Research Hub</h1>
        <div class="form-actions">
            <a href="{{ route('labour-laws.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Resources</div>
            <div class="summary-card-value">{{ $totalLaws }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Laws</div>
            <div class="summary-card-value">{{ $laws }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Articles & Books</div>
            <div class="summary-card-value">{{ $articles }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Q&A</div>
            <div class="summary-card-value">{{ $qa }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('labour-laws.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search title or content...">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="law" {{ request('type') == 'law' ? 'selected' : '' }}>Laws</option>
                        <option value="article" {{ request('type') == 'article' ? 'selected' : '' }}>Articles</option>
                        <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>Books</option>
                        <option value="qa" {{ request('type') == 'qa' ? 'selected' : '' }}>Q&A</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" class="form-control" value="{{ request('country') }}" placeholder="Country...">
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
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
                <a href="{{ route('labour-laws.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
    
    {{-- Table Section --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Country</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labourLaws as $law)
                <tr>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #605e5c; color: white;">
                            {{ ucfirst($law->type) }}
                        </span>
                    </td>
                    <td>{{ $law->title }}</td>
                    <td>{{ $law->country ?? 'N/A' }}</td>
                    <td>{{ $law->category ?? 'N/A' }}</td>
                    <td>{{ $law->author ?? 'N/A' }}</td>
                    <td>{{ $law->views }}</td>
                    <td>{{ $law->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('labour-laws.show', $law) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('labour-laws.edit', $law) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('labour-laws.destroy', $law) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px;">No labour law resources found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $labourLaws->links() }}
    </div>
</div>
@endsection

