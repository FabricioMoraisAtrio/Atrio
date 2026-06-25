<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornada Alimentar — {{ $school?->name ?? 'Escola' }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F8FAFC;
            color: #0D1F36;
            line-height: 1.5;
        }

        .page {
            max-width: 960px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── Cabeçalho ── */
        .header {
            background: #004B8D;
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .header-left h1 {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }
        .header-left p {
            font-size: 13px;
            color: rgba(255,255,255,0.65);
        }
        .header-right {
            text-align: right;
        }
        .header-right .date {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }
        .header-right .total {
            font-size: 28px;
            font-weight: 800;
            color: #60CFCA;
            line-height: 1;
            margin-top: 4px;
        }
        .header-right .total-label {
            font-size: 11px;
            color: rgba(255,255,255,0.55);
        }

        /* ── Legenda ── */
        .legend {
            display: flex;
            gap: 20px;
            align-items: center;
            background: #fff;
            border: 1px solid #C8DDF0;
            border-radius: 10px;
            padding: 14px 20px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .legend-title {
            font-size: 11px;
            font-weight: 700;
            color: #5A7FA8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-right: 4px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: #2C4A6E;
        }
        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── Aviso ── */
        .alert-box {
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            border-left: 4px solid #F59E0B;
            border-radius: 10px;
            padding: 12px 18px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #92400E;
            font-weight: 500;
        }

        /* ── Cards de aluno ── */
        .students-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .student-card {
            background: #fff;
            border: 1px solid #C8DDF0;
            border-radius: 12px;
            overflow: hidden;
        }
        .student-card-header {
            padding: 14px 18px;
            border-bottom: 1px solid #EEF4FB;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #E8F0F9;
            color: #004B8D;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .student-name {
            font-size: 14px;
            font-weight: 700;
            color: #0D1F36;
        }
        .student-class {
            font-size: 11px;
            color: #5A7FA8;
            margin-top: 1px;
        }
        .student-card-body {
            padding: 14px 18px;
        }

        /* ── Seção de restrições (destaque) ── */
        .restriction-box {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 10px;
        }
        .restriction-box-title {
            font-size: 10px;
            font-weight: 800;
            color: #991B1B;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .restriction-box-title::before {
            content: '✕';
            font-size: 9px;
        }
        .food-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .tag {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 9px;
            border-radius: 20px;
        }
        .tag-recusa  { background: #FEE2E2; color: #991B1B; }
        .tag-tolera  { background: #FEF3C7; color: #92400E; }
        .tag-aceita  { background: #D1FAE5; color: #065F46; }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            color: #5A7FA8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 8px 0 5px;
        }

        .no-data {
            font-size: 12px;
            color: #8EB3D4;
            font-style: italic;
        }

        /* ── Rodapé ── */
        .footer {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px solid #C8DDF0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: #8EB3D4;
        }

        /* ── Botão imprimir (só na tela) ── */
        .print-bar {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        .btn-print {
            background: #004B8D;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 20px rgba(0,75,141,0.35);
        }
        .btn-back {
            background: #fff;
            color: #2C4A6E;
            border: 1px solid #C8DDF0;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        @media print {
            body { background: #fff; }
            .print-bar { display: none !important; }
            .page { padding: 16px; }
            .students-grid { grid-template-columns: repeat(2, 1fr); }
            .header { border-radius: 8px; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @media (max-width: 640px) {
            .students-grid { grid-template-columns: 1fr; }
            .header { flex-direction: column; text-align: center; }
            .header-right { text-align: center; }
        }
    </style>
</head>
<body>

<div class="page">

    {{-- Cabeçalho --}}
    <div class="header">
        <div class="header-left">
            <h1>Jornada Alimentar</h1>
            <p>{{ $school?->name ?? 'Escola' }} · Ano letivo {{ date('Y') }}</p>
        </div>
        <div class="header-right">
            <div class="date">Gerado em {{ now()->format('d/m/Y \à\s H:i') }}</div>
            <div class="total">{{ $alunos->count() }}</div>
            <div class="total-label">aluno(s) com perfil</div>
        </div>
    </div>

    {{-- Aviso --}}
    <div class="alert-box">
        Atenção: verifique sempre os itens marcados como <strong>RECUSA</strong> antes de preparar o lanche. Respeitar as restrições alimentares é fundamental para o bem-estar e segurança dos alunos.
    </div>

    {{-- Legenda --}}
    <div class="legend">
        <span class="legend-title">Legenda:</span>
        <div class="legend-item">
            <div class="legend-dot" style="background: var(--danger-solid);"></div>
            Recusa — não servir
        </div>
        <div class="legend-item">
            <div class="legend-dot" style="background: #F59E0B;"></div>
            Tolera — pode causar desconforto
        </div>
        <div class="legend-item">
            <div class="legend-dot" style="background: var(--success-solid);"></div>
            Aceita — sem restrição
        </div>
    </div>

    {{-- Grid de alunos --}}
    @if($alunos->isEmpty())
        <div style="text-align: center; padding: 60px; color: var(--text-4); font-size: 14px;">
            Nenhum aluno com perfil alimentar cadastrado.
        </div>
    @else
    <div class="students-grid">
        @foreach($alunos as $aluno)
        @php
            $turma   = $aluno->schoolClasses->first();
            $recusa  = $aluno->foodItems->where('status', 'recusa');
            $tolera  = $aluno->foodItems->where('status', 'tolera');
            $aceita  = $aluno->foodItems->where('status', 'aceita');
        @endphp
        <div class="student-card">
            <div class="student-card-header">
                <div class="student-avatar">{{ strtoupper(substr($aluno->name, 0, 1)) }}</div>
                <div>
                    <div class="student-name">{{ $aluno->name }}</div>
                    <div class="student-class">{{ $turma?->name ?? 'Sem turma' }}</div>
                </div>
            </div>
            <div class="student-card-body">

                {{-- Recusa em destaque --}}
                @if($recusa->isNotEmpty())
                <div class="restriction-box">
                    <div class="restriction-box-title">Não servir</div>
                    <div class="food-tags">
                        @foreach($recusa as $item)
                            <span class="tag tag-recusa">{{ $item->name }}@if($item->notes) <em style="font-weight:400; opacity:0.75;">· {{ $item->notes }}</em>@endif</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Tolera --}}
                @if($tolera->isNotEmpty())
                <div class="section-label">Tolera (pode causar desconforto)</div>
                <div class="food-tags" style="margin-bottom: 8px;">
                    @foreach($tolera as $item)
                        <span class="tag tag-tolera">{{ $item->name }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Aceita --}}
                @if($aceita->isNotEmpty())
                <div class="section-label">Aceita normalmente</div>
                <div class="food-tags">
                    @foreach($aceita as $item)
                        <span class="tag tag-aceita">{{ $item->name }}</span>
                    @endforeach
                </div>
                @endif

                @if($recusa->isEmpty() && $tolera->isEmpty() && $aceita->isEmpty())
                    <p class="no-data">Nenhum alimento registrado.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Rodapé --}}
    <div class="footer">
        <span>Sistema Átrio · Jornada Alimentar</span>
        <span>{{ $school?->name }} · {{ date('Y') }}</span>
    </div>

</div>

{{-- Barra de ações flutuante --}}
<div class="print-bar">
    <a href="{{ route('secretaria.seletividade.index') }}" class="btn-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar
    </a>
    <button onclick="window.print()" class="btn-print">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/>
        </svg>
        Imprimir / Salvar PDF
    </button>
</div>

</body>
</html>
