<?php

use App\Models\Lure;
use App\Models\User;
use App\Models\Specie;
use App\Models\Activity;
use Database\Seeders\LureSeeder;
use Database\Seeders\SpecieSeeder;

test('view increment when user click on button', function () {
    $user = User::factory()->create();
    $this->seed(SpecieSeeder::class);
    $specie = Specie::first();
    $this->seed(LureSeeder::class);
    $lure = Lure::first();
    $activity = Activity::factory()->create([
        'user_id' => $user->id,
        'specie_id' => $specie->id,
        'lure_id' => $lure->id,
    ]);

    $this->actingAs($user)->post(route('feed.view', $activity));

    $this->assertDatabaseHas('activities', ['id' => $activity->id, 'views' => 1]);
});

// TODO : only one view per user.
