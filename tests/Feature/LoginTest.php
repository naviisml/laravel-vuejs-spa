<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function authenticate()
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['token', 'expires_in'])
            ->assertJson(['token_type' => 'bearer']);
    }

    /** @test */
    public function fetch_the_current_user()
    {
        $this->actingAs(User::factory()->create())
            ->getJson('/api/v1/me')
            ->assertSuccessful()
            ->assertJsonStructure(['id', 'username', 'email']);
    }

    /** @test */
    public function log_out()
    {
        $token = $this->postJson('/api/v1/login', [
            'email' => User::factory()->create()->email,
            'password' => 'password',
        ])->json()['token'];

        $this->postJson("/api/v1/logout?token=$token")
            ->assertSuccessful();
    }

	public function profile()
	{
        $token = $this->postJson('/api/v1/login', [
            'email' => User::factory()->create()->email,
            'password' => 'password',
        ])->json()['token'];

        $this->getJson("/api/v1/me?token=$token")
            ->assertStatus(422);
	}
}
