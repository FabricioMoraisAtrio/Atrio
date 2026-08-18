<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Portal unificado: todos (inclusive professor) usam o mesmo dashboard de cards,
        // que aparecem conforme as flags de rotina do perfil. Dados são escopados.
        $ano = date('Y');

        $turmas = SchoolClass::visiveisPara(auth()->user())->where('year', $ano)
            ->with([
                'students' => fn($q) => $q->with([
                    'documents' => fn($q) => $q->where('year', $ano)->select('id', 'student_id', 'type', 'author_id'),
                ]),
                'teachers',
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($turma) {
                $atipicos = $turma->students->filter(fn($a) => $a->is_publico_alvo);
                $pendentes = $atipicos->filter(function ($aluno) {
                    $criados = $aluno->documents->pluck('type')->toArray();
                    return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
                });

                // Professores que ainda não preencheram PEI para algum aluno atípico
                $professoresPendentes = collect();
                if ($atipicos->isNotEmpty() && $turma->teachers->isNotEmpty()) {
                    foreach ($turma->teachers as $professor) {
                        $faltando = $atipicos->filter(function ($aluno) use ($professor) {
                            return !$aluno->documents
                                ->where('type', 'pei')
                                ->where('author_id', $professor->id)
                                ->count();
                        });
                        if ($faltando->isNotEmpty()) {
                            $professoresPendentes->push($professor->name);
                        }
                    }
                }

                return [
                    'turma'                  => $turma,
                    'total'                  => $turma->students->count(),
                    'atipicos'               => $atipicos->count(),
                    'atipicos_list'          => $atipicos->take(5)->values(),
                    'pendentes'              => $pendentes->count(),
                    'professores_pendentes'  => $professoresPendentes->unique()->values(),
                ];
            });

        $totalPendentes = Student::visiveisPara(auth()->user())->where(function ($q) {
                foreach (Student::PUBLICO_ALVO_FIELDS as $f) { $q->orWhere($f, true); }
            })
            ->with(['documents' => fn($q) => $q->where('year', $ano)->select('id', 'student_id', 'type')])
            ->get()
            ->filter(function ($aluno) {
                $criados = $aluno->documents->pluck('type')->toArray();
                return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
            });

        // Alunos com adaptações no PEI, agrupados por turma
        $adaptacoesPorTurma = SchoolClass::visiveisPara(auth()->user())->where('year', $ano)
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

    public function painel()
    {
        if (! auth()->user()->podeVerTodosEstudantes()) {
            return $this->painelProfessor();
        }

        $ano = date('Y');

        $turmas = SchoolClass::visiveisPara(auth()->user())->where('year', $ano)
            ->with([
                'students' => fn($q) => $q->with([
                    'documents' => fn($q) => $q->where('year', $ano)->select('id', 'student_id', 'type', 'author_id'),
                ]),
                'teachers',
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($turma) {
                $atipicos = $turma->students->filter(fn($a) => $a->is_publico_alvo);
                $pendentes = $atipicos->filter(function ($aluno) {
                    $criados = $aluno->documents->pluck('type')->toArray();
                    return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
                });

                $professoresPendentes = collect();
                if ($atipicos->isNotEmpty() && $turma->teachers->isNotEmpty()) {
                    foreach ($turma->teachers as $professor) {
                        $faltando = $atipicos->filter(function ($aluno) use ($professor) {
                            return !$aluno->documents->where('type', 'pei')->where('author_id', $professor->id)->count();
                        });
                        if ($faltando->isNotEmpty()) {
                            $professoresPendentes->push($professor->name);
                        }
                    }
                }

                return [
                    'turma'                 => $turma,
                    'total'                 => $turma->students->count(),
                    'atipicos'              => $atipicos->count(),
                    'pendentes'             => $pendentes->count(),
                    'professores_pendentes' => $professoresPendentes->unique()->values(),
                    'professor_regente'     => $turma->teachers->first(),
                ];
            });

        $totalPendentes = Student::visiveisPara(auth()->user())->where(function ($q) {
                foreach (Student::PUBLICO_ALVO_FIELDS as $f) { $q->orWhere($f, true); }
            })
            ->with(['documents' => fn($q) => $q->where('year', $ano)->select('id', 'student_id', 'type')])
            ->get()
            ->filter(function ($aluno) {
                $criados = $aluno->documents->pluck('type')->toArray();
                return count(array_diff(['estudo_caso', 'pei', 'paee'], $criados)) > 0;
            });

        $adaptacoesPorTurma = SchoolClass::visiveisPara(auth()->user())->where('year', $ano)
            ->with(['students' => fn($q) => $q->where('is_atypical', true)
                ->with(['documents' => fn($d) => $d->where('year', $ano)->where('type', 'pei')])
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($turma) {
                $alunos = $turma->students->map(function ($aluno) {
                    $pei = $aluno->documents->first();
                    $adaptacoes = [];
                    if ($pei && isset($pei->content['adaptacoes'])) {
                        $raw = $pei->content['adaptacoes'];
                        $adaptacoes = is_array($raw) ? $raw : array_filter(array_map('trim', explode(',', $raw)));
                    }
                    return ['aluno' => $aluno, 'adaptacoes' => array_values($adaptacoes)];
                })->filter(fn($r) => count($r['adaptacoes']) > 0)->values();

                return ['turma' => $turma, 'alunos' => $alunos];
            })->filter(fn($t) => $t['alunos']->isNotEmpty())->values();

        return view('secretaria.painel', compact('turmas', 'totalPendentes', 'adaptacoesPorTurma', 'ano'));
    }

    /** Painel do professor: pendências de PEI da matéria dele nas próprias turmas. */
    private function painelProfessor()
    {
        $professor   = auth()->user();
        $transtornos = config('transtornos');
        $ano         = date('Y');

        $filtroCid = request()->input('filtro_cid');
        if ($filtroCid && ! array_key_exists($filtroCid, $transtornos)) {
            $filtroCid = null;
        }

        $turmas = $professor->schoolClasses()
            ->where('year', $ano)
            ->with(['students' => function ($q) use ($filtroCid) {
                $q->where('is_atypical', true);
                if ($filtroCid) {
                    $q->where($filtroCid, true);
                }
                $q->with(['documents' => function ($d) {
                    $d->where('year', date('Y'))->whereIn('type', ['estudo_caso', 'pei']);
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
