<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name'  => 'Admin',
                'last_name'   => 'User',
                'password'    => Hash::make('123456'),
                'role'        => 'admin',
            ]
        );
    }
}