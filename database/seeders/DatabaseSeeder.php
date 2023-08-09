<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Wire;
use App\Models\Trade;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(5)->has(Profile::factory()->count(1))->create();
    }
}
