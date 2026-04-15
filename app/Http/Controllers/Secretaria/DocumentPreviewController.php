<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPreviewController extends Controller
{
    public function __invoke(Document $documento)
    {
        $documento->load('student', 'author');
        $documento->student->load([
            'school',
            'schoolClasses' => fn($q) => $q->where('year', $documento->year),
        ]);

        $pdf = Pdf::loadView('pdf.documento', compact('documento'))
            ->setOptions(['isRemoteEnabled' => true]);

        $output = $pdf->output();

        return response($output, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
            'Content-Length'      => strlen($output),
            'Cache-Control'       => 'no-store',
        ]);
    }
}
