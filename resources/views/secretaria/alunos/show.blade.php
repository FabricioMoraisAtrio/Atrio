@extends('layouts.app')
@section('title', $aluno->name)

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.alunos.index') }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para alunos
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
        <a href="{{ route('secretaria.alunos.edit', $aluno) }}"
           style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;">
            Editar
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
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
            <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">Atípico</span>
            @if($aluno->condition)
                <p style="font-size: 13px; color: #6B7280; margin: 6px 0 0;">{{ $aluno->condition }}</p>
            @endif
            {{-- Flags CID --}}
            <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px;">
                @foreach(['cid_autismo' => 'TEA', 'cid_tdah' => 'TDAH', 'cid_down' => 'Down', 'cid_deficiencia_intelectual' => 'D. Intelectual', 'cid_deficiencia_visual' => 'D. Visual', 'cid_deficiencia_auditiva' => 'D. Auditiva', 'cid_outros' => 'Outros'] as $field => $label)
                    @if($aluno->$field)
                        <span style="background: #F5EDE6; color: #7C3700; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;">{{ $label }}</span>
                    @endif
                @endforeach
            </div>
        @else
            <span style="background: #F3F4F6; color: #6B7280; font-size: 12px; padding: 4px 10px; border-radius: 20px;">Típico</span>
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
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">Documentos {{ date('Y') }}</h3>
        <div style="display: flex; gap: 8px;">
            @foreach(['estudo_caso' => 'Estudo de Caso', 'pei' => 'PEI', 'paee' => 'PAEE'] as $tipo => $label)
                @unless($aluno->documents->where('type', $tipo)->where('year', date('Y'))->count())
                    <a href="{{ route('secretaria.alunos.documentos.create', [$aluno, 'type' => $tipo]) }}"
                       style="font-size: 12px; background: #F3F4F6; color: #374151; font-weight: 600; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                        + {{ $label }}
                    </a>
                @endunless
            @endforeach
        </div>
    </div>

    @if($errors->has('documento'))
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
            {{ $errors->first('documento') }}
        </div>
    @endif

    @forelse($aluno->documents->where('year', date('Y')) as $doc)
        <a href="{{ route('secretaria.documentos.show', $doc) }}"
           style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; margin-bottom: 4px;"
           onmouseover="this.style.background='#F9FAFB'"
           onmouseout="this.style.background='transparent'">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
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
    @empty
        <p style="font-size: 13px; color: #9CA3AF;">Nenhum documento criado para {{ date('Y') }}.</p>
    @endforelse
</div>

{{-- Mural --}}
<x-observation-feed :aluno="$aluno" role="secretaria" />

{{-- Carregar laudos --}}
@php $aluno->load('laudos.uploader'); @endphp
<x-laudo-feed :aluno="$aluno" role="secretaria" />
@endsection