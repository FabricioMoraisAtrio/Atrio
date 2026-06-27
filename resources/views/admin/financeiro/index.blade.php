@extends('admin.layouts.app')
@section('title', 'Financeiro')

@section('content')
@php
    $brl = fn($v) => 'R$ ' . number_format((float) $v, 2, ',', '.');
    $badge = [
        'pago'      => ['Pago', 'var(--adm-green)', 'var(--adm-green-bg)'],
        'aberto'    => ['Em aberto', 'var(--adm-amber)', 'var(--adm-amber-bg)'],
        'vencido'   => ['Vencido', 'var(--adm-red)', 'var(--adm-red-bg)'],
        'cancelado' => ['Cancelado', 'var(--adm-text-3)', 'var(--adm-border-2)'],
    ];
@endphp

{{-- Resumo --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; margin-bottom:18px;">
    @foreach([['Recebido no mês',$resumo['recebido'],'var(--adm-green)'],['Em aberto',$resumo['aberto'],'var(--adm-amber)'],['Vencido',$resumo['vencido'],'var(--adm-red)']] as $r)
    <div class="adm-card" style="padding:16px 20px;">
        <div style="font-size:12px; font-weight:600; color:var(--adm-text-3); margin-bottom:6px;">{{ $r[0] }}</div>
        <div style="font-size:21px; font-weight:800; color:{{ $r[2] }};">{{ $brl($r[1]) }}</div>
    </div>
    @endforeach
</div>

{{-- Ações: gerar faturas + nova fatura --}}
<div class="adm-card" style="padding:18px 20px; margin-bottom:18px; display:flex; flex-wrap:wrap; gap:18px; align-items:flex-end; justify-content:space-between;">
    <form method="POST" action="{{ route('admin.invoices.generate') }}" style="display:flex; gap:10px; align-items:flex-end;">
        @csrf
        <div>
            <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Gerar faturas do mês</label>
            <input type="month" name="reference" value="{{ now()->format('Y-m') }}" required
                   style="border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px; color:var(--adm-text);">
        </div>
        <button type="submit" class="adm-btn adm-btn-primary">Gerar</button>
    </form>
    <button type="button" onclick="document.getElementById('nova-fatura').style.display = document.getElementById('nova-fatura').style.display==='block'?'none':'block'"
            class="adm-btn adm-btn-ghost">+ Nova fatura avulsa</button>
</div>

{{-- Form nova fatura (oculto) --}}
<div id="nova-fatura" class="adm-card" style="display:none; padding:20px; margin-bottom:18px;">
    <form method="POST" action="{{ route('admin.invoices.store') }}" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; align-items:end;">
        @csrf
        <div>
            <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Escola</label>
            <select name="school_id" required style="width:100%; border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px;">
                @foreach($schools as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Competência</label>
            <input type="month" name="reference" value="{{ now()->format('Y-m') }}" required style="width:100%; border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px;">
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Valor (R$)</label>
            <input type="number" step="0.01" min="0" name="amount" required placeholder="0.00" style="width:100%; border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px;">
        </div>
        <div>
            <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Vencimento</label>
            <input type="date" name="due_date" required style="width:100%; border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px;">
        </div>
        <button type="submit" class="adm-btn adm-btn-primary">Criar fatura</button>
    </form>
</div>

{{-- Filtros --}}
<form method="GET" class="adm-card" style="padding:14px 18px; margin-bottom:14px; display:flex; flex-wrap:wrap; gap:12px; align-items:end;">
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Escola</label>
        <select name="school_id" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
            <option value="">Todas</option>
            @foreach($schools as $s)<option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>@endforeach
        </select>
    </div>
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Status</label>
        <select name="status" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
            <option value="">Todos</option>
            @foreach(['aberto'=>'Em aberto','vencido'=>'Vencido','pago'=>'Pago','cancelado'=>'Cancelado'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Competência</label>
        <input type="month" name="reference" value="{{ request('reference') }}" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
    </div>
    <button type="submit" class="adm-btn adm-btn-ghost">Filtrar</button>
    @if(request()->hasAny(['school_id','status','reference']))
        <a href="{{ route('admin.invoices.index') }}" style="font-size:12px; color:var(--adm-text-3); text-decoration:none; align-self:center;">limpar</a>
    @endif
</form>

{{-- Tabela --}}
<div class="adm-card" style="overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:var(--adm-border-2); text-align:left;">
                <th style="padding:12px 18px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase; letter-spacing:.4px;">Escola</th>
                <th style="padding:12px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Comp.</th>
                <th style="padding:12px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Valor</th>
                <th style="padding:12px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Vencimento</th>
                <th style="padding:12px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Status</th>
                <th style="padding:12px 18px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase; text-align:right;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $f)
            @php $st = $f->effectiveStatus(); $b = $badge[$st] ?? $badge['aberto']; @endphp
            <tr style="border-top:1px solid var(--adm-border-2);">
                <td style="padding:12px 18px; font-weight:600; color:var(--adm-text);">{{ $f->school?->name ?? '—' }}</td>
                <td style="padding:12px 14px; color:var(--adm-text-2);">{{ $f->reference }}</td>
                <td style="padding:12px 14px; font-weight:700; color:var(--adm-text);">{{ $brl($f->amount) }}</td>
                <td style="padding:12px 14px; color:var(--adm-text-2);">{{ $f->due_date->format('d/m/Y') }}</td>
                <td style="padding:12px 14px;">
                    <span style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; color:{{ $b[1] }}; background:{{ $b[2] }};">{{ $b[0] }}</span>
                </td>
                <td style="padding:10px 18px; text-align:right; white-space:nowrap;">
                    <a href="{{ route('admin.invoices.pdf', $f) }}" target="_blank" class="adm-btn adm-btn-ghost" style="padding:6px 12px;">{{ $f->status === 'pago' ? 'Recibo' : 'PDF' }}</a>
                    @if(in_array($f->status, ['aberto']))
                    <form method="POST" action="{{ route('admin.invoices.pay', $f) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="adm-btn adm-btn-ghost" style="padding:6px 12px; color:var(--adm-green); border-color:#A7E0C7;">Marcar pago</button>
                    </form>
                    <form method="POST" action="{{ route('admin.invoices.cancel', $f) }}" style="display:inline;" onsubmit="return confirm('Cancelar esta fatura?')">
                        @csrf
                        <button type="submit" class="adm-btn adm-btn-ghost" style="padding:6px 12px;">Cancelar</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.invoices.destroy', $f) }}" style="display:inline;" onsubmit="return confirm('Excluir esta fatura?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="adm-btn adm-btn-ghost" style="padding:6px 12px; color:var(--adm-red);">Excluir</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:28px; text-align:center; color:var(--adm-text-3); font-style:italic;">Nenhuma fatura encontrada.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $invoices->links() }}</div>
@endsection
