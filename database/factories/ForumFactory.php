<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Forum>
 */
class ForumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'category' => fake()->randomElement(['General', 'Jobs', 'Legal', 'Community']),
            'views' => fake()->numberBetween(0, 500),
            'replies_count' => 0,
            'is_pinned' => fake()->boolean(10),
            'is_locked' => fake()->boolean(5),
            'last_reply_at' => now(),
        ];
    }
}
