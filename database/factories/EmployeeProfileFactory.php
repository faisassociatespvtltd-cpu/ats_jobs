<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeProfile>
 */
class EmployeeProfileFactory extends Factory
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
            'name' => fake()->name(),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'whatsapp_number' => fake()->phoneNumber(),
            'cnic' => fake()->numerify('#####-#######-#'),
            'expected_salary' => fake()->numberBetween(30000, 150000),
            'location' => fake()->city(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'Pakistan',
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'bio' => fake()->paragraph(),
            'education_level' => fake()->randomElement(['Bachelors', 'Masters', 'PhD', 'Inter']),
            'skills' => implode(', ', fake()->words(5)),
            'experience' => fake()->numberBetween(1, 15) . ' years',
            'linkedin_url' => fake()->url(),
            'portfolio_url' => fake()->url(),
        ];
    }
}
