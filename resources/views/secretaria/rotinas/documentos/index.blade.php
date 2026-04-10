@extends('layouts.app')
@section('title', 'Documentos')

@section('content')
<div style="margin-bottom: 28px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 6px;">Documentos</h1>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Acesse e gerencie os documentos por tipo em uma visão consolidada.</p>
</div>

@php
$cards = [
    [
        'label'       => 'Estudo de Caso',
        'descricao'   => 'Visão geral dos estudos de caso elaborados pela equipe pedagógica.',
        'route'       => 'secretaria.rotinas.documentos.estudo-caso',
        'cor'         => '#7C3700',
        'bg'          => '#F5EDE6',
        'icon'        => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/>',
    ],
    [
        'label'       => 'PAEE',
        'descricao'   => 'Plano de Atendimento Educacional Especializado dos ' . strtolower(term('alunos')) . ' ' . strtolower(term('publico_alvo')) . '.',
        'route'       => 'secretaria.rotinas.documentos.paee',
        'cor'         => '#009C8C',
        'bg'          => '#E6F5F4',
        'icon'        => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15h6"/>',
    ],
    [
        'label'       => 'PEI',
        'descricao'   => 'Plano Educacional Individualizado consolidado por aluno e disciplina.',
        'route'       => 'secretaria.rotinas.documentos.pei',
        'cor'         => '#004B8D',
        'bg'          => '#E8F0F9',
        'icon'        => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>',
    ],
];
@endphp

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    @foreach($cards as $card)
    <a href="{{ route($card['route']) }}" style="text-decoration: none;">
        <div style="background: #fff; border-radius: 14px; border: 1px solid #F3F4F6; padding: 28px 24px; display: flex; flex-direction: column; gap: 16px; height: 100%; transition: box-shadow 0.15s;"
             onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)'; this.style.borderColor='#E5E7EB';"
             onmouseout="this.style.boxShadow='none'; this.style.borderColor='#F3F4F6';">

            <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $card['bg'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $card['cor'] }}" stroke-width="2">
                    {!! $card['icon'] !!}
                </svg>
            </div>

            <div style="flex: 1;">
                <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 6px;">{{ $card['label'] }}</p>
                <p style="font-size: 13px; color: #9CA3AF; margin: 0; line-height: 1.5;">{{ $card['descricao'] }}</p>
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
