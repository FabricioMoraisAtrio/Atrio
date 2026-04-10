<?php

use App\Http\Controllers\Professor\DashboardController;
use App\Http\Controllers\Professor\DocumentController;
use App\Http\Controllers\Professor\DocumentPdfController;
use App\Http\Controllers\Professor\ObservationController;
use App\Http\Controllers\Professor\StudentController;
use App\Http\Controllers\Professor\DocumentWordController;
use App\Http\Controllers\Professor\SchoolClassController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
Route::get('/painel',    [DashboardController::class, 'painel'])->name('painel');

Route::get('/turmas',        [SchoolClassController::class, 'index'])->name('turmas.index');
Route::get('/turmas/{turma}',[SchoolClassController::class, 'show'])->name('turmas.show');

// PEI — professor edita sua seção no documento único do aluno
Route::get('alunos/{aluno}/pei',  [DocumentController::class, 'editPei'])->name('alunos.pei.edit');
Route::put('alunos/{aluno}/pei',  [DocumentController::class, 'updatePei'])->name('alunos.pei.update');

Route::get('documentos/{documento}/pdf',  [DocumentPdfController::class, '__invoke'])->name('documentos.pdf');
Route::get('documentos/{documento}/word', DocumentWordController::class)->name('documentos.word');

Route::post('alunos/{aluno}/observacoes',  [ObservationController::class, 'store'])->name('alunos.observacoes.store');
Route::delete('observacoes/{observation}', [ObservationController::class, 'destroy'])->name('observacoes.destroy');

Route::get('alunos/{aluno}', [StudentController::class, 'show'])->name('alunos.show');
