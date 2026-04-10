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

    // Imagens em base64 para DomPDF
    $logoB64  = null;
    $photoB64 = null;
    if ($school?->logo) {
        $logoPath = storage_path('app/public/' . $school->logo);
        if (file_exists($logoPath)) {
            $mime = mime_content_type($logoPath);
            $logoB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    }
    if ($aluno->photo) {
        $photoPath = storage_path('app/public/' . $aluno->photo);
        if (file_exists($photoPath)) {
            $mime = mime_content_type($photoPath);
            $photoB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
        }
    }

    $accent = '#7C3700';
    $accentBg = '#F8F0E8';

    // Carregar PEI e inventário de metas
    $pei = \App\Models\Document::where('student_id', $aluno->id)
        ->where('year', $documento->year)
        ->where('type', 'pei')
        ->first();
    $peiSubjects = $pei ? ($pei->content['subjects'] ?? []) : [];

    $allMetaIds = collect($peiSubjects)
        ->flatMap(fn($s) => array_keys($s['metas'] ?? []))
        ->map(fn($id) => (int) $id)->unique()->values()->all();

    $inventoryItems = $allMetaIds
        ? \App\Models\SubjectInventoryItem::withoutGlobalScopes()
            ->whereIn('id', $allMetaIds)->get()->keyBy('id')
        : collect();

    $categorias_inv = [
        'academica'      => 'Objetivos Curriculares',
        'socioemocional' => 'Desenvolvimento Socioemocional',
        'global'         => 'Desenvolvimento Global',
    ];

    // Helper: renderiza textarea como lista de itens (uma linha = um bullet)
    $asList = function(string $text): string {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        if (empty($lines)) return '';
        $out = '';
        foreach ($lines as $line) {
            $out .= '<div style="display:table;width:100%;margin-bottom:2px;">'
                  . '<span style="display:table-cell;width:10px;font-size:9px;">•</span>'
                  . '<span style="display:table-cell;font-size:9px;color:#1a1a1a;">' . e($line) . '</span>'
                  . '</div>';
        }
        return $out;
    };
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9.5px;
        color: #1a1a1a;
        padding: 0;
        line-height: 1.5;
    }

    /* ── Layout de página ── */
    .page { padding: 0 36px 20px; }
    .page-next { padding: 20px 36px 20px; }
    .page-break { page-break-after: always; }

    /* ── Cabeçalho ── */
    .doc-header {
        display: table;
        width: 100%;
        border-bottom: 3px solid {{ $accent }};
        padding-bottom: 12px;
        margin-bottom: 18px;
    }
    .doc-header-left  { display: table-cell; vertical-align: middle; width: 60px; }
    .doc-header-mid   { display: table-cell; vertical-align: middle; padding: 0 12px; }
    .doc-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 96px; }
    .doc-school { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #1a1a1a; }
    .doc-subtitle { font-size: 8.5px; color: #666; margin-top: 2px; }
    .doc-title {
        font-size: 13px; font-weight: bold; text-align: center;
        text-transform: uppercase; letter-spacing: 1.5px;
        color: {{ $accent }}; margin-top: 6px;
    }
    .school-logo { width: 52px; height: 52px; object-fit: contain; }
    .student-photo { width: 90px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc; }
    .photo-placeholder {
        width: 90px; height: 90px; border-radius: 6px; border: 1px dashed #ccc;
        display: table; text-align: center; vertical-align: middle;
    }

    /* ── Cabeçalho de continuação (páginas 2+) ── */
    .doc-header-sm {
        display: table; width: 100%;
        border-bottom: 1.5px solid {{ $accent }};
        padding-bottom: 6px; margin-bottom: 16px;
    }
    .doc-header-sm-l { display: table-cell; font-size: 9px; font-weight: bold; color: #333; text-transform: uppercase; }
    .doc-header-sm-r { display: table-cell; text-align: right; font-size: 8px; color: #888; }

    /* ── Identificação ── */
    .id-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; }
    .id-label { background: #f5f0ea; font-weight: bold; width: 120px; color: #444; white-space: nowrap; }

    /* ── Seção ── */
    .section { margin-bottom: 16px; }
    .section-header { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin-bottom: 10px; }
    .section-title { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }
    .section-sub { font-size: 8px; color: #888; font-style: italic; margin-top: 1px; }

    /* ── Campo ── */
    .field { margin-bottom: 12px; }
    .field-label { font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px; }
    .field-value {
        font-size: 9.5px; color: #1a1a1a;
        border-bottom: 1px solid #ddd;
        min-height: 26px; padding: 3px 2px 6px;
        white-space: pre-wrap; word-break: break-word;
    }
    .field-value.tall { min-height: 48px; }
    .field-value.taller { min-height: 64px; }
    .field-value.empty { color: #ccc; font-style: italic; }

    /* ── Grid ── */
    .grid-3 { display: table; width: 100%; }
    .grid-3 .col { display: table-cell; width: 33.3%; vertical-align: top; padding-right: 14px; }
    .grid-3 .col:last-child { padding-right: 0; }
    .grid-2 { display: table; width: 100%; }
    .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 14px; }
    .grid-2 .col:last-child { padding-right: 0; }

    /* ── Equipe ── */
    .equipe-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .equipe-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; }
    .equipe-label { background: #f5f0ea; font-weight: bold; width: 160px; color: #444; }

    /* ── Assinaturas ── */
    .sig-table { width: 100%; border-collapse: collapse; }
    .sig-table td { border: 1px solid #ddd; padding: 6px 10px; vertical-align: top; }
    .sig-role { font-size: 8.5px; font-weight: bold; color: #444; }
    .sig-name { font-size: 9px; color: #1a1a1a; margin-top: 2px; }
    .sig-line { border-bottom: 1px solid #888; display: block; margin-top: 14px; width: 100%; }
    .sig-date { font-size: 7.5px; color: #888; text-align: right; margin-top: 3px; }

    hr.div { border: none; border-top: 1px solid #efefef; margin: 14px 0; }
</style>

{{-- ══════════════════════════════════════════════ --}}
{{-- PÁGINA 1 --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="page">

{{-- Cabeçalho --}}
<div class="doc-header">
    <div class="doc-header-left">
        @if($logoB64)
            <img src="{{ $logoB64 }}" class="school-logo" alt="Logo">
        @else
            <div class="photo-placeholder"><span style="font-size:7px; color:#ccc;">Logo</span></div>
        @endif
    </div>
    <div class="doc-header-mid">
        <div class="doc-school">{{ $school?->name }}</div>
        <div class="doc-subtitle">Atendimento Educacional Especializado &nbsp;·&nbsp; Ano Letivo {{ $documento->year }}</div>
        <div class="doc-title">Estudo de Caso</div>
    </div>
    <div class="doc-header-right">
        @if($photoB64)
            <img src="{{ $photoB64 }}" class="student-photo" alt="Foto">
        @else
            <div class="photo-placeholder"><span style="font-size:7px; color:#ccc;">Foto</span></div>
        @endif
    </div>
</div>

{{-- Identificação --}}
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
        <td class="id-label" style="width: 90px;">Matrícula</td>
        <td>{{ $aluno->registration_number }}</td>
    </tr>
    <tr>
        <td class="id-label">Turma / Turno</td>
        <td>{{ $turma ? $turma->name . ' · ' . $turma->shift : '—' }}</td>
        <td class="id-label">Ano Letivo</td>
        <td>{{ $documento->year }}</td>
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

{{-- Histórico Escolar --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Histórico Escolar</div>
        <div class="section-sub">Trajetória e experiências anteriores.</div>
    </div>
    <div class="field">
        <div class="field-label">Resumo da vida escolar</div>
        <div class="field-value tall {{ !$val('historico_escolar') ? 'empty' : '' }}">{{ $val('historico_escolar') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Frequência e assiduidade</div>
        <div class="field-value {{ !$val('frequencia_assiduidade') ? 'empty' : '' }}">{{ $val('frequencia_assiduidade') ?: ' ' }}</div>
    </div>
</div>

<hr class="div">

{{-- Observações Pedagógicas --}}
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

</div>{{-- /page --}}


<div class="page-break"></div>

{{-- ══════════════════════════════════════════════ --}}
{{-- PÁGINA 2 --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="page-next">

<div class="doc-header-sm">
    <div class="doc-header-sm-l">{{ $school?->name }} &nbsp;·&nbsp; Estudo de Caso {{ $documento->year }}</div>
    <div class="doc-header-sm-r">{{ $aluno->name }}</div>
</div>

{{-- Barreiras Identificadas --}}
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

<hr class="div">

{{-- Potencialidades --}}
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

<hr class="div">

{{-- Encaminhamentos --}}
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
        <div class="field-label">Encaminhamentos para redes de apoio</div>
        <div class="field-value {{ !$val('encaminhamentos_rede') ? 'empty' : '' }}">{{ $val('encaminhamentos_rede') ?: ' ' }}</div>
    </div>
</div>

</div>{{-- /page --}}


<div class="page-break"></div>

{{-- ══════════════════════════════════════════════ --}}
{{-- PÁGINA 3 —  Diagnóstico Pedagógico + Objetivos --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="page-next">

<div class="doc-header-sm">
    <div class="doc-header-sm-l">{{ $school?->name }} &nbsp;·&nbsp; Estudo de Caso {{ $documento->year }}</div>
    <div class="doc-header-sm-r">{{ $aluno->name }}</div>
</div>

{{-- Diagnóstico Pedagógico --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Diagnóstico Pedagógico</div>
        <div class="section-sub">Descrição das necessidades educacionais e perfil pedagógico do aluno.</div>
    </div>
    <div class="field">
        <div class="field-value taller {{ !$val('diagnostico_pedagogico') ? 'empty' : '' }}" style="white-space: pre-wrap;">{{ $val('diagnostico_pedagogico') ?: ' ' }}</div>
    </div>
</div>

<hr class="div">

{{-- Objetivos de Aprendizagem --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Objetivos de Aprendizagem</div>
        <div class="section-sub">Metas estabelecidas para o período letivo.</div>
    </div>
    @foreach([
        'objetivos_curto_prazo' => 'Curto Prazo',
        'objetivos_medio_prazo' => 'Médio Prazo',
        'objetivos_longo_prazo' => 'Longo Prazo',
    ] as $field => $label)
    @php $texto = $val($field); @endphp
    <div style="margin-bottom: 10px;">
        <div class="field-label" style="margin-bottom: 4px;">{{ $label }}</div>
        @if($texto)
            <div style="border-left: 2px solid {{ $accent }}; padding-left: 8px;">
                {!! $asList($texto) !!}
            </div>
        @else
            <div class="field-value empty"> </div>
        @endif
    </div>
    @endforeach
</div>

<hr class="div">


{{-- Assinaturas --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Termo de Ciência e Concordância</div>
    </div>
    @php
        $assinantes = array_filter([
            ['role' => 'Elaborado por', 'name' => $val('elaborado_por')],
            ['role' => 'Professor(a) Titular / Regente', 'name' => $val('equipe_titular')],
            ['role' => 'Orientador(a) Educacional', 'name' => $val('equipe_soe')],
            ['role' => 'Psicólogo(a) Escolar', 'name' => $val('equipe_scp')],
            ['role' => 'Psicólogo(a) / Terapeuta', 'name' => $val('equipe_psicologo')],
            ['role' => 'Psicopedagogo(a)', 'name' => $val('equipe_psicopedagogo')],
            ['role' => 'Responsável pelo(a) estudante', 'name' => ''],
            ['role' => 'Responsável 2', 'name' => ''],
        ]);
        $rows = array_chunk($assinantes, 2);
    @endphp
    <table class="sig-table">
        @foreach($rows as $row)
        <tr>
            @foreach($row as $sig)
            <td style="width: 50%;">
                <div class="sig-role">{{ $sig['role'] }}</div>
                @if($sig['name'])<div class="sig-name">{{ $sig['name'] }}</div>@endif
                <span class="sig-line"></span>
                <div class="sig-date">____/____/________</div>
            </td>
            @endforeach
            @if(count($row) === 1)<td style="width:50%; border: 1px solid #ddd;"></td>@endif
        </tr>
        @endforeach
    </table>
</div>

<div style="margin-top: 10px; padding: 8px 10px; border: 1px solid #e0d0c0; border-radius: 4px; background: #fdf8f3; font-size: 8px; color: #888; line-height: 1.5;">
    <strong>Nota:</strong> Este documento é confidencial e destina-se exclusivamente ao planejamento educacional do(a) estudante, em conformidade com a LGPD (Lei 13.709/2018).
    Elaborado em {{ $dataElab }} &nbsp;·&nbsp; Gerado em {{ now()->format('d/m/Y H:i') }}
</div>

</div>{{-- /page --}}

