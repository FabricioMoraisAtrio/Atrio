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
</body>
</html>
