<?php

namespace Tests;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Authenticate user.
     *
     * @param int|null $role
     * @return object $authenticated
     */
    protected function authenticate(int $role = null)
    {
        $userRole = UserRole::Customer->value;
        if ($role && $role === UserRole::Admin->value) {
            $userRole = UserRole::Admin->value;
        }

        $user = User::create([
            'email' => 'jamesb@gmail.com',
            'username' => 'jamesb',
            'first_name' => 'James',
            'last_name' => 'Bond',
            'password' => Hash::make('P@ssw0rd123'),
            'role' => $userRole,
            'activated' => UserStatus::Active->value,
        ]);

        $attempt = auth()->attempt(['email' => $user->email, 'password' => 'P@ssw0rd123']);
        if (!$attempt) {
            return response(['message' => 'Invalid email or password. Please try again.']);
        }
        $token = auth()->user()->createToken('SariSariStore')->plainTextToken;

        $authenticated = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);

        return $authenticated;
    }
}
