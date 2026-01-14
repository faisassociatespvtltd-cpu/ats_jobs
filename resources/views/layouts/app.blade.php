<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ATS Job Site') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #323130;
            background-color: #faf9f8;
            line-height: 1.5;
        }
        
        /* MS Dynamics Color Scheme */
        :root {
            --primary-color: #0078D4;
            --primary-dark: #106ebe;
            --secondary-color: #605e5c;
            --success-color: #107c10;
            --warning-color: #ffaa44;
            --error-color: #d13438;
            --border-color: #edebe9;
            --bg-color: #ffffff;
            --bg-light: #faf9f8;
            --text-primary: #323130;
            --text-secondary: #605e5c;
            --hover-bg: #f3f2f1;
        }
        
        /* Top Navigation Bar */
        .navbar {
            background-color: var(--primary-color);
            color: white;
            height: 48px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-size: 18px;
            font-weight: 600;
            margin-right: 32px;
            color: white;
            text-decoration: none;
        }
        
        .navbar-menu {
            display: flex;
            list-style: none;
            flex: 1;
            align-items: center;
        }
        
        .navbar-menu > li {
            position: relative;
        }
        
        .navbar-menu > li > a {
            color: white;
            text-decoration: none;
            padding: 12px 16px;
            display: block;
            transition: background-color 0.2s;
            border-radius: 2px;
        }
        
        .navbar-menu > li > a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .navbar-menu > li:hover .submenu {
            display: block;
        }
        
        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            border: 1px solid var(--border-color);
            list-style: none;
            z-index: 1001;
        }
        
        .submenu li a {
            color: var(--text-primary);
            padding: 12px 16px;
            display: block;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        
        .submenu li a:hover {
            background-color: var(--hover-bg);
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Main Container */
        .main-container {
            padding: 24px;
            max-width: 100%;
        }
        
        /* Form Container */
        .form-container {
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 2px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .form-actions {
            display: flex;
            gap: 8px;
        }
        
        /* Buttons */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4a4846;
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--error-color);
            color: white;
        }
        
        .btn-sm {
            padding: 4px 12px;
            font-size: 12px;
        }
        
        /* Summary Cards */
        .summary-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .summary-card {
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 2px;
            padding: 16px;
        }
        
        .summary-card-title {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }
        
        .summary-card-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        /* Filters Section */
        .filters-section {
            background-color: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 2px;
            padding: 16px;
            margin-bottom: 24px;
        }
        
        .filters-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .form-control {
            width: 100%;
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 2px;
            font-size: 14px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 1px var(--primary-color);
        }
        
        /* Table */
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--bg-color);
        }
        
        thead {
            background-color: var(--bg-light);
        }
        
        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        
        tbody tr:hover {
            background-color: var(--hover-bg);
        }
        
        .action-buttons {
            display: flex;
            gap: 4px;
        }
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }
        
        .pagination {
            display: flex;
            gap: 4px;
            list-style: none;
        }
        
        .pagination li a,
        .pagination li span {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 2px;
            text-decoration: none;
            color: var(--text-primary);
            display: block;
        }
        
        .pagination li.active span {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination li a:hover {
            background-color: var(--hover-bg);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-brand">ATS Job Site</a>
        <ul class="navbar-menu">
            <li>
                <a href="#">ATS Recruitment</a>
                <ul class="submenu">
                    <li><a href="{{ route('job-postings.index') }}">Job Postings</a></li>
                    <li><a href="{{ route('applicants.index') }}">Applicants</a></li>
                    <li><a href="{{ route('interviews.index') }}">Interviews</a></li>
                    <li><a href="{{ route('resumes.index') }}">Resume Parsing</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Labour Laws</a>
                <ul class="submenu">
                    <li><a href="{{ route('labour-laws.index') }}?type=law">Country Laws</a></li>
                    <li><a href="{{ route('labour-laws.index') }}?type=article">Articles & Books</a></li>
                    <li><a href="{{ route('labour-laws.index') }}?type=qa">Legal Q&A</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Job Scraping</a>
                <ul class="submenu">
                    <li><a href="{{ route('scraped-jobs.index') }}?source=whatsapp">WhatsApp Jobs</a></li>
                    <li><a href="{{ route('scraped-jobs.index') }}?source=linkedin">LinkedIn Jobs</a></li>
                    <li><a href="{{ route('scraped-jobs.index') }}?source=facebook">Facebook Jobs</a></li>
                    <li><a href="{{ route('scraped-jobs.index') }}?source=other">Other Job Sites</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Blog & Community</a>
                <ul class="submenu">
                    <li><a href="{{ route('blogs.index') }}">Member Blogs</a></li>
                    <li><a href="{{ route('forums.index') }}">Discussion Forums</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Membership</a>
                <ul class="submenu">
                    <li><a href="{{ route('memberships.index') }}">Memberships</a></li>
                    <li><a href="{{ route('memberships.referrals') }}">Referral Invites</a></li>
                </ul>
            </li>
        </ul>
        <div class="navbar-actions">
            @auth
                @if(auth()->user()->isEmployee())
                    <a href="{{ route('employee.profile') }}" style="color: white; text-decoration: none; padding: 8px;">My Profile</a>
                @elseif(auth()->user()->isEmployer())
                    <a href="{{ route('employer.profile') }}" style="color: white; text-decoration: none; padding: 8px;">My Profile</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; padding: 8px;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color: white; text-decoration: none; padding: 8px;">Login</a>
                <a href="{{ route('welcome') }}" style="color: white; text-decoration: none; padding: 8px;">Sign Up</a>
            @endauth
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-container">
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>

