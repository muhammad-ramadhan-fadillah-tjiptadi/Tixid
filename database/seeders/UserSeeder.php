<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('adminID')
        ]);
        // Staff
        User::create([
            'name' => 'Staff',
            'email' => 'staff@gmail.com',
            'role' => 'staff',
            'password' => Hash::make('staffID')
        ]);
    }
}
