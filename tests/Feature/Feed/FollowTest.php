<?php

use App\Models\User;

test('authenticated user can follow', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $this->actingAs($user1);

    $response = $this->post(route('feed.follow', $user2));

    $response->assertStatus(200);

    $this->assertDatabaseHas('follows', [
        'follower_id' => $user1->id,
        'followed_id' => $user2->id,
    ]);
});

// TODO
/*
test('authenticated user can unfollow', function () {

});
*/
