<?php

use App\Models\User;

test('user can modify privacy', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->patch(route('privacy.update'), ['visibility' => 'private'])
        ->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'activities_visibility' => 'private',
    ]);
});
