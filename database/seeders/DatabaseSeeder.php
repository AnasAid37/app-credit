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
        // ✅ Créer ou mettre à jour l'admin par défaut
        $admin = User::updateOrCreate(
            ['email' => 'admin@credit.ma'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ]
        );

        // ✅ Créer quelques clients de test (seulement en local)
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

                // ✅ Créer un crédit de test pour chaque client
                $amount = rand(1000, 50000);
                $paid   = rand(0, $amount); // payé aléatoirement

                Credit::create([
                    'client_id'        => $client->id,
                    'amount'           => $amount,
                    'paid_amount'      => $paid,
                    'remaining_amount' => $amount - $paid,
                    'reason'           => 'Crédit de test',
                    'status'           => ['active', 'paid', 'cancelled'][rand(0, 2)],
                    'created_by'       => $admin->id,
                ]);
            }
        }
    }
}
