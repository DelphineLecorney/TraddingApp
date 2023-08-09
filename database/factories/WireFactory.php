<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Wire;
use App\Models\Profile;

class WireFactory extends Factory
{
    protected $model = Wire::class;

    public function definition()
    {
        return [
            'profile_id' => Profile::factory(),
            'amount' => $this->faker->randomNumber(4),
            'withdrawal' => $this->faker->boolean,
        ];
    }
}
