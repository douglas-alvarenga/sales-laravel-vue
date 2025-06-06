<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $username = fake()->unique()->username() . rand(1, 9999);
        while (strlen($username) > 20) {
            $username = fake()->unique()->username() . rand(1, 9999);
        }
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '1234',
            'username' => $username,
            'is_admin' => true,
        ];
    }
}
