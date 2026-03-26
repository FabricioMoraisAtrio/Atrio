<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $professor = auth()->user();

        $turmas = $professor->schoolClasses()
            ->where('year', date('Y'))
            ->with(['students' => function ($q) {
                $q->where('is_atypical', true)
                  ->with(['documents' => function ($d) {
                      $d->where('year', date('Y'));
                  }]);
            }])
            ->get();

        // Alunos atípicos com documentos pendentes
        $pendentes = collect();
        foreach ($turmas as $turma) {
            foreach ($turma->students as $aluno) {
                $tipos = ['estudo_caso', 'pei', 'paee'];
                $tiposCriados = $aluno->documents->pluck('type')->toArray();
                $tiposFaltando = array_diff($tipos, $tiposCriados);

                if (! empty($tiposFaltando)) {
                    $pendentes->push([
                        'aluno'   => $aluno,
                        'turma'   => $turma,
                        'faltando' => $tiposFaltando,
                    ]);
                }
            }
        }

        return view('professor.dashboard', compact('turmas', 'pendentes'));
    }
}