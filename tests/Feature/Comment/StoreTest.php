<?php

use App\Models\Lure;
use App\Models\User;
use App\Models\Specie;
use App\Models\Activity;
use Database\Seeders\LureSeeder;
use Database\Seeders\SpecieSeeder;

test('user can comment an activity', function () {
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

    $payload = [
        'activity_id' => $activity->id,
        'content' => 'Test',
    ];

    $this->actingAs($user);

    $response = $this->post(route('comment.store'), $payload);

    $response->assertStatus(200);

    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'activity_id' => $activity->id,
        'content' => 'Test',
    ]);
});
