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

        // Alunos com adaptações no PEI, agrupados por turma
        $adaptacoesPorTurma = SchoolClass::where('year', $ano)
            ->with(['students' => fn($q) => $q->where('is_atypical', true)
                ->with(['documents' => fn($d) => $d->where('year', $ano)->where('type', 'pei')])
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($turma) {
                $alunos = $turma->students->map(function ($aluno) use ($turma) {
                    $pei = $aluno->documents->first();
                    $adaptacoes = [];
                    if ($pei && isset($pei->content['adaptacoes'])) {
                        $raw = $pei->content['adaptacoes'];
                        $adaptacoes = is_array($raw) ? $raw : array_filter(array_map('trim', explode(',', $raw)));
                    }
                    return [
                        'aluno'      => $aluno,
                        'adaptacoes' => array_values($adaptacoes),
                        'tem_pei'    => $pei !== null,
                    ];
                })->filter(fn($r) => count($r['adaptacoes']) > 0)->values();

                return ['turma' => $turma, 'alunos' => $alunos];
            })->filter(fn($t) => $t['alunos']->isNotEmpty())->values();

        return view('secretaria.dashboard', compact('turmas', 'totalPendentes', 'adaptacoesPorTurma', 'ano'));
    }
}
