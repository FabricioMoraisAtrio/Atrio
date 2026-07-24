@php
    use App\Models\GoalProgress;

    // Cor do preenchimento da barra do gráfico conforme o % atingido.
    $corBarra = function (?int $pct) {
        if ($pct === null)  return 'var(--border)';
        if ($pct < 40)      return 'var(--danger)';
        if ($pct < 70)      return 'var(--warning)';
        return 'var(--success)';
    };

    // <select> de status de uma meta em um bimestre (colorido via JS).
    $statusSelect = function (int $goalId, int $bimestre, string $selected) {
        $opts = '';
        foreach (GoalProgress::STATUSES as $val => $label) {
            $sel = $val === $selected ? ' selected' : '';
            $opts .= '<option value="' . $val . '"' . $sel . '>' . $label . '</option>';
        }
        return '<select name="status[' . $goalId . '][' . $bimestre . ']" onchange="corStatus(this)"
                    style="width:100%;border:1px solid var(--border);border-radius:7px;padding:6px 8px;font-size:12px;
                           font-weight:600;outline:none;cursor:pointer;-webkit-appearance:none;appearance:none;
                           background-position:right 6px center;">' . $opts . '</select>';
    };

    // Linha (tr) de uma meta na matriz.
    $metaRow = function ($meta) use ($statusSelect, $progresso, $bimestres) {
        $cells = '';
        foreach ($bimestres as $b) {
            $sel = $progresso[$meta->id][$b] ?? 'nao_avaliado';
            $cells .= '<td style="padding:8px 6px;border-left:1px solid var(--border-sub);vertical-align:middle;">'
                    . $statusSelect($meta->id, $b, $sel) . '</td>';
        }
        return '<tr>
                    <td style="padding:10px 14px;font-size:13px;color:var(--text-2);line-height:1.4;min-width:200px;">'
                    . e($meta->meta) . '</td>' . $cells . '</tr>';
    };

    $temMetas = $metas->isNotEmpty();
@endphp

@unless($temMetas)
    <div style="background: var(--warning-bg); border: 1px solid var(--warning-border); border-radius: 10px; padding: 16px 20px;">
        <p style="font-size: 13px; color: var(--warning); margin: 0;">
            Nenhuma meta cadastrada para {{ $ano }}. Cadastre as metas em
            <a href="{{ route('secretaria.alunos.metas-academicas.edit', $aluno) }}" style="color: var(--warning); font-weight: 700;">Personalizar Metas</a>
            antes de acompanhar a evolução.
        </p>
    </div>
@else

{{-- ═══ Gráfico: % atingido por bimestre ═══ --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 22px 24px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 18px;">Progresso geral por bimestre</p>
    <div style="display: flex; align-items: flex-end; gap: 20px; height: 150px;">
        @foreach($bimestres as $b)
            @php $g = $grafico[$b]; $pct = $g['percentual']; @endphp
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; height: 100%;">
                <div style="flex: 1; width: 100%; display: flex; flex-direction: column; justify-content: flex-end; align-items: center;">
                    <span style="font-size: 13px; font-weight: 700; color: {{ $pct === null ? 'var(--text-4)' : 'var(--text-1)' }}; margin-bottom: 6px;">
                        {{ $pct === null ? '—' : $pct . '%' }}
                    </span>
                    <div style="width: 100%; max-width: 90px; height: {{ $pct === null ? 4 : max(4, (int) round($pct * 0.9)) }}px;
                                background: {{ $corBarra($pct) }}; border-radius: 6px 6px 3px 3px; transition: height .3s;"></div>
                </div>
                <div style="text-align: center;">
                    <p style="font-size: 12px; font-weight: 600; color: var(--text-2); margin: 0;">{{ $b }}º bim.</p>
                    <p style="font-size: 10px; color: var(--text-4); margin: 0;">
                        {{ $g['avaliadas'] > 0 ? $g['avaliadas'] . ' avaliada' . ($g['avaliadas'] > 1 ? 's' : '') : 'sem avaliação' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ═══ Matriz de acompanhamento ═══ --}}
<form method="POST" action="{{ route('secretaria.alunos.metas-evolucao.update', $aluno) }}">
    @csrf @method('PUT')

    @php
        // Cabeçalho de colunas reutilizado em cada bloco.
        $thead = '<thead><tr>
                    <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:700;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Meta</th>';
        foreach ($bimestres as $b) {
            $thead .= '<th style="padding:10px 6px;font-size:11px;font-weight:700;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;border-left:1px solid var(--border-sub);width:120px;">' . $b . 'º bim.</th>';
        }
        $thead .= '</tr></thead>';
    @endphp

    {{-- Acadêmicas por matéria --}}
    @foreach($subjects as $subject)
        @php $ms = $metasPorMateria->get($subject->id, collect()); @endphp
        @if($ms->isNotEmpty())
            <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 10px;">{{ $subject->name }}</p>
            <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; overflow-x: auto; margin-bottom: 20px;">
                <table style="width: 100%; border-collapse: collapse; min-width: 640px;">
                    {!! $thead !!}
                    <tbody>
                        @foreach($ms as $meta)
                            {!! $metaRow($meta) !!}
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach

    {{-- Socioemocionais --}}
    @if($metasSocio->isNotEmpty())
        <p style="font-size: 11px; font-weight: 700; color: #007A6E; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 10px;">Socioemocionais</p>
        <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; overflow-x: auto; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; min-width: 640px;">
                {!! $thead !!}
                <tbody>
                    @foreach($metasSocio as $meta)
                        {!! $metaRow($meta) !!}
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Funcionais --}}
    @if($metasFuncionais->isNotEmpty())
        <p style="font-size: 11px; font-weight: 700; color: var(--purple); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 10px;">Funcionais</p>
        <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; overflow-x: auto; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; min-width: 640px;">
                {!! $thead !!}
                <tbody>
                    @foreach($metasFuncionais as $meta)
                        {!! $metaRow($meta) !!}
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div style="display: flex; justify-content: flex-end; margin-top: 4px;">
        <button type="submit"
                style="background: var(--accent); color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Salvar evolução
        </button>
    </div>
</form>
@endunless

<script>
    // Cores por status aplicadas ao próprio <select>.
    const CORES_STATUS = {
        nao_avaliado: { bg: 'var(--bg-subtle)',  fg: 'var(--text-4)', bd: 'var(--border)' },
        nao_atingiu:  { bg: 'var(--danger-bg)',  fg: 'var(--danger)', bd: 'var(--danger-border)' },
        em_progresso: { bg: 'var(--warning-bg)', fg: 'var(--warning)', bd: 'var(--warning-border)' },
        atingiu:      { bg: 'var(--success-bg)', fg: 'var(--success)', bd: 'var(--success-border)' },
    };
    function corStatus(select) {
        const c = CORES_STATUS[select.value] || CORES_STATUS.nao_avaliado;
        select.style.background = c.bg;
        select.style.color = c.fg;
        select.style.borderColor = c.bd;
    }
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('select[name^="status["]').forEach(corStatus);
    });
</script>
