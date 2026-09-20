<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bitesync.test'],
            [
                'name' => 'BiteSync Administrator',
                'password' => Hash::make('password'),
                'role' => 'CEO/Admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'finance@bitesync.test'],
            [
                'name' => 'BiteSync Finance',
                'password' => Hash::make('password'),
                'role' => 'Finance',
            ]
        );

        User::updateOrCreate(
            ['email' => 'procurement@bitesync.test'],
            [
                'name' => 'BiteSync Procurement',
                'password' => Hash::make('password'),
                'role' => 'Procurement',
            ]
        );
    }
}