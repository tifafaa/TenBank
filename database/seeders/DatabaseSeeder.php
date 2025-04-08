<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'latifa',
            'email' => 'tifa@gmail.com',
            'password' => Hash::make('tifatifa'),
            'role' => 'user',
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'bank',
            'email' => 'bank@gmail.com',
            'password' => Hash::make('bank123'),
            'role' => 'bank',
        ]);
    }
}
