<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Subject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $professor = auth()->user();
        $ano       = date('Y');

        $subjectSlug = $professor->schoolClasses()->where('year', $ano)->first()?->pivot->subject;
        $subject     = $subjectSlug ? Subject::where('slug', $subjectSlug)->first() : null;

        return view('professor.dashboard', compact('ano', 'subject'));
    }

    public function painel(Request $request)
    {
        $professor   = auth()->user();
        $professorId = $professor->id;
        $transtornos = config('transtornos');
        $ano         = date('Y');

        $filtroCid = $request->input('filtro_cid');
        if ($filtroCid && !array_key_exists($filtroCid, $transtornos)) {
            $filtroCid = null;
        }

        $turmas = $professor->schoolClasses()
            ->where('year', $ano)
            ->with(['students' => function ($q) use ($filtroCid) {
                $q->where('is_atypical', true);
                if ($filtroCid) {
                    $q->where($filtroCid, true);
                }
                $q->with(['documents' => function ($d) use ($filtroCid) {
                    $d->where('year', date('Y'))
                      ->whereIn('type', ['estudo_caso', 'pei']);
                }]);
            }])
            ->get();

        $pendentes = collect();
        foreach ($turmas as $turma) {
            $subjectSlugTurma = $turma->pivot->subject;
            foreach ($turma->students as $aluno) {
                $temEstudoCaso = $aluno->documents->contains('type', 'estudo_caso');
                $pei           = $aluno->documents->firstWhere('type', 'pei');
                $preencheu     = $pei && isset(($pei->content['subjects'] ?? [])[$subjectSlugTurma]);
                if ($temEstudoCaso && ! $preencheu) {
                    $pendentes->push(['aluno' => $aluno, 'turma' => $turma]);
                }
            }
        }

        $subjectSlug = $professor->schoolClasses()->where('year', $ano)->first()?->pivot->subject;
        $subject     = $subjectSlug ? Subject::where('slug', $subjectSlug)->first() : null;

        return view('professor.painel', compact('turmas', 'pendentes', 'transtornos', 'filtroCid', 'subject'));
    }
}