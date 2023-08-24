<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Profile;

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

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
{
    return $this->afterCreating(function (User $user) {
        $profile = $user->profile;

        $profile->trades()->create([
            'symbol' => 'TSLA',
            'quantity' => 123,
            'open_price' => 21000,
            'close_price' => null,
            'open_datetime' => now(),
            'close_datetime' => null,
            'open' => true,
        ]);
        $profile->trades()->create([
            'symbol' => 'TSLA',
            'quantity' => 123,
            'open_price' => 21000,
            'close_price' => 24000,
            'open_datetime' => now(),
            'close_datetime' => now()->addHours(2),
            'open' => false,
        ]);
    });
}

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
