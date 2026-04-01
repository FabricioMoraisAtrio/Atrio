@extends('layouts.app')
@section('title', $turma->name)

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('professor.turmas.index') }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para turmas
    </a>
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $turma->name }}</h1>
        <p style="font-size: 13px; color: #9CA3AF; margin: 0;">{{ $turma->shift }} · Ano letivo {{ $turma->year }}</p>
    </div>
</div>

{{-- Cards métricas --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Total de alunos</p>
        <p style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">{{ $turma->students->count() }}</p>
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Alunos atípicos</p>
        <p style="font-size: 28px; font-weight: 700; color: #7E22CE; margin: 0;">{{ $turma->students->where('is_atypical', true)->count() }}</p>
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Professores</p>
        <p style="font-size: 28px; font-weight: 700; color: #004B8D; margin: 0;">{{ $turma->teachers->count() }}</p>
    </div>
</div>

{{-- Lista de alunos --}}
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid #F3F4F6;">
        <p style="font-size: 13px; font-weight: 600; color: #374151; margin: 0;">Alunos matriculados</p>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F9FAFB;">
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Aluno</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Matrícula</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                <th style="padding: 12px 20px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($turma->students as $aluno)
            <tr style="border-top: 1px solid #F9FAFB;"
                onmouseover="this.style.background='#FAFAFA'"
                onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            {{ strtoupper(substr($aluno->name, 0, 1)) }}
                        </div>
                        <p style="font-size: 14px; font-weight: 500; color: #111827; margin: 0;">{{ $aluno->name }}</p>
                    </div>
                </td>
                <td style="padding: 14px 20px; font-size: 13px; color: #6B7280;">{{ $aluno->registration_number }}</td>
                <td style="padding: 14px 20px;">
                    @if($aluno->is_atypical)
                        <span style="background: #F3E8FF; color: #7E22CE; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Atípico</span>
                        <x-cid-badges :aluno="$aluno" />
                    @else
                        <span style="background: #F3F4F6; color: #6B7280; font-size: 11px; padding: 3px 8px; border-radius: 20px;">Típico</span>
                    @endif
                </td>
                <td style="padding: 14px 20px; text-align: right;">
                    <a href="{{ route('professor.alunos.show', $aluno) }}"
                       style="font-size: 13px; color: #004B8D; text-decoration: none; font-weight: 500;">Ver aluno</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 48px; text-align: center; color: #9CA3AF; font-size: 14px;">
                    Nenhum aluno matriculado nesta turma.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection