<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Resume;
use App\Models\LabourLaw;
use App\Models\ScrapedJob;
use App\Models\Blog;
use App\Models\Forum;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ATSDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users with Profiles
        $users = User::factory(20)->create()->each(function ($user) {
            if ($user->user_type === 'employee') {
                \App\Models\EmployeeProfile::factory()->create(['user_id' => $user->id]);
            } else {
                \App\Models\EmployerProfile::factory()->create(['user_id' => $user->id]);
            }
        });

        $admin = User::firstOrCreate(
            ['email' => 'admin@ats.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'employer',
            ]
        );
        \App\Models\EmployerProfile::firstOrCreate(['user_id' => $admin->id], [
            'company_name' => 'ATS Admin',
            'company_address' => 'Main Office',
        ]);

        $employers = $users->where('user_type', 'employer');
        $employees = $users->where('user_type', 'employee');

        // Create Job Postings
        $jobPostings = JobPosting::factory(30)->create([
            'posted_by' => fn() => $employers->count() > 0 ? $employers->random()->id : $admin->id,
        ]);

        // Create Applicants
        $applicants = collect();
        foreach ($jobPostings as $job) {
            $appCount = rand(3, 10);
            for ($i = 0; $i < $appCount; $i++) {
                $applicantUser = $employees->count() > 0 ? $employees->random() : null;
                $applicants->push(Applicant::factory()->create([
                    'job_posting_id' => $job->id,
                    'user_id' => $applicantUser ? $applicantUser->id : null,
                    'email' => $applicantUser ? $applicantUser->email : fake()->safeEmail(),
                ]));
            }
        }

        // Create Interviews
        foreach ($applicants->take(30) as $applicant) {
            Interview::factory()->create([
                'applicant_id' => $applicant->id,
                'job_posting_id' => $applicant->job_posting_id,
                'interviewer_id' => $employers->count() > 0 ? $employers->random()->id : $admin->id,
            ]);
        }

        // Create Resumes
        foreach ($applicants as $applicant) {
            Resume::factory()->create([
                'applicant_id' => $applicant->id,
            ]);
        }

        // Create Labour Laws
        LabourLaw::factory(20)->create([
            'created_by' => $admin->id,
        ]);

        // Create Scraped Jobs
        ScrapedJob::factory(50)->create();

        // Create Blogs
        Blog::factory(10)->create([
            'author_id' => $admin->id,
        ]);

        // Create Forums
        $parentForums = Forum::factory(15)->create([
            'user_id' => fn() => $users->random()->id,
            'parent_id' => null,
        ]);

        // Create Forum Replies
        foreach ($parentForums as $forum) {
            Forum::factory(rand(2, 5))->create([
                'user_id' => fn() => $users->random()->id,
                'parent_id' => $forum->id,
            ]);
        }

        // Create Memberships
        foreach ($users as $user) {
            Membership::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}

