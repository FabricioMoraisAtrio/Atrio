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
                            <a href="{{ route('professor.alunos.documentos.create', [$item['aluno'], 'type' => $tipo]) }}"
                               style="background: #92400E; color: #fff; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; text-decoration: none;">
                                + {{ strtoupper(str_replace('_', ' ', $tipo)) }}
                            </a>
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
                                <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                                    @if($aluno->cid_autismo)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #FEF3C7; color: #92400E;">TEA</span>
                                    @endif
                                    @if($aluno->cid_tdah)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #EDE9FE; color: #5B21B6;">TDAH</span>
                                    @endif
                                    @if($aluno->cid_down)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #FCE7F3; color: #9D174D;">Down</span>
                                    @endif
                                    @if($aluno->cid_deficiencia_intelectual)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #DBEAFE; color: #1E40AF;">Def. Intelectual</span>
                                    @endif
                                    @if($aluno->cid_deficiencia_visual)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #D1FAE5; color: #065F46;">Def. Visual</span>
                                    @endif
                                    @if($aluno->cid_deficiencia_auditiva)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #E0F2FE; color: #0369A1;">Def. Auditiva</span>
                                    @endif
                                    @if($aluno->cid_outros)
                                        <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #F3F4F6; color: #6B7280;">Outros</span>
                                    @endif
                                    @if($aluno->condition && !$aluno->cid_autismo && !$aluno->cid_tdah && !$aluno->cid_down && !$aluno->cid_deficiencia_intelectual && !$aluno->cid_deficiencia_visual && !$aluno->cid_deficiencia_auditiva && !$aluno->cid_outros)
                                        <span style="font-size: 11px; color: #9CA3AF;">{{ $aluno->condition }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="{{ route('professor.alunos.show', $aluno) }}"
                               style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 600; padding: 5px 12px; border: 1px solid #E8F0F9; border-radius: 8px; background: #E8F0F9;">
                                Ver perfil
                            </a>
                            @php
                                // Apenas o PEI deste professor para este aluno
                                $meuPei = $aluno->documents->firstWhere('type', 'pei');
                                $temEstudoCaso = $aluno->documents->contains('type', 'estudo_caso');
                            @endphp
                            @if($meuPei)
                                <a href="{{ route('professor.documentos.show', $meuPei) }}"
                                   style="font-size: 11px; background: #ECFDF5; color: #065F46; font-weight: 600; padding: 5px 10px; border-radius: 8px; text-decoration: none;">
                                    PEI ✓
                                </a>
                            @elseif(!$temEstudoCaso)
                                <span style="font-size: 11px; background: #F3F4F6; color: #9CA3AF; font-weight: 500; padding: 5px 10px; border-radius: 8px;" title="Aguardando Estudo de Caso">
                                    PEI bloqueado
                                </span>
                            @else
                                <a href="{{ route('professor.alunos.documentos.create', [$aluno, 'type' => 'pei']) }}"
                                   style="font-size: 11px; background: #004B8D; color: #fff; font-weight: 600; padding: 5px 10px; border-radius: 8px; text-decoration: none;">
                                    + PEI
                                </a>
                            @endif
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