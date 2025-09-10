<?php
use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;
use App\Exports\SekolahBolaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UserSekolahController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/daftar');
});

// Event Lari Routes - Public Form
Route::get('/daftar', [PublicFormController::class, 'index'])->name('event.form');
Route::post('/daftar', [PublicFormController::class, 'store'])->name('event.store');

// Admin Routes - Dashboard & Management
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [PublicFormController::class, 'showRegistrations'])->name('registrations.index');
    Route::get('/peserta', [PublicFormController::class, 'showRegistrations'])->name('registrations.index');
    Route::get('/peserta/{id}', [PublicFormController::class, 'showDetail'])->name('registrations.detail');
    Route::get('/export', [PublicFormController::class, 'export'])->name('export');
});

// API Routes - Statistics & QR Verification
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/stats', [PublicFormController::class, 'getStats'])->name('stats');
    Route::get('/verify/{token}', [PublicFormController::class, 'verifyQR'])->name('qr.verify');
});

// Alternative route for QR verification (shorter URL)
Route::get('/verify/{token}', [PublicFormController::class, 'verifyQR'])->name('qr.verify.short');