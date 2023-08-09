<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;
use App\Models\User;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->address,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Profile $profile) {

            $minWireAmount = 123 * 103059;

            $profile->wires()->create([
                'amount' => $this->faker->randomNumber(5, true),
                'withdrawal' => $this->faker->boolean,
            ]);

        });
    }
}
