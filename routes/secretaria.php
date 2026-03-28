<?php

use App\Http\Controllers\Secretaria\DashboardController;
use App\Http\Controllers\Secretaria\SchoolClassController;
use App\Http\Controllers\Secretaria\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretaria\DocumentController;
use App\Http\Controllers\Secretaria\UserController;
use App\Http\Controllers\Secretaria\DocumentPdfController;
use App\Http\Controllers\Secretaria\ObservationController;
use App\Http\Controllers\Secretaria\DocumentWordController;
use App\Http\Controllers\Secretaria\AllDocumentsController;
use App\Http\Controllers\Secretaria\LaudoController;



Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');

Route::resource('turmas', SchoolClassController::class);
Route::resource('alunos', StudentController::class);
Route::resource('alunos.documentos', DocumentController::class)
    ->shallow();
Route::resource('usuarios', UserController::class)->except(['show']);
Route::post('alunos/{aluno}/turma', [StudentController::class, 'attachClass'])->name('alunos.attachClass');
Route::get('documentos/{documento}/pdf', [DocumentPdfController::class, '__invoke'])
    ->name('documentos.pdf');
Route::post('alunos/{aluno}/observacoes', [ObservationController::class, 'store'])->name('alunos.observacoes.store');
Route::delete('observacoes/{observation}', [ObservationController::class, 'destroy'])->name('observacoes.destroy');
Route::get('documentos/{documento}/word', DocumentWordController::class)
    ->name('documentos.word');
Route::post('alunos/{aluno}/laudos', [LaudoController::class, 'store'])->name('alunos.laudos.store');
Route::get('laudos/{laudo}/download', [LaudoController::class, 'download'])->name('laudos.download');
Route::delete('laudos/{laudo}', [LaudoController::class, 'destroy'])->name('laudos.destroy');
Route::get('laudos', [LaudoController::class, 'index'])->name('laudos.index');
Route::get('documentos', AllDocumentsController::class)->name('documentos.index');
