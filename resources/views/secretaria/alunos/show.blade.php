@extends('layouts.app')
@section('title', $aluno->name)

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.alunos.index') }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para {{ strtolower(term('alunos')) }}
    </a>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="position: relative; width: 110px; height: 110px; flex-shrink: 0;">
                @if($aluno->photo)
                    <img src="{{ asset('storage/' . $aluno->photo) }}" alt="{{ $aluno->name }}"
                         style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid #E5E7EB;">
                @else
                    <div style="width: 110px; height: 110px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 36px; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                    </div>
                @endif
                <label title="Alterar foto" style="position: absolute; bottom: 4px; right: 4px; width: 26px; height: 26px; background: #004B8D; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; border: 2px solid #fff;">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <form method="POST" action="{{ route('secretaria.alunos.uploadPhoto', $aluno) }}" enctype="multipart/form-data" style="display:none;" id="foto-form-{{ $aluno->id }}">
                        @csrf
                        <input type="file" name="photo" accept="image/*" onchange="document.getElementById('foto-form-{{ $aluno->id }}').submit()">
                    </form>
                </label>
            </div>
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $aluno->name }}</h1>
                <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Matrícula: {{ $aluno->registration_number }}</p>
            </div>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            @if($aluno->is_atypical)
            <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
               style="display: flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; background: #E8F0F9; color: #004B8D;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
                PEI Consolidado
            </a>
            @endif
            <a href="{{ route('secretaria.alunos.documento-final', $aluno) }}"
               style="display: flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                Documento Final
            </a>
            <a href="{{ route('secretaria.alunos.edit', $aluno) }}"
               style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                Editar
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

{{-- Responsáveis --}}
@if($aluno->responsavel_nome || $aluno->responsavel_2_nome)
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 16px 20px; margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 24px;">
    @if($aluno->responsavel_nome)
    <div>
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Responsável</p>
        <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">{{ $aluno->responsavel_nome }}</p>
    </div>
    @endif
    @if($aluno->responsavel_2_nome)
    <div>
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">2º Responsável</p>
        <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">{{ $aluno->responsavel_2_nome }}</p>
    </div>
    @endif
</div>
@endif

{{-- Cards de info --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Nascimento</p>
        <p style="font-size: 15px; font-weight: 600; color: #111827; margin: 0;">{{ $aluno->birth_date->format('d/m/Y') }}</p>
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Perfil</p>
        @if($aluno->is_atypical)
            <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
            @if($aluno->condition)
                <p style="font-size: 13px; color: #6B7280; margin: 6px 0 0;">{{ $aluno->condition }}</p>
            @endif
            {{-- Flags CID --}}
            <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px;">
                @foreach(config('transtornos') as $field => [$sigla, $nome])
                    @if($aluno->$field)
                        <span style="background: #F5EDE6; color: #7C3700; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;" title="{{ $nome }}">{{ $sigla }}</span>
                        @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                            <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px;">N{{ $aluno->tea_nivel_suporte }}</span>
                        @endif
                    @endif
                @endforeach
            </div>
        @else
            <span style="background: #F3F4F6; color: #6B7280; font-size: 12px; padding: 4px 10px; border-radius: 20px;">{{ term('nao_publico_alvo') }}</span>
        @endif
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Estudo de caso</p>
        @if($aluno->has_case_study)
            <span style="background: #ECFDF5; color: #065F46; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">✓ Preenchido</span>
        @else
            <span style="background: #FEF2F2; color: #991B1B; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">Pendente</span>
        @endif
    </div>
</div>

{{-- Turmas --}}
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
    <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 16px;">Turmas</h3>

    @if($aluno->schoolClasses->isEmpty())
        <p style="font-size: 13px; color: #9CA3AF;">Nenhuma turma vinculada.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
            @foreach($aluno->schoolClasses as $turma)
                <span style="background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                    {{ $turma->name }} — {{ $turma->shift }} {{ $turma->year }}
                </span>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.alunos.attachClass', $aluno) }}"
          style="display: flex; gap: 10px; align-items: center;">
        @csrf
        <select name="school_class_id"
                style="border: 1px solid #E5E7EB; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #374151; outline: none; background: #fff;">
            <option value="">Vincular turma</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}">{{ $turma->name }} — {{ $turma->shift }}</option>
            @endforeach
        </select>
        <button type="submit"
                style="background: #111827; color: white; border: none; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Vincular
        </button>
    </form>
</div>

{{-- Documentos --}}
@php
    $docsAno = $aluno->documents->where('year', date('Y'));
    $docsNaoPei = $docsAno->whereIn('type', ['estudo_caso', 'paee']);
    $peis = $docsAno->where('type', 'pei')->load('author');
@endphp
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">Documentos {{ date('Y') }}</h3>
        <div style="display: flex; gap: 8px;">
            @foreach(['estudo_caso' => 'Estudo de Caso', 'paee' => 'PAEE'] as $tipo => $label)
                @unless($docsNaoPei->where('type', $tipo)->count())
                    <a href="{{ route('secretaria.alunos.documentos.create', [$aluno, 'type' => $tipo]) }}"
                       style="font-size: 12px; background: #F3F4F6; color: #374151; font-weight: 600; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                        + {{ $label }}
                    </a>
                @endunless
            @endforeach
            <a href="{{ route('secretaria.alunos.documentos.create', [$aluno, 'type' => 'pei']) }}"
               style="font-size: 12px; background: #E8F0F9; color: #004B8D; font-weight: 600; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                + PEI
            </a>
        </div>
    </div>

    @if($errors->has('documento'))
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
            {{ $errors->first('documento') }}
        </div>
    @endif

    {{-- Estudo de caso e PAEE --}}
    @foreach($docsNaoPei as $doc)
        <a href="{{ route('secretaria.documentos.show', $doc) }}"
           style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; margin-bottom: 4px;"
           onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                </div>
                <div>
                    <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">{{ strtoupper(str_replace('_', ' ', $doc->type)) }}</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; {{ $doc->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
                {{ $doc->status === 'published' ? 'Publicado' : 'Rascunho' }}
            </span>
        </a>
    @endforeach

    {{-- PEIs consolidados por professor --}}
    @if($peis->isNotEmpty())
        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #F3F4F6;">
            <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px;">PEIs preenchidos pelos professores</p>
            @foreach($peis as $pei)
                <a href="{{ route('secretaria.documentos.show', $pei) }}"
                   style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; margin-bottom: 4px;"
                   onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                        </div>
                        <div>
                            <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">PEI</p>
                            <p style="font-size: 12px; color: #9CA3AF; margin: 0;">{{ $pei->author->name ?? '—' }} · {{ $pei->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; {{ $pei->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
                        {{ $pei->status === 'published' ? 'Publicado' : 'Rascunho' }}
                    </span>
                </a>
            @endforeach
        </div>
    @endif

    @if($docsNaoPei->isEmpty() && $peis->isEmpty())
        <p style="font-size: 13px; color: #9CA3AF;">Nenhum documento criado para {{ date('Y') }}.</p>
    @endif
</div>

{{-- Mural --}}
<x-observation-feed :aluno="$aluno" role="secretaria" />

{{-- Carregar laudos --}}
@php $aluno->load('laudos.uploader'); @endphp
<x-laudo-feed :aluno="$aluno" role="secretaria" />
@endsection