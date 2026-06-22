@php
    use App\Models\SubjectInventoryItem;

    $aluno   = $documento->student;
    $turma   = $aluno->schoolClasses->first();
    $school  = $aluno->school;
    $c       = $documento->content ?? [];
    $subjects = $c['subjects'] ?? [];

    // Estudo de Caso do mesmo aluno/ano
    $ec = \App\Models\Document::where('student_id', $aluno->id)
        ->where('year', $documento->year)
        ->where('type', 'estudo_caso')
        ->first()?->content ?? [];

    // Campos que vêm do Estudo de Caso
    $diagnostico_peda = $ec['diagnostico_pedagogico'] ?? '';
    $adaptacoes       = $ec['adaptacoes_necessarias'] ?? '';

    // Campos preenchidos no próprio PEI (global)
    $peiGlobal  = $c['global'] ?? [];
    $obj_curto  = $peiGlobal['objetivos_curto_prazo']   ?? '';
    $obj_medio  = $peiGlobal['objetivos_medio_prazo']   ?? '';
    $obj_longo  = $peiGlobal['objetivos_longo_prazo']   ?? '';
    $estrategias_peda  = $peiGlobal['estrategias_pedagogicas'] ?? '';
    $criterios_peda    = $peiGlobal['criterios_avaliacao']     ?? '';

    // Diagnóstico clínico do aluno
    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $idade = $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') . ' (' . $aluno->birth_date->age . ' anos)' : '—';

    // Carregar metas do inventário
    $allMetaIds = collect($subjects)
        ->flatMap(fn($s) => array_keys($s['metas'] ?? []))
        ->map(fn($id) => (int) $id)
        ->unique()->values()->all();

    $inventoryItems = SubjectInventoryItem::withoutGlobalScopes()
        ->whereIn('id', $allMetaIds)
        ->get()->keyBy('id');

    $categorias = [
        'academica'      => 'Objetivos Curriculares',
        'socioemocional' => 'Desenvolvimento Socioemocional',
        'global'         => 'Desenvolvimento Global',
    ];

    $accent   = '#004B8D';
    $accentBg = '#E8F0F9';

    // Imagens
    $logoB64  = null;
    $photoB64 = null;
    if ($school?->logo) {
        $p = storage_path('app/public/' . $school->logo);
        if (file_exists($p)) {
            $logoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p));
        }
    }
    if ($aluno->photo) {
        $p = storage_path('app/public/' . $aluno->photo);
        if (file_exists($p)) {
            $photoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p));
        }
    }
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #1a1a1a; margin: 0; padding: 0; line-height: 1.5; }

    /* ── Cabeçalho principal ── */
    .doc-header { display: table; width: 100%; table-layout: fixed; border-bottom: 3px solid {{ $accent }}; padding-bottom: 12px; margin-bottom: 18px; }
    .doc-header-left  { display: table-cell; vertical-align: middle; width: 60px; }
    .doc-header-mid   { display: table-cell; vertical-align: middle; padding: 0 12px; }
    .doc-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 96px; }
    .doc-school   { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-subtitle { font-size: 8.5px; color: #666; margin-top: 2px; }
    .doc-title    { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-top: 6px; }
    .school-logo    { width: 52px; height: 52px; object-fit: contain; }
    .student-photo  { width: 90px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc; }
    .photo-placeholder { width: 90px; height: 90px; border-radius: 6px; border: 1px dashed #ccc; display: table; text-align: center; }

    /* ── Identificação ── */
    .id-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; word-break: break-word; }
    .id-label { background: #E8F0F9; font-weight: bold; width: 130px; color: #004B8D; white-space: nowrap; }

    /* ── Seção ── */
    .section { margin-bottom: 14px; page-break-inside: avoid; }
    .section-header { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin-bottom: 8px; page-break-after: avoid; }
    .section-title { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }
    .section-sub   { font-size: 8px; color: #888; font-style: italic; margin-top: 1px; }

    /* ── Campo ── */
    .field-label { font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px; }
    .field-value {
        font-size: 9.5px; color: #1a1a1a; border-bottom: 1px solid #ddd;
        min-height: 26px; padding: 3px 2px 6px; white-space: pre-wrap; word-break: break-word;
    }
    .field-value.tall   { min-height: 48px; }
    .field-value.taller { min-height: 64px; }
    .field-value.empty  { color: #ccc; font-style: italic; }

    /* ── Grid ── */
    .grid-3 { display: table; width: 100%; table-layout: fixed; }
    .grid-3 .col { display: table-cell; width: 33.3%; vertical-align: top; padding-right: 14px; }
    .grid-3 .col:last-child { padding-right: 0; }

    /* ── Inventário (tabela por matéria) ── */
    .inv-table   { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .inv-subject { background: #E8F0F9; font-weight: bold; font-size: 9px; padding: 5px 8px; border: 1px solid #bcd; }
    .inv-cat     { background: #F5F8FC; font-weight: bold; font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 8px; border: 1px solid #ddd; color: {{ $accent }}; }
    .inv-th      { background: #f0f0f0; font-weight: bold; font-size: 8px; text-align: center; padding: 4px; border: 1px solid #ddd; }
    .inv-meta    { font-size: 8.5px; padding: 4px 6px; border: 1px solid #ddd; }
    .inv-chk     { text-align: center; padding: 4px 3px; font-size: 10px; border: 1px solid #ddd; }
    .inv-obs     { font-size: 8px; padding: 3px 5px; border: 1px solid #ddd; }

    /* ── Assinaturas ── */
    .sig-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .sig-table td { border: 1px solid #ddd; padding: 6px 10px; vertical-align: top; word-break: break-word; }
    .sig-role { font-size: 8.5px; font-weight: bold; color: #444; }
    .sig-name { font-size: 9px; color: #1a1a1a; margin-top: 2px; }
    .sig-line { border-bottom: 1px solid #888; display: block; margin-top: 14px; }
    .sig-date { font-size: 7.5px; color: #888; text-align: right; margin-top: 3px; }

    hr.div { border: none; border-top: 1px solid #efefef; margin: 12px 0; }
</style>

{{-- ══ PÁGINA 1 — Identificação + Diagnóstico + Objetivos ══ --}}
<div class="page">

{{-- Cabeçalho principal --}}
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
        <div class="doc-subtitle">Plano Educacional Individualizado &nbsp;·&nbsp; Ano Letivo {{ $documento->year }}</div>
        <div class="doc-title">PEI — Plano Educacional Individualizado</div>
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
    @if($aluno->responsavel_nome || $aluno->responsavel_2_nome)
    <tr>
        <td class="id-label">Responsável</td>
        <td colspan="3">{{ collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])->filter()->implode(' / ') }}</td>
    </tr>
    @endif
    @if($diagnostico)
    <tr>
        <td class="id-label">Diagnóstico / Laudo</td>
        <td colspan="3">{{ $diagnostico }}</td>
    </tr>
    @endif
    @if(!empty($ec['contexto_familiar']))
    <tr>
        <td class="id-label">Contexto Familiar</td>
        <td colspan="3">{{ $ec['contexto_familiar'] }}</td>
    </tr>
    @endif
</table>

@if($diagnostico_peda)
{{-- Diagnóstico Pedagógico --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Diagnóstico Pedagógico</div>
        <div class="section-sub">Descrição das necessidades educacionais — extraído do Estudo de Caso.</div>
    </div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $diagnostico_peda }}</div>
</div>

<hr class="div">
@endif

{{-- Objetivos de Aprendizagem --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Objetivos de Aprendizagem</div>
        <div class="section-sub">Metas estabelecidas para o período letivo — extraído do Estudo de Caso.</div>
    </div>
    @foreach([
        'Curto Prazo'  => $obj_curto,
        'Médio Prazo'  => $obj_medio,
        'Longo Prazo'  => $obj_longo,
    ] as $label => $valor)
    <div style="margin-bottom: 6px; padding: 6px 10px; border-left: 3px solid {{ $accent }}; background: #f9fbfd;">
        <div style="font-size: 8px; font-weight: bold; color: {{ $accent }}; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">{{ $label }}</div>
        <div style="font-size: 9px; color: {{ $valor ? '#1a1a1a' : '#bbb' }}; font-style: {{ $valor ? 'normal' : 'italic' }}; white-space: pre-wrap;">{{ $valor ?: '(não preenchido)' }}</div>
    </div>
    @endforeach
</div>


{{-- ══ INVENTÁRIOS por Disciplina ══ --}}
@foreach($subjects as $slug => $secao)
<div style="{{ !$loop->first ? 'page-break-before: always;' : '' }}">

<div class="section">
    <div class="section-header">
        <div class="section-title">Metas de Habilidades</div>
        <div class="section-sub">
            {{ $secao['subject_name'] ?? $slug }}
            @if(!empty($secao['teacher_name'])) &nbsp;·&nbsp; {{ $secao['teacher_name'] }} @endif
            @if(!empty($secao['updated_at'])) &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($secao['updated_at'])->format('d/m/Y') }} @endif
        </div>
    </div>

    @php
        $metas = $secao['metas'] ?? [];
        $isReg = ($secao['subject_tipo'] ?? '') === 'regente'
            || !empty($secao['metas_socioemocionais']) || !empty($secao['metas_funcionais']);
    @endphp

    @if($isReg)
        {{-- Regente: metas socioemocionais e funcionais (texto livre) --}}
        @if(empty($secao['metas_socioemocionais']) && empty($secao['metas_funcionais']))
            <div class="field-value empty">Seção ainda não preenchida.</div>
        @else
            @if(!empty($secao['metas_socioemocionais']))
            <div class="inv-cat">Metas Socioemocionais</div>
            <div class="field-value" style="white-space: pre-wrap; margin-bottom: 10px;">{{ $secao['metas_socioemocionais'] }}</div>
            @endif
            @if(!empty($secao['metas_funcionais']))
            <div class="inv-cat">Metas Funcionais</div>
            <div class="field-value" style="white-space: pre-wrap; margin-bottom: 10px;">{{ $secao['metas_funcionais'] }}</div>
            @endif
        @endif
    @elseif(empty($metas))
        <div class="field-value empty">Seção ainda não preenchida.</div>
    @else
        {{-- Disciplina: metas acadêmicas avaliadas --}}
        <div class="inv-cat">Metas Acadêmicas</div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
            <tr>
                <th class="inv-th" style="width: 38%; text-align: left;">Meta / Objetivo</th>
                <th class="inv-th" style="width: 10%;">Autonomia</th>
                <th class="inv-th" style="width: 10%;">Com suporte</th>
                <th class="inv-th" style="width: 10%;">Não executa</th>
                <th class="inv-th" style="width: 10%;">Não observado</th>
                <th class="inv-th">Observações</th>
            </tr>
            @foreach($metas as $metaId => $dados)
            @php $texto = trim($dados['texto'] ?? '') ?: ($inventoryItems->get((int) $metaId)?->meta ?? "Meta #{$metaId}"); @endphp
            <tr>
                <td class="inv-meta">{{ $texto }}</td>
                <td class="inv-chk">{{ ($dados['flag'] ?? '') === 'autonomia'     ? 'X' : '' }}</td>
                <td class="inv-chk">{{ ($dados['flag'] ?? '') === 'suporte'       ? 'X' : '' }}</td>
                <td class="inv-chk">{{ ($dados['flag'] ?? '') === 'nao_executa'   ? 'X' : '' }}</td>
                <td class="inv-chk">{{ ($dados['flag'] ?? '') === 'nao_observado' ? 'X' : '' }}</td>
                <td class="inv-obs">{{ $dados['obs'] ?? '' }}</td>
            </tr>
            @endforeach
        </table>
    @endif

    @if(!empty($secao['observacoes_livres']))
    <div class="field-label" style="margin-top: 8px;">Observações Adicionais</div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $secao['observacoes_livres'] }}</div>
    @endif
</div>

</div>{{-- /disciplina --}}
@endforeach

{{-- ══ SEÇÃO FINAL — Estratégias + Adaptações + Avaliação + Equipe + Assinaturas ══ --}}

{{-- Estratégias --}}
<div class="section" style="page-break-before: always;">
    <div class="section-header">
        <div class="section-title">Estratégias Pedagógicas</div>
        <div class="section-sub">Metodologias e adaptações definidas no PEI.</div>
    </div>
    <div class="field-value tall {{ !$estrategias_peda ? 'empty' : '' }}" style="white-space: pre-wrap;">{{ $estrategias_peda ?: '(não preenchido)' }}</div>
</div>

<hr class="div">

{{-- Adaptações --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Adaptações e/ou Adequações Curriculares</div>
        <div class="section-sub">Extraído do Estudo de Caso.</div>
    </div>
    <div class="field-value tall {{ !$adaptacoes ? 'empty' : '' }}" style="white-space: pre-wrap;">{{ $adaptacoes ?: '(não preenchido no Estudo de Caso)' }}</div>
</div>

<hr class="div">

{{-- Avaliação --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Avaliação</div>
        <div class="section-sub">Como será acompanhado o progresso do aluno.</div>
    </div>
    <div class="field-value tall {{ !$criterios_peda ? 'empty' : '' }}" style="white-space: pre-wrap;">{{ $criterios_peda ?: '(não preenchido)' }}</div>
</div>

<hr class="div">

{{-- Equipe Responsável --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Equipe Responsável</div>
        <div class="section-sub">Profissionais envolvidos na elaboração e acompanhamento do PEI.</div>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
        @foreach([
            'equipe_titular'        => 'Professor(a) Titular / Regente',
            'equipe_soe'            => 'Orientador(a) Educacional (SOE)',
            'equipe_scp'            => 'Psicólogo(a) Escolar (SCP)',
            'equipe_saee'           => 'Coordenador(a) do SAEE',
            'equipe_psicologo'      => 'Psicólogo(a) / Terapeuta Externo',
            'equipe_psicopedagogo'  => 'Psicopedagogo(a)',
            'equipe_fisioterapeuta' => 'Fisioterapeuta / Ed. Físico',
            'equipe_at'             => 'Acompanhante Terapêutico (AT)',
        ] as $field => $label)
        @php $nome = $ec[$field] ?? ''; @endphp
        @if($nome)
        <tr>
            <td style="border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; background: #E8F0F9; font-weight: bold; width: 160px; color: #004B8D;">{{ $label }}</td>
            <td style="border: 1px solid #ddd; padding: 4px 8px; font-size: 9px;">{{ $nome }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>

<hr class="div">

{{-- Assinaturas --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Termo de Ciência e Concordância</div>
    </div>
    @php
        $assinantes = array_values(array_filter([
            ['role' => 'Professor(a) Titular / Regente', 'name' => $ec['equipe_titular'] ?? ''],
            ['role' => 'Orientador(a) Educacional',       'name' => $ec['equipe_soe']     ?? ''],
            ['role' => 'Psicólogo(a) Escolar',            'name' => $ec['equipe_scp']     ?? ''],
            ['role' => 'Profissional do SAEE',             'name' => $ec['equipe_saee']    ?? ''],
            ['role' => 'Psicólogo(a) / Terapeuta',         'name' => $ec['equipe_psicologo'] ?? ''],
            ['role' => 'Responsável pelo(a) estudante',    'name' => ''],
            ['role' => 'Responsável 2',                    'name' => ''],
        ]));
        while (count($assinantes) < 4) {
            $assinantes[] = ['role' => 'Profissional', 'name' => ''];
        }
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

</div>{{-- /page --}}
