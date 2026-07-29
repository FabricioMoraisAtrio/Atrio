@php
    $school = $aluno->school;
    $turma  = $aluno->schoolClasses->first();

    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $responsaveis = collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])->filter()->implode(' / ');
    $idade = $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') . ' (' . $aluno->birth_date->age . ' anos)' : '—';

    $accent = '#004B8D'; $accentBg = '#E8F0F9';

    $tipoLabels = [
        'meta' => 'Meta', 'reuniao' => 'Reunião', 'laudo' => 'Laudo',
        'observacao' => 'Observação', 'fechamento' => 'Fechamento',
    ];
    $tipoCor = [
        'meta' => '#004B8D', 'reuniao' => '#004B8D', 'laudo' => '#009C8C',
        'observacao' => '#7C3AED', 'fechamento' => '#004B8D',
    ];
    $statusLabels = ['atingida' => 'Atingida', 'parcial' => 'Parcial', 'nao_atingida' => 'Não atingida', 'em_andamento' => 'Em andamento'];

    $resumo = ['meta' => 0, 'reuniao' => 0, 'laudo' => 0, 'observacao' => 0, 'fechamento' => 0];
    foreach ($eventos as $e) { $resumo[$e['tipo']] = ($resumo[$e['tipo']] ?? 0) + 1; }

    $logoB64 = null; $photoB64 = null;
    if ($school?->logo) { $p = storage_path('app/public/' . $school->logo); if (file_exists($p)) $logoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p)); }
    if ($aluno->photo) { $p = storage_path('app/public/' . $aluno->photo); if (file_exists($p)) $photoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p)); }
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"></head>
<body>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #1a1a1a; line-height: 1.5; }

    .doc-header { width: 100%; table-layout: fixed; border-collapse: collapse; border-bottom: 3px solid {{ $accent }}; margin-bottom: 18px; }
    .doc-header-left  { vertical-align: middle; width: 60px; padding-bottom: 12px; }
    .doc-header-mid   { vertical-align: middle; padding: 0 12px 12px; }
    .doc-header-right { vertical-align: middle; text-align: right; width: 96px; padding-bottom: 12px; }
    .doc-school   { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .doc-subtitle { font-size: 8.5px; color: #666; margin-top: 2px; }
    .doc-title    { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; color: {{ $accent }}; margin-top: 6px; }
    .school-logo   { width: 52px; height: 52px; object-fit: contain; }
    .student-photo { width: 90px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc; }
    .photo-placeholder { width: 90px; height: 90px; border-radius: 6px; border: 1px dashed #ccc; }

    .id-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; word-break: break-word; }
    .id-label { background: {{ $accentBg }}; font-weight: bold; width: 92px; color: {{ $accent }}; white-space: nowrap; }

    .section { margin-bottom: 18px; }
    .section-header { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin-bottom: 8px; page-break-after: avoid; }
    .section-title { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }

    .resumo-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .resumo-table td { border: 1px solid #ddd; text-align: center; padding: 8px 4px; }
    .resumo-n { font-size: 15px; font-weight: bold; color: {{ $accent }}; }
    .resumo-l { font-size: 8px; color: #666; text-transform: uppercase; letter-spacing: 0.4px; }

    .tl-table { width: 100%; border-collapse: collapse; }
    .tl-table th { background: {{ $accentBg }}; color: {{ $accent }}; font-size: 8.5px; text-transform: uppercase; letter-spacing: 0.4px; text-align: left; padding: 5px 8px; border: 1px solid #ddd; }
    .tl-table td { border: 1px solid #ddd; padding: 5px 8px; font-size: 9px; vertical-align: top; word-break: break-word; }
    .tl-date { width: 62px; white-space: nowrap; color: #555; }
    .tl-type { width: 78px; }
    .tl-badge { display: inline-block; font-size: 7.5px; font-weight: bold; padding: 1px 6px; border-radius: 8px; }
    .tl-desc { color: #555; margin-top: 2px; }
</style>

{{-- Cabeçalho --}}
<table class="doc-header"><tr>
    <td class="doc-header-left">@if($logoB64)<img src="{{ $logoB64 }}" class="school-logo">@endif</td>
    <td class="doc-header-mid">
        <div class="doc-school">{{ $school?->name ?? 'Escola' }}</div>
        <div class="doc-subtitle">Portal de Inclusão</div>
        <div class="doc-title">Linha do Tempo — Roadmap de Evolução</div>
    </td>
    <td class="doc-header-right">
        @if($photoB64)<img src="{{ $photoB64 }}" class="student-photo">@else<div class="photo-placeholder"></div>@endif
    </td>
</tr></table>

{{-- Identificação --}}
<table class="id-table">
    <tr>
        <td class="id-label">Aluno(a)</td><td>{{ $aluno->name }}</td>
        <td class="id-label">Matrícula</td><td>{{ $aluno->registration_number ?: '—' }}</td>
    </tr>
    <tr>
        <td class="id-label">Nascimento</td><td>{{ $idade }}</td>
        <td class="id-label">Turma</td><td>{{ $turma?->name ?? '—' }}{{ $turma?->shift ? ' · ' . $turma->shift : '' }}</td>
    </tr>
    <tr>
        <td class="id-label">Diagnóstico</td><td>{{ $diagnostico ?: '—' }}</td>
        <td class="id-label">Responsável</td><td>{{ $responsaveis ?: '—' }}</td>
    </tr>
    <tr>
        <td class="id-label">Emitido em</td><td>{{ now()->format('d/m/Y') }}</td>
        <td class="id-label">Registros</td><td>{{ count($eventos) }} evento(s)</td>
    </tr>
</table>

{{-- Resumo --}}
<div class="section">
    <div class="section-header"><span class="section-title">Resumo</span></div>
    <table class="resumo-table"><tr>
        <td><div class="resumo-n">{{ $resumo['meta'] }}</div><div class="resumo-l">Metas avaliadas</div></td>
        <td><div class="resumo-n">{{ $resumo['fechamento'] }}</div><div class="resumo-l">Bimestres fechados</div></td>
        <td><div class="resumo-n">{{ $resumo['reuniao'] }}</div><div class="resumo-l">Reuniões</div></td>
        <td><div class="resumo-n">{{ $resumo['laudo'] }}</div><div class="resumo-l">Laudos</div></td>
        <td><div class="resumo-n">{{ $resumo['observacao'] }}</div><div class="resumo-l">Observações</div></td>
    </tr></table>
</div>

{{-- Cronologia --}}
<div class="section">
    <div class="section-header"><span class="section-title">Histórico cronológico</span></div>
    @if(count($eventos))
        <table class="tl-table">
            <thead>
                <tr>
                    <th class="tl-date">Data</th>
                    <th class="tl-type">Tipo</th>
                    <th>Evento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventos as $e)
                    <tr>
                        <td class="tl-date">{{ $e['data'] ? $e['data']->format('d/m/Y') : '—' }}</td>
                        <td class="tl-type"><span class="tl-badge" style="background: {{ $accentBg }}; color: {{ $tipoCor[$e['tipo']] ?? $accent }};">{{ $tipoLabels[$e['tipo']] ?? ucfirst($e['tipo']) }}</span></td>
                        <td>
                            <strong>{{ $e['titulo'] }}</strong>@if($e['status']) — {{ $statusLabels[$e['status']] ?? $e['status'] }}@endif
                            @if($e['descricao'])<div class="tl-desc">{{ \Illuminate\Support\Str::limit($e['descricao'], 220) }}</div>@endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-size: 9px; color: #777;">Nenhum registro na linha do tempo ainda.</p>
    @endif
</div>

</body>
</html>
