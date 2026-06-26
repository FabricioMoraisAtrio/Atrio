@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $brl = fn($v) => 'R$ ' . number_format((float) $v, 2, ',', '.');
    $kpis = [
        ['label'=>'Recebido no mês', 'value'=>$brl($recebidoMes), 'color'=>'var(--adm-green)', 'bg'=>'var(--adm-green-bg)', 'icon'=>'<path d="M20 6L9 17l-5-5"/>'],
        ['label'=>'Em aberto',       'value'=>$brl($emAberto),    'color'=>'var(--adm-amber)', 'bg'=>'var(--adm-amber-bg)', 'icon'=>'<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'],
        ['label'=>'Vencido',         'value'=>$brl($vencido),     'color'=>'var(--adm-red)',   'bg'=>'var(--adm-red-bg)',   'icon'=>'<path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/>'],
        ['label'=>'Receita recorrente (MRR)', 'value'=>$brl($mrr), 'color'=>'var(--adm-accent)', 'bg'=>'#E8F1FE', 'icon'=>'<path d="M3 3v18h18"/><path d="M7 14l3-3 3 2 5-6"/>'],
        ['label'=>'Escolas ativas',  'value'=>$activeSchools.' de '.$totalSchools, 'color'=>'var(--adm-text)', 'bg'=>'var(--adm-border-2)', 'icon'=>'<path d="M3 21h18M5 21V8l7-4 7 4v13"/>'],
    ];
    $maxReceita = max(array_map(fn($p) => $p['value'], $receitaSerie)) ?: 1;
@endphp

{{-- KPIs --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(196px,1fr)); gap:16px; margin-bottom:22px;">
    @foreach($kpis as $k)
    <div class="adm-card" style="padding:18px 20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <span style="font-size:12px; font-weight:600; color:var(--adm-text-3);">{{ $k['label'] }}</span>
            <span style="width:32px; height:32px; border-radius:9px; background:{{ $k['bg'] }}; display:flex; align-items:center; justify-content:center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $k['icon'] !!}</svg>
            </span>
        </div>
        <div style="font-size:22px; font-weight:800; color:var(--adm-text); letter-spacing:-.5px;">{{ $k['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Gráfico de receita + planos vencendo --}}
<div style="display:grid; grid-template-columns:1.5fr 1fr; gap:16px; margin-bottom:22px;">
    <div class="adm-card" style="padding:22px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 2px;">Receita recebida</p>
        <p style="font-size:12px; color:var(--adm-text-3); margin:0 0 18px;">Últimos 6 meses</p>
        <div style="display:flex; align-items:flex-end; gap:14px; height:160px; padding-top:8px;">
            @foreach($receitaSerie as $ponto)
                @php $h = $maxReceita > 0 ? max(4, round(($ponto['value'] / $maxReceita) * 140)) : 4; @endphp
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:8px; height:100%; justify-content:flex-end;">
                    <span style="font-size:10.5px; font-weight:600; color:var(--adm-text-2);">{{ $ponto['value'] > 0 ? number_format($ponto['value']/1000,1,',','.').'k' : '—' }}</span>
                    <div title="{{ $brl($ponto['value']) }}" style="width:100%; max-width:42px; height:{{ $h }}px; border-radius:7px 7px 3px 3px; background:linear-gradient(180deg,var(--adm-accent),#60A5FA);"></div>
                    <span style="font-size:11px; color:var(--adm-text-3);">{{ $ponto['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="adm-card" style="padding:22px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 2px;">Planos vencendo</p>
        <p style="font-size:12px; color:var(--adm-text-3); margin:0 0 14px;">Próximos 30 dias</p>
        @forelse($planosVencendo as $escola)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:9px 0; border-bottom:1px solid var(--adm-border-2);">
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:600; color:var(--adm-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $escola->name }}</div>
                    <div style="font-size:11px; color:var(--adm-text-3);">{{ ucfirst($escola->plan) }}</div>
                </div>
                <span style="font-size:12px; font-weight:600; color:var(--adm-amber); white-space:nowrap;">{{ $escola->plan_expires_at->format('d/m/Y') }}</span>
            </div>
        @empty
            <p style="font-size:13px; color:var(--adm-text-3); font-style:italic;">Nenhum plano vence nos próximos 30 dias.</p>
        @endforelse
    </div>
</div>

{{-- Vencimentos de faturas --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:22px;">
    @php
        $blocos = [
            ['titulo'=>'Faturas atrasadas', 'cor'=>'var(--adm-red)', 'lista'=>$faturasAtrasadas, 'vazio'=>'Nenhuma fatura atrasada.'],
            ['titulo'=>'A vencer (15 dias)', 'cor'=>'var(--adm-amber)', 'lista'=>$faturasAVencer, 'vazio'=>'Nenhuma fatura a vencer.'],
        ];
    @endphp
    @foreach($blocos as $bloco)
    <div class="adm-card" style="padding:22px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 14px; display:flex; align-items:center; gap:7px;">
            <span style="width:8px; height:8px; border-radius:50%; background:{{ $bloco['cor'] }};"></span>{{ $bloco['titulo'] }}
        </p>
        @forelse($bloco['lista'] as $f)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:9px 0; border-bottom:1px solid var(--adm-border-2);">
                <div style="min-width:0;">
                    <div style="font-size:13px; font-weight:600; color:var(--adm-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $f->school?->name ?? '—' }}</div>
                    <div style="font-size:11px; color:var(--adm-text-3);">Comp. {{ $f->reference }} · vence {{ $f->due_date->format('d/m/Y') }}</div>
                </div>
                <span style="font-size:13px; font-weight:700; color:{{ $bloco['cor'] }}; white-space:nowrap;">{{ $brl($f->amount) }}</span>
            </div>
        @empty
            <p style="font-size:13px; color:var(--adm-text-3); font-style:italic;">{{ $bloco['vazio'] }}</p>
        @endforelse
        @if(\Illuminate\Support\Facades\Route::has('admin.invoices.index'))
        <a href="{{ route('admin.invoices.index') }}" style="display:inline-block; margin-top:12px; font-size:12px; font-weight:600; color:var(--adm-accent); text-decoration:none;">Ver financeiro →</a>
        @endif
    </div>
    @endforeach
</div>

{{-- Novas escolas --}}
<div class="adm-card" style="padding:22px;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0;">Escolas recentes</p>
        <a href="{{ route('admin.schools.index') }}" style="font-size:12px; font-weight:600; color:var(--adm-accent); text-decoration:none;">Ver todas →</a>
    </div>
    @forelse($novasEscolas as $escola)
        <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--adm-border-2);">
            <div style="display:flex; align-items:center; gap:11px;">
                <div style="width:34px; height:34px; border-radius:9px; background:var(--adm-border-2); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:var(--adm-text-2);">{{ strtoupper(substr($escola->name,0,1)) }}</div>
                <div>
                    <div style="font-size:13px; font-weight:600; color:var(--adm-text);">{{ $escola->name }}</div>
                    <div style="font-size:11px; color:var(--adm-text-3);">{{ ucfirst($escola->plan) }} · {{ $escola->is_active ? 'Ativa' : 'Inativa' }}</div>
                </div>
            </div>
            <a href="{{ route('admin.schools.edit', $escola) }}" style="font-size:12px; font-weight:600; color:var(--adm-accent); text-decoration:none;">Gerenciar</a>
        </div>
    @empty
        <p style="font-size:13px; color:var(--adm-text-3); font-style:italic;">Nenhuma escola cadastrada.</p>
    @endforelse
</div>
@endsection
