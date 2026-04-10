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
            'document_id' => $documento->id,
            'user_id'     => auth()->id(),
            'action'      => 'exported',
            'ip'          => request()->ip(),
            'accessed_at' => now(),
        ]);

        $pdf = Pdf::loadView('pdf.documento', compact('documento'))
            ->setOptions(['margin_top' => 20, 'margin_bottom' => 20]);
        $pdf->render();

        self::addFooter($pdf->getDomPDF(), $documento);

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

    private static function addFooter(\Dompdf\Dompdf $dompdf, \App\Models\Document $documento): void
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

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics)
            use ($yText, $lightgray, $logoPath, $logoX, $logoY, $logoSz, $labelX, $copyX)
        {
            $font = $fontMetrics->getFont('DejaVu Sans');
            $canvas->text($copyX, $yText, "\xC2\xA9 Todos os direitos reservados", $font, 7.5, $lightgray);
            $canvas->text($labelX, $yText, "Desenvolvido por \xC3\x81trio System", $font, 7.5, $lightgray);
            if (file_exists($logoPath)) {
                $canvas->image($logoPath, $logoX, $logoY, $logoSz, $logoSz);
            }
        });
    }
}