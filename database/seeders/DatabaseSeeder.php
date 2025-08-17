<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Credit;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Créer un admin par défaut
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@credit.ma',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Créer quelques clients de test (optionnel)
        if (app()->environment('local')) {
            $clients = [
                ['name' => 'Ahmed Alami', 'phone' => '0612345678', 'address' => 'Casablanca, Maroc'],
                ['name' => 'Fatima Benali', 'phone' => '0623456789', 'address' => 'Rabat, Maroc'],
                ['name' => 'Mohamed Tazi', 'phone' => '0634567890', 'address' => 'Marrakech, Maroc'],
                ['name' => 'Aicha Naciri', 'phone' => '0645678901', 'address' => 'Fès, Maroc'],
                ['name' => 'Youssef Amrani', 'phone' => '0656789012', 'address' => 'Agadir, Maroc']
            ];

            foreach ($clients as $clientData) {
                $client = Client::create($clientData);
                
                // Créer des crédits de test
                Credit::create([
                    'client_id' => $client->id,
                    'amount' => rand(1000, 50000),
                    'reason' => 'Crédit de test',
                    'status' => ['active', 'paid', 'cancelled'][rand(0, 2)],
                    'created_by' => $admin->id
                ]);
            }
        }
    }
}