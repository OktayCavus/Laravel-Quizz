<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Roles_Permissions>
 */
class Roles_PermissionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => $this->faker->numberBetween(1, 10),
            'perm_id' => $this->faker->numberBetween(1, 10)
        ];
    }
}
