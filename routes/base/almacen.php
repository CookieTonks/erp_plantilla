
<?php

/**
 * This file contains the routes for the tools.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlmacenController;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:almacen_dashboard'])->group(function () {
        Route::get('/almacen/home', [AlmacenController::class, 'home'])->name('almacen.home');
        Route::get('/almacen/material/{materialId}/receive', [AlmacenController::class, 'check'])->name('almacen.material.check');
        Route::post('/almacen/material/add', [AlmacenController::class, 'askMaterial'])->name('almacen.material.ask');
    });
});
