@extends('layouts.app')
@section('title', 'Documento Final — ' . $aluno->name)

@section('content')
<div style="max-width: 760px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.show', $aluno) }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para {{ $aluno->name }}
        </a>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Documento Final</h1>
                <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Consolidado de todos os documentos de {{ $aluno->name }} — Ano letivo {{ date('Y') }}</p>
            </div>
            <button onclick="window.print()"
                    style="background: #004B8D; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
                Imprimir
            </button>
        </div>
    </div>

    {{-- Card: Identificação do Aluno --}}
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
        <h2 style="font-size: 14px; font-weight: 700; color: #111827; margin: 0 0 16px; text-transform: uppercase; letter-spacing: 0.5px;">Identificação do Estudante</h2>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Nome</p>
                <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">{{ $aluno->name }}</p>
            </div>
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Matrícula</p>
                <p style="font-size: 14px; color: #374151; margin: 0;">{{ $aluno->registration_number }}</p>
            </div>
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Nascimento</p>
                <p style="font-size: 14px; color: #374151; margin: 0;">{{ $aluno->birth_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Turma(s)</p>
                <p style="font-size: 14px; color: #374151; margin: 0;">{{ $aluno->schoolClasses->pluck('name')->join(', ') ?: '—' }}</p>
            </div>
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px;">Perfil</p>
                @if($aluno->is_atypical)
                    <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Atípico</span>
                @else
                    <span style="background: #F3F4F6; color: #6B7280; font-size: 12px; padding: 3px 10px; border-radius: 20px;">Típico</span>
                @endif
            </div>
            @if($aluno->is_atypical)
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Condições</p>
                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                    @foreach(['cid_autismo' => 'TEA', 'cid_tdah' => 'TDAH', 'cid_down' => 'Down', 'cid_deficiencia_intelectual' => 'D. Intelectual', 'cid_deficiencia_visual' => 'D. Visual', 'cid_deficiencia_auditiva' => 'D. Auditiva', 'cid_outros' => 'Outros'] as $field => $label)
                        @if($aluno->$field)
                            <span style="background: #F5EDE6; color: #7C3700; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;">{{ $label }}</span>
                            @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                                <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px;">Nível {{ $aluno->tea_nivel_suporte }}</span>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Estudo de Caso --}}
    @if($estudoCaso)
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h2 style="font-size: 14px; font-weight: 700; color: #7C3700; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">Estudo de Caso</h2>
            <span style="font-size: 12px; color: #9CA3AF;">{{ $estudoCaso->updated_at->format('d/m/Y') }} · {{ $estudoCaso->author->name ?? '—' }}</span>
        </div>
        @php $ec = $estudoCaso->content ?? []; @endphp
        @foreach(['historico' => 'Histórico', 'barreiras' => 'Barreiras', 'potencialidades' => 'Potencialidades', 'observacoes_livres' => 'Observações Livres'] as $key => $label)
            @if(!empty($ec[$key]))
            <div style="margin-bottom: 14px;">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">{{ $label }}</p>
                <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.6; white-space: pre-line;">{{ $ec[$key] }}</p>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- PAEE --}}
    @if($paee)
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h2 style="font-size: 14px; font-weight: 700; color: #009C8C; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">PAEE</h2>
            <span style="font-size: 12px; color: #9CA3AF;">{{ $paee->updated_at->format('d/m/Y') }} · {{ $paee->author->name ?? '—' }}</span>
        </div>
        @php $p = $paee->content ?? []; @endphp
        @foreach(['cronograma' => 'Cronograma', 'recursos' => 'Recursos', 'acessibilidade' => 'Acessibilidade', 'parcerias' => 'Parcerias', 'observacoes_livres' => 'Observações Livres'] as $key => $label)
            @if(!empty($p[$key]))
            <div style="margin-bottom: 14px;">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">{{ $label }}</p>
                <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.6; white-space: pre-line;">{{ $p[$key] }}</p>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- PEIs por professor --}}
    @if($peis->isNotEmpty())
        <h3 style="font-size: 13px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px;">PEIs — Por Professor</h3>

        @foreach($peis as $pei)
        @php $pc = $pei->content ?? []; @endphp
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ strtoupper(substr($pei->author->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">{{ $pei->author->name ?? '—' }}</p>
                        @if(!empty($pc['materia']))
                            <p style="font-size: 12px; color: #004B8D; margin: 0;">{{ $pc['materia'] }}</p>
                        @endif
                    </div>
                </div>
                <span style="font-size: 12px; color: #9CA3AF;">{{ $pei->updated_at->format('d/m/Y') }}</span>
            </div>

            @if(!empty($pc['objetivos']))
            <div style="margin-bottom: 14px;">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Objetivos Pedagógicos</p>
                <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.6; white-space: pre-line;">{{ $pc['objetivos'] }}</p>
            </div>
            @endif

            @if(!empty($pc['adaptacoes']))
            <div style="margin-bottom: 14px;">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Adaptações Curriculares</p>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach($pc['adaptacoes'] as $tag)
                        <span style="background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 500; padding: 3px 10px; border-radius: 20px;">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($pc['avaliacao']))
            <div style="margin-bottom: 14px;">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Adaptações de Avaliação</p>
                <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.6; white-space: pre-line;">{{ $pc['avaliacao'] }}</p>
            </div>
            @endif

            @if(!empty($pc['observacoes_livres']))
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Observações Livres</p>
                <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.6; white-space: pre-line;">{{ $pc['observacoes_livres'] }}</p>
            </div>
            @endif
        </div>
        @endforeach
    @endif

    @if(!$estudoCaso && !$paee && $peis->isEmpty())
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 48px; text-align: center; color: #9CA3AF; font-size: 14px;">
            Nenhum documento preenchido para {{ date('Y') }}.
        </div>
    @endif

</div>

<style>
@media print {
    nav, aside, .no-print { display: none !important; }
    body { background: white; }
}
</style>
@endsection
