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
        $professor   = auth()->user();
        $professorId = $professor->id;
        $transtornos = config('transtornos');

        // Valida que o filtro é um campo existente
        $filtroCid = $request->input('filtro_cid');
        if ($filtroCid && !array_key_exists($filtroCid, $transtornos)) {
            $filtroCid = null;
        }

        $turmas = $professor->schoolClasses()
            ->where('year', date('Y'))
            ->with(['students' => function ($q) use ($professorId, $filtroCid) {
                $q->where('is_atypical', true);

                if ($filtroCid) {
                    $q->where($filtroCid, true);
                }

                $q->with(['documents' => function ($d) {
                    $d->where('year', date('Y'))
                      ->whereIn('type', ['estudo_caso', 'pei']);
                }]);
            }])
            ->get();

        // PEI pendente = existe Estudo de Caso mas professor ainda não preencheu sua seção
        $pendentes = collect();
        foreach ($turmas as $turma) {
            $subjectSlugTurma = $turma->pivot->subject;
            foreach ($turma->students as $aluno) {
                $temEstudoCaso = $aluno->documents->contains('type', 'estudo_caso');
                $pei           = $aluno->documents->firstWhere('type', 'pei');
                $preencheu     = $pei && isset(($pei->content['subjects'] ?? [])[$subjectSlugTurma]);

                if ($temEstudoCaso && ! $preencheu) {
                    $pendentes->push([
                        'aluno' => $aluno,
                        'turma' => $turma,
                    ]);
                }
            }
        }

        // Matéria vinculada ao professor (slug no pivot)
        $subjectSlug = $professor->schoolClasses()->first()?->pivot->subject;
        $subject     = $subjectSlug ? Subject::where('slug', $subjectSlug)->first() : null;

        return view('professor.dashboard', compact('turmas', 'pendentes', 'transtornos', 'filtroCid', 'subject'));
    }
}