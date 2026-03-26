<?php

use App\Http\Controllers\Pai\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pai\DocumentPdfController;

Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
Route::get('documentos/{documento}/pdf', DocumentPdfController::class)
    ->name('documentos.pdf');