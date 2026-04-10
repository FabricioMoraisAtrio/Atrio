<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;

class StudentController extends Controller
{
    public function show(Student $aluno)
    {
        $temAcesso = \App\Models\SchoolClass::withoutGlobalScopes()
            ->whereIn('id', auth()->user()->schoolClasses()->pluck('school_classes.id'))
            ->whereHas('students', fn($q) => $q->withoutGlobalScopes()->where('students.id', $aluno->id))
            ->exists();

        if (! $temAcesso) abort(403);

        $aluno->load([
            'observations.user',
            'documents' => fn($q) => $q->where('year', date('Y'))
                ->where(fn($q2) => $q2->where('type', '!=', 'pei')->orWhere('author_id', auth()->id())),
        ]);

        $subjectSlug = auth()->user()->schoolClasses()->first()?->pivot->subject;
        $subject     = $subjectSlug ? Subject::where('slug', $subjectSlug)->first() : null;

        return view('professor.alunos.show', compact('aluno', 'subject'));
    }
}