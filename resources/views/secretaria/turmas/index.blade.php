@extends('layouts.app')
@section('title', term('turmas'))

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">{{ term('turmas') }}</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">{{ $turmas->count() }} {{ strtolower(term('turmas')) }} cadastradas</p>
    </div>
    <a href="{{ route('secretaria.turmas.create') }}"
       style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Nova {{ strtolower(term('turma')) }}
    </a>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="display: flex; flex-direction: column; gap: 8px;">
    @forelse($turmas as $turma)
    @php
        $turnoColor = match($turma->shift) {
            'Matutino'   => ['bg' => '#E8F0F9', 'text' => '#004B8D'],
            'Vespertino' => ['bg' => '#E6F5F4', 'text' => '#009C8C'],
            default      => ['bg' => '#F3E8FF', 'text' => '#7C3AED'],
        };
    @endphp
    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">

        {{-- Cabeçalho da turma (clicável para expandir) --}}
        <div onclick="toggleTurma({{ $turma->id }})"
             style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; cursor: pointer; user-select: none;"
             onmouseover="this.style.background='var(--bg-sidebar)'" onmouseout="this.style.background='transparent'">
            <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #E8F0F9; color: #004B8D; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    {{ strtoupper(substr($turma->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size: 15px; font-weight: 600; color: var(--text-1);">{{ $turma->name }}</div>
                    <div style="display: flex; gap: 8px; align-items: center; margin-top: 3px;">
                        <span style="font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: {{ $turnoColor['bg'] }}; color: {{ $turnoColor['text'] }};">{{ $turma->shift }}</span>
                        <span style="font-size: 12px; color: var(--text-3);">{{ $turma->year }}</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <span style="background: #E6F5F4; color: #009C8C; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">
                    {{ $turma->students_count }} {{ strtolower(term('alunos')) }}
                </span>
                <div style="display: flex; align-items: center; gap: 12px;" onclick="event.stopPropagation()">
                    <a href="{{ route('secretaria.turmas.edit', $turma) }}"
                       style="font-size: 13px; color: var(--text-3); text-decoration: none;">Editar</a>
                    <form method="POST" action="{{ route('secretaria.turmas.destroy', $turma) }}" style="display: inline;">
                        @csrf @method('DELETE')
                        <button type="button" data-confirm="Remover {{ strtolower(term('turma')) }}?"
                                style="font-size: 13px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 0;">
                            Remover
                        </button>
                    </form>
                </div>
                <svg id="chevron-{{ $turma->id }}" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2"
                     style="transition: transform 0.2s; flex-shrink: 0;">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        </div>

        {{-- Lista de alunos (recolhida por padrão) --}}
        <div id="turma-{{ $turma->id }}" style="display: none; border-top: 1px solid var(--border);">
            @if($turma->students->isEmpty())
                <div style="padding: 24px 20px; text-align: center; font-size: 13px; color: var(--text-3);">
                    Nenhum {{ strtolower(term('aluno')) }} matriculado.
                </div>
            @else
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-sidebar);">
                            <th style="text-align: left; padding: 10px 20px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">{{ term('aluno') }}</th>
                            <th style="text-align: left; padding: 10px 20px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Matrícula</th>
                            <th style="text-align: left; padding: 10px 20px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                            <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Est. Caso</th>
                            <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">PAEE</th>
                            <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">PEI</th>
                            <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Laudo</th>
                            <th style="padding: 10px 20px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turma->students as $aluno)
                        @php
                            $docs     = $aluno->documents;
                            $temEC    = $docs->firstWhere('type', 'estudo_caso');
                            $temPAEE  = $docs->firstWhere('type', 'paee');
                            $temPEI   = $docs->whereIn('type', ['pei', 'pei_consolidado'])->first();
                            $temLaudo = $aluno->laudos->isNotEmpty();
                        @endphp
                        <tr style="border-top: 1px solid var(--border);"
                            onmouseover="this.style.background='var(--bg-sidebar)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 12px 20px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size: 13px; font-weight: 500; color: var(--text-1);">{{ $aluno->name }}</span>
                                </div>
                            </td>
                            <td style="padding: 12px 20px; font-size: 12px; color: var(--text-3);">{{ $aluno->registration_number }}</td>
                            <td style="padding: 12px 20px;">
                                @if($aluno->is_atypical)
                                    @if($aluno->is_publico_alvo)
                                        <span style="background: #F3E8FF; color: #7E22CE; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
                                    @else
                                        <span style="background: #FEF3C7; color: #92400E; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px;">Atípico</span>
                                    @endif
                                @else
                                    <span style="background: #F3F4F6; color: #6B7280; font-size: 10px; padding: 2px 7px; border-radius: 20px;">{{ term('nao_publico_alvo') }}</span>
                                @endif
                            </td>
                            @foreach([
                                ['ok' => $temEC],
                                ['ok' => $temPAEE],
                                ['ok' => $temPEI],
                                ['ok' => $temLaudo],
                            ] as $col)
                            <td style="padding: 12px 10px; text-align: center;">
                                @if($col['ok'])
                                    <span style="background: #ECFDF5; color: #065F46; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px; white-space: nowrap;">✓</span>
                                @else
                                    <span style="background: #F3F4F6; color: #9CA3AF; font-size: 10px; padding: 2px 7px; border-radius: 20px;">—</span>
                                @endif
                            </td>
                            @endforeach
                            <td style="padding: 12px 20px; text-align: right;">
                                <a href="{{ route('secretaria.alunos.show', $aluno) }}?back={{ urlencode(url()->current()) }}"
                                   style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 500;">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    @empty
    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); padding: 48px; text-align: center; color: var(--text-3); font-size: 14px;">
        Nenhuma {{ strtolower(term('turma')) }} cadastrada.
    </div>
    @endforelse
</div>

<script>
function toggleTurma(id) {
    const panel  = document.getElementById('turma-' + id);
    const chev   = document.getElementById('chevron-' + id);
    const open   = panel.style.display === 'none';
    panel.style.display = open ? 'block' : 'none';
    chev.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
}

document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const openId = params.get('open');
    if (openId) {
        const panel = document.getElementById('turma-' + openId);
        const chev  = document.getElementById('chevron-' + openId);
        if (panel) {
            panel.style.display = 'block';
            if (chev) chev.style.transform = 'rotate(180deg)';
            panel.closest('[style*="border-radius: 12px"]')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});
</script>
@endsection
