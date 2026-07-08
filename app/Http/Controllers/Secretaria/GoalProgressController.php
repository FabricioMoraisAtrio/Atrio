<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\GoalProgress;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalProgressController extends Controller
{
    /** Bimestres avaliados no acompanhamento. */
    private const BIMESTRES = [1, 2, 3, 4];

    /**
     * Tela de evolução (acompanhamento bimestral) das metas do PEI do aluno.
     * Matriz metas × 4 bimestres + gráfico de % atingido por bimestre.
     * Restrita por pei.metas_gerenciar (mesma das metas).
     */
    public function edit(Student $aluno)
    {
        $ano = (int) date('Y');

        $subjects = Subject::where('tipo', 'disciplina')->orderBy('ordem')->orderBy('name')->get();

        $metas = $aluno->academicGoals()
            ->where('year', $ano)
            ->with('progressos')
            ->orderBy('ordem')
            ->get();

        // Mapa [goal_id][bimestre] => status para preencher a matriz.
        $progresso = [];
        foreach ($metas as $meta) {
            foreach ($meta->progressos as $p) {
                $progresso[$meta->id][$p->bimestre] = $p->status;
            }
        }

        $metasPorMateria = $metas->where('categoria', 'academica')->groupBy('subject_id');
        $metasSocio      = $metas->where('categoria', 'socioemocional')->values();
        $metasFuncionais = $metas->where('categoria', 'funcional')->values();

        $grafico = $this->calcularGrafico($metas, $progresso);

        return view('secretaria.alunos.metas-evolucao', compact(
            'aluno', 'subjects', 'metas', 'metasPorMateria', 'metasSocio',
            'metasFuncionais', 'progresso', 'grafico', 'ano'
        ) + ['bimestres' => self::BIMESTRES]);
    }

    public function update(Request $request, Student $aluno)
    {
        $statusValidos = array_keys(GoalProgress::STATUSES);

        $request->validate([
            'status'     => 'nullable|array',
            'status.*'   => 'nullable|array',
            'status.*.*' => 'nullable|in:' . implode(',', $statusValidos),
        ]);

        $ano      = (int) date('Y');
        $schoolId = session('school_id');
        $entrada  = (array) $request->input('status', []);

        // Só processa metas que pertencem de fato ao aluno/ano (ignora ids forjados).
        $metas = $aluno->academicGoals()->where('year', $ano)->get();

        foreach ($metas as $meta) {
            $porBimestre = (array) ($entrada[$meta->id] ?? []);

            foreach (self::BIMESTRES as $bimestre) {
                $status = $porBimestre[$bimestre] ?? 'nao_avaliado';

                if ($status === 'nao_avaliado') {
                    $meta->progressos()->where('bimestre', $bimestre)->delete();
                    continue;
                }

                $meta->progressos()->updateOrCreate(
                    ['bimestre' => $bimestre],
                    [
                        'school_id'   => $schoolId,
                        'year'        => $ano,
                        'status'      => $status,
                        'evaluated_by' => Auth::id(),
                    ]
                );
            }
        }

        return redirect()->route('secretaria.alunos.metas-evolucao.edit', $aluno)
            ->with('success', 'Evolução das metas atualizada.');
    }

    /**
     * Percentual atingido por bimestre: média dos pesos (SCORES) das metas
     * avaliadas naquele bimestre. Bimestre sem avaliação fica sem barra.
     */
    private function calcularGrafico($metas, array $progresso): array
    {
        $grafico = [];

        foreach (self::BIMESTRES as $bimestre) {
            $soma = 0.0;
            $avaliadas = 0;

            foreach ($metas as $meta) {
                $status = $progresso[$meta->id][$bimestre] ?? 'nao_avaliado';
                if ($status === 'nao_avaliado') {
                    continue;
                }
                $soma += GoalProgress::SCORES[$status] ?? 0.0;
                $avaliadas++;
            }

            $grafico[$bimestre] = [
                'avaliadas' => $avaliadas,
                'percentual' => $avaliadas > 0 ? (int) round(($soma / $avaliadas) * 100) : null,
            ];
        }

        return $grafico;
    }
}
