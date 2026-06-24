<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPdfController extends Controller
{
    public function __invoke(Document $documento)
    {
        $documento->load('student', 'author');
        $documento->student->load([
            'school',
            'schoolClasses' => fn($q) => $q->where('year', $documento->year),
        ]);

        // Log LGPD
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

        $typeLabels = [
            'estudo_caso'     => 'ESTUDO-DE-CASO',
            'paee'            => 'PAEE',
            'pei'             => 'PEI',
            'pei_consolidado' => 'PEI',
        ];
        $typeLabel = $typeLabels[$documento->type]
            ?? strtoupper(str_replace('_', '-', $documento->type));

        $filename = $typeLabel
            . '_' . str($documento->student->name)->slug()
            . '_' . $documento->year
            . '.pdf';

        return $pdf->download($filename);
    }
}