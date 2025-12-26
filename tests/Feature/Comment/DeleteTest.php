<?php

use App\Models\Lure;
use App\Models\User;
use App\Models\Specie;
use App\Models\Comment;
use App\Models\Activity;
use Database\Seeders\LureSeeder;
use Database\Seeders\SpecieSeeder;

test('user can delete his comment', function () {
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
    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'activity_id' => $activity->id,
        'content' => 'Test',
    ]);

    $this->actingAs($user)->delete(route('comment.destroy', $comment));

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('other users cannot delete comments', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $this->seed(SpecieSeeder::class);
    $specie = Specie::first();
    $this->seed(LureSeeder::class);
    $lure = Lure::first();
    $activity = Activity::factory()->create([
        'user_id' => $user1->id,
        'specie_id' => $specie->id,
        'lure_id' => $lure->id,
        ]);
    Comment::factory()->create([
        'user_id' => $user1->id,
        'activity_id' => $activity->id,
        ]);

    $this->actingAs($user2);

    $response = $this->delete(route('comment.destroy', Comment::first()));

    $response->assertStatus(403);
});

// TODO : helpers or beforeEach()
