@php
    $aluno  = $documento->student;
    $c      = $documento->content ?? [];
    $turma  = $aluno->schoolClasses->first();
    $school = $aluno->school;

    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $idade = $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') . ' (' . $aluno->birth_date->age . ' anos)' : '—';

    $val = fn($key) => $c[$key] ?? '';

    $dataElab = $val('data_elaboracao')
        ? \Carbon\Carbon::parse($val('data_elaboracao'))->format('d/m/Y')
        : date('d/m/Y');
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9.5px;
        color: #1a1a1a;
        padding: 28px 36px;
        line-height: 1.5;
    }

    /* Cabeçalho */
    .doc-header { border-bottom: 2px solid #1a1a1a; padding-bottom: 12px; margin-bottom: 16px; }
    .doc-header-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
    .doc-school { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-type { font-size: 9px; color: #555; }
    .doc-title { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; margin: 8px 0 0; }

    /* Identificação */
    .id-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ccc; padding: 4px 8px; font-size: 9px; }
    .id-label { background: #f0f0f0; font-weight: bold; width: 110px; color: #444; white-space: nowrap; }

    /* Seção */
    .section { margin-bottom: 16px; }
    .section-header {
        border-left: 3px solid #7C3700;
        padding: 3px 0 3px 8px;
        margin-bottom: 10px;
    }
    .section-title { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #1a1a1a; }
    .section-sub { font-size: 8.5px; color: #888; font-style: italic; margin-top: 1px; }

    /* Campo */
    .field { margin-bottom: 12px; }
    .field-label { font-size: 9px; font-weight: bold; color: #333; margin-bottom: 4px; }
    .field-value {
        font-size: 9.5px;
        color: #1a1a1a;
        border-bottom: 1px solid #ccc;
        min-height: 28px;
        padding: 3px 0 6px;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .field-value.tall { min-height: 50px; }
    .field-value.empty { color: #bbb; font-style: italic; }

    /* Grid 2 colunas */
    .grid-2 { display: table; width: 100%; }
    .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; }
    .grid-2 .col:first-child { padding-right: 16px; }

    /* Assinaturas */
    .sig-box { margin-top: 20px; border-top: 1px solid #ccc; padding-top: 14px; }
    .sig-line { display: inline-block; border-bottom: 1px solid #555; width: 200px; margin-right: 8px; height: 16px; vertical-align: bottom; }
    .sig-label { font-size: 8.5px; color: #555; margin-top: 2px; }

    .footer { font-size: 8px; color: #999; text-align: center; margin-top: 10px; }
    .page-break { page-break-after: always; }
    hr.divider { border: none; border-top: 1px solid #e0e0e0; margin: 14px 0; }
</style>

{{-- ══ CABEÇALHO ══ --}}
<div class="doc-header">
    <div class="doc-header-top">
        <div>
            <div class="doc-school">{{ $school?->name }}</div>
            <div class="doc-type">Atendimento Educacional Especializado</div>
        </div>
        <div style="text-align: right; font-size: 8.5px; color: #555;">
            Matrícula: {{ $aluno->registration_number }}<br>
            Ano Letivo: {{ $documento->year }}
        </div>
    </div>
    <div class="doc-title">Estudo de Caso</div>
</div>

{{-- ══ IDENTIFICAÇÃO ══ --}}
<table class="id-table">
    <tr>
        <td class="id-label">Escola</td>
        <td colspan="3">{{ $school?->name }}</td>
    </tr>
    <tr>
        <td class="id-label">Aluno(a)</td>
        <td colspan="3" style="font-weight: bold;">{{ $aluno->name }}</td>
    </tr>
    <tr>
        <td class="id-label">Data de Nascimento</td>
        <td>{{ $idade }}</td>
        <td class="id-label" style="width: 80px;">Turma / Turno</td>
        <td>{{ $turma ? $turma->name . ' · ' . $turma->shift : '—' }} &nbsp;&nbsp; Ano: {{ $documento->year }}</td>
    </tr>
    @if($diagnostico)
    <tr>
        <td class="id-label">Diagnóstico / Laudo</td>
        <td colspan="3">{{ $diagnostico }}</td>
    </tr>
    @endif
    @if($val('contexto_familiar'))
    <tr>
        <td class="id-label">Contexto Familiar</td>
        <td colspan="3">{{ $val('contexto_familiar') }}</td>
    </tr>
    @endif
</table>

{{-- ══ HISTÓRICO ESCOLAR ══ --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Histórico Escolar</div>
        <div class="section-sub">Trajetória e experiências anteriores.</div>
    </div>

    <div class="field">
        <div class="field-label">Resumo da vida escolar (escolas anteriores, retenções ou avanços)</div>
        <div class="field-value tall {{ !$val('historico_escolar') ? 'empty' : '' }}">{{ $val('historico_escolar') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Frequência e assiduidade</div>
        <div class="field-value {{ !$val('frequencia_assiduidade') ? 'empty' : '' }}">{{ $val('frequencia_assiduidade') ?: ' ' }}</div>
    </div>
</div>

<hr class="divider">

{{-- ══ OBSERVAÇÕES PEDAGÓGICAS ══ --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Observações Pedagógicas</div>
        <div class="section-sub">Comportamento, aprendizagem e interação.</div>
    </div>

    <div class="field">
        <div class="field-label">Nível de desenvolvimento e aprendizagem atual</div>
        <div class="field-value tall {{ !$val('nivel_desenvolvimento') ? 'empty' : '' }}">{{ $val('nivel_desenvolvimento') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Comportamento em sala de aula e rotina</div>
        <div class="field-value tall {{ !$val('comportamento_sala') ? 'empty' : '' }}">{{ $val('comportamento_sala') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Como interage com colegas, professores e equipe</div>
        <div class="field-value {{ !$val('interacao_colegas') ? 'empty' : '' }}">{{ $val('interacao_colegas') ?: ' ' }}</div>
    </div>
</div>

<p class="footer">{{ $aluno->name }} · Estudo de Caso · {{ $documento->year }} &nbsp;|&nbsp; Página 1</p>
<div class="page-break"></div>

{{-- ══ PÁGINA 2 ══ --}}
<div class="doc-header" style="border-bottom: 1px solid #ccc; padding-bottom: 8px; margin-bottom: 16px;">
    <div style="display: flex; justify-content: space-between;">
        <div class="doc-school">{{ $school?->name }}</div>
        <div style="font-size: 8.5px; color: #555;">{{ $aluno->name }} &nbsp;·&nbsp; Estudo de Caso &nbsp;·&nbsp; {{ $documento->year }}</div>
    </div>
</div>

{{-- ══ BARREIRAS IDENTIFICADAS ══ --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Barreiras Identificadas</div>
        <div class="section-sub">Dificuldades e limitações encontradas.</div>
    </div>

    <div class="field">
        <div class="field-label">Desafios na assimilação de conteúdos</div>
        <div class="field-value tall {{ !$val('desafios_conteudo') ? 'empty' : '' }}">{{ $val('desafios_conteudo') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Barreiras físicas, de comunicação ou atitudinais</div>
        <div class="field-value {{ !$val('barreiras_fisicas') ? 'empty' : '' }}">{{ $val('barreiras_fisicas') ?: ' ' }}</div>
    </div>
</div>

<hr class="divider">

{{-- ══ POTENCIALIDADES ══ --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Potencialidades</div>
        <div class="section-sub">Habilidades e pontos fortes.</div>
    </div>

    <div class="field">
        <div class="field-label">Áreas de maior interesse e motivação</div>
        <div class="field-value tall {{ !$val('interesses_motivacao') ? 'empty' : '' }}">{{ $val('interesses_motivacao') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Habilidades cognitivas, motoras e sociais de destaque</div>
        <div class="field-value {{ !$val('habilidades_destaque') ? 'empty' : '' }}">{{ $val('habilidades_destaque') ?: ' ' }}</div>
    </div>
</div>

<hr class="divider">

{{-- ══ ENCAMINHAMENTOS ══ --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Encaminhamentos</div>
        <div class="section-sub">Sugestões e ações pedagógicas.</div>
    </div>

    <div class="field">
        <div class="field-label">Estratégias e metodologias a serem adotadas em sala de aula</div>
        <div class="field-value tall {{ !$val('estrategias_sala') ? 'empty' : '' }}">{{ $val('estrategias_sala') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Necessidade de adaptações curriculares ou materiais específicos</div>
        <div class="field-value {{ !$val('adaptacoes_necessarias') ? 'empty' : '' }}">{{ $val('adaptacoes_necessarias') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Encaminhamentos para redes de apoio (psicologia, fonoaudiologia, etc.)</div>
        <div class="field-value {{ !$val('encaminhamentos_rede') ? 'empty' : '' }}">{{ $val('encaminhamentos_rede') ?: ' ' }}</div>
    </div>
</div>

<hr class="divider">

{{-- ══ ASSINATURAS ══ --}}
<div class="sig-box">
    <div style="font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; color: #444;">
        Termo de Ciência e Concordância
    </div>

    <div style="display: table; width: 100%; margin-bottom: 20px;">
        <div style="display: table-cell; width: 50%; padding-right: 20px;">
            <div style="font-size: 9px; font-weight: bold; margin-bottom: 2px;">Elaborado por</div>
            <div style="font-size: 9px; color: #333; margin-bottom: 12px;">
                {{ $val('elaborado_por') ?: '___________________________________' }}
            </div>
            <div class="sig-label">Assinatura &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Data: {{ $dataElab }}</div>
            <span class="sig-line"></span>
        </div>
        <div style="display: table-cell; width: 50%;">
            <div style="font-size: 9px; font-weight: bold; margin-bottom: 14px;">Assinatura do(a) Responsável pelo(a) Estudante</div>
            <span class="sig-line"></span>
            <div class="sig-label">___/___/________</div>
        </div>
    </div>

    @foreach(['Psicólogo(a) / Terapeuta', 'Orientador(a) Educacional', 'Coordenador(a) Pedagógico(a)'] as $sig)
    <div style="margin-bottom: 16px;">
        <div style="font-size: 9px; color: #555; margin-bottom: 4px;">{{ $sig }}</div>
        <span class="sig-line" style="width: 240px;"></span>
        &nbsp;&nbsp; ___/___/________
    </div>
    @endforeach
</div>

<p class="footer">{{ $aluno->name }} · Estudo de Caso · {{ $documento->year }} &nbsp;|&nbsp; Página 2</p>
