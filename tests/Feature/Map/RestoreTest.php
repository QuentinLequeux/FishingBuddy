<?php

use App\Models\Spot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can cancel a deletion', function () {
    $user = User::factory()->create();
    $spot = Spot::factory()->create();

    $spot->delete();
    expect($spot->fresh()->trashed())->toBeTrue();

    $this->actingAs($user)
        ->post(route('map.restore', $spot->id))
        ->assertRedirect(route('map'));

    expect($spot->fresh()->trashed())->toBeFalse();
});
