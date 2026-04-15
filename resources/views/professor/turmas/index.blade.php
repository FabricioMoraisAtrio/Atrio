@extends('layouts.app')
@section('title', 'Turmas')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Turmas</h1>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Turmas associadas à sua escola</p>
</div>

@if($turmas->isEmpty())
    <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 48px; text-align: center;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 12px; display: block;">
            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
        </svg>
        <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Nenhuma turma cadastrada.</p>
    </div>
@else
    <div style="display: flex; flex-direction: column; gap: 10px;">
        @foreach($turmas as $turma)
        @php
            $cor = match($turma->shift) {
                'Matutino'   => ['bg' => '#E8F0F9', 'text' => '#004B8D'],
                'Vespertino' => ['bg' => '#E6F5F4', 'text' => '#009C8C'],
                default      => ['bg' => '#F3E8FF', 'text' => '#7C3AED'],
            };
        @endphp
        <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 14px; overflow: hidden;">

            {{-- Cabeçalho do card (clicável) --}}
            <div onclick="toggleTurma({{ $turma->id }})"
                 style="padding: 20px 24px; cursor: pointer; user-select: none; display: flex; align-items: center; justify-content: space-between;"
                 onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                <div style="display: flex; align-items: flex-start; gap: 14px; flex: 1;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: {{ $cor['bg'] }}; color: {{ $cor['text'] }}; font-size: 16px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ strtoupper(substr($turma->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 4px;">{{ $turma->name }}</div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span style="font-size: 11px; font-weight: 600; padding: 2px 9px; border-radius: 20px; background: {{ $cor['bg'] }}; color: {{ $cor['text'] }};">{{ $turma->shift }}</span>
                            <span style="font-size: 12px; color: #9CA3AF;">Ano letivo {{ $turma->year }}</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="text-align: right;">
                        <div style="font-size: 22px; font-weight: 700; color: #111827; line-height: 1;">{{ $turma->students_count }}</div>
                        <div style="font-size: 11px; color: #9CA3AF;">alunos</div>
                    </div>
                    <svg id="chevron-{{ $turma->id }}" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"
                         style="transition: transform 0.2s; flex-shrink: 0;">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </div>
            </div>

            {{-- Lista de alunos (recolhida por padrão) --}}
            <div id="turma-{{ $turma->id }}" style="display: none; border-top: 1px solid #F3F4F6;">
                @if($turma->students->isEmpty())
                    <div style="padding: 24px; text-align: center; font-size: 13px; color: #9CA3AF;">
                        Nenhum aluno matriculado.
                    </div>
                @else
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F9FAFB;">
                                <th style="text-align: left; padding: 10px 24px; font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Aluno</th>
                                <th style="text-align: left; padding: 10px 16px; font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                                <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Est. Caso</th>
                                <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">PAEE</th>
                                <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">PEI</th>
                                <th style="text-align: center; padding: 10px 10px; font-size: 10px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Laudo</th>
                                <th style="padding: 10px 24px;"></th>
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
                            <tr style="border-top: 1px solid #F9FAFB;"
                                onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 12px 24px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            {{ strtoupper(substr($aluno->name, 0, 1)) }}
                                        </div>
                                        <span style="font-size: 13px; font-weight: 500; color: #111827;">{{ $aluno->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 16px;">
                                    @if($aluno->is_atypical)
                                        @if($aluno->is_publico_alvo)
                                            <span style="background: #F3E8FF; color: #7E22CE; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px;">Público Alvo</span>
                                        @else
                                            <span style="background: #FEF3C7; color: #92400E; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 20px;">Atípico</span>
                                        @endif
                                    @else
                                        <span style="background: #F3F4F6; color: #6B7280; font-size: 10px; padding: 2px 7px; border-radius: 20px;">Típico</span>
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
                                        <span style="background: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 600; padding: 2px 7px; border-radius: 20px;">✓</span>
                                    @else
                                        <span style="background: #F3F4F6; color: #9CA3AF; font-size: 11px; padding: 2px 7px; border-radius: 20px;">—</span>
                                    @endif
                                </td>
                                @endforeach
                                <td style="padding: 12px 24px; text-align: right;">
                                    <a href="{{ route('professor.turmas.show', $turma) }}"
                                       style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 500;">Ver turma</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

<script>
function toggleTurma(id) {
    const panel = document.getElementById('turma-' + id);
    const chev  = document.getElementById('chevron-' + id);
    const open  = panel.style.display === 'none';
    panel.style.display = open ? 'block' : 'none';
    chev.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
}
</script>
@endsection
