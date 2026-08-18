<?php

use App\Http\Controllers\Professor\DocumentController;
use App\Http\Controllers\Professor\DocumentPdfController;
use App\Http\Controllers\Professor\ObservationController;
use App\Http\Controllers\Professor\DocumentWordController;
use Illuminate\Support\Facades\Route;

// Leitura (menu, painel, turmas, perfil do estudante) migrou para o portal unificado
// (secretaria.*), escopada por permissão. Aqui ficam só os fluxos ainda específicos
// do professor: edição do PEI por matéria, exportações e observações.

// PEI — professor edita sua seção no documento único do aluno
Route::get('alunos/{aluno}/pei',  [DocumentController::class, 'editPei'])->name('alunos.pei.edit');
Route::put('alunos/{aluno}/pei',  [DocumentController::class, 'updatePei'])->name('alunos.pei.update');

Route::get('documentos/{documento}/pdf',  [DocumentPdfController::class, '__invoke'])->name('documentos.pdf');
Route::get('documentos/{documento}/word', DocumentWordController::class)->name('documentos.word');

Route::post('alunos/{aluno}/observacoes',  [ObservationController::class, 'store'])->name('alunos.observacoes.store');
Route::delete('observacoes/{observation}', [ObservationController::class, 'destroy'])->name('observacoes.destroy');
