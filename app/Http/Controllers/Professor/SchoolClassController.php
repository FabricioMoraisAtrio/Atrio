<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function index()
    {
        $year   = date('Y');
        $turmas = SchoolClass::where('school_id', session('school_id'))
            ->withCount('students')
            ->with([
                'students' => fn($q) => $q->orderBy('name')->with([
                    'documents' => fn($d) => $d->whereYear('created_at', $year)
                        ->whereIn('type', ['estudo_caso', 'paee', 'pei', 'pei_consolidado']),
                    'laudos',
                ]),
            ])
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
