<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Authorization is required
     */
    public function testAuthorizationIsRequired(): void
    {
        $this->patchJson('/api/change-password', [])
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Form fields are required
     */
    public function testFormFieldsAreRequired(): void
    {
        $this->authenticate()
            ->patchJson('/api/change-password', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'password',
                    'new_password',
                    'confirm_new_password',
                ]
            ]);
    }

    /**
     * Password is incorrect
     */
    public function testPasswordIsIncorrect(): void
    {
        $this->authenticate()
            ->patchJson('/api/change-password', [
                'password' => 'P@ssw0rd123456',
                'new_password' => 'P@ssw0rd456',
                'confirm_new_password' => 'P@ssw0rd456',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password']
            ]);
    }

    /**
     * New password format is invalid
     */
    public function testPasswordInvalidFormat(): void
    {
        $this->authenticate()
            ->patchJson('/api/change-password', [
                'password' => 'P@ssw0rd123',
                'new_password' => 'password',
                'confirm_new_password' => 'password',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['new_password']
            ]);
    }

    /**
     * Password confirmation is not matched with password
     */
    public function testPasswordConfirmationNotMatch(): void
    {
        $this->authenticate()
            ->patchJson('/api/change-password', [
                'password' => 'P@ssw0rd123',
                'new_password' => 'P@ssw0rd456',
                'confirm_new_password' => 'password456',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['confirm_new_password']
            ]);
    }

    /**
     * Change password is successful
     */
    public function testChangePasswordSuccessful(): void
    {
        $this->authenticate()
            ->patchJson('/api/change-password', [
                'password' => 'P@ssw0rd123',
                'new_password' => 'P@ssw0rd456',
                'confirm_new_password' => 'P@ssw0rd456',
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'message',
            ]);
    }
}
