<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testTheApplicationReturnsASuccessfulResponse(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testDisplayExistingUsers(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            dump($user->toArray());
        }
    }
}
