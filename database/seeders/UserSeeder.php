<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'LUNARA System Admin',
            'email' => 'admin@lunara.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular Customer
        User::create([
            'name' => 'John Doe (Customer)',
            'email' => 'customer@lunara.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
