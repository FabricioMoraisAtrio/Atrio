@php
    $aluno = $documento->student;
    $c     = $documento->content ?? [];
    $turma = $aluno->schoolClasses->first();
@endphp

<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; padding: 32px; }
    .doc-title { text-align: center; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    td { border: 1px solid #aaa; padding: 5px 8px; vertical-align: top; }
    .hdr { background: #A8BAD0; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; }
    .lbl { background: #E8EEF4; font-weight: bold; font-size: 9px; width: 160px; white-space: nowrap; }
    .val { font-size: 9.5px; min-height: 20px; }
    .footer { font-size: 8px; color: #666; text-align: center; margin-top: 12px; }
</style>

<p class="doc-title">{{ $aluno->registration_number }} — {{ strtoupper(str_replace('_', ' ', $documento->type)) }}</p>

<table>
    <tr><td class="hdr" colspan="2">Dados de Identificação</td></tr>
    <tr>
        <td class="lbl">Estudante</td>
        <td class="val">{{ $aluno->name }}</td>
    </tr>
    @if($turma)
    <tr>
        <td class="lbl">Turma / Turno</td>
        <td class="val">{{ $turma->name }} · {{ $turma->shift }}</td>
    </tr>
    @endif
    <tr>
        <td class="lbl">Ano letivo</td>
        <td class="val">{{ $documento->year }}</td>
    </tr>
    <tr>
        <td class="lbl">Elaborado por</td>
        <td class="val">{{ $documento->author->name }}</td>
    </tr>
</table>

@foreach($c as $campo => $valor)
    @if(!is_null($valor) && $valor !== '' && $valor !== [])
    <table>
        <tr><td class="hdr">{{ ucwords(str_replace('_', ' ', $campo)) }}</td></tr>
        <tr>
            <td class="val" style="min-height: 32px; white-space: pre-wrap;">{{ is_array($valor) ? implode(', ', array_filter(array_column($valor, 'meta') ?: $valor)) : $valor }}</td>
        </tr>
    </table>
    @endif
@endforeach

<p class="footer">Gerado em {{ now()->format('d/m/Y H:i') }} · Documento confidencial — LGPD</p>
