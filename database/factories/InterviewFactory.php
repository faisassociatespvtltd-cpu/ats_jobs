<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interview>
 */
class InterviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'applicant_id' => \App\Models\Applicant::factory(),
            'job_posting_id' => \App\Models\JobPosting::factory(),
            'interviewer_id' => \App\Models\User::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 month'),
            'location' => fake()->address(),
            'interview_type' => fake()->randomElement(['In-person', 'Online', 'Phone']),
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'feedback' => fake()->paragraph(),
        ];
    }
}
