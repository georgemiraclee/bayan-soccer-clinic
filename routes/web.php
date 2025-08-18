<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicFormController;

Route::get('/', [PublicFormController::class, 'index'])->name('public.form');
Route::post('/', [PublicFormController::class, 'store'])->name('public.form.store');
