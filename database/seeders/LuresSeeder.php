<?php

namespace Database\Seeders;

use App\Models\Lures;
use Illuminate\Database\Seeder;

class LuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lures = [
            'Cuillère',
            'Mouche',
            'Twist',
            'Vif',
        ];

        foreach ($lures as $lure) {
            Lures::create(['name' => $lure]);
        }
    }
}

// TODO : Ajouter leurres.
