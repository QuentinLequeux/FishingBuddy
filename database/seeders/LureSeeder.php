<?php

namespace Database\Seeders;

use App\Models\Lure;
use Illuminate\Database\Seeder;

class LuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lures = [
            'Asticot',
            'Crankbait',
            'Cuillère',
            'Drop shot',
            'Jerkbait',
            'Maïs',
            'Mouche',
            'Pain',
            'Shad',
            'Spinnerbait',
            'Swimbait',
            'Twist',
            'Vers',
            'Vif',
        ];

        foreach ($lures as $lure) {
            Lure::create(['name' => $lure]);
        }
    }
}

// TODO : Ajouter leurres.
