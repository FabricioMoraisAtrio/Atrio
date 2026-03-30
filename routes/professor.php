<?php

use App\Http\Controllers\Professor\DashboardController;
use App\Http\Controllers\Professor\DocumentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Professor\DocumentPdfController;
use App\Http\Controllers\Professor\ObservationController;
use App\Http\Controllers\Professor\StudentController;
use App\Http\Controllers\Professor\DocumentWordController;
use App\Http\Controllers\Professor\SchoolClassController;
Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');

Route::get('/turmas', [SchoolClassController::class, 'index'])->name('turmas.index');
Route::get('/turmas/{turma}', [SchoolClassController::class, 'show'])->name('turmas.show');

Route::get('/documentos', [DocumentController::class, 'index'])->name('documentos.index');

Route::resource('alunos.documentos', DocumentController::class)
    ->shallow()
    ->only(['create', 'store', 'show', 'edit', 'update']);

Route::get('documentos/{documento}/pdf', [DocumentPdfController::class, '__invoke'])
    ->name('documentos.pdf');
Route::post('alunos/{aluno}/observacoes', [ObservationController::class, 'store'])->name('alunos.observacoes.store');
Route::delete('observacoes/{observation}', [ObservationController::class, 'destroy'])->name('observacoes.destroy');
Route::get('alunos/{aluno}', [StudentController::class, 'show'])->name('alunos.show');
Route::get('documentos/{documento}/word', DocumentWordController::class)
    ->name('documentos.word');