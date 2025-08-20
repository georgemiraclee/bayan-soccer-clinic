<?php

use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;
use App\Exports\SekolahBolaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UserSekolahController;

// Route untuk form pendaftaran publik
Route::get('/', [PublicFormController::class, 'index'])->name('public.form.index');
Route::post('/daftar', [PublicFormController::class, 'store'])->name('public.form.store');

// Route untuk redirect ke admin setelah pendaftaran
Route::get('/admin-redirect/{id}', [PublicFormController::class, 'redirectToAdmin'])->name('admin.redirect');

// API Routes untuk statistik (opsional)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/stats', [PublicFormController::class, 'getStats'])->name('stats');
});

Route::get('/sekolahbola-export', function () {
    $ids = session('export_ids'); // ambil dari session
    if ($ids) {
        return Excel::download(new SekolahBolaExport, 'sekolah_bola.xlsx');
    }
    return back()->with('error', 'Tidak ada data untuk diexport.');
})->name('sekolahbola.export');

// UPDATED: User management routes menggunakan token UUID, bukan ID
Route::prefix('user/{userToken}')->name('user.sekolah.show')->group(function () {
    // Halaman utama user management
    Route::get('/', [UserSekolahController::class, 'show'])->name('show');
    
    // API untuk update data sekolah
    Route::put('/sekolah', [UserSekolahController::class, 'updateSekolah'])->name('update');
    
    // API untuk update data pemain
    Route::put('/pemain/{pemainId}', [UserSekolahController::class, 'updatePemain'])->name('pemain.update');
    
    // API untuk hapus pemain
    Route::delete('/pemain/{pemainId}', [UserSekolahController::class, 'deletePemain'])->name('pemain.delete');
});

Route::get('/user/{userToken}', [UserSekolahController::class, 'show'])
    ->name('user.sekolah.show')
    ->where('userToken', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

// DEPRECATED: Route lama dengan ID - bisa dihapus setelah migrasi
// Route::get('/user/{id}', [UserSekolahController::class, 'show'])->name('user.sekolah.show');