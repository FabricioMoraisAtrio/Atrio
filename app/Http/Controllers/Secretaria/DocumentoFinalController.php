<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;

class DocumentoFinalController extends Controller
{
    public function __invoke(Student $aluno)
    {
        $aluno->load([
            'schoolClasses:id,name,shift,year',
        ]);

        $docs = Document::where('student_id', $aluno->id)
            ->where('year', date('Y'))
            ->with('author:id,name')
            ->get();

        $estudoCaso = $docs->firstWhere('type', 'estudo_caso');
        $paee       = $docs->firstWhere('type', 'paee');
        $peis       = $docs->where('type', 'pei')->values();

        return view('secretaria.alunos.documento-final', compact('aluno', 'estudoCaso', 'paee', 'peis'));
    }
}
