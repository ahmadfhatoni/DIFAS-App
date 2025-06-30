<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin Satu',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Sesuaikan jika pakai table `roles` terpisah
        ]);

        // Owner
        User::create([
            'name' => 'Owner DIFAS',
            'email' => 'difashomeindustry@gmail.com',
            'password' => Hash::make('OwnerDIFAS'),
            'role' => 'owner',
        ]);
    }
}

