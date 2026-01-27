<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
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
            'membership_type' => fake()->randomElement(['basic', 'premium', 'enterprise']),
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => fake()->randomElement(['active', 'expired']),
            'referral_code' => \Illuminate\Support\Str::random(10),
            'referral_count' => 0,
        ];
    }
}
