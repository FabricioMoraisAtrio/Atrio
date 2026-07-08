<?php

namespace App\Http\Controllers\Secretaria\Rotinas;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

/**
 * Rotina "Reuniões / Atas": lista os alunos com o total de reuniões e a data da
 * última, para acessar/registrar as reuniões de cada um.
 */
class ReunioesHubController extends Controller
{
    public function __invoke(Request $request)
    {
        $busca       = trim((string) $request->input('busca', ''));
        $filtroTurma = $request->input('turma');

        $query = Student::withCount('meetings')->withMax('meetings', 'data');

        if ($busca !== '') {
            $query->where('name', 'like', "%{$busca}%");
        }
        if ($filtroTurma) {
            $query->whereHas('schoolClasses', fn ($q) => $q->where('school_classes.id', $filtroTurma));
        }

        $alunos = $query->orderBy('name')->get();
        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get();

        $totalReunioes = $alunos->sum('meetings_count');

        return view('secretaria.rotinas.reunioes', compact('alunos', 'turmas', 'busca', 'filtroTurma', 'totalReunioes'));
    }
}
