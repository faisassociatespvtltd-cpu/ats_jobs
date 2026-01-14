<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Welcome and Authentication Routes
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('welcome');
});
Route::get('/welcome', [AuthController::class, 'welcome'])->name('welcome');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');

// Employee Signup Routes
Route::get('/employee/signup', [AuthController::class, 'showEmployeeSignup'])->name('employee.signup');
Route::post('/employee/signup/step1', [AuthController::class, 'employeeSignupStep1'])->name('employee.signup.step1');
Route::get('/employee/signup/step2', [AuthController::class, 'showEmployeeSignupStep2'])->name('employee.signup.step2');
Route::post('/employee/signup/step2', [AuthController::class, 'employeeSignupStep2'])->name('employee.signup.step2');
Route::get('/employee/signup/step3', [AuthController::class, 'showEmployeeSignupStep3'])->name('employee.signup.step3');
Route::post('/employee/signup/step3', [AuthController::class, 'employeeSignupStep3'])->name('employee.signup.step3');
Route::get('/employee/profile/complete', [AuthController::class, 'showEmployeeProfileComplete'])->name('employee.profile.complete');

// Employer Signup Routes
Route::get('/employer/signup', [AuthController::class, 'showEmployerSignup'])->name('employer.signup');
Route::post('/employer/signup/step1', [AuthController::class, 'employerSignupStep1'])->name('employer.signup.step1');
Route::get('/employer/signup/step2', [AuthController::class, 'showEmployerSignupStep2'])->name('employer.signup.step2');
Route::post('/employer/signup/step2', [AuthController::class, 'employerSignupStep2'])->name('employer.signup.step2');
Route::get('/employer/signup/step3', [AuthController::class, 'showEmployerSignupStep3'])->name('employer.signup.step3');
Route::post('/employer/signup/step3', [AuthController::class, 'employerSignupStep3'])->name('employer.signup.step3');
Route::get('/employer/profile/complete', [AuthController::class, 'showEmployerProfileComplete'])->name('employer.profile.complete');

// Profile Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/employee/profile', [ProfileController::class, 'showEmployeeProfile'])->name('employee.profile');
    Route::get('/employee/profile/edit', [ProfileController::class, 'editEmployeeProfile'])->name('employee.profile.edit');
    Route::put('/employee/profile', [ProfileController::class, 'updateEmployeeProfile'])->name('employee.profile.update');
    
    Route::get('/employer/profile', [ProfileController::class, 'showEmployerProfile'])->name('employer.profile');
    Route::get('/employer/profile/edit', [ProfileController::class, 'editEmployerProfile'])->name('employer.profile.edit');
    Route::put('/employer/profile', [ProfileController::class, 'updateEmployerProfile'])->name('employer.profile.update');
});

// Dashboard and other routes (Protected)
Route::middleware('auth')->group(function () {
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
});
