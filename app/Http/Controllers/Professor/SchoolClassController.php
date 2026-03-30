<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function index()
    {
        $turmas = SchoolClass::where('school_id', session('school_id'))
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('professor.turmas.index', compact('turmas'));
    }

    public function show(SchoolClass $turma)
    {
        $turma->load(['students' => fn($q) => $q->orderBy('name'), 'teachers']);

        return view('professor.turmas.show', compact('turma'));
    }
}
