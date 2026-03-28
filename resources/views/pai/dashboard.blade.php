@extends('layouts.app')
@section('title', 'Acompanhamento')

@section('content')

@php
$tipoConfig = [
    'estudo_caso' => ['label' => 'Estudo de Caso', 'color' => '#004B8D', 'bg' => '#E8F0F9'],
    'pei'         => ['label' => 'PEI',             'color' => '#7C3AED', 'bg' => '#F3E8FF'],
    'paee'        => ['label' => 'PAEE',            'color' => '#009C8C', 'bg' => '#E6F5F4'],
];
@endphp

@forelse($filhos as $filho)

{{-- ── CABEÇALHO DO FILHO ── --}}
<div style="background: #fff; border-radius: 16px; border: 1px solid #F3F4F6; padding: 28px; margin-bottom: 24px;">

    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 22px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                {{ strtoupper(substr($filho->name, 0, 1)) }}
            </div>
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $filho->name }}</h2>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Matrícula: {{ $filho->registration_number }}</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            @if($filho->is_atypical)
                <span style="background: #F3E8FF; color: #7C3AED; font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px;">
                    {{ $filho->condition ?? 'Atípico' }}
                </span>
            @endif
            @foreach($filho->schoolClasses as $turma)
                <span style="background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px;">
                    {{ $turma->name }} · {{ $turma->shift }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Progresso de documentos --}}
    @php
        $tipos = ['estudo_caso', 'pei', 'paee'];
        $criados = $filho->documents->pluck('type')->toArray();
        $total = count($tipos);
        $feitos = count(array_intersect($tipos, $criados));
    @endphp
    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #F9FAFB;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <p style="font-size: 12px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                Documentação {{ date('Y') }}
            </p>
            <span style="font-size: 12px; font-weight: 700; color: {{ $feitos === $total ? '#009C8C' : '#004B8D' }};">
                {{ $feitos }}/{{ $total }} completos
            </span>
        </div>
        <div style="height: 6px; background: #F3F4F6; border-radius: 10px; overflow: hidden;">
            <div style="height: 100%; width: {{ ($feitos / $total) * 100 }}%; background: {{ $feitos === $total ? '#009C8C' : '#004B8D' }}; border-radius: 10px; transition: width 0.4s;"></div>
        </div>
        <div style="display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap;">
            @foreach($tipos as $tipo)
                @php $ok = in_array($tipo, $criados); $cfg = $tipoConfig[$tipo]; @endphp
                <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
                    {{ $ok ? 'background:' . $cfg['bg'] . '; color:' . $cfg['color'] . ';' : 'background: #F3F4F6; color: #9CA3AF;' }}">
                    {{ $ok ? '✓' : '○' }} {{ $cfg['label'] }}
                </span>
            @endforeach
        </div>
    </div>
</div>

{{-- ── DOCUMENTOS ── --}}
@if($filho->documents->isNotEmpty())
<div style="margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px;">
        Documentos {{ date('Y') }}
    </p>
    <div style="display: flex; flex-direction: column; gap: 12px;">
        @foreach($filho->documents as $doc)
            @php $cfg = $tipoConfig[$doc->type] ?? ['label' => strtoupper($doc->type), 'color' => '#374151', 'bg' => '#F3F4F6']; @endphp
            <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">

                {{-- Header do documento --}}
                <div style="padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid {{ $cfg['color'] }};">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }}; letter-spacing: 0.5px; text-transform: uppercase;">
                            {{ $cfg['label'] }}
                        </span>
                        <span style="font-size: 11px; color: #9CA3AF;">{{ $doc->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <a href="{{ route('pai.documentos.pdf', $doc) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: {{ $cfg['color'] }}; text-decoration: none; padding: 6px 14px; border-radius: 8px; border: 1px solid {{ $cfg['bg'] }}; background: {{ $cfg['bg'] }};">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Ver PDF
                    </a>
                </div>

                {{-- Conteúdo do documento --}}
                @php
                    $campos = match($doc->type) {
                        'pei'         => [['key' => 'objetivos', 'label' => 'Objetivos'], ['key' => 'progresso', 'label' => 'Progresso']],
                        'estudo_caso' => [['key' => 'potencialidades', 'label' => 'Potencialidades'], ['key' => 'necessidades', 'label' => 'Necessidades']],
                        'paee'        => [['key' => 'recursos', 'label' => 'Recursos de Apoio'], ['key' => 'estrategias', 'label' => 'Estratégias']],
                        default       => [],
                    };
                @endphp
                @foreach($campos as $campo)
                    @if(!empty($doc->content[$campo['key']]))
                        <div style="padding: 14px 20px; border-top: 1px solid #F9FAFB;">
                            <p style="font-size: 10px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 6px;">{{ $campo['label'] }}</p>
                            <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.7;">{{ $doc->content[$campo['key']] }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@else
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 32px; text-align: center; margin-bottom: 24px;">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 10px; display: block;">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>
    </svg>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Nenhum documento registrado este ano.</p>
</div>
@endif

{{-- ── LAUDOS ── --}}
@if($filho->laudos->isNotEmpty())
<div style="margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: #9CA3AF; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px;">
        Laudos
    </p>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
        @foreach($filho->laudos as $laudo)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">{{ $laudo->tipo_label }}</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                            {{ $laudo->data_laudo->format('d/m/Y') }}
                            @if($laudo->descricao) · {{ $laudo->descricao }} @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('pai.laudos.download', $laudo) }}"
                   style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 600; padding: 6px 14px; border: 1px solid #E8F0F9; background: #E8F0F9; border-radius: 8px;">
                    Baixar PDF
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif

@empty
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 56px; text-align: center;">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 14px; display: block;">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
    </svg>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Nenhum aluno vinculado à sua conta ainda.</p>
</div>
@endforelse
@endsection
