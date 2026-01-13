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
        // Create Users
        $users = User::factory(10)->create();
        $admin = User::firstOrCreate(
            ['email' => 'admin@ats.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create Job Postings
        $jobPostings = JobPosting::factory(50)->create([
            'posted_by' => fn() => $users->random()->id,
            'status' => fn() => collect(['draft', 'active', 'active', 'active', 'closed'])->random(),
        ]);

        // Create Applicants
        $applicants = collect();
        foreach ($jobPostings->take(30) as $job) {
            $appCount = rand(2, 8);
            for ($i = 0; $i < $appCount; $i++) {
                $applicants->push(Applicant::factory()->create([
                    'job_posting_id' => $job->id,
                    'status' => collect(['pending', 'reviewed', 'shortlisted', 'interviewed', 'offered', 'hired', 'rejected'])->random(),
                ]));
            }
        }

        // Create Interviews
        foreach ($applicants->where('status', '!=', 'rejected')->take(20) as $applicant) {
            Interview::factory()->create([
                'applicant_id' => $applicant->id,
                'job_posting_id' => $applicant->job_posting_id,
                'interviewer_id' => $users->random()->id,
                'status' => collect(['scheduled', 'completed', 'completed'])->random(),
            ]);
        }

        // Create Resumes
        foreach ($applicants->take(25) as $applicant) {
            Resume::factory()->create([
                'applicant_id' => $applicant->id,
                'parsing_status' => collect(['pending', 'completed', 'completed', 'completed'])->random(),
            ]);
        }

        // Create Labour Laws
        foreach (['law', 'article', 'book', 'qa'] as $type) {
            for ($i = 0; $i < 15; $i++) {
                LabourLaw::factory()->create([
                    'type' => $type,
                    'created_by' => $users->random()->id,
                    'country' => collect(['USA', 'UK', 'Canada', 'Australia', 'Germany'])->random(),
                ]);
            }
        }

        // Create Scraped Jobs
        foreach (['whatsapp', 'linkedin', 'facebook', 'other'] as $source) {
            for ($i = 0; $i < 20; $i++) {
                ScrapedJob::factory()->create([
                    'source' => $source,
                    'status' => collect(['pending', 'reviewed', 'imported', 'rejected'])->random(),
                ]);
            }
        }

        // Create Blogs
        Blog::factory(25)->create([
            'author_id' => fn() => $users->random()->id,
            'status' => fn() => collect(['draft', 'published', 'published'])->random(),
        ]);

        // Create Forums
        $forums = Forum::factory(30)->create([
            'user_id' => fn() => $users->random()->id,
            'parent_id' => null,
            'category' => fn() => collect(['General', 'Jobs', 'Legal', 'Community'])->random(),
        ]);

        // Create Forum Replies
        foreach ($forums->take(20) as $forum) {
            Forum::factory(rand(2, 5))->create([
                'user_id' => fn() => $users->random()->id,
                'parent_id' => $forum->id,
            ]);
        }

        // Create Memberships
        foreach ($users as $user) {
            Membership::factory()->create([
                'user_id' => $user->id,
                'membership_type' => collect(['basic', 'premium', 'enterprise'])->random(),
                'status' => collect(['active', 'active', 'expired'])->random(),
            ]);
        }
    }
}

