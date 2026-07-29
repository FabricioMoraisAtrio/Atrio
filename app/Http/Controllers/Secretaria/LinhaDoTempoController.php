<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\BimestreClosing;
use App\Models\DocumentAccessLog;
use App\Models\Student;
use App\Services\BimestreService;
use App\Services\DocumentPdfRenderer;
use App\Services\StudentTimelineService;

class LinhaDoTempoController extends Controller
{
    /**
     * Linha do tempo (roadmap de evolução) de um aluno: metas por bimestre,
     * reuniões, laudos e observações, em ordem cronológica.
     */
    public function show(Student $aluno, StudentTimelineService $timeline)
    {
        $eventos = $timeline->build($aluno);

        // Resumo por tipo para o topo da tela.
        $resumo = [
            'meta'       => 0,
            'reuniao'    => 0,
            'laudo'      => 0,
            'observacao' => 0,
        ];
        foreach ($eventos as $e) {
            $resumo[$e['tipo']] = ($resumo[$e['tipo']] ?? 0) + 1;
        }

        // Dados da matriz de evolução de metas (gráfico + tabela editável),
        // incorporados na própria Linha do Tempo.
        $evolucao = GoalProgressController::dadosEvolucao($aluno);

        // Situação dos bimestres (datas configuradas + fechamentos).
        $ano       = (int) date('Y');
        $bimService = app(BimestreService::class);
        $datas     = $bimService->datas(session('school_id'));
        $closings  = BimestreClosing::where('student_id', $aluno->id)->where('year', $ano)->get()->keyBy('bimestre');

        $bimestresFechados = $closings->keys()->all();
        $bimestresInfo = [];
        foreach (BimestreService::BIMESTRES as $b) {
            $bimestresInfo[$b] = [
                'fechado'      => $closings->has($b),
                'podeEncerrar' => $bimService->podeEncerrar($datas, $b),
                'situacao'     => $bimService->situacao($datas, $b),
                'inicio'       => $datas[$b]['inicio'],
                'fim'          => $datas[$b]['fim'],
            ];
        }

        return view('secretaria.alunos.linha-do-tempo', compact('aluno', 'eventos', 'resumo', 'bimestresFechados', 'bimestresInfo') + $evolucao);
    }

    /** Exporta a Linha do Tempo do aluno em PDF, no estilo dos documentos do PEI. */
    public function pdf(Student $aluno, StudentTimelineService $timeline)
    {
        $aluno->load(['school', 'schoolClasses' => fn ($q) => $q->where('year', date('Y'))]);
        $eventos = $timeline->build($aluno);

        DocumentAccessLog::create([
            'school_id'     => $aluno->school_id,
            'student_id'    => $aluno->id,
            'user_id'       => auth()->id(),
            'action'        => 'exported',
            'document_type' => 'linha_do_tempo',
            'document_year' => (int) date('Y'),
            'student_name'  => $aluno->name,
            'ip'            => request()->ip(),
            'accessed_at'   => now(),
        ]);

        $filename = 'LINHA-DO-TEMPO_' . str($aluno->name)->slug() . '_' . date('Y') . '.pdf';

        return response(DocumentPdfRenderer::renderView('pdf.linha-do-tempo', compact('aluno', 'eventos')), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
