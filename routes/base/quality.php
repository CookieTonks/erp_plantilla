<?php

/**
 * This file contains the routes for the tools.
 */

use App\Http\Controllers\QualityController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:calidad_dashboard'])->group(function () {
        Route::get('/quality/home', [QualityController::class, 'Home'])->name('quality.home');
        Route::post('/quality/ot/{id}/liberacion', [QualityController::class, 'liberacion'])->name('quality.ot.liberacion');
        Route::get('/quality/ot/{id}/rechazo', [QualityController::class, 'rechazo'])->name('quality.ot.rechazo');

    });
});
