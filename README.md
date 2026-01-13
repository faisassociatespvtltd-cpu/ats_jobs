# ATS Job Site Software

A comprehensive Applicant Tracking System (ATS) built with Laravel and MySQL, featuring MS Dynamics ERP-style UI/UX.

## Quick Start

1. **Install Dependencies**
   ```bash
   composer install
   composer require maatwebsite/excel barryvdh/laravel-dompdf
   ```

2. **Setup Database**
   - Create MySQL database
   - Update `.env` file with database credentials
   - Run migrations: `php artisan migrate`

3. **Start Server**
   ```bash
   php artisan serve
   ```

4. **Access Application**
   - Navigate to: http://localhost:8000
   - Dashboard: http://localhost:8000/dashboard

## Features

✅ **Complete Database Structure** - All tables and relationships
✅ **MS Dynamics Style UI** - Professional, clean interface
✅ **Job Postings Module** - Fully functional with CRUD, filters, sorting, pagination
✅ **Horizontal Navigation** - Module-wise organization with sub-menus
✅ **Standard Form Layout** - Title, action buttons, summaries, filters, table, pagination
✅ **Responsive Design** - Works on all screen sizes

## Module Structure

- **ATS Recruitment**: Job Postings ✅, Applicants ⚠️, Interviews ⚠️, Resume Parsing ⚠️
- **Labour Laws**: Country Laws ⚠️, Articles & Books ⚠️, Legal Q&A ⚠️
- **Job Scraping**: WhatsApp, LinkedIn, Facebook, Other Job Sites ⚠️
- **Blog & Community**: Member Blogs ⚠️, Discussion Forums ⚠️
- **Membership**: Memberships ⚠️, Referral Invites ⚠️

Legend: ✅ Fully Implemented | ⚠️ Controller Created, Views Needed

## UI/UX Features

All forms follow MS Dynamics ERP design standards:
- Form title on top-left
- Action buttons on top-right (Add, Export Excel, Export PDF, Import Excel)
- Summary cards showing key metrics
- Filter and sorting section
- Data table with 30+ rows per page
- View/Edit/Delete buttons in each row
- Pagination on bottom-right

## Documentation

See `SETUP_GUIDE.md` for detailed setup instructions and implementation guide.

## Next Steps

1. Complete remaining views (use Job Postings as template)
2. Implement Excel import/export using maatwebsite/excel
3. Implement PDF export using barryvdh/laravel-dompdf
4. Add authentication (optional)
5. Implement file upload for resumes
6. Add resume parsing functionality

## Technology Stack

- Laravel 12
- MySQL
- PHP 8.2+
- MS Dynamics-style CSS
- maatwebsite/excel (for Excel operations)
- barryvdh/laravel-dompdf (for PDF generation)
