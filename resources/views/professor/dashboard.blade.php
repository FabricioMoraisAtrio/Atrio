@extends('layouts.hub')
@section('title', 'Dashboard')

@section('content')
@php
$dias   = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
$meses  = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
$now    = now();
$dataFmt = $dias[$now->dayOfWeek] . ', ' . $now->day . ' de ' . $meses[$now->month] . ' de ' . $now->year;

$cards = [
    [
        'label'    => 'Painel',
        'descricao'=> 'Turmas, estudantes inclusivos e pendências de documentação.',
        'route'    => 'professor.painel',
        'cor'      => '#004B8D',
        'bg'       => '#E8F0F9',
        'icon'     => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
        'module'   => 'painel',
    ],
    [
        'label'    => 'Turmas',
        'descricao'=> 'Veja todas as turmas e os estudantes vinculados a cada uma.',
        'route'    => 'professor.turmas.index',
        'cor'      => '#009C8C',
        'bg'       => '#E6F5F4',
        'icon'     => '<path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
        'module'   => 'turmas',
    ],
];
@endphp

{{-- Cabeçalho --}}
<div style="margin-bottom: 40px;">
    <p style="font-size: 13px; color: var(--text-4); margin: 0 0 6px;">{{ $dataFmt }}</p>
    <h1 style="font-size: 26px; font-weight: 700; color: var(--text-1); margin: 0 0 4px; line-height: 1.2;">
        Olá, {{ explode(' ', auth()->user()->name)[0] }}
    </h1>
    <p style="font-size: 14px; color: var(--text-3); margin: 0;">
        Ano letivo {{ $ano }}
        @if($subject) · {{ $subject->name }} @endif
        · {{ auth()->user()->school?->name }}
    </p>
</div>

{{-- Grid de atalhos --}}
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
    @foreach($cards as $card)
    @continue(! pode_rotina($card['module'] ?? null))
    <a href="{{ route($card['route']) }}" style="text-decoration: none;">
        <div style="background: var(--bg-card); border-radius: 14px; border: 1px solid var(--border); padding: 28px 24px; display: flex; flex-direction: column; gap: 16px; height: 100%;"
             onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.09)'; this.style.borderColor='{{ $card['cor'] }}33';"
             onmouseout="this.style.boxShadow='none'; this.style.borderColor='var(--border)';">

            <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $card['bg'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $card['cor'] }}" stroke-width="2">
                    {!! $card['icon'] !!}
                </svg>
            </div>

            <div style="flex: 1;">
                <p style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0 0 6px;">{{ $card['label'] }}</p>
                <p style="font-size: 13px; color: var(--text-3); margin: 0; line-height: 1.5;">{{ $card['descricao'] }}</p>
            </div>

            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: {{ $card['cor'] }};">
                Acessar
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $card['cor'] }}" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>
        </div>
    </a>
    @endforeach
</div>

@endsection
