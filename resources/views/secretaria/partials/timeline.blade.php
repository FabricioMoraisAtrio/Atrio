@php
    use App\Models\GoalProgress;

    // Cor e ícone por tipo de evento. Metas variam a cor pelo status.
    $corMeta = [
        'atingiu'      => 'var(--success)',
        'em_progresso' => 'var(--warning)',
        'nao_atingiu'  => 'var(--danger)',
    ];
    $icone = [
        'meta'       => '<path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>',
        'reuniao'    => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/>',
        'laudo'      => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>',
        'observacao' => '<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>',
        'fechamento' => '<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>',
    ];
    $rotulo = ['meta' => 'Meta', 'reuniao' => 'Reunião', 'laudo' => 'Laudo', 'observacao' => 'Observação', 'fechamento' => 'Fechamento'];
    $corBgMeta = [
        'atingiu'      => 'var(--success-bg)',
        'em_progresso' => 'var(--warning-bg)',
        'nao_atingiu'  => 'var(--danger-bg)',
    ];
    $corEvento = function ($e) use ($corMeta) {
        if ($e['tipo'] === 'meta')       return $corMeta[$e['status']] ?? 'var(--accent)';
        if ($e['tipo'] === 'reuniao')    return 'var(--accent)';
        if ($e['tipo'] === 'laudo')      return 'var(--teal)';
        if ($e['tipo'] === 'fechamento') return 'var(--accent-text)';
        return $e['critico'] ? 'var(--danger)' : 'var(--purple)';
    };
    $corBgEvento = function ($e) use ($corBgMeta) {
        if ($e['tipo'] === 'meta')       return $corBgMeta[$e['status']] ?? 'var(--accent-bg)';
        if ($e['tipo'] === 'reuniao')    return 'var(--accent-bg)';
        if ($e['tipo'] === 'laudo')      return 'var(--teal-bg)';
        if ($e['tipo'] === 'fechamento') return 'var(--accent-bg)';
        return $e['critico'] ? 'var(--danger-bg)' : 'var(--purple-bg)';
    };
@endphp

@forelse($eventos as $e)
    @php $cor = $corEvento($e); $bg = $corBgEvento($e); @endphp
    <div style="display: flex; gap: 14px; {{ !$loop->last ? 'padding-bottom: 16px;' : '' }}">
        {{-- Trilho + marcador --}}
        <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
            <div style="width: 30px; height: 30px; border-radius: 8px; background: {{ $bg }}; color: {{ $cor }}; display: flex; align-items: center; justify-content: center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $icone[$e['tipo']] ?? '' !!}</svg>
            </div>
            @unless($loop->last)
                <div style="flex: 1; width: 2px; background: var(--border-sub); margin-top: 4px;"></div>
            @endunless
        </div>

        {{-- Conteúdo --}}
        <div style="flex: 1; min-width: 0; padding-bottom: 2px;">
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 2px;">
                <span style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $cor }};">{{ $rotulo[$e['tipo']] ?? '' }}</span>
                <span style="font-size: 11px; color: var(--text-4);">{{ $e['data'] ? $e['data']->format('d/m/Y') : 's/ data' }}</span>
                @if($e['tipo'] === 'meta' && $e['status'])
                    <span style="font-size: 10px; font-weight: 700; padding: 1px 8px; border-radius: 20px; background: {{ $bg }}; color: {{ $cor }};">{{ GoalProgress::STATUSES[$e['status']] ?? $e['status'] }}</span>
                @endif
            </div>
            <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0;">{{ $e['titulo'] }}</p>
            @if($e['descricao'])
                <p style="font-size: 12.5px; color: var(--text-3); margin: 2px 0 0; line-height: 1.5;">{{ \Illuminate\Support\Str::limit($e['descricao'], 160) }}</p>
            @endif
        </div>
    </div>
@empty
    <div style="padding: 28px; text-align: center;">
        <p style="font-size: 13px; color: var(--text-4); margin: 0;">Nenhum evento registrado ainda.</p>
    </div>
@endforelse
