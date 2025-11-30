<?php

use App\Models\User;
use App\Models\Lure;
use App\Models\Specie;
use Database\Seeders\LureSeeder;
use Database\Seeders\SpecieSeeder;

test('user can create a post', function () {
    $user = User::factory()->create();
    $this->seed(SpecieSeeder::class);
    $specie = Specie::first();
    $this->seed(LureSeeder::class);
    $lure = Lure::first();

    $payload = [
        'specie_id' => $specie->id,
        'size' => '60',
        'weight' => '10',
        'lure_id' => $lure->id,
    ];

    $response = $this->actingAs($user)->post(route('feed.store'), $payload);

    $response->assertRedirect(route('feed'));

    $this->assertDatabaseHas('activities', [
        'user_id' => $user->id,
        'specie_id' => $specie->id,
        'size' => '60',
        'weight' => '10',
        'lure_id' => $lure->id,
    ]);
});
