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

                $q->with(['documents' => function ($d) use ($professorId) {
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
                $tiposCriados  = $aluno->documents->where('type', 'pei')->pluck('type')->toArray();
                $tiposFaltando = array_diff(['pei'], $tiposCriados);

                if (!empty($tiposFaltando)) {
                    $pendentes->push([
                        'aluno'    => $aluno,
                        'turma'    => $turma,
                        'faltando' => $tiposFaltando,
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