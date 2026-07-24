<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\BimestreClosing;
use App\Models\Student;
use App\Services\BimestreService;
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
}
