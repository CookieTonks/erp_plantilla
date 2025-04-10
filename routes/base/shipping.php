<?php

/**
 * This file contains the routes for the tools.
 */

use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:embarques_dashboard'])->group(function () {
        Route::get('/shipping/home', [ShippingController::class, 'Home'])->name('shipping.home');
        Route::post('/shipping/ot/{id}/salida_factura', [ShippingController::class, 'salida_factura'])->name('shipping.ot.salida_factura');
        Route::post('/shipping/ot/{id}/salida_remision', [ShippingController::class, 'salida_remision'])->name('shipping.ot.salida_remision');
        Route::get('/historial/{id}', [ShippingController::class, 'showHistorial']);
        Route::get('/shipping/entregas', [ShippingController::class, 'Entregas'])->name('shipping.entregas');
        Route::post('/shipping/entregas/{id}', [ShippingController::class, 'cargaSalida'])->name('shipping.cargaSalida');


    });
});
