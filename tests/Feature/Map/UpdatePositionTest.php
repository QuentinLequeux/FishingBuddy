<?php

use App\Models\User;
use App\Models\Spot;

test('user can drag a spot and update position', function () {
    $user = User::factory()->create();
    $spot = Spot::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->patch(route('map.position.update', $spot),
        [
            'latitude' => 2.1234567,
            'longitude' => 6.1234567,
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas('spots', [
        'id' => $spot->id,
        'latitude' => 2.1234567,
        'longitude' => 6.1234567,
    ]);
});
