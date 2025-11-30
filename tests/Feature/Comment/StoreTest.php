<?php

use App\Models\Lure;
use App\Models\User;
use App\Models\Specie;
use App\Models\Activity;
use Database\Seeders\LuresSeeder;
use Database\Seeders\SpecieSeeder;

test('user can comment an activity', function () {
    $user = User::factory()->create();
    $this->seed(SpecieSeeder::class);
    $specie = Specie::first();
    $this->seed(LuresSeeder::class);
    $lure = Lure::first();
    $activity = Activity::factory()->create([
        'user_id' => $user->id,
        'specie_id' => $specie->id,
        'size' => '60',
        'weight' => '10',
        'lure_id' => $lure->id
    ]);

    $payload = [
        'activity_id' => $activity->id,
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quibusdam.',
    ];

    $this->actingAs($user);

    $response = $this->post(route('comment.store'), $payload);

    $response->assertRedirect(route('feed'));

    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'activity_id' => $activity->id,
        'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quibusdam.',
    ]);
});
