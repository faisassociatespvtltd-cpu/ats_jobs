<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobPosting>
 */
class JobPostingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'company_name' => fake()->company(),
            'location' => fake()->city() . ', ' . fake()->country(),
            'required_skills' => implode(', ', fake()->words(5)),
            'job_type' => fake()->randomElement(['Full-time', 'Part-time', 'Contract', 'Remote']),
            'salary_min' => fake()->numberBetween(30000, 50000),
            'salary_max' => fake()->numberBetween(60000, 150000),
            'experience_level' => fake()->randomElement(['Junior', 'Mid', 'Senior', 'Lead']),
            'education_required' => fake()->randomElement(['Bachelors', 'Masters', 'PhD', 'High School']),
            'experience_required' => fake()->numberBetween(1, 10) . ' years',
            'posted_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'closing_date' => fake()->dateTimeBetween('now', '+2 months'),
            'status' => fake()->randomElement(['active', 'draft', 'closed']),
            'requirements' => fake()->sentences(5),
            'benefits' => fake()->sentences(3),
            'responsibilities' => fake()->paragraphs(2, true),
            'qualifications' => fake()->paragraphs(2, true),
            'hard_skills' => fake()->words(5),
            'soft_skills' => fake()->words(5),
        ];
    }
}
