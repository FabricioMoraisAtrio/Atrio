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
use App\Http\Controllers\Secretaria\PeiConsolidadoController;
use App\Http\Controllers\Secretaria\LaudoController;
use App\Http\Controllers\Secretaria\RotinaAdaptacoesController;
use App\Http\Controllers\Secretaria\Rotinas\DocumentosHubController;
use App\Http\Controllers\Secretaria\Rotinas\RotinaDocumentoListController;
use App\Http\Controllers\Secretaria\SubjectController;
use App\Http\Controllers\Secretaria\Config\ConfigController;
use App\Http\Controllers\Secretaria\Config\SchoolRoleController;



Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');

// Configurações — admin only
Route::middleware('role:admin')->prefix('config')->name('config.')->group(function () {
    Route::get('/',                    [ConfigController::class, 'index'])->name('index');
    Route::put('/escola',              [ConfigController::class, 'updateEscola'])->name('escola.update');
    Route::put('/terminologias',       [ConfigController::class, 'updateTerminologias'])->name('terminologias.update');
    Route::resource('perfis', SchoolRoleController::class)->except(['show'])->parameters(['perfis' => 'perfil']);
});

Route::resource('turmas', SchoolClassController::class);
Route::resource('alunos', StudentController::class);
Route::resource('alunos.documentos', DocumentController::class)
    ->shallow();
Route::resource('usuarios', UserController::class)->except(['show']);
Route::post('alunos/{aluno}/turma', [StudentController::class, 'attachClass'])->name('alunos.attachClass');
Route::post('alunos/{aluno}/foto', [StudentController::class, 'uploadPhoto'])->name('alunos.uploadPhoto');
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
Route::get('alunos/{aluno}/documento-final', DocumentoFinalController::class)->name('alunos.documento-final');
Route::get('alunos/{aluno}/pei-consolidado',  [PeiConsolidadoController::class, 'edit'])->name('alunos.pei-consolidado');
Route::post('alunos/{aluno}/pei-consolidado', [PeiConsolidadoController::class, 'update'])->name('alunos.pei-consolidado.update');
Route::get('rotinas/adaptacoes-prova', RotinaAdaptacoesController::class)->name('rotinas.adaptacoes');

// Rotina Documentos — hub + seções
Route::get('rotinas/documentos', DocumentosHubController::class)->name('rotinas.documentos.index');
Route::get('rotinas/documentos/estudo-caso',          [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'estudo_caso')->name('rotinas.documentos.estudo-caso');
Route::get('rotinas/documentos/paee',                 [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'paee')->name('rotinas.documentos.paee');
Route::get('rotinas/documentos/pei',                  [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'pei')->name('rotinas.documentos.pei');
Route::get('rotinas/documentos/atendimentos',         [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'atendimento')->name('rotinas.documentos.atendimentos');

Route::resource('materias', SubjectController::class)
    ->names('subjects')
    ->parameters(['materias' => 'subject']);
Route::post('materias/{subject}/metas', [SubjectController::class, 'saveItems'])->name('subjects.saveItems');
