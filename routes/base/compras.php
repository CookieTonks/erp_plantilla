<?php

/**
 * This file contains the routes for the tools.
 */

use App\Http\Controllers\ComprasController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:compras_dashboard'])->group(function () {
    Route::get('/compras/home', [ComprasController::class, 'Home'])->name('compras.home');
    Route::post('/compras/oc/store', [ComprasController::class, 'store'])->name('compras.oc.store');
    Route::post('/compras/material/{materialId}/oc', [ComprasController::class, 'materialOc'])->name('compras.material.oc');
    Route::get('/compras/oc/{ocId}/materials', [ComprasController::class, 'getMaterials'])->name('compras.oc.materials');
    Route::get('/compras/oc/{ocId}/pdf', [ComprasController::class, 'ocPdf'])->name('compras.oc.pdf');

    });
});
