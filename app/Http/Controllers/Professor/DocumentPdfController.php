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
            'document_id' => $documento->id,
            'user_id'     => auth()->id(),
            'action'      => 'exported',
            'ip'          => request()->ip(),
            'accessed_at' => now(),
        ]);

        $pdf = Pdf::loadView('pdf.documento', compact('documento'))
            ->setOptions(['margin_top' => 20, 'margin_bottom' => 20]);
        $pdf->render();

        if (in_array($documento->type, ['pei', 'paee'])) {
            self::addPeiFooter($pdf->getDomPDF(), $documento);
        }

        $filename = strtoupper(str_replace('_', '-', $documento->type))
            . '_' . str($documento->student->name)->slug()
            . '_' . $documento->year
            . '.pdf';

        return $pdf->download($filename);
    }

    private static function addPeiFooter(\Dompdf\Dompdf $dompdf, \App\Models\Document $documento): void
    {
        $canvas = $dompdf->getCanvas();
        $w      = $canvas->get_width();
        $h      = $canvas->get_height();

        $lightgray = [0.533, 0.533, 0.533];
        $logoPath  = public_path('images/atrio-logo.png');

        $yText  = $h - 18;
        $logoSz = 16;
        $logoX  = $w - 36 - $logoSz;
        $logoY  = $yText - 3;
        $labelX = $w - 36 - $logoSz - 125;
        $copyX  = 36;

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($yText, $lightgray, $logoPath, $logoX, $logoY, $logoSz, $labelX, $copyX) {
            $font = $fontMetrics->getFont('DejaVu Sans');
            $canvas->text($copyX, $yText, "\xC2\xA9 Todos os direitos reservados", $font, 7.5, $lightgray);
            $canvas->text($labelX, $yText, "Desenvolvido por \xC3\x81trio System", $font, 7.5, $lightgray);
            if (file_exists($logoPath)) {
                $canvas->image($logoPath, $logoX, $logoY, $logoSz, $logoSz);
            }
        });
    }
}