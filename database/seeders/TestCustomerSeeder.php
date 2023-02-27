<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        // Insert pre-defined attributes
        DB::table('users')->insert([
            [
                'email' => 'johndc@gmail.com',
                'username' => 'johndc',
                'first_name' => 'John',
                'last_name' => 'Dela Cruz',
                'password' => Hash::make(config('custom.default_password')),
                'role' => UserRole::Customer->value,
                'activated' => UserStatus::Active->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email' => 'juandc@gmail.com',
                'username' => 'juandc',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'password' => Hash::make(config('custom.default_password')),
                'role' => UserRole::Customer->value,
                'activated' => UserStatus::Active->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email' => 'jhondc@gmail.com',
                'username' => 'jhondc',
                'first_name' => 'Jhon',
                'last_name' => 'Dela Cruz',
                'password' => Hash::make(config('custom.default_password')),
                'role' => UserRole::Customer->value,
                'activated' => UserStatus::Active->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
