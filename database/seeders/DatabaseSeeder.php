<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\User;
use App\Models\Specie;
use App\Models\Comment;
use App\Models\Activity;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([SpecieSeeder::class]);

        $this->call([LureSeeder::class]);

        $users = [
            [
                'name' => 'Quentin',
                'email' => 'quent789@gmail.com',
            ],
            [
                'name' => 'Nicolas',
                'email' => 'quentin.lequeux@student.hepl.be',
            ],
            [
                'name' => 'Benjamin',
                'email' => 'quent690@yahoo.fr',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::factory()->create($userData);

            $spots = Spot::factory(1)->create([
                'user_id' => $user->id,
            ]);

            $specie = Specie::all();

            foreach ($spots as $spot) {
                $randomSpecie = $specie->random(rand(1, 3))->pluck('id');
                $spot->species()->attach($randomSpecie);
            }
        }

        Activity::factory()->count(3)->create();

        Comment::factory()->count(5)->create();
    }
}
