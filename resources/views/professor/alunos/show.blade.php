@extends('layouts.app')
@section('title', $aluno->name)

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('professor.dashboard') }}"
       style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para o painel
    </a>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 16px;">
            @if($aluno->photo)
                <img src="{{ route('alunos.foto', $aluno) }}" alt="{{ $aluno->name }}"
                     style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border); flex-shrink: 0;">
            @else
                <div style="width: 110px; height: 110px; border-radius: 50%; background: var(--accent-bg); color: var(--accent-text); font-size: 36px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    {{ strtoupper(substr($aluno->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                    <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">{{ $aluno->name }}</h1>
                    @if($subject)
                        <span style="background: var(--accent-bg); color: var(--accent-text); font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                            {{ $subject->name }}
                        </span>
                    @endif
                </div>
                <p style="font-size: 13px; color: var(--text-4); margin: 0;">Matrícula: {{ $aluno->registration_number }}</p>
            </div>
        </div>
        @if($aluno->is_atypical)
            <span style="background: var(--purple-bg); color: var(--purple); font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px;">
                {{ term('publico_alvo') }}
            </span>
        @endif
    </div>
</div>

{{-- Flags CID --}}
@if($aluno->is_atypical)
    @php
        $transtornos = config('transtornos');
        $ativos = array_filter($transtornos, fn($v, $k) => $aluno->$k, ARRAY_FILTER_USE_BOTH);
    @endphp
    @if(count($ativos))
        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 20px;">
            @foreach($ativos as $field => [$sigla, $nome])
                <span style="background: var(--brown-bg); color: var(--brown); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;" title="{{ $nome }}">{{ $sigla }}</span>
                @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                    <span style="background: var(--warning-bg); color: var(--warning); font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">Nível {{ $aluno->tea_nivel_suporte }}</span>
                @endif
            @endforeach
        </div>
    @endif
@endif

{{-- Documentos --}}
@php
    $docsAluno = $aluno->documents; // já filtrados pelo controller (apenas pei do próprio professor)
    $meuPei = $docsAluno->firstWhere('type', 'pei');
@endphp
@if($docsAluno->isNotEmpty() || true)
    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: var(--text-1); margin: 0 0 16px;">Documentos {{ date('Y') }}</h3>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            {{-- Estudo de Caso e PAEE (somente leitura para o professor) --}}
            @foreach($docsAluno->whereIn('type', ['estudo_caso', 'paee']) as $doc)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
                            {{ $doc->type === 'paee' ? 'background: #E6F5F4;' : 'background: #F5EDE6;' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="{{ $doc->type === 'paee' ? '#009C8C' : '#7C3700' }}"
                                 stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0;">
                                {{ strtoupper(str_replace('_', ' ', $doc->type)) }}
                            </p>
                            <p style="font-size: 12px; color: var(--text-4); margin: 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Meu PEI (registro privado do professor) --}}
            <a href="{{ route('professor.alunos.pei.edit', $aluno) }}"
               style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none;"
               onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--accent-bg); display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0;">Meu PEI</p>
                        <p style="font-size: 12px; color: var(--text-4); margin: 0;">
                            {{ $meuPei ? $meuPei->updated_at->format('d/m/Y') : 'Não preenchido' }}
                            <span style="color: var(--text-4);"> · </span>
                            <span style="font-style: italic; color: var(--text-4);">registro privado</span>
                        </p>
                    </div>
                </div>
                @if($meuPei)
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; background: var(--success-bg); color: var(--success);">Preenchido</span>
                @else
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; background: var(--warning-bg); color: var(--warning);">Pendente</span>
                @endif
            </a>
        </div>
    </div>
@endif

{{-- Mural --}}
<x-observation-feed :aluno="$aluno" role="professor" />
@endsection