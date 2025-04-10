<?php

/**
 * This file contains the routes for the tools.
 */

use App\Http\Controllers\ProductionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:production_dashboard'])->group(function () {
    Route::get('/production/home', [ProductionController::class, 'Home'])->name('production.home');
    Route::post('/production/tecnico/{otId}/ot', [ProductionController::class, 'tecnicoOt'])->name('production.tecnico.ot');
    Route::post('/production/liberacion/{otId}/ot', [ProductionController::class, 'liberacionOt'])->name('production.liberacion.ot');

    // Route::post('/compras/oc/store', [ProductionController::class, 'store'])->name('compras.oc.store');
    // Route::post('/compras/material/{materialId}/oc', [ProductionController::class, 'materialOc'])->name('compras.material.oc');
    // Route::get('/compras/oc/{ocId}/materials', [ProductionController::class, 'getMaterials'])->name('compras.oc.materials');
    // Route::get('/compras/oc/{ocId}/pdf', [ProductionController::class, 'ocPdf'])->name('compras.oc.pdf');


    });
});
