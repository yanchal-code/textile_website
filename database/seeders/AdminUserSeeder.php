<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 1,
            'phone' => '1234567890',
            'is_blocked' => false,
            'password' => Hash::make('A@123'),
            'email_verified_at' => now(),
        ]);
    }
}
