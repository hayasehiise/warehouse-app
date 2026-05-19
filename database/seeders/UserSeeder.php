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
        // creating admin user
        $admin = User::firstOrCreate([
            'username' => 'adminapp',
        ], [
            'name' => 'Admin App',
            'email' => 'admin@app.com',
            'password' => Hash::make('adminapp'),
        ]);
        // assign admin role
        $admin->assignRole('admin');

        // creating supervisor user
        $supervisor = User::firstOrCreate([
            'username' => 'supervisorapp',
        ], [
            'name' => 'Supervisor App',
            'email' => 'supervisor@app.com',
            'password' => Hash::make('supervisorapp'),
        ]);
        // assign supervisor role
        $supervisor->assignRole('supervisor');

        // creating staff user
        $staff = User::firstOrCreate([
            'username' => 'staffapp',
        ], [
            'name' => 'Staff App',
            'email' => 'staff@app.com',
            'password' => Hash::make('staffapp'),
        ]);
        // assign staff role
        $staff->assignRole('staff');
    }
}
