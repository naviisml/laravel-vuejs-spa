<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_register()
    {
        $this->postJson('/api/v1/register', [
            'username' => 'Johnny',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['id', 'username', 'email']);
    }

    /** @test */
    public function can_not_register_with_existing_email()
    {
        User::factory()->create(['email' => 'test@test.app']);

        $this->postJson('/api/v1/register', [
            'username' => 'Johnny',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
