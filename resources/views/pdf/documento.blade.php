<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        /* Margens aplicadas em TODAS as páginas (topo, laterais e base).
           A base reserva espaço para o rodapé desenhado em cada página. */
        @page {
            margin: 1.7cm 1.4cm 1.9cm 1.4cm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
    </style>
</head>
<body>
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
</body>
</html>
