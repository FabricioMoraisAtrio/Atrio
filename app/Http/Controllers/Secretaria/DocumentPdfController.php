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

        // Log LGPD
        DocumentAccessLog::create([
            'document_id' => $documento->id,
            'user_id'     => auth()->id(),
            'action'      => 'exported',
            'ip'          => request()->ip(),
            'accessed_at' => now(),
        ]);

        $pdf = Pdf::loadView('pdf.documento', compact('documento'));

        $filename = strtoupper(str_replace('_', '-', $documento->type))
            . '_' . str($documento->student->name)->slug()
            . '_' . $documento->year
            . '.pdf';

        return $pdf->download($filename);
    }
}