@extends('layouts.app')
@section('title', 'Linha do Tempo — ' . $aluno->name)

@section('content')
<div style="max-width: 760px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 22px;">
        <a href="{{ route('secretaria.rotinas.linha-do-tempo') }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para Linha do Tempo
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Linha do Tempo — {{ $aluno->name }}</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">Evolução do aluno em ordem cronológica (mais recente primeiro).</p>
    </div>

    {{-- Resumo por tipo --}}
    @php
        $chips = [
            ['n' => $resumo['meta'],       'lbl' => 'metas avaliadas', 'cor' => 'var(--accent-text)'],
            ['n' => $resumo['reuniao'],    'lbl' => 'reuniões',        'cor' => 'var(--accent-text)'],
            ['n' => $resumo['laudo'],      'lbl' => 'laudos',          'cor' => 'var(--teal)'],
            ['n' => $resumo['observacao'], 'lbl' => 'observações',     'cor' => 'var(--purple)'],
        ];
    @endphp
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
        @foreach($chips as $c)
            <div style="display: flex; align-items: baseline; gap: 6px; background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 10px; padding: 8px 14px;">
                <span style="font-size: 18px; font-weight: 700; color: {{ $c['cor'] }}; font-variant-numeric: tabular-nums;">{{ $c['n'] }}</span>
                <span style="font-size: 12px; color: var(--text-3);">{{ $c['lbl'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Linha do tempo --}}
    <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 22px 24px;">
        @include('secretaria.partials.timeline', ['eventos' => $eventos])
    </div>
</div>
@endsection
