<?php

use App\Models\User;

test('user can enable location visibility', function () {
    $user = User::factory()->create([
        'location_visibility' => '0'
    ]);

    $response = $this->actingAs($user)->patch(route('location.update'), [
        'location_visibility' => '1'
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'location_visibility' => '1'
    ]);
});

test('user can disable location visibility', function () {
    $user = User::factory()->create([
        'location_visibility' => '1'
    ]);

    $response = $this->actingAs($user)->patch(route('location.update'), [
        'location_visibility' => '0'
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'location_visibility' => '0'
    ]);
});

test('user can update location', function () {
    $user = User::factory()->create([
        'location' => null
    ]);

    $response = $this->actingAs($user)->patch(route('location.store'), [
        'location' => 'test'
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'location' => 'test'
    ]);
});

test('user can delete location', function () {
    $user = User::factory()->create([
        'location' => 'test'
    ]);

    $response = $this->actingAs($user)->delete(route('location.destroy'));

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'location' => null
    ]);
});
