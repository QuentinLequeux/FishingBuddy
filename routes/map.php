<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Map\MapController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/map', [MapController::class, 'show'])->name('map');

    Route::post('/map', [MapController::class, 'store'])->name('map.store');

    Route::post('/map/{spot}', [MapController::class, 'update'])->name('map.update');

    Route::patch('/map/{spot}/position', [MapController::class, 'updatePosition'])->name('map.position.update');

    Route::delete('/map/{spot}', [MapController::class, 'destroy'])->name('map.destroy');

    Route::post('/map/{spot}/restore', [MapController::class, 'restore'])->name('map.restore');
});
