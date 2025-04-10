<?php

/**
 * This file contains the routes for the tools.
 */

use App\Http\Controllers\AdministrationController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BudgetClosed;
use App\Exports\BudgetGeneral;
use App\Exports\BudgetOpen;
use App\Exports\BudgetRejected;




Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:administracion_dashboard'])->group(function () {
        Route::get('/administration/home', [AdministrationController::class, 'home'])->name('administration.home');

        Route::get('/export-closed-budgets', function () {
            return Excel::download(new BudgetClosed, 'closed_budgets.xlsx');
        })->name('export.closed.budgets');

        Route::get('/export-open-budgets', function () {
            return Excel::download(new BudgetOpen, 'open_budgets.xlsx');
        })->name('export.open.budgets');

        Route::get('/export-rejected-budgets', function () {
            return Excel::download(new BudgetRejected, 'rejected_budgets.xlsx');
        })->name('export.rejected.budgets');

        Route::get('/export-general-budgets', function () {
            return Excel::download(new BudgetGeneral, 'general_budgets.xlsx');
        })->name('export.general.budgets');


        Route::post('/dashboard/administrador/cliente', [AdministrationController::class, 'cliente'])->name('administracion.cliente');
        Route::post('/dashboard/administrador/clienteUsuario', [AdministrationController::class, 'clienteUsuario'])->name('administracion.clienteUsuario');


        Route::post('/dashboard/administrador/proveedor', [AdministrationController::class, 'proveedor'])->name('administracion.proveedor');
        Route::post('/dashboard/administrador/empleado', [AdministrationController::class, 'empleado'])->name('administracion.empleado');
    });
});
