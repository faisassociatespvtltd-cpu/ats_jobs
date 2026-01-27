<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LabourLaw>
 */
class LabourLawFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['law', 'article', 'book', 'qa']),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'country' => fake()->country(),
            'category' => fake()->randomElement(['Employment', 'Health & Safety', 'Wages', 'Discrimination']),
            'author' => fake()->name(),
            'source' => fake()->url(),
            'published_date' => fake()->date(),
            'views' => fake()->numberBetween(0, 1000),
            'is_featured' => fake()->boolean(10),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
