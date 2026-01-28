<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployerProfile>
 */
class EmployerProfileFactory extends Factory
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
            'company_name' => fake()->company(),
            'company_address' => fake()->address(),
            'contact_person_name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'whatsapp_number' => fake()->phoneNumber(),
            'website_url' => fake()->url(),
            'company_description' => fake()->paragraph(),
            'industry' => fake()->randomElement(['Technology', 'Healthcare', 'Finance', 'Education', 'Construction']),
            'company_size' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
            'license_number' => fake()->numerify('LIC-########'),
            'registration_number' => fake()->numerify('REG-########'),
            'tax_number' => fake()->numerify('TAX-########'),
            'company_type' => fake()->randomElement(['Private', 'Public', 'NGO', 'Government']),
            'founded_year' => fake()->year(),
            'linkedin_url' => fake()->url(),
        ];
    }
}
