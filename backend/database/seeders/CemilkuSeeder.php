<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CemilkuSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@cemilku.com',
            ],
            [
                'name' => 'Admin Cemilku',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}

