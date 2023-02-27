<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->name(),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'password' => '$2y$10$iA4d1iVGNCgYB9cnF7e3LuKqkL2xE99Y1rFSrMkp9Pp0FbmlRzsv.', // P@ssw0rd123
            'role' => UserRole::Customer->value,
            'activated' => UserStatus::Active->value,
        ];
    }
}
