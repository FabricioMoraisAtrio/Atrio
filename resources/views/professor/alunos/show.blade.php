@extends('layouts.app')
@section('title', $aluno->name)

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('professor.dashboard') }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para o painel
    </a>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 16px;">
            @if($aluno->photo)
                <img src="{{ asset('storage/' . $aluno->photo) }}" alt="{{ $aluno->name }}"
                     style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid #E5E7EB; flex-shrink: 0;">
            @else
                <div style="width: 110px; height: 110px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 36px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    {{ strtoupper(substr($aluno->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                    <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">{{ $aluno->name }}</h1>
                    @if($subject)
                        <span style="background: #E8F0F9; color: #004B8D; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                            {{ $subject->name }}
                        </span>
                    @endif
                </div>
                <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Matrícula: {{ $aluno->registration_number }}</p>
            </div>
        </div>
        @if($aluno->is_atypical)
            <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px;">
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
                <span style="background: #F5EDE6; color: #7C3700; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;" title="{{ $nome }}">{{ $sigla }}</span>
                @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                    <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">Nível {{ $aluno->tea_nivel_suporte }}</span>
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
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 16px;">Documentos {{ date('Y') }}</h3>
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
                            <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">
                                {{ strtoupper(str_replace('_', ' ', $doc->type)) }}
                            </p>
                            <p style="font-size: 12px; color: #9CA3AF; margin: 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Meu PEI (registro privado do professor) --}}
            <a href="{{ route('professor.alunos.pei.edit', $aluno) }}"
               style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none;"
               onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">Meu PEI</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                            {{ $meuPei ? $meuPei->updated_at->format('d/m/Y') : 'Não preenchido' }}
                            <span style="color: #D1D5DB;"> · </span>
                            <span style="font-style: italic; color: #9CA3AF;">registro privado</span>
                        </p>
                    </div>
                </div>
                @if($meuPei)
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; background: #ECFDF5; color: #065F46;">Preenchido</span>
                @else
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; background: #FEF3C7; color: #92400E;">Pendente</span>
                @endif
            </a>
        </div>
    </div>
@endif

{{-- Mural --}}
<x-observation-feed :aluno="$aluno" role="professor" />
@endsection