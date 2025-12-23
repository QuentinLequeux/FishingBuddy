<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('user can upload avatar', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('avatar.jpg');

    $this->actingAs($user)
        ->post(route('profile.avatar.update'), [
        'avatar' => $file,
        'avatar_updated_at' => now(),
    ]);

    $user->refresh();

    Storage::disk('public')->assertExists($user->avatar);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'avatar' => $user->avatar,
    ]);
});

test('user can delete avatar', function () {
    Storage::fake('public');
    UploadedFile::fake()->image('avatar.jpg');
    $user = User::factory()->create([
        'avatar' => 'avatars/avatar.jpg'
    ]);

    $this->actingAs($user)
        ->delete(route('profile.avatar.destroy'), [
        'avatar' => $user->avatar,
        'avatar_updated_at' => now(),
    ]);

    $user->refresh();

    Storage::disk('public')->assertMissing($user->avatar);
    Storage::disk('public')->assertDirectoryEmpty('/avatars');

    $this->assertDatabaseMissing('users', ['avatar' => '']);
});

test('old avatar is deleted when new is uploaded', function () {

});

// TODO : test
