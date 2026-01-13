<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\LabourLawController;
use App\Http\Controllers\ScrapedJobController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\MembershipController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Job Postings
Route::resource('job-postings', JobPostingController::class);
Route::get('job-postings/export/excel', [JobPostingController::class, 'exportExcel'])->name('job-postings.export-excel');
Route::get('job-postings/export/pdf', [JobPostingController::class, 'exportPdf'])->name('job-postings.export-pdf');
Route::post('job-postings/import/excel', [JobPostingController::class, 'importExcel'])->name('job-postings.import-excel');

// Applicants
Route::resource('applicants', ApplicantController::class);

// Interviews
Route::resource('interviews', InterviewController::class);

// Resumes
Route::resource('resumes', ResumeController::class);

// Labour Laws
Route::resource('labour-laws', LabourLawController::class);

// Scraped Jobs
Route::resource('scraped-jobs', ScrapedJobController::class);

// Blogs
Route::resource('blogs', BlogController::class);

// Forums
Route::resource('forums', ForumController::class);

// Memberships
Route::resource('memberships', MembershipController::class);
Route::get('memberships/referrals/list', [MembershipController::class, 'referrals'])->name('memberships.referrals');
