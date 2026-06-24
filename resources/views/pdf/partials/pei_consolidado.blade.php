@php
    use App\Http\Controllers\Secretaria\PeiConsolidadoController;

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

    $responsaveis = collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])
        ->filter()->implode(' / ');

    $idade = $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') . ' (' . $aluno->birth_date->age . ' anos)' : '—';

    // Documento PEI compartilhado
    $peiDoc = \App\Models\Document::where('student_id', $aluno->id)
        ->where('year', $documento->year)
        ->where('type', 'pei')
        ->first();
    $peiContent  = $peiDoc?->content ?? [];
    $peiGlobal   = $peiContent['global'] ?? [];
    $peiSubjects = $peiContent['subjects'] ?? [];

    $inventoryItems = PeiConsolidadoController::loadInventoryItems($peiSubjects);
    $inventario     = PeiConsolidadoController::consolidarInventario($peiSubjects, $inventoryItems, $peiGlobal);

    $ec = \App\Models\Document::where('student_id', $aluno->id)
        ->where('year', $documento->year)
        ->where('type', 'estudo_caso')
        ->value('content') ?? [];

    $val = fn($key) => $c[$key] ?? '';

    $accent   = '#004B8D';
    $accentBg = '#E8F0F9';

    $grupos = [
        'academica'      => 'Metas Acadêmicas',
        'socioemocional' => 'Metas Socioemocionais',
        'funcional'      => 'Metas Funcionais',
        'global'         => 'Desenvolvimento Global',
    ];

    $flagLabels = [
        'autonomia'    => 'Com autonomia',
        'suporte'      => 'Com suporte',
        'nao_executa'  => 'Não executa',
        'nao_observado'=> 'Não observado',
    ];

    $temInventario = collect($grupos)->keys()->some(fn($k) => !empty($inventario[$k]));

    $equipeColegio = array_filter([
        'Professor(a) Titular / Regente'  => $ec['equipe_titular']       ?? '',
        'Orientador(a) Educacional (SOE)' => $ec['equipe_soe']           ?? '',
        'Psicólogo(a) Escolar (SCP)'      => $ec['equipe_scp']           ?? '',
        'Coordenador(a) do SAEE'          => $ec['equipe_saee']          ?? '',
    ]);
    $equipeMulti = array_filter([
        'Psicólogo(a) / Terapeuta'        => $ec['equipe_psicologo']      ?? '',
        'Psicopedagogo(a)'                => $ec['equipe_psicopedagogo']  ?? '',
        'Fisioterapeuta / Ed. Físico'     => $ec['equipe_fisioterapeuta'] ?? '',
        'Acompanhante Terapêutico'        => $ec['equipe_at']             ?? '',
    ]);

    $logoB64  = null;
    $photoB64 = null;
    if ($school?->logo) {
        $p = storage_path('app/public/' . $school->logo);
        if (file_exists($p)) $logoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p));
    }
    if ($aluno->photo) {
        $p = storage_path('app/public/' . $aluno->photo);
        if (file_exists($p)) $photoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p));
    }
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #1a1a1a; margin: 0; padding: 0; line-height: 1.5; }
    .page-break { page-break-after: always; }

    .doc-header { display: table; width: 100%; table-layout: fixed; border-bottom: 3px solid {{ $accent }}; padding-bottom: 12px; margin-bottom: 18px; }
    .doc-header-left  { display: table-cell; vertical-align: middle; width: 60px; }
    .doc-header-mid   { display: table-cell; vertical-align: middle; padding: 0 12px; }
    .doc-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 96px; }
    .doc-school   { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-subtitle { font-size: 8.5px; color: #666; margin-top: 2px; }
    .doc-title    { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-top: 6px; }
    .school-logo   { width: 52px; height: 52px; object-fit: contain; }
    .student-photo { width: 90px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc; }
    .photo-placeholder { width: 90px; height: 90px; border-radius: 6px; border: 1px dashed #ccc; display: table; text-align: center; vertical-align: middle; }

    .doc-header-sm { display: table; width: 100%; table-layout: fixed; border-bottom: 1.5px solid {{ $accent }}; padding-bottom: 6px; margin-bottom: 16px; }
    .doc-header-sm-l { display: table-cell; font-size: 9px; font-weight: bold; color: #333; text-transform: uppercase; }
    .doc-header-sm-r { display: table-cell; text-align: right; font-size: 8px; color: #888; }

    .id-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; word-break: break-word; }
    .id-label { background: {{ $accentBg }}; font-weight: bold; width: 130px; color: {{ $accent }}; white-space: nowrap; }

    .section { margin-bottom: 18px; page-break-inside: avoid; }
    .section-header { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin-bottom: 8px; page-break-after: avoid; }
    .section-title { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }
    .section-sub   { font-size: 8px; color: #888; font-style: italic; margin-top: 1px; }

    .field-label { font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px; }
    .field-value { font-size: 9.5px; color: #1a1a1a; border-bottom: 1px solid #ddd; padding: 3px 2px 6px; white-space: pre-wrap; word-break: break-word; }
    .field-value.empty { color: #bbb; font-style: italic; min-height: 18px; }

    .grid-2 { display: table; width: 100%; table-layout: fixed; }
    .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 14px; }
    .grid-2 .col:last-child { padding-right: 0; }

    .inv-table    { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .inv-cat-hdr  { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin: 8px 0 5px; }
    .inv-cat-title { font-size: 8.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }
    .inv-th   { background: {{ $accentBg }}; font-weight: bold; font-size: 8px; text-align: center; padding: 4px 3px; border: 1px solid #c5d8f0; }
    .inv-meta { font-size: 8.5px; padding: 4px 6px; border: 1px solid #ddd; word-break: break-word; }
    .inv-chk  { text-align: center; padding: 4px 3px; font-size: 9px; border: 1px solid #ddd; }
    .inv-obs  { font-size: 8px; padding: 3px 5px; border: 1px solid #ddd; }

    .sig-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .sig-table td { border: 1px solid #ddd; padding: 6px 10px; vertical-align: top; word-break: break-word; }
    .sig-role { font-size: 8.5px; font-weight: bold; color: {{ $accent }}; }
    .sig-line { border-bottom: 1px solid #888; display: block; margin-top: 14px; width: 100%; }
    .sig-date { font-size: 7.5px; color: #888; text-align: right; margin-top: 3px; }

    hr.div { border: none; border-top: 1px solid #efefef; margin: 10px 0; }
</style>

{{-- ══ CONTEÚDO PRINCIPAL ══ --}}
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
    @if($diagnostico)
    <tr>
        <td class="id-label">Diagnóstico / Laudo</td>
        <td colspan="3">{{ $diagnostico }}</td>
    </tr>
    @endif
    @if($responsaveis)
    <tr>
        <td class="id-label">Responsável(is)</td>
        <td colspan="3">{{ $responsaveis }}</td>
    </tr>
    @endif
</table>

{{-- Equipe --}}
@if($equipeColegio || $equipeMulti)
<div class="section">
    <div class="section-header">
        <div class="section-title">Equipe Responsável</div>
        <div class="section-sub">Profissionais envolvidos na elaboração e acompanhamento do PEI.</div>
    </div>
    <div class="grid-2">
        @if($equipeColegio)
        <div class="col">
            <div class="field-label" style="margin-bottom: 5px;">Equipe do Colégio</div>
            @foreach($equipeColegio as $cargo => $nome)
            <div style="margin-bottom: 3px; font-size: 9px; color: #1a1a1a;">
                <span style="color: #555; font-weight: bold; font-size: 8px;">{{ $cargo }}:</span> {{ $nome }}
            </div>
            @endforeach
        </div>
        @endif
        @if($equipeMulti)
        <div class="col">
            <div class="field-label" style="margin-bottom: 5px;">Equipe Multidisciplinar</div>
            @foreach($equipeMulti as $cargo => $nome)
            <div style="margin-bottom: 3px; font-size: 9px; color: #1a1a1a;">
                <span style="color: #555; font-weight: bold; font-size: 8px;">{{ $cargo }}:</span> {{ $nome }}
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
<hr class="div">
@endif

{{-- Trajetória Escolar --}}
@if(!empty($ec['historico_escolar']))
<div class="section">
    <div class="section-header">
        <div class="section-title">Trajetória Escolar</div>
    </div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $ec['historico_escolar'] }}</div>
</div>
<hr class="div">
@endif

{{-- Diagnóstico Pedagógico (do EC) --}}
@php $diagnostico_peda = $ec['diagnostico_pedagogico'] ?? ''; @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Diagnóstico Pedagógico</div>
        <div class="section-sub">Descrição das necessidades educacionais — extraído do Estudo de Caso.</div>
    </div>
    @if($diagnostico_peda)
        <div class="field-value" style="white-space: pre-wrap;">{{ $diagnostico_peda }}</div>
    @else
        <div class="field-value empty" style="font-style: italic; color: #aaa;">Ainda não preenchido no Estudo de Caso.</div>
    @endif
</div>
<hr class="div">

{{-- Objetivos de Aprendizagem (do PEI global) --}}
@php
$objCurto = $peiGlobal['objetivos_curto_prazo'] ?? '';
$objMedio = $peiGlobal['objetivos_medio_prazo'] ?? '';
$objLongo = $peiGlobal['objetivos_longo_prazo'] ?? '';
@endphp
@if($objCurto || $objMedio || $objLongo)
<div class="section">
    <div class="section-header">
        <div class="section-title">Objetivos de Aprendizagem</div>
    </div>
    @foreach(['Curto Prazo' => $objCurto, 'Médio Prazo' => $objMedio, 'Longo Prazo' => $objLongo] as $label => $texto)
    @if($texto)
    <div style="margin-bottom: 6px; padding: 5px 8px; border-left: 3px solid {{ $accent }}; background: #f9fbfd;">
        <div style="font-size: 8px; font-weight: bold; color: {{ $accent }}; text-transform: uppercase; margin-bottom: 2px;">{{ $label }}</div>
        <div style="font-size: 9px; white-space: pre-wrap;">{{ $texto }}</div>
    </div>
    @endif
    @endforeach
</div>
<hr class="div">
@endif

{{-- Inventário de Habilidades --}}
@if($temInventario)
<div class="section">
    <div class="section-header">
        <div class="section-title">Metas de Habilidades</div>
        <div class="section-sub">Consolidado das metas preenchidas pelos professores.</div>
    </div>
    @foreach($grupos as $catKey => $catLabel)
        @php $itens = $inventario[$catKey] ?? []; @endphp
        @if(count($itens))
        <div class="inv-cat-hdr">
            <div class="inv-cat-title">{{ $catLabel }}</div>
        </div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px; page-break-inside: avoid;">
            <tr>
                <th class="inv-th" style="text-align: left; width: 36%;">Metas / Objetivos</th>
                <th class="inv-th" style="width: 20%;">Disciplina</th>
                <th class="inv-th" style="width: 18%;">Avaliação</th>
                <th class="inv-th">Observações</th>
            </tr>
            @foreach($itens as $item)
            <tr>
                <td class="inv-meta">{{ $item['meta'] }}</td>
                <td class="inv-obs">{{ $item['subject_name'] }}</td>
                <td class="inv-obs">{{ $flagLabels[$item['flag'] ?? ''] ?? '—' }}</td>
                <td class="inv-obs">{{ $item['obs'] }}</td>
            </tr>
            @endforeach
        </table>
        @endif
    @endforeach
</div>
<hr class="div">
@endif

{{-- Estratégias Pedagógicas (do PEI global) --}}
@php $estrategias = $peiGlobal['estrategias_pedagogicas'] ?? ''; @endphp
@if($estrategias)
<div class="section">
    <div class="section-header">
        <div class="section-title">Estratégias Pedagógicas</div>
    </div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $estrategias }}</div>
</div>
<hr class="div">
@endif

{{-- Adaptações Curriculares (do EC) --}}
@php $adaptacoes = $ec['adaptacoes_necessarias'] ?? ''; @endphp
@if($adaptacoes)
<div class="section">
    <div class="section-header">
        <div class="section-title">Adaptações e/ou Adequações Curriculares</div>
        <div class="section-sub">Extraído do Estudo de Caso.</div>
    </div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $adaptacoes }}</div>
</div>
<hr class="div">
@endif

{{-- Avaliação (do PEI global) --}}
@php $avaliacao = $peiGlobal['criterios_avaliacao'] ?? ''; @endphp
@if($avaliacao)
<div class="section">
    <div class="section-header">
        <div class="section-title">Avaliação</div>
        <div class="section-sub">Como será acompanhado o progresso do aluno.</div>
    </div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $avaliacao }}</div>
</div>
<hr class="div">
@endif

{{-- Observações --}}
@if($val('observacoes'))
<div class="section">
    <div class="section-header">
        <div class="section-title">Observações não contempladas ao longo do PEI</div>
    </div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $val('observacoes') }}</div>
</div>
<hr class="div">
@endif


{{-- Termo de Ciência e Concordância --}}
<hr class="div">
<div class="section">
    <div class="section-header">
        <div class="section-title">Termo de Ciência e Concordância por Parte dos Envolvidos</div>
        <div class="section-sub">Assinatura dos responsáveis pela elaboração do PEI e Responsáveis pelo estudante.</div>
    </div>
    <table class="sig-table">
        @foreach(['Orientador(a) Educacional', 'Profissional do AEE', 'Professor(a) Titular', 'Mãe/Responsável', 'Pai/Responsável', 'Terapeuta'] as $assinante)
        <tr>
            <td style="width: 170px; vertical-align: middle; background: {{ $accentBg }};">
                <div class="sig-role">{{ $assinante }}</div>
            </td>
            <td>
                <span class="sig-line"></span>
                <div class="sig-date">____/____/________</div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

</div>{{-- /page --}}
