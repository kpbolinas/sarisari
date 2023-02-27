<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Form fields are required
     */
    public function testFormFieldsAreRequired(): void
    {
        $this->postJson('/api/register', [])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'username',
                    'first_name',
                    'last_name',
                    'password',
                    'confirm_password',
                ]
            ]);
    }

    /**
     * Email format is invalid
     */
    public function testEmailInvalidFormat(): void
    {
        $this->postJson('/api/register', [
            'email' => 'adminnnnnn',
            'username' => 'adminnn',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => 'P@ssw0rd123',
            'confirm_password' => 'P@ssw0rd123',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ]);
    }

    /**
     * Email is already used
     */
    public function testEmailAlreadyUsed(): void
    {
        $this->postJson('/api/register', [
            'email' => 'admin@sarisari.net',
            'username' => 'adminnn',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => 'P@ssw0rd123',
            'confirm_password' => 'P@ssw0rd123',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ]);
    }

    /**
     * Username is already used
     */
    public function testUsernameAlreadyUsed(): void
    {
        $this->postJson('/api/register', [
            'email' => 'jamesb@gmail.com',
            'username' => 'admin',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => 'P@ssw0rd123',
            'confirm_password' => 'P@ssw0rd123',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['username']
            ]);
    }

    /**
     * Password format is invalid
     */
    public function testPasswordInvalidFormat(): void
    {
        $this->postJson('/api/register', [
            'email' => 'jamesb@gmail.com',
            'username' => 'jamesb',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => 'password',
            'confirm_password' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password']
            ]);
    }

    /**
     * Password confirmation is not matched with password
     */
    public function testPasswordConfirmationNotMatch(): void
    {
        $this->postJson('/api/register', [
            'email' => 'jamesb@gmail.com',
            'username' => 'jamesb',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => 'P@ssw0rd123',
            'confirm_password' => 'P@ssw0rd456',
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['confirm_password']
            ]);
    }

    /**
     * Registration is successful
     */
    public function testRegistrationSuccessful(): void
    {
        $this->postJson('/api/register', [
            'email' => 'jamesb@gmail.com',
            'username' => 'jamesb',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => 'P@ssw0rd123',
            'confirm_password' => 'P@ssw0rd123',
        ])
            ->assertStatus(200);
    }
}
