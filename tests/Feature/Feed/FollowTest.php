<?php

use App\Models\User;

test('authenticated user can follow', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $this->actingAs($user1);

    $response = $this->post(route('feed.follow', $user2));

    $response->assertStatus(302);
});
