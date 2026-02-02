<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPosting;
use App\Models\User;
use Carbon\Carbon;

class JobVerificationSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have an employer
        $employer = User::where('role', 'employer')->first();
        if (!$employer) {
            $employer = User::factory()->create(['role' => 'employer']);
        }

        // 1. Expired Job (Should NOT show)
        JobPosting::create([
            'title' => 'Expired Job Check',
            'description' => 'This job should not be visible.',
            'company_name' => 'Old Corp',
            'location' => 'Riyadh',
            'job_type' => 'Full-time',
            'posted_date' => Carbon::now()->subDays(10),
            'closing_date' => Carbon::now()->subDay(), // Expired yesterday
            'status' => 'active',
            'posted_by' => $employer->id,
            'experience_level' => 'Mid',
        ]);

        // 2. Fresher Job (Should show with Fresher filter)
        JobPosting::create([
            'title' => 'Junior Developer',
            'description' => 'Great role for freshers.',
            'company_name' => 'StartUp Inc',
            'location' => 'Jeddah',
            'job_type' => 'Full-time',
            'posted_date' => Carbon::now(),
            'closing_date' => Carbon::now()->addDays(10),
            'status' => 'active',
            'posted_by' => $employer->id,
            'experience_level' => 'Entry Level',
            'experience_required' => '0-1 years',
        ]);

        // 3. Experienced Job (Should NOT show with Fresher filter)
        JobPosting::create([
            'title' => 'Senior Architect',
            'description' => 'For experts only.',
            'company_name' => 'Big Tech',
            'location' => 'Riyadh',
            'job_type' => 'Full-time',
            'posted_date' => Carbon::now(),
            'closing_date' => Carbon::now()->addDays(20),
            'status' => 'active',
            'posted_by' => $employer->id,
            'experience_level' => 'Senior',
            'experience_required' => '5+ years',
        ]);
        
        $this->command->info('Verification jobs seeded!');
    }
}
