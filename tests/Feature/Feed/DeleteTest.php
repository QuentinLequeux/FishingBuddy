<?php

use App\Models\Lure;
use App\Models\User;
use App\Models\Specie;
use App\Models\Activity;
use Database\Seeders\LuresSeeder;
use Database\Seeders\SpecieSeeder;

test('user can delete his post', function () {
    $user = User::factory()->create();
    $this->seed(SpecieSeeder::class);
    $specie = Specie::first();
    $this->seed(LuresSeeder::class);
    $lure = Lure::first();
    $activity = Activity::factory()->create([
        'user_id' => $user->id,
        'specie_id' => $specie->id,
        'lure_id' => $lure->id
    ]);

    $this->actingAs($user);

    $response = $this->delete(route('feed.destroy', $activity));

    $response->assertRedirect(route('feed'));

    $this->assertSoftDeleted('activities', ['id' => $activity->id]);
});

test('other users cannot delete posts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $this->seed(SpecieSeeder::class);
    $specie = Specie::first();
    $this->seed(LuresSeeder::class);
    $lure = Lure::first();
    $activity = Activity::factory()->create([
        'user_id' => $user1->id,
        'specie_id' => $specie->id,
        'lure_id' => $lure->id
    ]);

    $this->actingAs($user2);

    $response = $this->delete(route('feed.destroy', $activity));

    $response->assertStatus(403);
});
