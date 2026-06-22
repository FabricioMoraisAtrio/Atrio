<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Models\StudentAcademicGoal;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentAcademicGoalController extends Controller
{
    /**
     * Tela de gestão das metas acadêmicas customizadas do aluno (por matéria),
     * usadas no PEI. Restrita a admin/coordenador/orientador (documentos.ver_todos).
     */
    public function edit(Student $aluno)
    {
        $ano = (int) date('Y');

        $subjects = Subject::where('tipo', 'disciplina')->orderBy('ordem')->orderBy('name')->get();

        $metasPorMateria = $aluno->academicGoals()
            ->where('year', $ano)
            ->orderBy('ordem')
            ->get()
            ->groupBy('subject_id');

        // Metas socioemocionais e funcionais (texto livre) — guardadas no PEI global
        $peiDoc    = Document::where('student_id', $aluno->id)
            ->where('type', 'pei')
            ->where('year', $ano)
            ->first();
        $peiGlobal = $peiDoc?->content['global'] ?? [];

        return view('secretaria.alunos.metas-academicas', compact('aluno', 'subjects', 'metasPorMateria', 'ano', 'peiGlobal'));
    }

    public function update(Request $request, Student $aluno)
    {
        $request->validate([
            'metas'                 => 'nullable|array',
            'metas.*'               => 'nullable|array',
            'metas.*.*'             => 'nullable|string|max:500',
            'metas_socioemocionais' => 'nullable|string|max:5000',
            'metas_funcionais'      => 'nullable|string|max:5000',
        ]);

        $ano      = (int) date('Y');
        $schoolId = session('school_id');

        $subjectsValidos = Subject::pluck('id')->all();

        foreach (($request->input('metas', [])) as $subjectId => $textos) {
            if (! in_array((int) $subjectId, $subjectsValidos, true)) {
                continue;
            }

            // Substitui as metas daquela matéria/ano
            $aluno->academicGoals()
                ->where('subject_id', $subjectId)
                ->where('year', $ano)
                ->delete();

            $ordem = 1;
            foreach ((array) $textos as $texto) {
                $texto = trim((string) $texto);
                if ($texto === '') {
                    continue;
                }

                StudentAcademicGoal::create([
                    'school_id'  => $schoolId,
                    'student_id' => $aluno->id,
                    'subject_id' => $subjectId,
                    'year'       => $ano,
                    'meta'       => $texto,
                    'ordem'      => $ordem++,
                ]);
            }
        }

        // Metas socioemocionais e funcionais (texto livre dos perfis com acesso) → PEI global
        $peiDoc = Document::where('student_id', $aluno->id)
            ->where('type', 'pei')
            ->where('year', $ano)
            ->first();

        if ($peiDoc) {
            $content = $peiDoc->content ?? [];
            $content['global'] = array_merge($content['global'] ?? [], [
                'metas_socioemocionais' => $request->input('metas_socioemocionais'),
                'metas_funcionais'      => $request->input('metas_funcionais'),
            ]);
            $peiDoc->update(['content' => $content]);
        }

        return redirect()->route('secretaria.alunos.metas-academicas.edit', $aluno)
            ->with('success', 'Metas atualizadas.');
    }
}
