<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'email'      => fake()->unique()->safeEmail(),
            'pwrd_hash'  => static::$password ??= Hash::make('password'),
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'is_admin'   => false,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['is_admin' => true]);
    }
}
