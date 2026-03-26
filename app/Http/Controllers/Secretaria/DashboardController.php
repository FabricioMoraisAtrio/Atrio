<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Carrega tudo de uma vez
        $alunosAtipicos = Student::where('is_atypical', true)
            ->with(['documents' => fn($q) => $q->where('year', date('Y'))->select('id','student_id','type')])
            ->get();

        $pendentes = $alunosAtipicos->filter(function ($aluno) {
            $criados = $aluno->documents->pluck('type')->toArray();
            return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
        })->map(function ($aluno) {
            $criados = $aluno->documents->pluck('type')->toArray();
            return [
                'aluno'    => $aluno,
                'faltando' => array_values(array_diff(['estudo_caso', 'pei', 'paee'], $criados)),
            ];
        });

        return view('secretaria.dashboard', compact('pendentes'));
    }
}