<?php

namespace App\Http\Controllers\Secretaria\Rotinas;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

/**
 * Rotina "Evolução de Metas": lista os alunos com a quantidade de metas do PEI
 * no ano, para acompanhar a evolução bimestral de cada um.
 */
class MetasEvolucaoHubController extends Controller
{
    public function __invoke(Request $request)
    {
        $ano         = (int) date('Y');
        $busca       = trim((string) $request->input('busca', ''));
        $filtroTurma = $request->input('turma');

        $query = Student::withCount(['academicGoals' => fn ($q) => $q->where('year', $ano)]);

        if ($busca !== '') {
            $query->where('name', 'like', "%{$busca}%");
        }
        if ($filtroTurma) {
            $query->whereHas('schoolClasses', fn ($q) => $q->where('school_classes.id', $filtroTurma));
        }

        $alunos = $query->orderBy('name')->get();
        $turmas = SchoolClass::where('year', $ano)->orderBy('name')->get();

        return view('secretaria.rotinas.metas_evolucao', compact('alunos', 'turmas', 'busca', 'filtroTurma', 'ano'));
    }
}
