<?php

use Inertia\Inertia;
use App\Enums\Privacy;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\UserAvatarController;
use App\Http\Controllers\Settings\PrivacySettingsController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/avatar', [UserAvatarController::class, 'update'])->name('profile.avatar.update');
    Route::delete('profile/avatar', [UserAvatarController::class, 'destroy'])->name('profile.avatar.destroy');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance.edit');

    Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');

    Route::get('settings/privacy', function () {
        return Inertia::render('settings/Privacy', [
            'privacy' => array_map(fn($p) => $p->value, Privacy::cases()),
            'defaultVisibility' => auth()->user()->activities_visibility,
        ]);
    })->name('privacy.edit');

    Route::patch('settings/privacy', [PrivacySettingsController::class, 'update'])->name('privacy.update');
});
