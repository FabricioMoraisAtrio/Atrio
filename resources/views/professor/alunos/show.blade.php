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
            <div style="width: 52px; height: 52px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 20px; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                {{ strtoupper(substr($aluno->name, 0, 1)) }}
            </div>
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $aluno->name }}</h1>
                <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Matrícula: {{ $aluno->registration_number }}</p>
            </div>
        </div>
        @if($aluno->is_atypical)
            <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px;">
                {{ $aluno->condition ?? 'Atípico' }}
            </span>
        @endif
    </div>
</div>

{{-- Flags CID --}}
@if($aluno->is_atypical)
    @php
        $flags = array_filter([
            'cid_autismo' => 'TEA',
            'cid_tdah' => 'TDAH',
            'cid_down' => 'Down',
            'cid_deficiencia_intelectual' => 'D. Intelectual',
            'cid_deficiencia_visual' => 'D. Visual',
            'cid_deficiencia_auditiva' => 'D. Auditiva',
            'cid_outros' => 'Outros',
        ], fn($label, $field) => $aluno->$field, ARRAY_FILTER_USE_BOTH);
    @endphp
    @if(count($flags))
        <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 20px;">
            @foreach($flags as $field => $label)
                <span style="background: #F5EDE6; color: #7C3700; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">{{ $label }}</span>
                @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                    <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">Nível {{ $aluno->tea_nivel_suporte }}</span>
                @endif
            @endforeach
        </div>
    @endif
@endif

{{-- Documentos --}}
@if($aluno->documents->isNotEmpty())
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 16px;">Documentos {{ date('Y') }}</h3>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            @foreach($aluno->documents->where('year', date('Y')) as $doc)
                <a href="{{ route('professor.documentos.show', $doc) }}"
                   style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none;"
                   onmouseover="this.style.background='#F9FAFB'"
                   onmouseout="this.style.background='transparent'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
                            {{ $doc->type === 'pei' ? 'background: #E8F0F9;' : ($doc->type === 'paee' ? 'background: #E6F5F4;' : 'background: #F5EDE6;') }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                 stroke="{{ $doc->type === 'pei' ? '#004B8D' : ($doc->type === 'paee' ? '#009C8C' : '#7C3700') }}"
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
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;
                        {{ $doc->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
                        {{ $doc->status === 'published' ? 'Publicado' : 'Rascunho' }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif

{{-- Mural --}}
<x-observation-feed :aluno="$aluno" role="professor" />
@endsection