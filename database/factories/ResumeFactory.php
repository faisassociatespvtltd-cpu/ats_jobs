<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resume>
 */
class ResumeFactory extends Factory
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
            'parsing_status' => fake()->randomElement(['pending', 'completed']),
            'parsed_content' => fake()->paragraphs(3, true),
            'file_path' => 'resumes/' . fake()->uuid() . '.pdf',
            'file_name' => fake()->word() . '.pdf',
            'file_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(100, 5000),
        ];
    }
}
