@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div style="margin-bottom: 28px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 4px;">Painel da Secretaria</h1>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Visão geral da escola para hoje</p>
</div>

{{-- Métricas --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff; border-radius: 12px; padding: 24px; border: 1px solid #F3F4F6;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <span style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Alunos cadastrados</span>
            <div style="width: 36px; height: 36px; background: #E8F0F9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
        </div>
        <div style="font-size: 32px; font-weight: 700; color: #111827;">{{ \App\Models\Student::count() }}</div>
        <div style="font-size: 12px; color: #9CA3AF; margin-top: 4px;">de {{ auth()->user()->school?->max_students ?? '—' }} no plano</div>
    </div>

    <div style="background: #fff; border-radius: 12px; padding: 24px; border: 1px solid #F3F4F6;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <span style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Turmas ativas</span>
            <div style="width: 36px; height: 36px; background: #E6F5F4; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#009C8C" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
        </div>
        <div style="font-size: 32px; font-weight: 700; color: #111827;">{{ \App\Models\SchoolClass::where('year', date('Y'))->count() }}</div>
        <div style="font-size: 12px; color: #9CA3AF; margin-top: 4px;">Ano letivo {{ date('Y') }}</div>
    </div>

    <div style="background: #fff; border-radius: 12px; padding: 24px; border: 1px solid #F3F4F6;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <span style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Documentos gerados</span>
            <div style="width: 36px; height: 36px; background: #F5EDE6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7C3700" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                </svg>
            </div>
        </div>
        <div style="font-size: 32px; font-weight: 700; color: #111827;">{{ \App\Models\Document::count() }}</div>
        <div style="font-size: 12px; color: #9CA3AF; margin-top: 4px;">PEI, PAEE e Estudo de Caso</div>
    </div>
</div>

{{-- Card de pendentes --}}
@if($pendentes->isNotEmpty())
<div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <p style="font-size: 13px; font-weight: 600; color: #92400E; margin: 0;">
                {{ $pendentes->count() }} aluno(s) com documentos pendentes
            </p>
        </div>
        <a href="{{ route('secretaria.alunos.index') }}"
           style="font-size: 12px; color: #92400E; text-decoration: none; font-weight: 600;">
            Ver todos →
        </a>
    </div>
    <div style="display: flex; flex-direction: column; gap: 8px;">
        @foreach($pendentes->take(5) as $item)
            <a href="{{ route('secretaria.alunos.show', $item['aluno']) }}"
               style="background: #fff; border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; border: 1px solid #FDE68A; text-decoration: none;"
               onmouseover="this.style.borderColor='#F59E0B'"
               onmouseout="this.style.borderColor='#FDE68A'">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #FEF3C7; color: #92400E; font-size: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($item['aluno']->name, 0, 1)) }}
                    </div>
                    <span style="font-size: 13px; font-weight: 500; color: #111827;">{{ $item['aluno']->name }}</span>
                </div>
                <div style="display: flex; gap: 4px;">
                    @foreach($item['faltando'] as $tipo)
                        <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                            {{ strtoupper(str_replace('_', ' ', $tipo)) }}
                        </span>
                    @endforeach
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

{{-- Acesso rápido --}}
<div style="margin-bottom: 12px; font-size: 13px; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: 0.5px;">
    Acesso rápido
</div>
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
    <a href="{{ route('secretaria.turmas.index') }}"
       style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 20px; text-decoration: none; display: flex; align-items: center; gap: 14px;"
       onmouseover="this.style.borderColor='#004B8D'"
       onmouseout="this.style.borderColor='#F3F4F6'">
        <div style="width: 40px; height: 40px; background: #E8F0F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 14px; font-weight: 600; color: #111827;">Turmas</div>
            <div style="font-size: 12px; color: #9CA3AF;">Gerenciar turmas e vínculos</div>
        </div>
    </a>

    <a href="{{ route('secretaria.alunos.index') }}"
       style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 20px; text-decoration: none; display: flex; align-items: center; gap: 14px;"
       onmouseover="this.style.borderColor='#009C8C'"
       onmouseout="this.style.borderColor='#F3F4F6'">
        <div style="width: 40px; height: 40px; background: #E6F5F4; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#009C8C" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 14px; font-weight: 600; color: #111827;">Alunos</div>
            <div style="font-size: 12px; color: #9CA3AF;">Cadastrar e gerenciar alunos</div>
        </div>
    </a>

    <a href="{{ route('secretaria.usuarios.index') }}"
       style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 20px; text-decoration: none; display: flex; align-items: center; gap: 14px;"
       onmouseover="this.style.borderColor='#7C3700'"
       onmouseout="this.style.borderColor='#F3F4F6'">
        <div style="width: 40px; height: 40px; background: #F5EDE6; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3700" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
        <div>
            <div style="font-size: 14px; font-weight: 600; color: #111827;">Usuários</div>
            <div style="font-size: 12px; color: #9CA3AF;">Professores e responsáveis</div>
        </div>
    </a>
</div>
@endsection