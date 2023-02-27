<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Email is required
     */
    public function testEmailIsRequired(): void
    {
        $this->postJson('/api/forgot-password', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ]);
    }

    /**
     * Email format is invalid
     */
    public function testEmailInvalidFormat(): void
    {
        $this->postJson('/api/forgot-password', [
            'email' => 'testemail',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ]);
    }

    /**
     * Forgot password is successful
     */
    public function testForgotPasswordSuccessful(): void
    {
        $this->postJson('/api/forgot-password', [
            'email' => 'admin@sarisari.net',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
