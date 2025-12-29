<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Spot;
use App\Models\User;
use App\Models\Specie;
use App\Models\Comment;
use App\Models\Activity;
use App\Models\ActivityView;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SpecieSeeder::class,
            LureSeeder::class
            ]);

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
            [
                'name' => 'Dominique',
                'email' => 'dominique.vilain@hepl.be',
            ],
            [
                'name' => 'Dylan',
                'email' => 'dylan.jacquet@hepl.be',
            ],
            [
                'name' => 'François',
                'email' => 'francois.parmentier@hepl.be',
            ],
            [
                'name' => 'Myriam',
                'email' => 'myriam.dupont@hepl.be',
            ],
            [
                'name' => 'Cedric',
                'email' => 'cedric.muller@hepl.be',
            ],
            [
                'name' => 'Daniel',
                'email' => 'daniel.schreurs@hepl.be',
            ],
            [
                'name' => 'Maud',
                'email' => 'maud.wera@hepl.be',
            ],
            [
                'name' => 'Toon',
                'email' => 'toon.vandenbos@hepl.be',
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

        Activity::factory()->count(10)->create();

        ActivityView::factory()->count(5)->create()->each(function ($view) {
            $view->activity()->increment('views');
        });

        Comment::factory()->count(10)->create();

        Like::factory()->count(5)->create();
    }
}
