@extends('layouts.app')
@section('title', 'Jornada Alimentar')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Jornada Alimentar</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">Perfil alimentar dos estudantes cadastrados</p>
    </div>
    <a href="{{ route('secretaria.seletividade.export') }}"
       style="display: inline-flex; align-items: center; gap: 8px; background: var(--accent); color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(0,75,141,0.25);">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/>
        </svg>
        Exportar para Lanche
    </a>
</div>

@if(session('success'))
    <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
    @if($alunos->isEmpty())
        <div style="padding: 60px; text-align: center; color: var(--text-3); font-size: 14px;">
            Nenhum estudante atípico cadastrado.
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--bg-sidebar);">
                    <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Estudante</th>
                    <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Turma</th>
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Aceita</th>
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Tolera</th>
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Recusa</th>
                    <th style="padding: 12px 20px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($alunos as $aluno)
                @php
                    $turma   = $aluno->schoolClasses->first();
                    $aceita  = $aluno->foodItems->where('status', 'aceita')->count();
                    $tolera  = $aluno->foodItems->where('status', 'tolera')->count();
                    $recusa  = $aluno->foodItems->where('status', 'recusa')->count();
                    $total   = $aluno->foodItems->count();
                @endphp
                <tr style="border-top: 1px solid var(--border);"
                    onmouseover="this.style.background='var(--bg-sidebar)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 14px 20px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent-bg); color: var(--accent-text); font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ strtoupper(substr($aluno->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0;">{{ $aluno->name }}</p>
                                @if($total === 0)
                                    <p style="font-size: 11px; color: var(--text-3); margin: 0; font-style: italic;">Sem perfil alimentar</p>
                                @else
                                    <p style="font-size: 11px; color: var(--text-3); margin: 0;">{{ $total }} alimento(s) registrado(s)</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 14px 20px; font-size: 13px; color: var(--text-3);">
                        {{ $turma?->name ?? '—' }}
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        @if($aceita > 0)
                            <span style="background: var(--success-bg); color: var(--success); font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">{{ $aceita }}</span>
                        @else
                            <span style="color: var(--text-3); font-size: 13px;">—</span>
                        @endif
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        @if($tolera > 0)
                            <span style="background: var(--warning-bg); color: var(--warning); font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">{{ $tolera }}</span>
                        @else
                            <span style="color: var(--text-3); font-size: 13px;">—</span>
                        @endif
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        @if($recusa > 0)
                            <span style="background: var(--danger-bg); color: var(--danger); font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">{{ $recusa }}</span>
                        @else
                            <span style="color: var(--text-3); font-size: 13px;">—</span>
                        @endif
                    </td>
                    <td style="padding: 14px 20px; text-align: right;">
                        <a href="{{ route('secretaria.seletividade.show', $aluno) }}"
                           style="font-size: 12px; color: var(--accent-text); text-decoration: none; font-weight: 500;">
                            {{ $total === 0 ? 'Cadastrar' : 'Gerenciar' }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
