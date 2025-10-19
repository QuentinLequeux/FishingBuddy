<?php

use App\Models\User;

test('authenticated user can access to map', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('map'));

    $response->assertStatus(200);
});

test('guest user cant access to map', function () {
    $response = $this->get(route('map'));

    $response->assertRedirect(route('login'));
});
