<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nom' => 'Administrateur',
            'email' => 'admin@pneumatique.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '0612345678',
        ]);

        User::create([
            'nom' => 'Manager Test',
            'email' => 'manager@pneumatique.ma',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'telephone' => '0623456789',
        ]);
    }
}