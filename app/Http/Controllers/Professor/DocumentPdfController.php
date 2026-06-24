<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use App\Services\DocumentPdfRenderer;

class DocumentPdfController extends Controller
{
    public function __invoke(Document $documento)
    {
        // Professor só exporta documentos dos seus alunos
        $temAcesso = auth()->user()->schoolClasses()
            ->whereHas('students', fn($q) => $q->where('students.id', $documento->student_id))
            ->exists();

        if (! $temAcesso) abort(403);

        $documento->load('student', 'author');
        $documento->student->load([
            'school',
            'schoolClasses' => fn($q) => $q->where('year', $documento->year),
        ]);

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

        $filename = strtoupper(str_replace('_', '-', $documento->type))
            . '_' . str($documento->student->name)->slug()
            . '_' . $documento->year
            . '.pdf';

        return response(DocumentPdfRenderer::render($documento), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}