<?php

use App\Models\Spot;
use App\Models\User;

test('user can delete a spot', function () {
    $user = User::factory()->create();
    $spot = Spot::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->delete(route('map.destroy', $spot));

    $response->assertRedirect(route('map'));

    $this->assertDatabaseMissing('spots', ['id' => $spot->id]);
});
