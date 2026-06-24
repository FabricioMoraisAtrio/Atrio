<?php

namespace App\Support;

use Dompdf\Dompdf;

class PdfFooter
{
    /**
     * Desenha o rodapé institucional em TODAS as páginas de um PDF do DomPDF:
     * linha separadora, créditos à esquerda, número da página ao centro e
     * "Desenvolvido por Átrio System" + logo à direita.
     */
    public static function apply(Dompdf $dompdf): void
    {
        $canvas   = $dompdf->getCanvas();
        $w        = $canvas->get_width();
        $h        = $canvas->get_height();
        $gray     = [0.45, 0.45, 0.45];
        $line     = [0.82, 0.82, 0.82];
        $logoPath = public_path('images/atrio-logo.png');

        $margin = 40;        // ~1.4cm
        $lineY  = $h - 34;   // linha separadora
        $yText  = $h - 24;   // baseline do texto
        $logoSz = 13;

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics)
            use ($w, $gray, $line, $logoPath, $margin, $lineY, $yText, $logoSz)
        {
            $font = $fontMetrics->getFont('DejaVu Sans');
            $size = 7.5;

            // Linha separadora (hairline)
            $canvas->line($margin, $lineY, $w - $margin, $lineY, $line, 0.6);

            // Esquerda: direitos reservados
            $canvas->text($margin, $yText, "\xC2\xA9 Todos os direitos reservados", $font, $size, $gray);

            // Centro: número da página
            $pg  = "P\xC3\xA1gina {$pageNumber} de {$pageCount}";
            $pgW = $fontMetrics->getTextWidth($pg, $font, $size);
            $canvas->text(($w - $pgW) / 2, $yText, $pg, $font, $size, $gray);

            // Direita: "Desenvolvido por Átrio System" + logo
            $label  = "Desenvolvido por \xC3\x81trio System";
            $labelW = $fontMetrics->getTextWidth($label, $font, $size);
            $logoX  = $w - $margin - $logoSz;
            $canvas->text($logoX - 6 - $labelW, $yText, $label, $font, $size, $gray);

            if (file_exists($logoPath)) {
                $canvas->image($logoPath, $logoX, $yText - 3, $logoSz, $logoSz);
            }
        });
    }
}
