<?php

use App\Models\Lure;
use App\Models\User;
use App\Models\Specie;
use App\Models\Activity;
use Database\Seeders\LureSeeder;
use Database\Seeders\SpecieSeeder;

test('authenticated user can like a post', function () {
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

    $this->actingAs($user);

    $response = $this->post(route('feed.like', $activity));

    $response->assertStatus(200);
});
