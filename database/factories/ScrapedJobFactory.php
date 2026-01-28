<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScrapedJob>
 */
class ScrapedJobFactory extends Factory
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
            'description' => fake()->paragraph(),
            'company_name' => fake()->company(),
            'location' => fake()->address(),
            'source' => fake()->randomElement(['whatsapp', 'linkedin', 'facebook', 'other']),
            'status' => fake()->randomElement(['pending', 'reviewed', 'imported', 'rejected']),
            'source_url' => fake()->url(),
            'scraped_at' => now(),
        ];
    }
}
