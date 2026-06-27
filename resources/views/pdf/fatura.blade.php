@php
    $brl = fn($v) => 'R$ ' . number_format((float) $v, 2, ',', '.');
    $pago = $invoice->status === 'pago';
    $titulo = $pago ? 'RECIBO DE PAGAMENTO' : 'FATURA';
    $statusLabel = ['aberto'=>'Em aberto','pago'=>'Pago','vencido'=>'Vencido','cancelado'=>'Cancelado'][$invoice->effectiveStatus()] ?? $invoice->status;
    $statusCor = ['aberto'=>'#B45309','pago'=>'#0F7A52','vencido'=>'#B42318','cancelado'=>'#777777'][$invoice->effectiveStatus()] ?? '#333';
    [$ano, $mes] = array_pad(explode('-', $invoice->reference), 2, '');
    $meses = ['01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril','05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto','09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro'];
    $competencia = ($meses[$mes] ?? $mes) . '/' . $ano;
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><style>
    body { font-family: sans-serif; color: #1a1a1a; font-size: 12px; }
    .head { border-bottom: 2px solid #004B8D; padding-bottom: 12px; margin-bottom: 22px; }
    .brand { font-size: 20px; font-weight: bold; color: #004B8D; letter-spacing: 1px; }
    .sub { font-size: 10px; color: #777; }
    .titulo { font-size: 16px; font-weight: bold; color: #1a1a1a; margin: 0 0 4px; }
    table.det { width: 100%; border-collapse: collapse; margin-top: 16px; }
    table.det td { padding: 9px 10px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }
    table.det td.lbl { color: #6b7280; width: 38%; }
    table.det td.val { color: #1a1a1a; font-weight: bold; }
    .total { margin-top: 22px; background: #F0F5FB; border: 1px solid #d6e3f2; border-radius: 8px; padding: 16px 18px; }
    .total .v { font-size: 24px; font-weight: bold; color: #004B8D; }
    .badge { font-size: 11px; font-weight: bold; padding: 3px 12px; border-radius: 20px; }
    .foot { margin-top: 40px; font-size: 9.5px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
</style></head>
<body>
    <table width="100%"><tr>
        <td>
            <div class="brand">ÁTRIO</div>
            <div class="sub">Sistema de Gestão de Inclusão Escolar</div>
        </td>
        <td align="right">
            <div class="titulo">{{ $titulo }}</div>
            <div class="sub">Competência: {{ $competencia }}</div>
            <div class="sub">Nº {{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</div>
        </td>
    </tr></table>
    <div class="head"></div>

    <table class="det">
        <tr><td class="lbl">Escola</td><td class="val">{{ $invoice->school?->name ?? '—' }}</td></tr>
        <tr><td class="lbl">Plano</td><td class="val">{{ ucfirst($invoice->school?->plan ?? '—') }}</td></tr>
        <tr><td class="lbl">Competência</td><td class="val">{{ $competencia }}</td></tr>
        <tr><td class="lbl">Vencimento</td><td class="val">{{ $invoice->due_date?->format('d/m/Y') }}</td></tr>
        <tr><td class="lbl">Status</td><td><span class="badge" style="color:{{ $statusCor }};">{{ $statusLabel }}</span></td></tr>
        @if($pago && $invoice->paid_at)
        <tr><td class="lbl">Pago em</td><td class="val">{{ $invoice->paid_at->format('d/m/Y') }}{{ $invoice->method ? ' · ' . $invoice->method : '' }}</td></tr>
        @endif
        @if($invoice->notes)
        <tr><td class="lbl">Observações</td><td>{{ $invoice->notes }}</td></tr>
        @endif
    </table>

    <div class="total">
        <table width="100%"><tr>
            <td style="color:#6b7280; font-size:12px;">{{ $pago ? 'Valor pago' : 'Valor a pagar' }}</td>
            <td align="right" class="v">{{ $brl($invoice->amount) }}</td>
        </tr></table>
    </div>

    <div class="foot">
        Documento gerado por Átrio System em {{ now()->format('d/m/Y H:i') }}.
        @if(!$pago) Em caso de dúvidas sobre esta cobrança, entre em contato com a administração do Átrio. @endif
    </div>
</body>
</html>
