<?php

namespace App\Http\Controllers\Secretaria\Rotinas;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class RotinaEstudoCasoController extends Controller
{
    public function __invoke(Request $request)
    {
        $turmas = SchoolClass::where('year', date('Y'))->orderBy('name')->get(['id', 'name', 'shift']);

        $query = Student::with([
            'schoolClasses' => fn($q) => $q->where('year', date('Y'))->select('school_classes.id', 'name', 'shift'),
            'documents'     => fn($q) => $q->where('year', date('Y'))->where('type', 'estudo_caso')->select('id', 'student_id', 'status', 'updated_at'),
        ]);

        if ($request->filled('turma')) {
            $query->whereHas('schoolClasses', fn($q) => $q->where('school_classes.id', $request->turma));
        }

        if ($request->filled('publico')) {
            $query->where('is_atypical', $request->publico === 'sim');
        }

        $alunos = $query->orderBy('name')->get();

        return view('secretaria.rotinas.documentos.estudo-caso', compact('alunos', 'turmas'));
    }
}
