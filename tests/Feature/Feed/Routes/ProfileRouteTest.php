<?php

use App\Models\User;

test('authenticated user can access to profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('profile', $user));

    $response->assertStatus(200);
});

test('guest user can access to profile', function () {
    $user = User::factory()->create();

    $response = $this->get(route('profile', $user));

    $response->assertStatus(200);
});
