<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@inktouch.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff',
            'email' => 'staff@inktouch.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
