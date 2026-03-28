<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $ano = date('Y');

        $turmas = SchoolClass::where('year', $ano)
            ->with(['students' => fn($q) => $q->with([
                'documents' => fn($q) => $q->where('year', $ano)->select('id', 'student_id', 'type'),
            ])])
            ->orderBy('name')
            ->get()
            ->map(function ($turma) {
                $atipicos = $turma->students->where('is_atypical', true);
                $pendentes = $atipicos->filter(function ($aluno) {
                    $criados = $aluno->documents->pluck('type')->toArray();
                    return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
                });

                return [
                    'turma'         => $turma,
                    'total'         => $turma->students->count(),
                    'atipicos'      => $atipicos->count(),
                    'atipicos_list' => $atipicos->take(5)->values(),
                    'pendentes'     => $pendentes->count(),
                ];
            });

        $totalPendentes = Student::where('is_atypical', true)
            ->with(['documents' => fn($q) => $q->where('year', $ano)->select('id', 'student_id', 'type')])
            ->get()
            ->filter(function ($aluno) {
                $criados = $aluno->documents->pluck('type')->toArray();
                return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
            });

        return view('secretaria.dashboard', compact('turmas', 'totalPendentes', 'ano'));
    }
}
