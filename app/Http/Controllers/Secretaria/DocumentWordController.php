<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class DocumentWordController extends Controller
{
    public function __invoke(Document $documento)
    {
        $documento->load('student', 'author');

        DocumentAccessLog::create([
            'school_id'     => $documento->school_id,
            'document_id'   => $documento->id,
            'student_id'    => $documento->student_id,
            'user_id'       => auth()->id(),
            'action'        => 'exported',
            'document_type' => $documento->type,
            'document_year' => $documento->year,
            'student_name'  => $documento->student?->name,
            'ip'            => request()->ip(),
            'accessed_at'   => now(),
        ]);

        return (new \App\Http\Controllers\Professor\DocumentWordController)->gerarWord($documento);
    }
}