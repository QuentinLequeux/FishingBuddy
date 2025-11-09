<?php

use App\Models\Spot;
use App\Models\User;
use App\Models\Specie;

test('user can create a spot', function () {
    $user = User::factory()->create();
    $species = Specie::factory()->count(3)->create();

    $payload = [
        'user_id' => $user->id,
        'latitude' => 1.1234567,
        'longitude' => 5.1234567,
        'name' => 'Test',
        'license' => 'Test',
        'url' => 'https://test.com',
        'species' => $species->pluck('id')->toArray(),
        'environement' => ['Test'],
        'equipments' => ['Test'],
        'rules' => ['Test'],
        'is_public' => true,
    ];

    $response = $this->actingAs($user)
        ->post(route('map.store'), $payload);

    $response->assertRedirect(route('map'));

    $this->assertDatabaseHas('spots', [
        'user_id' => $user->id,
        'latitude' => '1.1234567',
        'longitude' => '5.1234567',
        'name' => 'Test',
        'license' => 'Test',
        'url' => 'https://test.com',
        'is_public' => 1,
    ]);

    $spot = Spot::where('name', 'Test')->firstOrFail();

    $this->assertIsArray($spot->environement);
    $this->assertEquals(['Test'], $spot->environement);

    $this->assertIsArray($spot->equipments);
    $this->assertEquals(['Test'], $spot->equipments);

    $this->assertIsArray($spot->rules);
    $this->assertEquals(['Test'], $spot->rules);

    $this->assertEqualsCanonicalizing(
        $species->pluck('id')->toArray(),
        $spot->species()->pluck('species.id')->toArray()
    );
});

// TODO : user cant create a spot ?
