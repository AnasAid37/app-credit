<?php
// database/seeders/UserProfileSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// database/seeders/UserProfileSeeder.php


class UserProfileSeeder extends Seeder
{
    public function run()
    {
        // العثور على المستخدم admin موجود
        $admin = User::where('email', 'admin@pneumatique.ma')->first();

        // إذا وجدنا المستخدم
        if ($admin) {
            $admin->profile()->updateOrCreate([], [
                'adresse' => '123 Rue Principale, Casablanca, Maroc',
                'departement' => 'Direction Générale',
                'date_embauche' => now()->subYear(),
            ]);
        }
    }
}
