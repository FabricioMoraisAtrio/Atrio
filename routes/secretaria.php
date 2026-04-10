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
use App\Http\Controllers\Secretaria\DocumentoFinalController;
use App\Http\Controllers\Secretaria\LaudoController;
use App\Http\Controllers\Secretaria\RotinaAdaptacoesController;
use App\Http\Controllers\Secretaria\Rotinas\DocumentosHubController;
use App\Http\Controllers\Secretaria\Rotinas\RotinaDocumentoListController;
use App\Http\Controllers\Secretaria\SubjectController;
use App\Http\Controllers\Secretaria\Config\ConfigController;
use App\Http\Controllers\Secretaria\Config\SchoolRoleController;
use App\Http\Controllers\Secretaria\PeiConsolidadoController;



Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
Route::get('/painel',    [DashboardController::class, 'painel'])->name('painel');

// Configurações — requer permissão escola.configurar
Route::middleware('can:escola.configurar')->prefix('config')->name('config.')->group(function () {
    Route::get('/',              [ConfigController::class, 'index'])->name('index');
    Route::put('/escola',        [ConfigController::class, 'updateEscola'])->name('escola.update');
    Route::put('/terminologias', [ConfigController::class, 'updateTerminologias'])->name('terminologias.update');
    Route::resource('perfis', SchoolRoleController::class)->except(['show'])->parameters(['perfis' => 'perfil']);
});

// ─── Turmas ───────────────────────────────────────────────────────────────────
Route::middleware('can:turmas.gerenciar')->group(function () {
    Route::get('turmas/create',           [SchoolClassController::class, 'create'])->name('turmas.create');
    Route::post('turmas',                 [SchoolClassController::class, 'store'])->name('turmas.store');
    Route::get('turmas/{turma}/edit',     [SchoolClassController::class, 'edit'])->name('turmas.edit');
    Route::put('turmas/{turma}',          [SchoolClassController::class, 'update'])->name('turmas.update');
    Route::patch('turmas/{turma}',        [SchoolClassController::class, 'update']);
    Route::delete('turmas/{turma}',       [SchoolClassController::class, 'destroy'])->name('turmas.destroy');
});
Route::middleware('can:turmas.ver')->group(function () {
    Route::get('turmas',              [SchoolClassController::class, 'index'])->name('turmas.index');
    Route::get('turmas/{turma}',      [SchoolClassController::class, 'show'])->name('turmas.show');
});

// ─── Alunos ───────────────────────────────────────────────────────────────────
Route::middleware('can:alunos.criar')->group(function () {
    Route::get('alunos/create',   [StudentController::class, 'create'])->name('alunos.create');
    Route::post('alunos',         [StudentController::class, 'store'])->name('alunos.store');
});
Route::middleware('can:alunos.ver')->group(function () {
    Route::get('alunos',              [StudentController::class, 'index'])->name('alunos.index');
    Route::get('alunos/{aluno}',      [StudentController::class, 'show'])->name('alunos.show');
});
Route::middleware('can:alunos.editar')->group(function () {
    Route::get('alunos/{aluno}/edit',  [StudentController::class, 'edit'])->name('alunos.edit');
    Route::put('alunos/{aluno}',       [StudentController::class, 'update'])->name('alunos.update');
    Route::patch('alunos/{aluno}',     [StudentController::class, 'update']);
    Route::post('alunos/{aluno}/turma',[StudentController::class, 'attachClass'])->name('alunos.attachClass');
    Route::post('alunos/{aluno}/foto', [StudentController::class, 'uploadPhoto'])->name('alunos.uploadPhoto');
});
Route::middleware('can:alunos.deletar')->group(function () {
    Route::delete('alunos/{aluno}', [StudentController::class, 'destroy'])->name('alunos.destroy');
});

// ─── Documentos ───────────────────────────────────────────────────────────────
Route::middleware('can:documentos.ver_todos')->group(function () {
    Route::get('documentos', AllDocumentsController::class)->name('documentos.index');
    Route::get('documentos/{documento}/pdf',  [DocumentPdfController::class, '__invoke'])->name('documentos.pdf');
    Route::get('documentos/{documento}/word', DocumentWordController::class)->name('documentos.word');
    Route::get('rotinas/documentos', DocumentosHubController::class)->name('rotinas.documentos.index');
    Route::get('rotinas/documentos/estudo-caso', [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'estudo_caso')->name('rotinas.documentos.estudo-caso');
    Route::get('rotinas/documentos/paee',         [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'paee')->name('rotinas.documentos.paee');
    Route::get('rotinas/documentos/pei',          [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'pei')->name('rotinas.documentos.pei');
    Route::get('alunos/{aluno}/documento-final', DocumentoFinalController::class)->name('alunos.documento-final');
    Route::get('alunos/{aluno}/pei-consolidado', [PeiConsolidadoController::class, 'edit'])->name('alunos.pei-consolidado');
    Route::put('alunos/{aluno}/pei-consolidado', [PeiConsolidadoController::class, 'update'])->name('alunos.pei-consolidado.update');
});

// Documentos por aluno — requer visualização de documentos
Route::middleware('can:pei.ver')->group(function () {
    Route::get('alunos/{aluno}/documentos/create',           [DocumentController::class, 'create'])->name('alunos.documentos.create');
    Route::get('alunos/{aluno}/documentos',                  [DocumentController::class, 'index'])->name('alunos.documentos.index');
    Route::get('alunos/{aluno}/documentos/{documento}',      [DocumentController::class, 'show'])->name('alunos.documentos.show');
    Route::get('documentos/{documento}',                     [DocumentController::class, 'show'])->name('documentos.show');
    Route::post('alunos/{aluno}/documentos',                 [DocumentController::class, 'store'])->name('alunos.documentos.store');
    Route::get('alunos/{aluno}/documentos/{documento}/edit', [DocumentController::class, 'edit'])->name('alunos.documentos.edit');
    Route::put('alunos/{aluno}/documentos/{documento}',      [DocumentController::class, 'update'])->name('alunos.documentos.update');
    Route::patch('alunos/{aluno}/documentos/{documento}',    [DocumentController::class, 'update']);
    Route::delete('alunos/{aluno}/documentos/{documento}',   [DocumentController::class, 'destroy'])->name('alunos.documentos.destroy');
    Route::get('documentos/{documento}/edit',                [DocumentController::class, 'edit'])->name('documentos.edit');
    Route::put('documentos/{documento}',                     [DocumentController::class, 'update'])->name('documentos.update');
    Route::patch('documentos/{documento}',                   [DocumentController::class, 'update']);
    Route::delete('documentos/{documento}',                  [DocumentController::class, 'destroy'])->name('documentos.destroy');
});

// ─── Laudos ───────────────────────────────────────────────────────────────────
Route::middleware('can:laudos.anexar')->group(function () {
    Route::get('laudos',                       [LaudoController::class, 'index'])->name('laudos.index');
    Route::post('alunos/{aluno}/laudos',       [LaudoController::class, 'store'])->name('alunos.laudos.store');
    Route::get('laudos/{laudo}/download',      [LaudoController::class, 'download'])->name('laudos.download');
    Route::delete('laudos/{laudo}',            [LaudoController::class, 'destroy'])->name('laudos.destroy');
});

// ─── Observações ──────────────────────────────────────────────────────────────
Route::middleware('can:observacoes.criar')->group(function () {
    Route::post('alunos/{aluno}/observacoes',       [ObservationController::class, 'store'])->name('alunos.observacoes.store');
    Route::delete('observacoes/{observation}',      [ObservationController::class, 'destroy'])->name('observacoes.destroy');
});

// ─── Usuários ─────────────────────────────────────────────────────────────────
Route::middleware('can:usuarios.ver')->group(function () {
    Route::get('usuarios', [UserController::class, 'index'])->name('usuarios.index');
});
Route::middleware('can:usuarios.criar')->group(function () {
    Route::get('usuarios/create',  [UserController::class, 'create'])->name('usuarios.create');
    Route::post('usuarios',        [UserController::class, 'store'])->name('usuarios.store');
});
Route::middleware('can:usuarios.editar')->group(function () {
    Route::get('usuarios/{usuario}/edit',  [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{usuario}',       [UserController::class, 'update'])->name('usuarios.update');
    Route::patch('usuarios/{usuario}',     [UserController::class, 'update']);
});
Route::middleware('can:usuarios.deletar')->group(function () {
    Route::delete('usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});

// ─── Matérias ─────────────────────────────────────────────────────────────────
Route::middleware('can:materias.gerenciar')->group(function () {
    Route::resource('materias', SubjectController::class)
        ->names('subjects')
        ->parameters(['materias' => 'subject']);
    Route::post('materias/{subject}/metas', [SubjectController::class, 'saveItems'])->name('subjects.saveItems');
});

// ─── Adaptações para Prova ────────────────────────────────────────────────────
Route::get('rotinas/adaptacoes-prova', RotinaAdaptacoesController::class)->name('rotinas.adaptacoes');
