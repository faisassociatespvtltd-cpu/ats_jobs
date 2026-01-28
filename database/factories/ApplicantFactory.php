<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Applicant>
 */
class ApplicantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'cover_letter' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'reviewed', 'shortlisted', 'interviewed', 'offered', 'hired', 'rejected']),
            'application_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
