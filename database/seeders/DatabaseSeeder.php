<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\User;
use App\Models\Specie;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([SpecieSeeder::class]);

        $user = User::factory()->create([
            'name' => 'Quentin',
            'email' => 'quent789@gmail.com',
        ]);

        $specie = Specie::all();

        $spots = Spot::factory(1)->create([
            'user_id' => $user->id,
        ]);

        foreach ($spots as $spot) {
            $randomSpecie = $specie->random(rand(1, 3))->pluck('id');
            $spot->species()->attach($randomSpecie);
        }
    }
}
