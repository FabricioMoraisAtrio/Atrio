<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $professor = auth()->user();

        $professorId = $professor->id;

        $turmas = $professor->schoolClasses()
            ->where('year', date('Y'))
            ->with(['students' => function ($q) use ($professorId) {
                $q->where('is_atypical', true)
                  ->with(['documents' => function ($d) use ($professorId) {
                      // Carrega o PEI deste professor + qualquer estudo_caso (de qualquer autor)
                      $d->where('year', date('Y'))
                        ->where(fn($q) => $q->where('type', 'estudo_caso')
                            ->orWhere(fn($q2) => $q2->where('type', 'pei')->where('author_id', $professorId)));
                  }]);
            }])
            ->get();

        // PEI pendente = este professor ainda não criou o seu para o aluno
        $pendentes = collect();
        foreach ($turmas as $turma) {
            foreach ($turma->students as $aluno) {
                $tiposCriados = $aluno->documents->where('type', 'pei')->pluck('type')->toArray();
                $tipos = ['pei'];
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