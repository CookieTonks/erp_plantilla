
<?php

/**
 * This file contains the routes for the tools.
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::group(['middleware' => ['auth']], function () {
    Route::middleware(['can:ver_usuarios'])->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
