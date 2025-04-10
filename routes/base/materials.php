
<?php

/**
 * This file contains the routes for the tools.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:materiales_dashboard'])->group(function () {
    Route::get('/materiales/home', [MaterialController::class, 'index'])->name('materiales.home');
    Route::get('/budgets/{budgetId}/materials', [MaterialController::class, 'getMaterials'])->name('budgets.materials');
    Route::get('/budgets/show/{budgetId}/materials', [MaterialController::class, 'show'])->name('budgets.show.materials');
    Route::post('/budgets/create/{budgetId}/materials', [MaterialController::class, 'store'])->name('budgets.create.materials');
    Route::delete('/budgets/destroy/{materialId}/materials', [MaterialController::class, 'destroy'])->name('budgets.destroy.materials');
    Route::put('/budgets/edit/{materialId}/materials', [MaterialController::class, 'update'])->name('budgets.edit.materials');

    });
});
