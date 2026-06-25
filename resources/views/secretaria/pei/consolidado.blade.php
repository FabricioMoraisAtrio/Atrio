@extends('layouts.app')
@section('title', 'PEI — ' . $aluno->name)

@section('content')
@php
    use App\Http\Controllers\Secretaria\PeiConsolidadoController;

    $c     = $peiConsolidado?->content ?? [];
    $turma = $aluno->schoolClasses->first();
    $accent = '#004B8D';

    $inventario = PeiConsolidadoController::consolidarInventario($peiSubjects, $inventoryItems, $peiGlobal);

    $categorias = [
        'academica'      => ['label' => 'Metas Acadêmicas',       'cor' => '#004B8D'],
        'socioemocional' => ['label' => 'Metas Socioemocionais',  'cor' => '#009C8C'],
        'funcional'      => ['label' => 'Metas Funcionais',       'cor' => '#6D28D9'],
        'global'         => ['label' => 'Desenvolvimento Global', 'cor' => '#6D28D9'],
    ];

    $flagLabels = [
        'autonomia'    => 'Executa com autonomia',
        'suporte'      => 'Executa com suporte',
        'nao_executa'  => 'Ainda não executa',
        'nao_observado'=> 'Não observado',
    ];

    $flagCores = [
        'autonomia'    => '#059669',
        'suporte'      => '#D97706',
        'nao_executa'  => '#DC2626',
        'nao_observado'=> '#9CA3AF',
    ];

    $temInventario = collect($categorias)->keys()->some(fn($k) => !empty($inventario[$k]));

    $infoBlock = fn(string $title, string $sub, string $text, string $color = '#004B8D') =>
        '<div style="background: var(--info-bg); border: 1px solid var(--info-border); border-radius: 10px; padding: 18px 20px; margin-bottom: 20px;">
            <p style="font-size: 11px; font-weight: 700; color: ' . $color . '; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">' . e($title) . '</p>
            <p style="font-size: 11px; color: var(--text-3); font-style: italic; margin: 0 0 10px;">' . e($sub) . '</p>
            <p style="font-size: 13px; color: var(--text-2); white-space: pre-wrap; margin: 0; line-height: 1.6;">' . e($text) . '</p>
        </div>';

    $emptyBlock = fn(string $title, string $msg) =>
        '<div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;">
            <p style="font-size: 11px; font-weight: 700; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">' . e($title) . '</p>
            <p style="font-size: 12px; color: var(--text-4); font-style: italic; margin: 0;">' . e($msg) . '</p>
        </div>';
@endphp

<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.rotinas.documentos.pei') }}"
       style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para listagem de PEI
    </a>
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="background: var(--accent-bg); color: var(--accent-text); font-size: 12px; font-weight: 700; padding: 3px 12px; border-radius: 20px;">PEI</span>
                <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">{{ $aluno->name }}</h1>
            </div>
            <p style="font-size: 13px; color: var(--text-4); margin: 0;">
                Plano Educacional Individualizado · {{ date('Y') }}
                @if($turma) · {{ $turma->name }} {{ $turma->shift }} @endif
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            @can('pei.metas_gerenciar')
            <a href="{{ route('secretaria.alunos.metas-academicas.edit', $aluno) }}"
               style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; background: var(--accent); color: #fff;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 013 3L12 15l-4 1 1-4z"/></svg>
                Personalizar Metas
            </a>
            @endcan
            @if($peiConsolidado)
            <a href="{{ route('secretaria.documentos.pdf', $peiConsolidado) }}"
               style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid var(--border); color: var(--text-2);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Exportar PDF
            </a>
            @endif
        </div>
    </div>
</div>

{{-- A mensagem de sucesso já é exibida globalmente pelo layout --}}

{{-- ═══ OBJETIVOS DE APRENDIZAGEM — extraído do Estudo de Caso ═══ --}}
@php
    $temObjetivos = !empty($ec['objetivos_curto_prazo']) || !empty($ec['objetivos_medio_prazo']) || !empty($ec['objetivos_longo_prazo']);
@endphp
@if($temObjetivos)
<div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 16px;">Objetivos de Aprendizagem — extraído do Estudo de Caso</p>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        @foreach(['objetivos_curto_prazo' => 'Curto Prazo', 'objetivos_medio_prazo' => 'Médio Prazo', 'objetivos_longo_prazo' => 'Longo Prazo'] as $key => $label)
        @if(!empty($ec[$key]))
        <div>
            <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); margin: 0 0 6px;">{{ $label }}</p>
            <p style="font-size: 13px; color: var(--text-2); white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $ec[$key] }}</p>
        </div>
        @endif
        @endforeach
    </div>
</div>
@else
    {!! $emptyBlock('Objetivos de Aprendizagem', 'Preencha os objetivos no Estudo de Caso.') !!}
@endif

<form method="POST" action="{{ route('secretaria.alunos.pei-consolidado.update', $aluno) }}" id="form-pei">
@csrf @method('PUT')

{{-- ═══ INVENTÁRIO DE HABILIDADES ═══ --}}
@if($temInventario)
<div style="margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Metas de Habilidades — preenchido pelos professores</p>

    @foreach($categorias as $catKey => $cat)
    @php $itens = $inventario[$catKey] ?? []; @endphp
    @if(count($itens))
    <div style="border: 1px solid var(--border-sub); border-radius: 10px; overflow: hidden; margin-bottom: 16px;">
        <div style="padding: 12px 20px; border-bottom: 1px solid var(--border-sub); background: rgba(0,0,0,0.02);">
            <p style="font-size: 11px; font-weight: 700; color: {{ $cat['cor'] }}; letter-spacing: 1px; text-transform: uppercase; margin: 0;">{{ $cat['label'] }}</p>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: var(--bg-subtle);">
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); width: 36%;">Meta / Objetivo</th>
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); width: 20%;">Disciplina</th>
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); width: 18%;">Avaliação</th>
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3);">Observações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $i => $item)
                    <tr style="border-top: 1px solid var(--border-sub); {{ $i % 2 !== 0 ? 'background: #FAFAFA;' : '' }}">
                        <td style="padding: 10px 14px; color: var(--text-2); font-size: 12px;">{{ $item['meta'] }}</td>
                        <td style="padding: 10px 14px; font-size: 11px; color: var(--text-3);">{{ $item['subject_name'] }}</td>
                        <td style="padding: 10px 14px;">
                            @php $flag = $item['flag'] ?? null; @endphp
                            @if($flag)
                            <span style="font-size: 11px; font-weight: 600; color: {{ $flagCores[$flag] ?? 'var(--text-4)' }};">
                                {{ $flagLabels[$flag] ?? $flag }}
                            </span>
                            @else
                            <span style="font-size: 11px; color: var(--text-4);">—</span>
                            @endif
                        </td>
                        <td style="padding: 10px 14px; font-size: 11px; color: var(--text-3);">{{ $item['obs'] ?: '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endforeach
</div>
@else
    <div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Metas de Habilidades</p>
        <p style="font-size: 12px; color: var(--text-4); font-style: italic; margin: 0;">Nenhum professor preencheu as metas de habilidades ainda.</p>
        @can('pei.metas_gerenciar')
        <a href="{{ route('secretaria.alunos.metas-academicas.edit', $aluno) }}"
           style="display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 12px; font-weight: 600; color: var(--accent-text); text-decoration: none;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Personalizar metas acadêmicas que os professores irão preencher
        </a>
        @endcan
    </div>
@endif

{{-- ═══ ESTRATÉGIAS PEDAGÓGICAS ═══ --}}
@if(!empty($peiGlobal['estrategias_pedagogicas']))
    {!! $infoBlock('Estratégias Pedagógicas', 'Definidas no PEI.', $peiGlobal['estrategias_pedagogicas']) !!}
@endif

{{-- ═══ ADAPTAÇÕES CURRICULARES ═══ --}}
@if(!empty($ec['adaptacoes_necessarias']))
    {!! $infoBlock('Adaptações e/ou Adequações Curriculares', 'Extraído do Estudo de Caso.', $ec['adaptacoes_necessarias']) !!}
@else
    {!! $emptyBlock('Adaptações e/ou Adequações Curriculares', 'Preencha o campo "Necessidade de adaptações curriculares" no Estudo de Caso.') !!}
@endif

{{-- ═══ AVALIAÇÃO ═══ --}}
@if(!empty($peiGlobal['criterios_avaliacao']))
    {!! $infoBlock('Avaliação', 'Critérios definidos no PEI.', $peiGlobal['criterios_avaliacao']) !!}
@endif

{{-- ═══ EQUIPE RESPONSÁVEL ═══ --}}
@php
$equipeFields = [
    'equipe_titular'        => 'Professor(a) Titular / Regente',
    'equipe_soe'            => 'Orientador(a) Educacional (SOE)',
    'equipe_scp'            => 'Psicólogo(a) Escolar (SCP)',
    'equipe_saee'           => 'Coordenador(a) do SAEE',
    'equipe_psicologo'      => 'Psicólogo(a) / Terapeuta Externo',
    'equipe_psicopedagogo'  => 'Psicopedagogo(a)',
    'equipe_fisioterapeuta' => 'Fisioterapeuta / Ed. Físico',
    'equipe_at'             => 'Acompanhante Terapêutico (AT)',
];
$equipePreenchida = collect($equipeFields)->keys()->filter(fn($k) => !empty($ec[$k]))->isNotEmpty();
@endphp

@if($equipePreenchida)
<div style="background: var(--info-bg); border: 1px solid var(--info-border); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Equipe Responsável — extraído do Estudo de Caso</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
        @foreach($equipeFields as $field => $label)
        @if(!empty($ec[$field]))
        <div>
            <p style="font-size: 11px; font-weight: 600; color: var(--text-3); margin: 0 0 2px;">{{ $label }}</p>
            <p style="font-size: 13px; color: var(--text-2); margin: 0;">{{ $ec[$field] }}</p>
        </div>
        @endif
        @endforeach
    </div>
</div>
@else
    {!! $emptyBlock('Equipe Responsável', 'Preencha a seção "Equipe Responsável" no Estudo de Caso.') !!}
@endif

{{-- ═══ OBSERVAÇÕES ADICIONAIS ═══ --}}
<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 24px;">

    <div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 24px; margin-bottom: 28px;">
        <div style="border-left: 3px solid var(--accent); padding: 4px 0 4px 14px; margin-bottom: 16px;">
            <p style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0 0 2px;">Observações Complementares</p>
            <p style="font-size: 12px; color: var(--text-4); font-style: italic; margin: 0;">Informações adicionais não contempladas nos campos acima.</p>
        </div>
        <textarea name="observacoes" rows="4"
                  placeholder="Observações gerais sobre o aluno..."
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">{{ old('observacoes', $c['observacoes'] ?? '') }}</textarea>
    </div>

    <div style="display: flex; gap: 12px; align-items: center;">
        <button type="submit"
                style="background: var(--accent); color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Salvar
        </button>
        <a href="{{ route('secretaria.rotinas.documentos.pei') }}"
           style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
            Cancelar
        </a>
    </div>
</form>

@endsection
