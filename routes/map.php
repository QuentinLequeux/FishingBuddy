<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/map', [MapController::class, 'show'])->name('map');

    Route::post('/map', [MapController::class, 'store'])->name('map.store');

    Route::delete('/map/{spot}', [MapController::class, 'destroy'])->name('map.destroy');
});
