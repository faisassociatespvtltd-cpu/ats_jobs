<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScrapedJob;
use Illuminate\Support\Str;

class ScrapedJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'source' => 'linkedin',
                'title' => 'Laravel Developer',
                'description' => 'Build and maintain Laravel applications. Experience with MySQL and REST APIs required.',
                'company_name' => 'TechNova',
                'location' => 'Lahore, Punjab, Pakistan',
                'salary' => 'PKR 120,000 - 180,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://linkedin.com/jobs/view/laravel-developer-1',
                'status' => 'pending',
                'scraped_at' => now()->subDays(2),
            ],
            [
                'source' => 'whatsapp',
                'title' => 'Frontend Engineer',
                'description' => 'React developer needed for SPA development. 2+ years experience.',
                'company_name' => 'PixelWorks',
                'location' => 'Karachi, سندھ, Pakistan',
                'salary' => 'PKR 100,000 - 150,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://wa.me/1234567890',
                'status' => 'pending',
                'scraped_at' => now()->subDays(3),
            ],
            [
                'source' => 'facebook',
                'title' => 'Digital Marketing Specialist',
                'description' => 'Social media campaigns, SEO, and analytics. Google Ads experience preferred.',
                'company_name' => 'MarketHive',
                'location' => 'Islamabad, Pakistan',
                'salary' => 'PKR 70,000 - 110,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://facebook.com/jobs/marketing-specialist',
                'status' => 'reviewed',
                'scraped_at' => now()->subDays(4),
            ],
            [
                'source' => 'other',
                'title' => 'QA Engineer',
                'description' => 'Manual and automation testing. Selenium knowledge required.',
                'company_name' => 'QualityCore',
                'location' => 'Lahore, Punjab, Pakistan',
                'salary' => 'PKR 80,000 - 120,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://careers.qualitycore.com/qa-engineer',
                'status' => 'pending',
                'scraped_at' => now()->subDays(5),
            ],
            [
                'source' => 'linkedin',
                'title' => 'DevOps Engineer',
                'description' => 'AWS, Docker, CI/CD pipelines. Kubernetes is a plus.',
                'company_name' => 'CloudPeak',
                'location' => 'Remote',
                'salary' => 'PKR 200,000 - 300,000',
                'job_type' => 'Contract',
                'source_url' => 'https://linkedin.com/jobs/view/devops-engineer-2',
                'status' => 'pending',
                'scraped_at' => now()->subDays(1),
            ],
            [
                'source' => 'whatsapp',
                'title' => 'Content Writer',
                'description' => 'Write blog posts and website content. SEO knowledge preferred.',
                'company_name' => 'WritePro',
                'location' => 'Karachi, سندھ, Pakistan',
                'salary' => 'PKR 50,000 - 80,000',
                'job_type' => 'Part-time',
                'source_url' => 'https://wa.me/1987654321',
                'status' => 'pending',
                'scraped_at' => now()->subDays(6),
            ],
            [
                'source' => 'facebook',
                'title' => 'UI/UX Designer',
                'description' => 'Design web and mobile interfaces. Figma/Adobe XD required.',
                'company_name' => 'DesignArc',
                'location' => 'Islamabad, Pakistan',
                'salary' => 'PKR 90,000 - 140,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://facebook.com/jobs/uiux-designer',
                'status' => 'pending',
                'scraped_at' => now()->subDays(7),
            ],
            [
                'source' => 'other',
                'title' => 'Data Analyst',
                'description' => 'Data visualization and reporting. Power BI/SQL required.',
                'company_name' => 'DataPulse',
                'location' => 'Lahore, Punjab, Pakistan',
                'salary' => 'PKR 110,000 - 160,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://datapulse.pk/careers/data-analyst',
                'status' => 'reviewed',
                'scraped_at' => now()->subDays(8),
            ],
            [
                'source' => 'linkedin',
                'title' => 'Mobile App Developer',
                'description' => 'Flutter or React Native developer required.',
                'company_name' => 'AppForge',
                'location' => 'Peshawar, KP, Pakistan',
                'salary' => 'PKR 130,000 - 190,000',
                'job_type' => 'Full-time',
                'source_url' => 'https://linkedin.com/jobs/view/mobile-dev-3',
                'status' => 'pending',
                'scraped_at' => now()->subDays(9),
            ],
            [
                'source' => 'other',
                'title' => 'Network Engineer',
                'description' => 'Network monitoring, firewall configuration, and troubleshooting.',
                'company_name' => 'NetSecure',
                'location' => 'Quetta, Balochistan, Pakistan',
                'salary' => 'PKR 95,000 - 140,000',
                'job_type' => 'Contract',
                'source_url' => 'https://netsecure.pk/jobs/network-engineer',
                'status' => 'pending',
                'scraped_at' => now()->subDays(10),
            ],
        ];

        foreach ($jobs as $job) {
            ScrapedJob::create(array_merge($job, [
                'raw_data' => [
                    'external_id' => (string) Str::uuid(),
                    'source' => $job['source'],
                ],
                'is_imported' => false,
            ]));
        }
    }
}
