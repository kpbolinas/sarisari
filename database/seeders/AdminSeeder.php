<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        // Insert pre-defined attributes
        DB::table('users')->insert([
            [ //1
                'email' => 'superadmin@sarisari.net',
                'username' => 'superadmin',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make(config('custom.default_password')),
                'role' => UserRole::SuperAdmin->value,
                'activated' => UserStatus::Active->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //2
                'email' => 'admin@sarisari.net',
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'password' => Hash::make(config('custom.default_password')),
                'role' => UserRole::Admin->value,
                'activated' => UserStatus::Active->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
