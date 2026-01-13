@extends('layouts.app')

@section('title', 'Member Blogs')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Member Blogs</h1>
        <div class="form-actions">
            <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                <span>+</span> Add New
            </a>
        </div>
    </div>
    
    {{-- Summary Section --}}
    <div class="summary-section">
        <div class="summary-card">
            <div class="summary-card-title">Total Blogs</div>
            <div class="summary-card-value">{{ $totalBlogs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Published</div>
            <div class="summary-card-value">{{ $publishedBlogs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Drafts</div>
            <div class="summary-card-value">{{ $draftBlogs }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-card-title">Featured</div>
            <div class="summary-card-value">{{ $featuredBlogs }}</div>
        </div>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <form method="GET" action="{{ route('blogs.index') }}">
            <div class="filters-row">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search title or content...">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="published_at" {{ request('sort_by') == 'published_at' ? 'selected' : '' }}>Published Date</option>
                        <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Views</option>
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
                <a href="{{ route('blogs.index') }}" class="btn btn-secondary">Clear</a>
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
                    <th>Status</th>
                    <th>Views</th>
                    <th>Likes</th>
                    <th>Published Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->author->name ?? 'N/A' }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: {{ $blog->status == 'published' ? '#107c10' : ($blog->status == 'draft' ? '#605e5c' : '#d13438') }}; color: white;">
                            {{ ucfirst($blog->status) }}
                        </span>
                        @if($blog->is_featured)
                        <span style="padding: 4px 8px; border-radius: 2px; font-size: 12px; background-color: #ffaa44; color: white; margin-left: 4px;">
                            Featured
                        </span>
                        @endif
                    </td>
                    <td>{{ $blog->views }}</td>
                    <td>{{ $blog->likes }}</td>
                    <td>{{ $blog->published_at ? $blog->published_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('blogs.show', $blog) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px;">No blogs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $blogs->links() }}
    </div>
</div>
@endsection

