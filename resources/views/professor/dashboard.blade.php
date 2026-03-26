@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div style="margin-bottom: 28px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 4px;">
        Olá, {{ auth()->user()->name }}
    </h1>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Resumo das suas turmas e alunos inclusivos</p>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@if($pendentes->isNotEmpty())
    <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <p style="font-size: 13px; font-weight: 600; color: #92400E; margin: 0;">Documentos pendentes</p>
        </div>
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach($pendentes as $item)
                <div style="background: #fff; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #FDE68A;">
                    <div>
                        <span style="font-size: 13px; font-weight: 600; color: #111827;">{{ $item['aluno']->name }}</span>
                        <span style="font-size: 12px; color: #9CA3AF; margin-left: 8px;">{{ $item['turma']->name }}</span>
                    </div>
                    <div style="display: flex; gap: 4px;">
                        @foreach($item['faltando'] as $tipo)
                            <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                                {{ strtoupper(str_replace('_', ' ', $tipo)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@forelse($turmas as $turma)
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #F9FAFB;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $turma->name }}</h3>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                    {{ $turma->shift }} · {{ $turma->year }}
                    @if($turma->pivot->subject) · {{ $turma->pivot->subject }} @endif
                </p>
            </div>
            <span style="background: #E6F5F4; color: #009C8C; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                {{ $turma->students->count() }} aluno(s) atípico(s)
            </span>
        </div>

        @if($turma->students->isEmpty())
            <p style="font-size: 13px; color: #9CA3AF;">Nenhum aluno atípico nesta turma.</p>
        @else
            <div>
                @foreach($turma->students as $aluno)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ strtoupper(substr($aluno->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size: 14px; font-weight: 500; color: #111827; margin: 0;">{{ $aluno->name }}</p>
                                @if($aluno->condition)
                                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">{{ $aluno->condition }}</p>
                                @endif
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="{{ route('professor.alunos.show', $aluno) }}"
                               style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 600; padding: 5px 12px; border: 1px solid #E8F0F9; border-radius: 8px; background: #E8F0F9;">
                                Ver perfil
                            </a>
                            @foreach(['estudo_caso', 'pei', 'paee'] as $tipo)
                                @php $doc = $aluno->documents->firstWhere('type', $tipo); @endphp
                                @if($doc)
                                    <a href="{{ route('professor.documentos.show', $doc) }}"
                                       style="font-size: 11px; background: #ECFDF5; color: #065F46; font-weight: 600; padding: 5px 10px; border-radius: 8px; text-decoration: none;">
                                        {{ strtoupper(str_replace('_', ' ', $tipo)) }}
                                    </a>
                                @else
                                    <a href="{{ route('professor.alunos.documentos.create', [$aluno, 'type' => $tipo]) }}"
                                       style="font-size: 11px; background: #F3F4F6; color: #6B7280; font-weight: 600; padding: 5px 10px; border-radius: 8px; text-decoration: none;">
                                        + {{ strtoupper(str_replace('_', ' ', $tipo)) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@empty
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 48px; text-align: center;">
        <p style="font-size: 14px; color: #9CA3AF;">Você não está vinculado a nenhuma turma ainda.</p>
    </div>
@endforelse
@endsection