<?php

namespace Database\Seeders;

use App\Models\Specie;
use Illuminate\Database\Seeder;

class SpecieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $species = [
            'Ablette',
            'Anguille',
            'Barbeau',
            'Brème',
            'Brochet',
            'Carassin',
            'Carpe',
            'Chevaine',
            'Épinoche',
            'Gardon',
            'Goujon',
            'Hotu',
            'Ide Melanote',
            'Loche',
            'Ombre',
            'Perche',
            'Perche soleil',
            'Rotengle',
            'Sandre',
            'Silure',
            'Tanche',
            'Truite arc en ciel',
            'Truite fario',
            'Vairon',
            'Vandoise',
        ];

        foreach ($species as $specie) {
            Specie::create(['name' => $specie]);
        }
    }
}

// TODO : Ajouter saison et taille de prise minimum.
