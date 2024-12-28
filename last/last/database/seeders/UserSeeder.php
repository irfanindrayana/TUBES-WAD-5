<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff'
        ]);
    }
} 