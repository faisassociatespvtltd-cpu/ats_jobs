# ATS Job Site - Setup Guide

## Overview
This is a comprehensive ATS (Applicant Tracking System) Job Site built with Laravel and MySQL, featuring MS Dynamics ERP-style UI/UX.

## Features Implemented

### ✅ Completed Modules
1. **Database Structure** - All migrations created
2. **Models** - All models with relationships
3. **Base Layout** - MS Dynamics style UI with horizontal navigation
4. **Job Postings Module** - Full CRUD with filters, sorting, pagination
5. **Dashboard** - Summary statistics
6. **Routes** - All routes configured

### 📋 Modules Structure
- **ATS Recruitment**: Job Postings, Applicants, Interviews, Resume Parsing
- **Labour Laws Research Hub**: Country Laws, Articles & Books, Legal Q&A
- **Job Scraping & Portal**: WhatsApp, LinkedIn, Facebook, Other Job Sites
- **Blog & Community**: Member Blogs, Discussion Forums
- **Membership & Invites**: Membership Management, Referral System

## Installation Steps

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Install Additional Packages
The following packages are listed in composer.json but need to be installed:
```bash
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
```

After installation, publish the config:
```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### 3. Database Setup
Create a MySQL database, then update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ats_database
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Generate Application Key (if needed)
```bash
php artisan key:generate
```

### 6. Create Storage Link
```bash
php artisan storage:link
```

### 7. Start Development Server
```bash
php artisan serve
```

Visit: http://localhost:8000

## UI/UX Features

All forms follow MS Dynamics ERP design principles:

1. **Form Title**: Top-left corner
2. **Action Buttons**: Top-right (Add New, Export Excel, Export PDF, Import Excel)
3. **Summary Cards**: Below title showing key metrics
4. **Filters & Sorting**: Below summaries for data filtering
5. **Data Table**: Shows 30+ rows with View/Edit/Delete actions
6. **Pagination**: Bottom-right in standard format
7. **Navigation**: Horizontal menu bar with module-wise organization and sub-menus

## Module Implementation Status

### Fully Implemented
- ✅ Job Postings (Controller, Views, Routes)
- ✅ Dashboard

### Partially Implemented (Controllers created, views need to be created)
- ⚠️ Applicants
- ⚠️ Interviews
- ⚠️ Labour Laws
- ⚠️ Scraped Jobs
- ⚠️ Blogs
- ⚠️ Forums
- ⚠️ Memberships

## Next Steps to Complete Implementation

1. **Complete Remaining Controllers**: 
   - Implement index, create, store, show, edit, update, destroy methods
   - Follow the pattern used in JobPostingController

2. **Create Views for Each Module**:
   - Copy the structure from `resources/views/job-postings/index.blade.php`
   - Adapt for each module's specific fields
   - Create create.blade.php, edit.blade.php, show.blade.php views

3. **Implement Excel Import/Export**:
   - Use maatwebsite/excel package
   - Create Export classes for each model
   - Create Import classes for each model
   - Update controllers to use these classes

4. **Implement PDF Export**:
   - Use barryvdh/laravel-dompdf
   - Create PDF views for each module
   - Update controllers to generate PDFs

5. **Add Authentication** (Optional):
   ```bash
   php artisan make:auth
   ```
   Or use Laravel Breeze/Jetstream

6. **Add File Upload for Resumes**:
   - Configure storage
   - Add file upload handling in ResumeController

7. **Implement Resume Parsing**:
   - Integrate a resume parsing service/API
   - Store parsed data in JSON format

## Database Tables

- `job_postings` - Job listings
- `applicants` - Job applicants
- `interviews` - Interview scheduling
- `resumes` - Resume files and parsed data
- `labour_laws` - Labour law resources (laws, articles, books, Q&A)
- `scraped_jobs` - Scraped job listings
- `blogs` - Member blog posts
- `forums` - Discussion forum posts
- `memberships` - User memberships
- `social_media_shares` - Social media sharing records

## Color Scheme (MS Dynamics Style)

- Primary: #0078D4 (Blue)
- Secondary: #605e5c (Gray)
- Success: #107c10 (Green)
- Error: #d13438 (Red)
- Background: #faf9f8 (Light Gray)
- Border: #edebe9 (Light Border)

## Notes

- The application uses Laravel's default pagination (30 items per page)
- All forms follow the same structure for consistency
- The navigation menu supports dropdown sub-menus
- Excel/PDF export functionality needs to be fully implemented using the installed packages
- Authentication is not implemented but can be added using Laravel's built-in auth

## Support

For questions or issues, refer to Laravel documentation:
- https://laravel.com/docs
- https://docs.laravel-excel.com/
- https://github.com/barryvdh/laravel-dompdf

