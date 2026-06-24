<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
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

    {{-- Margens em TODAS as páginas: o DomPDF mapeia a margem do <body> para a
         margem de cada página (topo, laterais e base). Precisa vir DEPOIS dos
         partials (que resetam body{margin:0}) para prevalecer. As laterais
         (1,4cm) ficam alinhadas ao rodapé; a base reserva o espaço do rodapé. --}}
    <style>
        @page { margin: 0; }
        body { margin: 1.7cm 1.4cm 1.9cm 1.4cm; }
    </style>
</body>
</html>
