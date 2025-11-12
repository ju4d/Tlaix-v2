<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador Principal',
                'email' => 'admin@tlaix.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Chef María González',
                'email' => 'chef@tlaix.com',
                'password' => Hash::make('chef123'),
                'role' => 'chef'
            ],
            [
                'name' => 'Mesero Juan Pérez',
                'email' => 'mesero1@tlaix.com',
                'password' => Hash::make('mesero123'),
                'role' => 'mesero'
            ],
            [
                'name' => 'Mesera Ana Rodríguez',
                'email' => 'mesero2@tlaix.com',
                'password' => Hash::make('mesero123'),
                'role' => 'mesero'
            ],
            [
                'name' => 'Mesero Carlos López',
                'email' => 'mesero3@tlaix.com',
                'password' => Hash::make('mesero123'),
                'role' => 'mesero'
            ],
            [
                'name' => 'Gerente Roberto Martínez',
                'email' => 'gerente@tlaix.com',
                'password' => Hash::make('gerente123'),
                'role' => 'admin'
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✓ Usuarios creados: ' . count($users));
    }
}
