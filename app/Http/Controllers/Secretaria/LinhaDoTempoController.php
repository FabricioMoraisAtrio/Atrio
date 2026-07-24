<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Student;
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

        return view('secretaria.alunos.linha-do-tempo', compact('aluno', 'eventos', 'resumo'));
    }
}
