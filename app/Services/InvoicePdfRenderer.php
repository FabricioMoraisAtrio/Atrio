<?php

namespace App\Services;

use App\Models\Invoice;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class InvoicePdfRenderer
{
    /** Renderiza a fatura/recibo de uma fatura em PDF (bytes). */
    public static function render(Invoice $invoice): string
    {
        $html = view('pdf.fatura', ['invoice' => $invoice])->render();

        $tmp = storage_path('app/mpdf');
        if (! is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 16,
            'margin_right'  => 16,
            'margin_top'    => 18,
            'margin_bottom' => 16,
            'tempDir'       => $tmp,
        ]);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}
