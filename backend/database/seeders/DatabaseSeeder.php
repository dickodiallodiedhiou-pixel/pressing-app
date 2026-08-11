<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte Gestionnaire (Créé en base, pas d'auto-inscription)
        User::create([
            'name' => 'Gestionnaire Pressing',
            'email' => 'admin@pressing.com',
            'password' => Hash::make('password123'),
            'role' => 'gestionnaire',
        ]);

        // Compte Client exemple
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'client@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);

        // Services de départ
        Service::create([
            'libelle' => 'Lavage',
            'prix_unitaire' => 1500,
            'description' => 'Lavage au kilo pour vêtements ordinaires',
            'is_active' => true,
        ]);

        Service::create([
            'libelle' => 'Repassage',
            'prix_unitaire' => 500,
            'description' => 'Repassage à l\'unité (chemises, pantalons)',
            'is_active' => true,
        ]);

        Service::create([
            'libelle' => 'Nettoyage à sec',
            'prix_unitaire' => 3000,
            'description' => 'Nettoyage délicat pour costumes et vestes',
            'is_active' => true,
        ]);
    }
}