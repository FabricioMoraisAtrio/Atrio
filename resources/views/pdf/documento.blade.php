<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { margin: 0; padding: 0; }
        /* Margens via wrapper — única abordagem confiável nesta versão do DomPDF */
        .doc-wrapper {
            padding: 56px 43px 51px 43px; /* topo:2cm, lat:1.5cm, base:1.8cm em pt (1cm=28.35pt) */
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
