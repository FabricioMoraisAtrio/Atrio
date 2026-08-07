<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornada Alimentar — {{ $aluno->name }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F0F5FB;
            color: #0D1F36;
            line-height: 1.5;
        }

        .page {
            max-width: 860px;
            margin: 0 auto;
            padding: 32px 24px 80px;
        }

        /* ── Cabeçalho do estudante ── */
        .student-header {
            background: #004B8D;
            border-radius: 16px;
            padding: 28px 32px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .student-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 30px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 3px solid rgba(255,255,255,0.25);
        }
        .student-info { flex: 1; }
        .student-info h1 {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }
        .student-info .meta {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .student-info .meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .student-stats {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }
        .stat-pill {
            text-align: center;
            background: rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 10px 16px;
            min-width: 60px;
        }
        .stat-pill .num {
            font-size: 22px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 3px;
        }
        .stat-pill .lbl {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.7;
        }
        .stat-recusa .num { color: #FCA5A5; }
        .stat-tolera .num { color: #FCD34D; }
        .stat-aceita .num { color: #6EE7B7; }
        .stat-pill .lbl { color: rgba(255,255,255,0.65); }

        /* ── Aviso ── */
        .alert-box {
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            border-left: 4px solid #F59E0B;
            border-radius: 10px;
            padding: 11px 16px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #92400E;
            font-weight: 500;
        }

        /* ── Grid de status ── */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .status-card {
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px solid transparent;
        }
        .status-card.recusa { background: #FEF2F2; border-color: #FECACA; }
        .status-card.tolera { background: #FFFBEB; border-color: #FDE68A; }
        .status-card.aceita { background: #ECFDF5; border-color: #A7F3D0; }

        .status-card-header {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1.5px solid transparent;
        }
        .recusa .status-card-header { background: #FEE2E2; border-color: #FECACA; }
        .tolera .status-card-header { background: #FEF3C7; border-color: #FDE68A; }
        .aceita .status-card-header { background: #D1FAE5; border-color: #A7F3D0; }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .recusa .status-dot { background: #EF4444; }
        .tolera .status-dot { background: #F59E0B; }
        .aceita .status-dot { background: #10B981; }

        .status-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .recusa .status-title { color: #991B1B; }
        .tolera .status-title { color: #92400E; }
        .aceita .status-title { color: #065F46; }

        .status-card-body {
            padding: 12px 16px;
            min-height: 80px;
        }

        .category-group { margin-bottom: 10px; }
        .category-group:last-child { margin-bottom: 0; }

        .category-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5px;
        }
        .recusa .category-label { color: #EF4444; opacity: 0.7; }
        .tolera .category-label { color: #F59E0B; opacity: 0.7; }
        .aceita .category-label { color: #10B981; opacity: 0.7; }

        .food-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .tag {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 9px;
            border-radius: 20px;
            line-height: 1.4;
        }
        .tag-recusa { background: #FEE2E2; color: #991B1B; }
        .tag-tolera { background: #FEF3C7; color: #92400E; }
        .tag-aceita { background: #D1FAE5; color: #065F46; }

        .empty-status {
            font-size: 12px;
            color: #9CA3AF;
            font-style: italic;
            padding: 8px 0;
        }

        /* ── Seção por categoria ── */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #5A7FA8;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #C8DDF0;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .category-card {
            background: #fff;
            border: 1px solid #C8DDF0;
            border-radius: 10px;
            padding: 12px 14px;
        }
        .category-card-title {
            font-size: 10px;
            font-weight: 700;
            color: #5A7FA8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        /* ── Rodapé ── */
        .footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #C8DDF0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: #8EB3D4;
        }

        /* ── Barra de ações ── */
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
            .page { padding: 12px; }
            .student-header { border-radius: 10px; }
            .status-grid { grid-template-columns: repeat(3, 1fr); }
            .category-grid { grid-template-columns: repeat(3, 1fr); }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @media (max-width: 640px) {
            .student-header { flex-direction: column; text-align: center; }
            .student-stats { justify-content: center; }
            .status-grid { grid-template-columns: 1fr; }
            .category-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<div class="page">

    {{-- Cabeçalho do estudante --}}
    @php
        $turma   = $aluno->schoolClasses->first();
        $recusa  = $byStatus->get('recusa', collect());
        $tolera  = $byStatus->get('tolera', collect());
        $aceita  = $byStatus->get('aceita', collect());
        $total   = $aluno->foodItems->count();
    @endphp

    <div class="student-header">
        <div class="student-avatar">{{ strtoupper(substr($aluno->name, 0, 1)) }}</div>
        <div class="student-info">
            <h1>{{ $aluno->name }}</h1>
            <div class="meta">
                @if($turma)
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    {{ $turma->name }}
                </span>
                @endif
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Gerado em {{ now()->format('d/m/Y \à\s H:i') }}
                </span>
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    {{ $school?->name ?? 'Escola' }}
                </span>
            </div>
        </div>
        <div class="student-stats">
            <div class="stat-pill stat-recusa">
                <div class="num">{{ $recusa->count() }}</div>
                <div class="lbl">Recusa</div>
            </div>
            <div class="stat-pill stat-tolera">
                <div class="num">{{ $tolera->count() }}</div>
                <div class="lbl">Tolera</div>
            </div>
            <div class="stat-pill stat-aceita">
                <div class="num">{{ $aceita->count() }}</div>
                <div class="lbl">Aceita</div>
            </div>
        </div>
    </div>

    @if($recusa->isNotEmpty())
    <div class="alert-box">
        Atenção: este estudante <strong>recusa {{ $recusa->count() }} alimento(s)</strong>. Verifique a lista abaixo antes de preparar o lanche.
    </div>
    @endif

    {{-- Cards por status --}}
    <div class="status-grid">

        {{-- Recusa --}}
        <div class="status-card recusa">
            <div class="status-card-header">
                <div class="status-dot"></div>
                <span class="status-title">Não servir</span>
            </div>
            <div class="status-card-body">
                @if($recusa->isEmpty())
                    <p class="empty-status">Nenhum item registrado</p>
                @else
                    @foreach($recusa->groupBy('category') as $cat => $items)
                    <div class="category-group">
                        <div class="category-label">{{ $categories[$cat] ?? $cat }}</div>
                        <div class="food-tags">
                            @foreach($items as $item)
                                <span class="tag tag-recusa">{{ $item->name }}@if($item->notes) <em style="font-weight:400; opacity:0.75;">· {{ $item->notes }}</em>@endif</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Tolera --}}
        <div class="status-card tolera">
            <div class="status-card-header">
                <div class="status-dot"></div>
                <span class="status-title">Tolera</span>
            </div>
            <div class="status-card-body">
                @if($tolera->isEmpty())
                    <p class="empty-status">Nenhum item registrado</p>
                @else
                    @foreach($tolera->groupBy('category') as $cat => $items)
                    <div class="category-group">
                        <div class="category-label">{{ $categories[$cat] ?? $cat }}</div>
                        <div class="food-tags">
                            @foreach($items as $item)
                                <span class="tag tag-tolera">{{ $item->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Aceita --}}
        <div class="status-card aceita">
            <div class="status-card-header">
                <div class="status-dot"></div>
                <span class="status-title">Aceita</span>
            </div>
            <div class="status-card-body">
                @if($aceita->isEmpty())
                    <p class="empty-status">Nenhum item registrado</p>
                @else
                    @foreach($aceita->groupBy('category') as $cat => $items)
                    <div class="category-group">
                        <div class="category-label">{{ $categories[$cat] ?? $cat }}</div>
                        <div class="food-tags">
                            @foreach($items as $item)
                                <span class="tag tag-aceita">{{ $item->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>

    {{-- Detalhamento por categoria --}}
    @php $categoriesWithItems = $byCategory->keys()->toArray(); @endphp
    @if(!empty($categoriesWithItems))
    <p class="section-title">Detalhamento por categoria</p>
    <div class="category-grid">
        @foreach($categories as $catKey => $catLabel)
            @php $items = $byCategory->get($catKey, collect()); @endphp
            @if($items->isNotEmpty())
            <div class="category-card">
                <div class="category-card-title">{{ $catLabel }}</div>
                <div class="food-tags">
                    @foreach($items as $item)
                        @php $cls = 'tag-' . $item->status; @endphp
                        <span class="tag {{ $cls }}">{{ $item->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    @if($total === 0)
    <div style="text-align: center; padding: 60px; color: var(--text-4); font-size: 14px;">
        Nenhum alimento registrado para este estudante.
    </div>
    @endif

    {{-- Rodapé --}}
    <div class="footer">
        <span>Sistema Átrio · Jornada Alimentar</span>
        <span>{{ $school?->name }} · {{ date('Y') }}</span>
    </div>

</div>

{{-- Barra de ações --}}
<div class="print-bar">
    <a href="{{ route('secretaria.seletividade.show', $aluno) }}" class="btn-back">
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
