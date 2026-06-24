<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $pdf = Pdf::loadView('pdf.documento', compact('documento'))
            ->setOptions(['isRemoteEnabled' => true]);
        $pdf->render();

        \App\Support\PdfFooter::apply($pdf->getDomPDF());

        $filename = strtoupper(str_replace('_', '-', $documento->type))
            . '_' . str($documento->student->name)->slug()
            . '_' . $documento->year
            . '.pdf';

        return $pdf->download($filename);
    }
}