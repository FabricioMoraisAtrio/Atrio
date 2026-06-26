@extends('admin.layouts.app')
@section('title', 'Relatórios')

@section('content')
@php
    $cards = [
        ['Escolas', $totais['escolas'], '<path d="M3 21h18M5 21V8l7-4 7 4v13"/>'],
        ['Alunos', $totais['alunos'], '<path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87"/>'],
        ['Documentos', $totais['documentos'], '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>'],
        ['Usuários', $totais['usuarios'], '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>'],
    ];
    $chart = function($serie, $cor) {
        $max = max(array_map(fn($p) => $p['value'], $serie)) ?: 1;
        return ['max' => $max, 'cor' => $cor];
    };
@endphp

{{-- Totais --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:16px; margin-bottom:22px;">
    @foreach($cards as $c)
    <div class="adm-card" style="padding:18px 20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
            <span style="font-size:12px; font-weight:600; color:var(--adm-text-3);">{{ $c[0] }}</span>
            <span style="width:32px; height:32px; border-radius:9px; background:var(--adm-border-2); display:flex; align-items:center; justify-content:center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--adm-accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $c[2] !!}</svg>
            </span>
        </div>
        <div style="font-size:24px; font-weight:800; color:var(--adm-text);">{{ number_format($c[1], 0, ',', '.') }}</div>
    </div>
    @endforeach
</div>

{{-- Gráficos de crescimento --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:22px;">
    @foreach([['Novas escolas por mês', $serieEscolas, 'var(--adm-accent)'], ['Documentos criados por mês', $serieDocs, 'var(--adm-green)']] as $g)
    @php $max = max(array_map(fn($p) => $p['value'], $g[1])) ?: 1; @endphp
    <div class="adm-card" style="padding:22px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 18px;">{{ $g[0] }}</p>
        <div style="display:flex; align-items:flex-end; gap:12px; height:150px;">
            @foreach($g[1] as $p)
            @php $h = max(4, round(($p['value'] / $max) * 128)); @endphp
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:7px; height:100%; justify-content:flex-end;">
                <span style="font-size:11px; font-weight:700; color:var(--adm-text-2);">{{ $p['value'] }}</span>
                <div style="width:100%; max-width:38px; height:{{ $h }}px; border-radius:6px 6px 2px 2px; background:{{ $g[2] }}; opacity:.88;"></div>
                <span style="font-size:11px; color:var(--adm-text-3);">{{ $p['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

{{-- Engajamento por escola --}}
<div class="adm-card" style="overflow:hidden;">
    <div style="padding:16px 20px; border-bottom:1px solid var(--adm-border-2);">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0;">Engajamento por escola</p>
    </div>
    <table style="width:100%; border-collapse:collapse; font-size:13px;">
        <thead>
            <tr style="background:var(--adm-border-2); text-align:left;">
                <th style="padding:11px 20px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Escola</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Alunos</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Documentos</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Usuários</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Plano</th>
            </tr>
        </thead>
        <tbody>
            @forelse($porEscola as $e)
            <tr style="border-top:1px solid var(--adm-border-2);">
                <td style="padding:11px 20px; font-weight:600; color:var(--adm-text);">{{ $e->name }}</td>
                <td style="padding:11px 14px; color:var(--adm-text-2);">{{ $e->students_count }}</td>
                <td style="padding:11px 14px; color:var(--adm-text-2);">{{ $e->documents_count }}</td>
                <td style="padding:11px 14px; color:var(--adm-text-2);">{{ $e->users_count }}</td>
                <td style="padding:11px 14px; color:var(--adm-text-2);">{{ ucfirst($e->plan) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="padding:24px; text-align:center; color:var(--adm-text-3); font-style:italic;">Sem dados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
