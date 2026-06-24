<?php

namespace App\Services;

use App\Models\Document;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class DocumentPdfRenderer
{
    /**
     * Renderiza o PDF de um documento usando mPDF, com margens reais em todas
     * as páginas e o rodapé institucional repetido em cada folha (créditos,
     * número da página e logo). Retorna os bytes do PDF.
     */
    public static function render(Document $documento): string
    {
        $html = view('pdf.documento', ['documento' => $documento])->render();

        $tmp = storage_path('app/mpdf');
        if (! is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 14,   // mm (~1,4 cm) — alinhado ao rodapé
            'margin_right'  => 14,
            'margin_top'    => 17,   // ~1,7 cm em todas as páginas
            'margin_bottom' => 18,   // espaço reservado para o rodapé
            'margin_footer' => 9,    // distância do rodapé até a base
            'tempDir'       => $tmp,
        ]);

        $mpdf->SetHTMLFooter(self::footerHtml());
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * Rodapé institucional repetido em todas as páginas.
     * {PAGENO} e {nbpg} são substituídos pelo mPDF.
     */
    private static function footerHtml(): string
    {
        $logoPath = public_path('images/atrio-logo.png');
        $logoTag  = file_exists($logoPath)
            ? '<img src="' . $logoPath . '" width="11" height="11" style="vertical-align: middle;">'
            : '';

        return '<table width="100%" style="border-top: 0.4px solid #cccccc; font-size: 7.5pt; color: #777777;">
            <tr>
                <td width="40%" align="left" style="padding-top: 4px;">&copy; Todos os direitos reservados</td>
                <td width="20%" align="center" style="padding-top: 4px;">Página {PAGENO} de {nbpg}</td>
                <td width="40%" align="right" style="padding-top: 4px;">Desenvolvido por Átrio System ' . $logoTag . '</td>
            </tr>
        </table>';
    }
}
