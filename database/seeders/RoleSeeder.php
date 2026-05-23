<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@siaban.com',
            'password' => Hash::make('password'),
            'role' => 'Admin'
        ]);

        User::create([
            'name' => 'Staff Lapangan',
            'email' => 'staff@siaban.com',
            'password' => Hash::make('password'),
            'role' => 'Staff'
        ]);

        User::create([
            'name' => 'Warga Lokal',
            'email' => 'warga@siaban.com',
            'password' => Hash::make('password'),
            'role' => 'Warga'
        ]);
    }
}
