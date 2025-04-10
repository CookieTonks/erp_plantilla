
<?php

/**
 * This file contains the routes for the tools.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetController;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:cotizaciones_dashboard'])->group(function () {
        Route::get('/budgets/home', [BudgetController::class, 'index'])->name('budgets.index');
        Route::post('/budgets/store', [BudgetController::class, 'store'])->name('budgets.store');
        Route::get('/budgets/show/{budgetId}', [BudgetController::class, 'show'])->name('budgets.show');
        Route::get('/budgets/make/{budgetId}', [BudgetController::class, 'make'])->name('budgets.make');
        Route::get('/budgets/edit/{budgetId}', [BudgetController::class, 'edit'])->name('budgets.edit');
        Route::post('/budgets/update/{budgetId}', [BudgetController::class, 'update'])->name('budgets.update');
        Route::delete('/budgets/delete/{budgetId}', [BudgetController::class, 'destroyBudget'])->name('budgets.destroy');
        Route::get('/budgets/rejected/{budgetId}', [BudgetController::class, 'rejectedBudget'])->name('budgets.rejected');
        Route::post('/budgets/assign-oc/{budgetId}', [BudgetController::class, 'assignOC'])->name('budgets.assignOC');
        Route::get('/getClientUsers/{clientId}', [BudgetController::class, 'getClientUsers']);


        Route::get('/budgets/{id}/items', [BudgetController::class, 'getItems']);


        Route::post('/item/store/{budgetId}', [BudgetController::class, 'storeItem'])->name('item.store');
        Route::delete('/items/delete/{itemId}', [BudgetController::class, 'destroyItem'])->name('item.destroy');
        Route::put('/item/update/{itemId}', [BudgetController::class, 'updateItem'])->name('item.update');
    });
});
