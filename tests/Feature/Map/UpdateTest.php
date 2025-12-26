<?php

use App\Models\Spot;
use App\Models\User;
use App\Models\Specie;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can modify a spot', function () {
    $user = User::factory()->create();
    $spot = Spot::factory()->create(['user_id' => $user->id]);
    $species = Specie::factory()->count(3)->create();

    $data = [
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
        ->post(route('map.update', $spot), $data);

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

    expect($spot->species()->pluck('species.id')->toArray())->toEqualCanonicalizing($species->pluck('id')->toArray());
});
