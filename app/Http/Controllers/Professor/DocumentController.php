<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Models\Subject;
use App\Services\DocumentContentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    private function verificarAcesso(int $studentId): void
    {
        $turmasDoProfesor = auth()->user()->schoolClasses()->pluck('school_classes.id');

        $alunoNaTurma = \App\Models\SchoolClass::withoutGlobalScopes()
            ->whereIn('id', $turmasDoProfesor)
            ->whereHas('students', fn($q) => $q->withoutGlobalScopes()->where('students.id', $studentId))
            ->exists();

        if (! $alunoNaTurma) {
            abort(403);
        }
    }

    /**
     * Retorna a turma do professor que contém o aluno, com o pivot de subject.
     */
    private function turmaComAluno(int $studentId): ?\App\Models\SchoolClass
    {
        return auth()->user()->schoolClasses()
            ->whereHas('students', fn($q) => $q->withoutGlobalScopes()->where('students.id', $studentId))
            ->first();
    }

    /**
     * Exibe o formulário de preenchimento da seção do professor no PEI do aluno.
     */
    public function editPei(Student $aluno)
    {
        $this->verificarAcesso($aluno->id);

        $pei = Document::where('student_id', $aluno->id)
            ->where('type', 'pei')
            ->where('year', date('Y'))
            ->first();

        if (! $pei) {
            return back()->withErrors(['documento' => 'O Estudo de Caso ainda não foi preenchido para este aluno.']);
        }

        $turma       = $this->turmaComAluno($aluno->id);
        $subjectSlug = $turma?->pivot->subject;
        $subject     = $subjectSlug ? Subject::where('slug', $subjectSlug)->first() : null;

        $isRegente = $subject?->tipo === 'regente';

        // Metas customizadas do aluno (cadastradas pelo admin):
        // - disciplina: metas acadêmicas da matéria;
        // - regente: metas socioemocionais e funcionais (sem matéria).
        if ($isRegente) {
            $metasAcademicas = collect();
            $metasSocio = $aluno->academicGoals()
                ->where('categoria', 'socioemocional')->where('year', date('Y'))->orderBy('ordem')->get();
            $metasFuncionais = $aluno->academicGoals()
                ->where('categoria', 'funcional')->where('year', date('Y'))->orderBy('ordem')->get();
        } else {
            $metasSocio = collect();
            $metasFuncionais = collect();
            $metasAcademicas = $subject
                ? $aluno->academicGoals()
                    ->where('categoria', 'academica')
                    ->where('subject_id', $subject->id)
                    ->where('year', date('Y'))
                    ->orderBy('ordem')
                    ->get()
                : collect();
        }

        // Dados já preenchidos pelo professor nesta matéria
        $minha_secao = $pei->content['subjects'][$subjectSlug] ?? null;

        // Estudo de Caso do aluno (para exibir dados de diagnóstico/objetivos como somente leitura)
        $estudo_caso = \App\Models\Document::where('student_id', $aluno->id)
            ->where('type', 'estudo_caso')
            ->where('year', date('Y'))
            ->value('content') ?? [];

        return view('professor.documentos.pei_edit', compact('aluno', 'pei', 'subject', 'subjectSlug', 'isRegente', 'metasAcademicas', 'metasSocio', 'metasFuncionais', 'minha_secao', 'estudo_caso'));
    }

    /**
     * Salva a seção do professor no PEI compartilhado do aluno.
     */
    public function updatePei(Request $request, Student $aluno)
    {
        $this->verificarAcesso($aluno->id);

        $pei = Document::where('student_id', $aluno->id)
            ->where('type', 'pei')
            ->where('year', date('Y'))
            ->firstOrFail();

        $novoConteudo = DocumentContentService::mergePeiSubject($pei->content ?? [], $request);

        $pei->update(['content' => $novoConteudo]);

        return redirect()->route('professor.painel')
            ->with('success', 'PEI de ' . $aluno->name . ' preenchido com sucesso.');
    }
}
