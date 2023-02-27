<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Login attempt with empty fields
     */
    public function testLoginAttemptWithEmptyFields(): void
    {
        $this->postJson('/api/login', [])
            ->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }

    /**
     * Login attempt with invalid inputs
     */
    public function testLoginAttemptWithInvalidInputs(): void
    {
        $this->postJson('/api/login', [
            'email' => 'asd123',
            'password' => 'asdasd123123'
        ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }

    /**
     * Login is successful
     */
    public function testLoginSuccessful(): void
    {
        $this->postJson('/api/login', [
            'email' => 'admin@sarisari.net',
            'password' => 'P@ssw0rd123',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'token',
                    'role',
                ],
                'message',
            ]);
    }
}
