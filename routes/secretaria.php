<?php

use App\Http\Controllers\Secretaria\DashboardController;
use App\Http\Controllers\Secretaria\SchoolClassController;
use App\Http\Controllers\Secretaria\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secretaria\DocumentController;
use App\Http\Controllers\Secretaria\UserController;
use App\Http\Controllers\Secretaria\DocumentPdfController;
use App\Http\Controllers\Secretaria\DocumentPreviewController;
use App\Http\Controllers\Secretaria\ObservationController;
use App\Http\Controllers\Secretaria\DocumentWordController;
use App\Http\Controllers\Secretaria\AllDocumentsController;
use App\Http\Controllers\Secretaria\DocumentoFinalController;
use App\Http\Controllers\Secretaria\LaudoController;
use App\Http\Controllers\Secretaria\RotinaAdaptacoesController;
use App\Http\Controllers\Secretaria\Rotinas\DocumentosHubController;
use App\Http\Controllers\Secretaria\Rotinas\RotinaDocumentoListController;
use App\Http\Controllers\Secretaria\Config\ConfigController;
use App\Http\Controllers\Secretaria\Config\SchoolRoleController;
use App\Http\Controllers\Secretaria\PeiConsolidadoController;
use App\Http\Controllers\Secretaria\LogController;
use App\Http\Controllers\Secretaria\SeletividadeController;
use App\Http\Controllers\Secretaria\SubjectController;
use App\Http\Controllers\Secretaria\MeetingController;



Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
Route::middleware('school.module:painel')->group(function () {
    Route::get('/painel',    [DashboardController::class, 'painel'])->name('painel');
});

// Configurações — requer permissão escola.configurar
Route::middleware(['school.module:configuracoes', 'can:escola.configurar'])->prefix('config')->name('config.')->group(function () {
    Route::get('/',              [ConfigController::class, 'index'])->name('index');
    Route::put('/escola',        [ConfigController::class, 'updateEscola'])->name('escola.update');
    Route::put('/terminologias', [ConfigController::class, 'updateTerminologias'])->name('terminologias.update');
    Route::put('/bimestres',     [ConfigController::class, 'updateBimestres'])->name('bimestres.update');
    Route::resource('perfis', SchoolRoleController::class)->except(['show'])->parameters(['perfis' => 'perfil']);
});

// Matérias da grade curricular — gerenciadas na aba Configurações → Matérias
Route::middleware(['school.module:configuracoes', 'can:escola.configurar'])
    ->prefix('config/materias')->name('subjects.')->group(function () {
        Route::get('/create',         [SubjectController::class, 'create'])->name('create');
        Route::post('/',              [SubjectController::class, 'store'])->name('store');
        Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit');
        Route::put('/{subject}',      [SubjectController::class, 'update'])->name('update');
        Route::delete('/{subject}',   [SubjectController::class, 'destroy'])->name('destroy');
    });

// ─── Turmas ───────────────────────────────────────────────────────────────────
Route::middleware('school.module:turmas')->group(function () {
    Route::middleware('can:turmas.gerenciar')->group(function () {
        // Virada do ano (promoção assistida) — antes das rotas com {turma}
        Route::get('turmas/virada',           [\App\Http\Controllers\Secretaria\YearTransitionController::class, 'index'])->name('turmas.virada');
        Route::post('turmas/virada',          [\App\Http\Controllers\Secretaria\YearTransitionController::class, 'confirmar'])->name('turmas.virada.confirmar');
        Route::get('turmas/create',           [SchoolClassController::class, 'create'])->name('turmas.create');
        Route::post('turmas',                 [SchoolClassController::class, 'store'])->name('turmas.store');
        Route::get('turmas/{turma}/edit',     [SchoolClassController::class, 'edit'])->name('turmas.edit');
        Route::put('turmas/{turma}',          [SchoolClassController::class, 'update'])->name('turmas.update');
        Route::patch('turmas/{turma}',        [SchoolClassController::class, 'update']);
        Route::delete('turmas/{turma}',       [SchoolClassController::class, 'destroy'])->name('turmas.destroy');
    });
    Route::middleware('can:turmas.ver')->group(function () {
        Route::get('turmas',              [SchoolClassController::class, 'index'])->name('turmas.index');
    });
});

// ─── Alunos ───────────────────────────────────────────────────────────────────
Route::middleware('school.module:alunos')->group(function () {
    Route::middleware('can:alunos.criar')->group(function () {
        Route::get('alunos/create',   [StudentController::class, 'create'])->name('alunos.create');
        Route::post('alunos',         [StudentController::class, 'store'])->name('alunos.store');
    });
    Route::middleware('can:alunos.ver')->group(function () {
        Route::get('alunos',              [StudentController::class, 'index'])->name('alunos.index');
        // Rotina "Linha do Tempo" (roadmap de evolução) — antes de alunos/{aluno}
        Route::get('rotinas/linha-do-tempo', \App\Http\Controllers\Secretaria\Rotinas\LinhaDoTempoHubController::class)->name('rotinas.linha-do-tempo');
        Route::get('alunos/{aluno}/linha-do-tempo', [\App\Http\Controllers\Secretaria\LinhaDoTempoController::class, 'show'])->name('alunos.linha-do-tempo');
        Route::get('alunos/{aluno}',      [StudentController::class, 'show'])->name('alunos.show');
    });
    Route::middleware('can:alunos.editar')->group(function () {
        Route::get('alunos/{aluno}/edit',  [StudentController::class, 'edit'])->name('alunos.edit');
        Route::put('alunos/{aluno}',       [StudentController::class, 'update'])->name('alunos.update');
        Route::patch('alunos/{aluno}',     [StudentController::class, 'update']);
        Route::post('alunos/{aluno}/turma',[StudentController::class, 'attachClass'])->name('alunos.attachClass');
        Route::post('alunos/{aluno}/foto', [StudentController::class, 'uploadPhoto'])->name('alunos.uploadPhoto');
        Route::delete('alunos/{aluno}/foto', [StudentController::class, 'removePhoto'])->name('alunos.removePhoto');

        // Rotina "Reuniões / Atas" — hub que lista os alunos
        Route::get('rotinas/reunioes', \App\Http\Controllers\Secretaria\Rotinas\ReunioesHubController::class)->name('rotinas.reunioes');

        // Registro de reuniões do aluno
        Route::get('alunos/{aluno}/reunioes',                 [MeetingController::class, 'index'])->name('alunos.reunioes.index');
        Route::get('alunos/{aluno}/reunioes/create',          [MeetingController::class, 'create'])->name('alunos.reunioes.create');
        Route::post('alunos/{aluno}/reunioes',                [MeetingController::class, 'store'])->name('alunos.reunioes.store');
        Route::get('alunos/{aluno}/reunioes/{reuniao}/edit',  [MeetingController::class, 'edit'])->name('alunos.reunioes.edit');
        Route::put('alunos/{aluno}/reunioes/{reuniao}',       [MeetingController::class, 'update'])->name('alunos.reunioes.update');
        Route::delete('alunos/{aluno}/reunioes/{reuniao}',    [MeetingController::class, 'destroy'])->name('alunos.reunioes.destroy');
    });
    Route::middleware('can:alunos.deletar')->group(function () {
        Route::delete('alunos/{aluno}', [StudentController::class, 'destroy'])->name('alunos.destroy');
    });
});

// ─── Documentos ───────────────────────────────────────────────────────────────
Route::middleware('school.module:documentos')->group(function () {
    Route::middleware('can:documentos.ver_todos')->group(function () {
        Route::get('documentos', AllDocumentsController::class)->name('documentos.index');
        Route::get('documentos/{documento}/pdf',     [DocumentPdfController::class, '__invoke'])->name('documentos.pdf');
        Route::get('documentos/{documento}/preview', DocumentPreviewController::class)->name('documentos.preview');
        Route::get('documentos/{documento}/word',    DocumentWordController::class)->name('documentos.word');
        Route::get('rotinas/documentos', DocumentosHubController::class)->name('rotinas.documentos.index');
        Route::get('rotinas/documentos/estudo-caso', [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'estudo_caso')->name('rotinas.documentos.estudo-caso');
        Route::get('rotinas/documentos/paee',         [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'paee')->name('rotinas.documentos.paee');
        Route::get('rotinas/documentos/pei',          [RotinaDocumentoListController::class, '__invoke'])->defaults('tipo', 'pei')->name('rotinas.documentos.pei');
        Route::get('alunos/{aluno}/documento-final', DocumentoFinalController::class)->name('alunos.documento-final');
        Route::get('alunos/{aluno}/pei-consolidado', [PeiConsolidadoController::class, 'edit'])->name('alunos.pei-consolidado');
        Route::put('alunos/{aluno}/pei-consolidado', [PeiConsolidadoController::class, 'update'])->name('alunos.pei-consolidado.update');
    });

    // Metas acadêmicas customizadas do aluno (cadastradas pelo admin/perfis com permissão, usadas no PEI)
    Route::middleware('can:pei.metas_gerenciar')->group(function () {
        Route::get('alunos/{aluno}/metas-academicas', [\App\Http\Controllers\Secretaria\StudentAcademicGoalController::class, 'edit'])->name('alunos.metas-academicas.edit');
        Route::put('alunos/{aluno}/metas-academicas', [\App\Http\Controllers\Secretaria\StudentAcademicGoalController::class, 'update'])->name('alunos.metas-academicas.update');

        // Evolução (acompanhamento bimestral) das metas do PEI — salva a matriz
        // incorporada na Linha do Tempo. A tela de edição vive na Linha do Tempo.
        Route::put('alunos/{aluno}/metas-evolucao', [\App\Http\Controllers\Secretaria\GoalProgressController::class, 'update'])->name('alunos.metas-evolucao.update');

        // Fechamento/reabertura de bimestre (congela resultado + trava avaliações + marco no roadmap)
        Route::post('alunos/{aluno}/bimestres/{bimestre}/fechar', [\App\Http\Controllers\Secretaria\BimestreClosingController::class, 'store'])->whereNumber('bimestre')->name('alunos.bimestres.fechar');
        Route::delete('alunos/{aluno}/bimestres/{bimestre}', [\App\Http\Controllers\Secretaria\BimestreClosingController::class, 'destroy'])->whereNumber('bimestre')->name('alunos.bimestres.reabrir');

        // Banco de metas reutilizáveis da escola
        Route::get('metas/banco', [\App\Http\Controllers\Secretaria\GoalTemplateController::class, 'edit'])->name('metas.banco.edit');
        Route::put('metas/banco', [\App\Http\Controllers\Secretaria\GoalTemplateController::class, 'update'])->name('metas.banco.update');
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
});

// ─── Laudos ───────────────────────────────────────────────────────────────────
Route::middleware(['school.module:alunos', 'can:laudos.anexar'])->group(function () {
    Route::get('laudos',                       [LaudoController::class, 'index'])->name('laudos.index');
    Route::post('alunos/{aluno}/laudos',       [LaudoController::class, 'store'])->name('alunos.laudos.store');
    Route::get('laudos/{laudo}/download',      [LaudoController::class, 'download'])->name('laudos.download');
    Route::delete('laudos/{laudo}',            [LaudoController::class, 'destroy'])->name('laudos.destroy');
});

// ─── Observações ──────────────────────────────────────────────────────────────
Route::middleware(['school.module:alunos', 'can:observacoes.criar'])->group(function () {
    Route::post('alunos/{aluno}/observacoes',       [ObservationController::class, 'store'])->name('alunos.observacoes.store');
    Route::delete('observacoes/{observation}',      [ObservationController::class, 'destroy'])->name('observacoes.destroy');
});

// ─── Usuários ─────────────────────────────────────────────────────────────────
Route::middleware('school.module:usuarios')->group(function () {
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
});


// ─── Adaptações para Prova ────────────────────────────────────────────────────
Route::middleware(['school.module:adaptacoes', 'can:adaptacoes.ver'])->group(function () {
    Route::get('rotinas/adaptacoes-prova', RotinaAdaptacoesController::class)->name('rotinas.adaptacoes');
});

// ─── Logs de acesso ───────────────────────────────────────────────────────────
Route::middleware(['school.module:configuracoes', 'can:escola.configurar'])->group(function () {
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
});

// ─── Seletividade Alimentar ───────────────────────────────────────────────────
Route::middleware('school.module:seletividade')->group(function () {
    Route::middleware('can:seletividade.ver')->group(function () {
        Route::get('seletividade',                         [SeletividadeController::class, 'index'])->name('seletividade.index');
        Route::get('seletividade/exportar',                [SeletividadeController::class, 'export'])->name('seletividade.export');
        Route::get('alunos/{aluno}/seletividade',          [SeletividadeController::class, 'show'])->name('seletividade.show');
        Route::get('alunos/{aluno}/seletividade/exportar', [SeletividadeController::class, 'exportIndividual'])->name('seletividade.export.individual');
    });
    Route::middleware('can:seletividade.gerenciar')->group(function () {
        Route::post('alunos/{aluno}/seletividade',         [SeletividadeController::class, 'store'])->name('seletividade.store');
        Route::delete('seletividade/{item}',               [SeletividadeController::class, 'destroy'])->name('seletividade.destroy');
    });
});
