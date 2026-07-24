<?php

namespace App\Http\Controllers\Secretaria\Rotinas;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

/**
 * Rotina "Linha do Tempo": lista os alunos para acessar o roadmap de evolução
 * (metas, reuniões, laudos e observações) de cada um.
 */
class LinhaDoTempoHubController extends Controller
{
    public function __invoke(Request $request)
    {
        $busca       = trim((string) $request->input('busca', ''));
        $filtroTurma = $request->input('turma');

        $query = Student::query();

        if ($busca !== '') {
            $query->where('name', 'like', "%{$busca}%");
        }
        if ($filtroTurma) {
            $query->whereHas('schoolClasses', fn ($q) => $q->where('school_classes.id', $filtroTurma));
        }

        $alunos = $query->orderBy('name')->get();
        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get();

        return view('secretaria.rotinas.linha_do_tempo', compact('alunos', 'turmas', 'busca', 'filtroTurma'));
    }
}
