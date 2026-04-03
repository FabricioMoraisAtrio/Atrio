@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php
$dias   = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
$meses  = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
$now    = now();
$dataFmt = $dias[$now->dayOfWeek] . ', ' . $now->day . ' de ' . $meses[$now->month] . ' de ' . $now->year;
@endphp

{{-- Cabeçalho --}}
<div style="margin-bottom: 28px;">
    <p style="font-size: 13px; color: #9CA3AF; margin: 0 0 4px;">{{ $dataFmt }}</p>
    <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 2px;">
        Olá, {{ explode(' ', auth()->user()->name)[0] }} 👋
    </h1>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">
        Ano letivo {{ $ano }} · {{ auth()->user()->school?->name }}
    </p>
</div>

{{-- Métricas --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #F3F4F6; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; background: #E8F0F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 26px; font-weight: 700; color: #111827; line-height: 1;">{{ \App\Models\Student::count() }}</div>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 3px;">Alunos cadastrados</div>
        </div>
    </div>

    <div style="background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #F3F4F6; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; background: #E6F5F4; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#009C8C" stroke-width="2">
                <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 26px; font-weight: 700; color: #111827; line-height: 1;">{{ $turmas->count() }}</div>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 3px;">Turmas em {{ $ano }}</div>
        </div>
    </div>

    <div style="background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #F3F4F6; display: flex; align-items: center; gap: 16px;">
        <div style="width: 44px; height: 44px; background: #FEF3C7; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 26px; font-weight: 700; color: #111827; line-height: 1;">{{ $totalPendentes->count() }}</div>
            <div style="font-size: 12px; color: #9CA3AF; margin-top: 3px;">Docs pendentes</div>
        </div>
    </div>
</div>

{{-- Alerta de pendentes --}}
@if($totalPendentes->isNotEmpty())
<div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 16px 20px; margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2" style="flex-shrink:0;">
            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <span style="font-size: 13px; font-weight: 600; color: #92400E;">
            {{ $totalPendentes->count() }} aluno(s) com documentação incompleta (PEI, PAEE ou Estudo de Caso)
        </span>
    </div>
    <a href="{{ route('secretaria.alunos.index') }}"
       style="font-size: 12px; font-weight: 600; color: #92400E; text-decoration: none; white-space: nowrap;">
        Ver todos →
    </a>
</div>
@endif

{{-- Cards de turmas --}}
@if($turmas->isNotEmpty())
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
    <h2 style="font-size: 14px; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">
        Turmas — {{ $ano }}
    </h2>
    <a href="{{ route('secretaria.turmas.index') }}"
       style="font-size: 12px; color: #004B8D; font-weight: 600; text-decoration: none;">
        Gerenciar turmas →
    </a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-bottom: 32px;">
    @foreach($turmas as $item)
    @php
        $turma   = $item['turma'];
        $turno   = match($turma->shift) { 'manha' => 'Manhã', 'tarde' => 'Tarde', 'noite' => 'Noite', default => $turma->shift };
        $corTurno = match($turma->shift) { 'manha' => ['bg' => '#E8F0F9', 'text' => '#004B8D'], 'tarde' => ['bg' => '#E6F5F4', 'text' => '#009C8C'], default => ['bg' => '#F3E8FF', 'text' => '#7C3AED'] };
    @endphp
    <a href="{{ route('secretaria.turmas.show', $turma) }}"
       style="background: #fff; border: 1px solid #F3F4F6; border-radius: 14px; padding: 20px; text-decoration: none; display: block; transition: border-color 0.15s;"
       onmouseover="this.style.borderColor='#004B8D'; this.style.boxShadow='0 4px 16px rgba(0,75,141,0.08)'"
       onmouseout="this.style.borderColor='#F3F4F6'; this.style.boxShadow='none'">

        {{-- Header do card --}}
        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px;">
            <div>
                <div style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 4px;">{{ $turma->name }}</div>
                <span style="font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: {{ $corTurno['bg'] }}; color: {{ $corTurno['text'] }};">
                    {{ $turno }}
                </span>
            </div>
            <div style="width: 40px; height: 40px; background: #E8F0F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
        </div>

        {{-- Contadores --}}
        <div style="display: flex; gap: 20px; margin-bottom: 16px;">
            <div>
                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $item['total'] }}</div>
                <div style="font-size: 11px; color: #9CA3AF;">alunos</div>
            </div>
            @if($item['atipicos'] > 0)
            <div>
                <div style="font-size: 20px; font-weight: 700; color: #004B8D;">{{ $item['atipicos'] }}</div>
                <div style="font-size: 11px; color: #9CA3AF;">público alvo</div>
            </div>
            @endif
            @if($item['pendentes'] > 0)
            <div>
                <div style="font-size: 20px; font-weight: 700; color: #92400E;">{{ $item['pendentes'] }}</div>
                <div style="font-size: 11px; color: #9CA3AF;">pendentes</div>
            </div>
            @endif
        </div>

        {{-- Avatares dos alunos atípicos --}}
        @if($item['atipicos_list']->isNotEmpty())
        <div style="display: flex; align-items: center; gap: 0;">
            @foreach($item['atipicos_list'] as $i => $aluno)
            <div title="{{ $aluno->name }}"
                 style="width: 28px; height: 28px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; margin-left: {{ $i > 0 ? '-8px' : '0' }}; z-index: {{ 10 - $i }}; position: relative;">
                {{ strtoupper(substr($aluno->name, 0, 1)) }}
            </div>
            @endforeach
            @if($item['atipicos'] > 5)
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #F3F4F6; color: #6B7280; font-size: 10px; font-weight: 700; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; margin-left: -8px; position: relative;">
                +{{ $item['atipicos'] - 5 }}
            </div>
            @endif
            <span style="font-size: 11px; color: #9CA3AF; margin-left: 10px;">público alvo</span>
        </div>
        @else
        <div style="font-size: 12px; color: #9CA3AF;">Sem alunos público alvo</div>
        @endif

        {{-- Badge de pendentes + professores que faltam --}}
        @if($item['pendentes'] > 0 || $item['professores_pendentes']->isNotEmpty())
        <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #F9FAFB;">
            @if($item['pendentes'] > 0)
            <span style="font-size: 11px; font-weight: 600; color: #92400E; background: #FEF3C7; padding: 4px 10px; border-radius: 20px;">
                ⚠ {{ $item['pendentes'] }} com docs incompletos
            </span>
            @endif
            @if($item['professores_pendentes']->isNotEmpty())
            <div style="margin-top: 8px;">
                <p style="font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Faltam preencher PEI:</p>
                @foreach($item['professores_pendentes'] as $prof)
                    <span style="font-size: 11px; font-weight: 500; color: #DC2626; background: #FEF2F2; padding: 2px 8px; border-radius: 20px; display: inline-block; margin-right: 4px; margin-bottom: 4px;">{{ $prof }}</span>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </a>
    @endforeach
</div>
@else
<div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 40px; text-align: center;">
    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 12px; display: block;">
        <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
    </svg>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0 0 16px;">Nenhuma turma cadastrada para {{ $ano }}</p>
    <a href="{{ route('secretaria.turmas.create') }}"
       style="background: #004B8D; color: white; text-decoration: none; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;">
        Criar primeira turma
    </a>
</div>
@endif


{{-- Controle de Adaptações para Prova --}}
@if($adaptacoesPorTurma->isNotEmpty())
@php
    // Coleta todas as adaptações únicas para os filtros
    $todasAdaptacoes = $adaptacoesPorTurma->flatMap(fn($t) => $t['alunos']->flatMap(fn($a) => $a['adaptacoes']))->unique()->sort()->values();
    $todasTurmas = $adaptacoesPorTurma->pluck('turma');
@endphp

<div style="margin-top: 8px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h2 style="font-size: 14px; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">
            Controle de Adaptações para Prova
        </h2>
        <button onclick="window.print()" style="font-size: 12px; color: #004B8D; font-weight: 600; background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
            Imprimir
        </button>
    </div>

    {{-- Filtros estilo Excel --}}
    <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;">Turma</label>
            <select id="filtro-turma" onchange="filtrarAdaptacoes()"
                    style="border: 1px solid #D1D5DB; border-radius: 6px; padding: 7px 28px 7px 10px; font-size: 13px; color: #374151; background: #F9FAFB; cursor: pointer; appearance: auto;">
                <option value="">Todas as turmas</option>
                @foreach($todasTurmas as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;">Tipo de adaptação</label>
            <select id="filtro-adaptacao" onchange="filtrarAdaptacoes()"
                    style="border: 1px solid #D1D5DB; border-radius: 6px; padding: 7px 28px 7px 10px; font-size: 13px; color: #374151; background: #F9FAFB; cursor: pointer; appearance: auto;">
                <option value="">Todas as adaptações</option>
                @foreach($todasAdaptacoes as $tag)
                    <option value="{{ $tag }}">{{ $tag }}</option>
                @endforeach
            </select>
        </div>
        <button onclick="document.getElementById('filtro-turma').value=''; document.getElementById('filtro-adaptacao').value=''; filtrarAdaptacoes();"
                style="padding: 7px 14px; border: 1px solid #D1D5DB; border-radius: 6px; font-size: 12px; color: #6B7280; background: none; cursor: pointer;">
            Limpar filtros
        </button>
        <div style="margin-left: auto; font-size: 12px; color: #9CA3AF; align-self: center;">
            <span id="total-linhas">0</span> aluno(s)
        </div>
    </div>

    {{-- Tabela --}}
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;" id="tabela-adaptacoes">
            <thead>
                <tr style="background: #F9FAFB;">
                    <th style="text-align: left; padding: 11px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Aluno</th>
                    <th style="text-align: left; padding: 11px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Turma</th>
                    <th style="text-align: left; padding: 11px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Adaptações</th>
                </tr>
            </thead>
            <tbody id="tbody-adaptacoes">
                @foreach($adaptacoesPorTurma as $grupo)
                    @foreach($grupo['alunos'] as $registro)
                    <tr class="linha-adaptacao"
                        data-turma="{{ $grupo['turma']->id }}"
                        data-adaptacoes="{{ implode('|', $registro['adaptacoes']) }}"
                        style="border-top: 1px solid #F9FAFB;">
                        <td style="padding: 13px 20px;">
                            <a href="{{ route('secretaria.alunos.show', $registro['aluno']) }}"
                               style="font-size: 14px; font-weight: 500; color: #111827; text-decoration: none;">
                                {{ $registro['aluno']->name }}
                            </a>
                        </td>
                        <td style="padding: 13px 20px; font-size: 13px; color: #6B7280;">{{ $grupo['turma']->name }}</td>
                        <td style="padding: 13px 20px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($registro['adaptacoes'] as $tag)
                                    <span class="tag-adaptacao" style="padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background: #E8F0F9; color: #004B8D;">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <div id="sem-resultados" style="display: none; padding: 32px; text-align: center; font-size: 14px; color: #9CA3AF;">
            Nenhum aluno encontrado com este filtro.
        </div>
    </div>
</div>

<script>
function filtrarAdaptacoes() {
    const turmaFiltro = document.getElementById('filtro-turma').value;
    const adaptacaoFiltro = document.getElementById('filtro-adaptacao').value;
    const linhas = document.querySelectorAll('.linha-adaptacao');
    let visiveis = 0;

    linhas.forEach(function(linha) {
        const turmaOk = !turmaFiltro || linha.dataset.turma === turmaFiltro;
        const adaptOk = !adaptacaoFiltro || linha.dataset.adaptacoes.split('|').includes(adaptacaoFiltro);
        const mostrar = turmaOk && adaptOk;
        linha.style.display = mostrar ? '' : 'none';
        if (mostrar) visiveis++;
    });

    document.getElementById('total-linhas').textContent = visiveis;
    document.getElementById('sem-resultados').style.display = visiveis === 0 ? '' : 'none';
}

// Conta inicial
document.addEventListener('DOMContentLoaded', function() {
    filtrarAdaptacoes();
});
</script>
@endif

@endsection
