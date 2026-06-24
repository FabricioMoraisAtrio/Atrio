<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAcademicGoal;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentAcademicGoalController extends Controller
{
    /**
     * Tela de gestão das metas de habilidades do aluno (acadêmicas por matéria,
     * socioemocionais e funcionais), usadas no PEI. Todas no mesmo formato:
     * lista de metas que o professor depois avalia. Restrita por pei.metas_gerenciar.
     */
    public function edit(Student $aluno)
    {
        $ano = (int) date('Y');

        $subjects = Subject::where('tipo', 'disciplina')->orderBy('ordem')->orderBy('name')->get();

        $metas = $aluno->academicGoals()->where('year', $ano)->orderBy('ordem')->get();

        // Acadêmicas agrupadas por matéria; socio/funcionais como listas
        $metasPorMateria = $metas->where('categoria', 'academica')->groupBy('subject_id');
        $metasSocio      = $metas->where('categoria', 'socioemocional')->values();
        $metasFuncionais = $metas->where('categoria', 'funcional')->values();

        return view('secretaria.alunos.metas-academicas', compact(
            'aluno', 'subjects', 'metasPorMateria', 'metasSocio', 'metasFuncionais', 'ano'
        ));
    }

    public function update(Request $request, Student $aluno)
    {
        $request->validate([
            'metas'                 => 'nullable|array',
            'metas.*'               => 'nullable|array',
            'metas.*.*'             => 'nullable|string|max:500',
            'metas_socioemocional'  => 'nullable|array',
            'metas_socioemocional.*'=> 'nullable|string|max:500',
            'metas_funcional'       => 'nullable|array',
            'metas_funcional.*'     => 'nullable|string|max:500',
        ]);

        $ano      = (int) date('Y');
        $schoolId = session('school_id');

        $subjectsValidos = Subject::pluck('id')->all();

        // ── Acadêmicas (por matéria) ──
        foreach (($request->input('metas', [])) as $subjectId => $textos) {
            if (! in_array((int) $subjectId, $subjectsValidos, true)) {
                continue;
            }

            $aluno->academicGoals()
                ->where('categoria', 'academica')
                ->where('subject_id', $subjectId)
                ->where('year', $ano)
                ->delete();

            $this->criarMetas($aluno, $schoolId, $ano, 'academica', (array) $textos, (int) $subjectId);
        }

        // ── Socioemocionais e Funcionais (sem matéria) ──
        foreach (['socioemocional' => 'metas_socioemocional', 'funcional' => 'metas_funcional'] as $categoria => $campo) {
            $aluno->academicGoals()
                ->where('categoria', $categoria)
                ->where('year', $ano)
                ->delete();

            $this->criarMetas($aluno, $schoolId, $ano, $categoria, (array) $request->input($campo, []), null);
        }

        return redirect()->route('secretaria.alunos.metas-academicas.edit', $aluno)
            ->with('success', 'Metas atualizadas.');
    }

    /** Cria as metas não-vazias de uma categoria, na ordem informada. */
    private function criarMetas(Student $aluno, int $schoolId, int $ano, string $categoria, array $textos, ?int $subjectId): void
    {
        $ordem = 1;
        foreach ($textos as $texto) {
            $texto = trim((string) $texto);
            if ($texto === '') {
                continue;
            }

            StudentAcademicGoal::create([
                'school_id'  => $schoolId,
                'student_id' => $aluno->id,
                'subject_id' => $subjectId,
                'categoria'  => $categoria,
                'year'       => $ano,
                'meta'       => $texto,
                'ordem'      => $ordem++,
            ]);
        }
    }
}
