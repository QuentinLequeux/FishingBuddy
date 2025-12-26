<?php

use App\Models\User;

test('authenticated user can access to feed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('feed'));

    $response->assertStatus(200);
});

test('guest user can access to feed', function () {
    $response = $this->get(route('feed'));

    $response->assertStatus(200);
});
