<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        /* Margens via wrapper — abordagem confiável neste DomPDF (o @page margin
           é ignorado). Laterais (40pt ≈ 1,4cm) alinhadas ao rodapé desenhado em
           cada página; topo e base com folga generosa. */
        .doc-wrapper {
            padding: 52pt 40pt 54pt 40pt;
        }
    </style>
</head>
<body>
<div class="doc-wrapper">
    @if($documento->type === 'estudo_caso')
        @include('pdf.partials.estudo_caso')
    @elseif($documento->type === 'pei_consolidado')
        @include('pdf.partials.pei_consolidado')
    @elseif($documento->type === 'pei')
        @include('pdf.partials.pei')
    @elseif($documento->type === 'paee')
        @include('pdf.partials.paee')
    @else
        @include('pdf.partials.generic')
    @endif
</div>
</body>
</html>
