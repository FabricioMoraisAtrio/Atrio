@extends('layouts.hub')
@section('title', 'Início')

@section('content')

@php
$dias   = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
$meses  = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
$now    = now();
$dataFmt = $dias[$now->dayOfWeek] . ', ' . $now->day . ' de ' . $meses[$now->month] . ' de ' . $now->year;

$school     = auth()->user()->school;
$hasModule  = fn(string $k) => !$school || $school->hasModule($k);

$cards = [
    // 1. Painel de Controle
    [
        'label'    => 'Painel de Acompanhamento',
        'descricao'=> 'Turmas, pendências de documentação e adaptações para prova.',
        'route'    => 'secretaria.painel',
        'cor'      => '#1D4ED8',
        'bg'       => '#EFF6FF',
        'icon'     => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
        'role'     => null,
        'module'   => 'painel',
    ],
    // 2. Cadastro de Alunos
    [
        'label'    => 'Cadastro de ' . strtolower(term('alunos')),
        'descricao'=> 'Cadastro completo dos alunos, diagnósticos e histórico.',
        'route'    => 'secretaria.alunos.index',
        'cor'      => '#009C8C',
        'bg'       => '#E6F5F4',
        'icon'     => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
        'role'     => null,
        'module'   => 'alunos',
    ],
    // 3. Turmas
    [
        'label'    => term('turmas'),
        'descricao'=> 'Gerencie as turmas e os alunos matriculados no ano letivo.',
        'route'    => 'secretaria.turmas.index',
        'cor'      => '#004B8D',
        'bg'       => '#E8F0F9',
        'icon'     => '<path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
        'role'     => null,
        'module'   => 'turmas',
    ],
    // 4. Documentos de Inclusão
    [
        'label'    => 'Documentos de Inclusão',
        'descricao'=> 'PAEE, PEI e registros de atendimentos dos alunos.',
        'route'    => 'secretaria.rotinas.documentos.index',
        'cor'      => '#7C3700',
        'bg'       => '#F5EDE6',
        'icon'     => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15h6"/>',
        'role'     => null,
        'module'   => 'documentos',
    ],
    // 5. Adaptações para Prova
    [
        'label'    => 'Adaptações para Prova',
        'descricao'=> 'Controle das adaptações de avaliação por aluno e turma.',
        'route'    => 'secretaria.rotinas.adaptacoes',
        'cor'      => '#6D28D9',
        'bg'       => '#EDE9FE',
        'icon'     => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/>',
        'role'     => null,
        'module'   => 'adaptacoes',
    ],
    // 6. Usuários
    [
        'label'    => 'Usuários',
        'descricao'=> 'Gerencie os membros da equipe e seus perfis de acesso.',
        'route'    => 'secretaria.usuarios.index',
        'cor'      => '#4F46E5',
        'bg'       => '#EEF2FF',
        'icon'     => '<circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>',
        'role'     => null,
        'module'   => 'usuarios',
    ],
    // 7. Configurações
    [
        'label'    => 'Configurações',
        'descricao'=> 'Dados da escola, terminologias e perfis de acesso.',
        'route'    => 'secretaria.config.index',
        'cor'      => '#92400E',
        'bg'       => '#FEF3C7',
        'icon'     => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>',
        'role'     => 'admin',
        'module'   => 'configuracoes',
    ],
];
@endphp

{{-- Cabeçalho de boas-vindas --}}
<div style="margin-bottom: 40px;">
    <p style="font-size: 13px; color: var(--text-4); margin: 0 0 6px;">{{ $dataFmt }}</p>
    <h1 style="font-size: 26px; font-weight: 700; color: var(--text-1); margin: 0 0 4px; line-height: 1.2;">
        Olá, {{ explode(' ', auth()->user()->name)[0] }}
    </h1>
    <p style="font-size: 14px; color: var(--text-3); margin: 0;">
        Ano letivo {{ $ano }} · {{ auth()->user()->school?->name }}
    </p>
</div>

{{-- Grid de atalhos --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    @foreach($cards as $card)
        @if($card['role'] && !auth()->user()->hasRole($card['role']))
            @continue
        @endif
        @if(isset($card['module']) && !$hasModule($card['module']))
            @continue
        @endif
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
