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

    $accent   = '#009C8C';
    $accentBg = '#E6F5F4';

    // Imagens em base64
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

    // Helper: texto em bullet list
    $asList = function(string $text) use ($accent): string {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        if (empty($lines)) return '<span style="color:#bbb; font-style:italic; font-size:9px;">(não preenchido)</span>';
        $out = '';
        foreach ($lines as $line) {
            $out .= '<div style="display:table;width:100%;margin-bottom:3px;">'
                  . '<span style="display:table-cell;width:12px;font-size:9px;color:' . $accent . ';">•</span>'
                  . '<span style="display:table-cell;font-size:9px;color:#1a1a1a;">' . e($line) . '</span>'
                  . '</div>';
        }
        return $out;
    };

    // Helper: campo vazio
    $empty = fn($v) => !trim($v ?? '')
        ? '<span style="color:#bbb; font-style:italic;">não preenchido</span>'
        : e($v);
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #1a1a1a; line-height: 1.5; }

    .page { padding: 0 36px 44px; }
    .page-break { page-break-after: always; }

    /* Cabeçalho */
    .doc-header { display: table; width: 100%; border-bottom: 3px solid {{ $accent }}; padding-bottom: 12px; margin-bottom: 18px; }
    .doc-header-left  { display: table-cell; vertical-align: middle; width: 60px; }
    .doc-header-mid   { display: table-cell; vertical-align: middle; padding: 0 12px; }
    .doc-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 96px; }
    .doc-school   { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-subtitle { font-size: 8.5px; color: #666; margin-top: 2px; }
    .doc-title    { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-top: 6px; }
    .school-logo  { width: 52px; height: 52px; object-fit: contain; }
    .student-photo { width: 90px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc; }
    .photo-placeholder { width: 90px; height: 90px; border-radius: 6px; border: 1px dashed #ccc; display: table; text-align: center; }

    /* Cabeçalho reduzido */
    .doc-header-sm { display: table; width: 100%; border-bottom: 1.5px solid {{ $accent }}; padding-bottom: 6px; margin-bottom: 16px; }
    .doc-header-sm-l { display: table-cell; font-size: 9px; font-weight: bold; color: #333; text-transform: uppercase; }
    .doc-header-sm-r { display: table-cell; text-align: right; font-size: 8px; color: #888; }

    /* Identificação */
    .id-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; }
    .id-label { background: {{ $accentBg }}; font-weight: bold; width: 130px; color: {{ $accent }}; white-space: nowrap; }

    /* Seção */
    .section { margin-bottom: 14px; page-break-inside: avoid; }
    .section-header { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin-bottom: 8px; page-break-after: avoid; }
    .section-title { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }
    .section-sub   { font-size: 8px; color: #888; font-style: italic; margin-top: 1px; }

    /* Campo */
    .field-value {
        font-size: 9.5px; color: #1a1a1a; border-bottom: 1px solid #ddd;
        padding: 3px 2px 6px; white-space: pre-wrap; word-break: break-word;
    }

    /* Grid 3 colunas */
    .grid-3 { display: table; width: 100%; margin-bottom: 14px; }
    .grid-3 .col { display: table-cell; vertical-align: top; padding-right: 14px; }
    .grid-3 .col:last-child { padding-right: 0; }
    .inline-label { font-size: 8px; font-weight: bold; color: {{ $accent }}; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px; }
    .inline-value { font-size: 9px; color: #1a1a1a; border-bottom: 1px solid #ddd; padding-bottom: 4px; min-height: 20px; }

    /* Assinaturas */
    .sig-table { width: 100%; border-collapse: collapse; }
    .sig-table td { border: 1px solid #ddd; padding: 6px 10px; vertical-align: top; }
    .sig-role { font-size: 8.5px; font-weight: bold; color: #444; }
    .sig-name { font-size: 9px; color: #1a1a1a; margin-top: 2px; }
    .sig-line { border-bottom: 1px solid #888; display: block; margin-top: 14px; }
    .sig-date { font-size: 7.5px; color: #888; text-align: right; margin-top: 3px; }

    hr.div { border: none; border-top: 1px solid #efefef; margin: 12px 0; }
    .page-next { padding: 20px 36px 44px; }
</style>

{{-- Documento --}}
<div class="page">

{{-- Cabeçalho --}}
<div class="doc-header">
    <div class="doc-header-left">
        @if($logoB64)
            <img src="{{ $logoB64 }}" class="school-logo" alt="Logo">
        @else
            <div class="photo-placeholder" style="width:52px;height:52px;"><span style="font-size:7px;color:#ccc;">Logo</span></div>
        @endif
    </div>
    <div class="doc-header-mid">
        <div class="doc-school">{{ $school?->name }}</div>
        <div class="doc-subtitle">Plano de Atendimento Educacional Especializado &nbsp;·&nbsp; Ano Letivo {{ $documento->year }}</div>
        <div class="doc-title">PAEE — Plano de Atendimento Educacional Especializado</div>
    </div>
    <div class="doc-header-right">
        @if($photoB64)
            <img src="{{ $photoB64 }}" class="student-photo" alt="Foto">
        @else
            <div class="photo-placeholder"><span style="font-size:7px;color:#ccc;">Foto</span></div>
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
</table>

{{-- Diagnóstico / Perfil --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Diagnóstico / Perfil</div>
        <div class="section-sub">Necessidades educacionais e barreiras identificadas.</div>
    </div>
    <div class="field-value taller">{!! $asList($val('diagnostico_perfil')) !!}</div>
</div>

<hr class="div">

{{-- Objetivos --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Objetivos do AEE</div>
    </div>

    <div style="margin-bottom: 6px; padding: 6px 10px; border-left: 3px solid {{ $accent }}; background: #f9fbfd;">
        <div style="font-size: 8px; font-weight: bold; color: {{ $accent }}; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px;">Objetivo Geral</div>
        <div style="font-size: 9px; white-space: pre-wrap;">{{ $val('objetivo_geral') ?: '(não preenchido)' }}</div>
    </div>

    <div style="margin-top: 8px;">
        <div style="font-size: 8px; font-weight: bold; color: {{ $accent }}; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Objetivos Específicos</div>
        {!! $asList($val('objetivos_especificos')) !!}
    </div>
</div>

<hr class="div">

{{-- Recursos e Estratégias --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Recursos e Estratégias</div>
        <div class="section-sub">Tecnologias assistivas, adaptações e metodologias utilizadas.</div>
    </div>

    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px;">Tecnologias Assistivas</div>
    <div class="field-value" style="margin-bottom: 10px;">{{ $val('tecnologias_assistivas') ?: '(não preenchido)' }}</div>

    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px;">Adaptações Curriculares e de Material</div>
    <div class="field-value tall" style="margin-bottom: 10px; white-space: pre-wrap;">{{ $val('adaptacoes') ?: '(não preenchido)' }}</div>

    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px;">Metodologias e Estratégias Pedagógicas</div>
    <div class="field-value tall" style="white-space: pre-wrap;">{{ $val('metodologias') ?: '(não preenchido)' }}</div>
</div>

<hr class="div">

{{-- Organização do Atendimento --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Organização do Atendimento</div>
        <div class="section-sub">Frequência, duração e local do atendimento especializado.</div>
    </div>
    <div class="grid-3">
        <div class="col">
            <div class="inline-label">Frequência</div>
            <div class="inline-value">{{ $val('frequencia') ?: '—' }}</div>
        </div>
        <div class="col">
            <div class="inline-label">Duração</div>
            <div class="inline-value">{{ $val('duracao') ?: '—' }}</div>
        </div>
        <div class="col">
            <div class="inline-label">Local</div>
            <div class="inline-value">{{ $val('local_atendimento') ?: '—' }}</div>
        </div>
    </div>
</div>

</div>{{-- /page --}}

<div class="page-break"></div>

<div class="page-next">

<div class="doc-header-sm">
    <div class="doc-header-sm-l">{{ $school?->name }} &nbsp;·&nbsp; PAEE {{ $documento->year }}</div>
    <div class="doc-header-sm-r">{{ $aluno->name }}</div>
</div>

{{-- Avaliação e Monitoramento --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Avaliação e Monitoramento</div>
        <div class="section-sub">Critérios e periodicidade de avaliação do plano.</div>
    </div>

    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px;">Critérios de Avaliação</div>
    <div class="field-value tall" style="margin-bottom: 10px; white-space: pre-wrap;">{{ $val('criterios_avaliacao') ?: '(não preenchido)' }}</div>

    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px;">Periodicidade de Avaliação e Atualização</div>
    <div class="field-value" style="white-space: pre-wrap;">{{ $val('periodicidade_avaliacao') ?: '(não preenchido)' }}</div>
</div>

<hr class="div">

{{-- Observações Livres --}}
@if(!empty($val('observacoes_livres')))
<div class="section">
    <div class="section-header">
        <div class="section-title">Observações</div>
    </div>
    <div class="field-value tall" style="white-space: pre-wrap;">{{ $val('observacoes_livres') }}</div>
</div>
<hr class="div">
@endif

{{-- Assinaturas --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Responsáveis pela Elaboração do PAEE</div>
    </div>
    @php
        $responsaveis = array_values(array_filter([
            ['role' => 'Profissional do AEE',    'name' => $val('profissional_aee')],
            ['role' => 'Equipe Colaborativa',    'name' => $val('equipe_colaborativa')],
            ['role' => 'Professor(a) Titular',   'name' => ''],
            ['role' => 'Orientador(a) Educacional', 'name' => ''],
        ]));
        $rows = array_chunk($responsaveis, 2);
    @endphp
    <p style="font-size: 8px; color: #888; font-style: italic; margin-bottom: 8px;">
        Declaramos que este PAEE foi elaborado coletivamente, com base no Estudo de Caso, e que seu cumprimento será acompanhado ao longo do ano letivo.
    </p>
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

</div>{{-- /page-next --}}
