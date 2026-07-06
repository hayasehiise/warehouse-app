<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'adminapp',
                'name' => 'Admin App',
                'email' => 'admin@app.com',
                'password' => Hash::make('adminapp'),
                'role' => 'admin',
            ],
            [
                'username' => 'supervisorapp',
                'name' => 'Supervisor App',
                'email' => 'supervisor@app.com',
                'password' => Hash::make('supervisorapp'),
                'role' => 'supervisor',
            ],
            [
                'username' => 'staffapp',
                'name' => 'Staff App',
                'email' => 'staff@app.com',
                'password' => Hash::make('staffapp'),
                'role' => 'staff',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                ]
            );

            // ✅ PASTIKAN profile ada, baik dari observer ATAU manual create
            UserProfile::firstOrCreate(
                ['user_id' => $user->id],
                [] // Data null/empty
            );

            $user->assignRole($userData['role']);
        }
    }
}
