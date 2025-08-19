<?php

use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route untuk form pendaftaran publik
Route::get('/', [PublicFormController::class, 'index'])->name('public.form.index');
Route::post('/daftar', [PublicFormController::class, 'store'])->name('public.form.store');

// Route untuk redirect ke admin setelah pendaftaran
Route::get('/admin-redirect/{id}', [PublicFormController::class, 'redirectToAdmin'])->name('admin.redirect');

// API Routes untuk statistik (opsional)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/stats', [PublicFormController::class, 'getStats'])->name('stats');
});