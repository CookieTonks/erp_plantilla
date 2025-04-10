
<?php

/**
 * This file contains the routes for the tools.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolePermissionController;

Route::group(['middleware' => ['auth']], function () {
    // Route::middleware(['can:ver_permisos'])->group(function () {
        Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.home');
        Route::post('/roles/store', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::post('/roles/assign', [RolePermissionController::class, 'assignRole'])->name('roles.assign');
        Route::post('/roles/assign-permission', [RolePermissionController::class, 'assignPermission'])->name('roles.assign-permission');
        Route::post('/roles/remove', [RolePermissionController::class, 'removeRole'])->name('roles.remove');
        Route::post('/permissions/remove', [RolePermissionController::class, 'removePermission'])->name('permissions.remove');
        Route::post('/permissions/create', [RolePermissionController::class, 'createPermission'])->name('permissions.create');
        Route::post('/permissions/give', [RolePermissionController::class, 'givePermissionToUser'])->name('permissions.give');
        Route::post('/permissions/revoke', [RolePermissionController::class, 'revokePermissionFromUser'])->name('permissions.revoke');
    // });
});
