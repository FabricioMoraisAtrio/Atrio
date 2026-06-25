@props(['aluno'])

@php
$cids = [
    'cid_autismo'               => ['label' => 'TEA',             'bg' => '#FEF3C7', 'color' => '#92400E'],
    'cid_tdah'                  => ['label' => 'TDAH',            'bg' => '#EDE9FE', 'color' => '#5B21B6'],
    'cid_down'                  => ['label' => 'Down',            'bg' => '#FCE7F3', 'color' => '#9D174D'],
    'cid_deficiencia_intelectual' => ['label' => 'Def. Intelectual', 'bg' => '#DBEAFE', 'color' => '#1E40AF'],
    'cid_deficiencia_visual'    => ['label' => 'Def. Visual',     'bg' => '#D1FAE5', 'color' => '#065F46'],
    'cid_deficiencia_auditiva'  => ['label' => 'Def. Auditiva',   'bg' => '#E0F2FE', 'color' => '#0369A1'],
    'cid_outros'                => ['label' => 'Outros',          'bg' => '#F3F4F6', 'color' => '#6B7280'],
];

$temCid = collect($cids)->keys()->some(fn($field) => $aluno->$field);
@endphp

@if($temCid)
    <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
        @foreach($cids as $field => $cfg)
            @if($aluno->$field)
                <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }};">
                    {{ $cfg['label'] }}
                </span>
            @endif
        @endforeach
    </div>
@elseif($aluno->condition)
    <p style="font-size: 12px; color: var(--text-4); margin: 4px 0 0;">{{ $aluno->condition }}</p>
@endif