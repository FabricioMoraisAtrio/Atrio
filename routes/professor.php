<?php

use App\Http\Controllers\Professor\DocumentPdfController;
use App\Http\Controllers\Professor\ObservationController;
use App\Http\Controllers\Professor\DocumentWordController;
use Illuminate\Support\Facades\Route;

// Leitura e edição do PEI migraram para o portal unificado (secretaria.*), escopadas
// por permissão. Aqui ficam só fluxos ainda específicos do professor: exportações e
// observações (gated por papel). A edição da seção do PEI por matéria continua no
// Professor\DocumentController, mas é despachada pela rota única secretaria.alunos.pei.*.

Route::get('documentos/{documento}/pdf',  [DocumentPdfController::class, '__invoke'])->name('documentos.pdf');
Route::get('documentos/{documento}/word', DocumentWordController::class)->name('documentos.word');

Route::post('alunos/{aluno}/observacoes',  [ObservationController::class, 'store'])->name('alunos.observacoes.store');
Route::delete('observacoes/{observation}', [ObservationController::class, 'destroy'])->name('observacoes.destroy');
