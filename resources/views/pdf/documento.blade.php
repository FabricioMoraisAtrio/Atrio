<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            padding: 40px;
        }

        .header {
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
        }

        .meta {
            display: flex;
            gap: 32px;
            background: #f5f5f5;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 24px;
        }

        .meta-item label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            display: block;
            margin-bottom: 2px;
        }

        .meta-item span {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .section {
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .section-title {
            background: #f0f0f0;
            padding: 8px 14px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            border-bottom: 1px solid #e0e0e0;
        }

        .section-content {
            padding: 12px 14px;
            font-size: 11px;
            line-height: 1.6;
            color: #222;
            white-space: pre-wrap;
        }

        .empty {
            color: #aaa;
            font-style: italic;
        }

        .footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #888;
        }

        .signature-area {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            gap: 32px;
        }

        .signature-box {
            flex: 1;
            border-top: 1px solid #1a1a1a;
            padding-top: 8px;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ strtoupper(str_replace('_', ' ', $documento->type)) }}</h1>
        <div class="subtitle">Átrio — Sistema de Gestão Educacional Inclusiva</div>
    </div>

    <div class="meta">
        <div class="meta-item">
            <label>Aluno</label>
            <span>{{ $documento->student->name }}</span>
        </div>
        <div class="meta-item">
            <label>Matrícula</label>
            <span>{{ $documento->student->registration_number }}</span>
        </div>
        @if($documento->student->condition)
        <div class="meta-item">
            <label>Condição</label>
            <span>{{ $documento->student->condition }}</span>
        </div>
        @endif
        <div class="meta-item">
            <label>Ano letivo</label>
            <span>{{ $documento->year }}</span>
        </div>
        <div class="meta-item">
            <label>Elaborado por</label>
            <span>{{ $documento->author->name }}</span>
        </div>
    </div>

    @foreach($documento->content as $campo => $valor)
        <div class="section">
            <div class="section-title">{{ str_replace('_', ' ', $campo) }}</div>
            <div class="section-content">
                @if($valor)
                    {{ $valor }}
                @else
                    <span class="empty">Não preenchido</span>
                @endif
            </div>
        </div>
    @endforeach

    <div class="signature-area">
        <div class="signature-box">
            Professor / Elaborador
        </div>
        <div class="signature-box">
            Coordenação / Secretaria
        </div>
        <div class="signature-box">
            Responsável pelo aluno
        </div>
    </div>

    <div class="footer">
        <span>Gerado em {{ now()->format('d/m/Y \à\s H:i') }}</span>
        <span>Documento confidencial — LGPD</span>
    </div>

</body>
</html>