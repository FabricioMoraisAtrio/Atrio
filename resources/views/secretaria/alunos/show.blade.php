@extends('layouts.app')
@section('title', $aluno->name)

@section('content')
<div style="margin-bottom: 24px;">
    @php
        $backUrl   = request('back') ? urldecode(request('back')) : route('secretaria.alunos.index');
        $backLabel = request('back') ? 'Voltar' : 'Voltar para ' . strtolower(term('alunos'));
    @endphp
    <a href="{{ $backUrl }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        {{ $backLabel }}
    </a>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="position: relative; width: 110px; height: 110px; flex-shrink: 0;">
                @if($aluno->photo)
                    <img src="{{ route('alunos.foto', $aluno) }}" alt="{{ $aluno->name }}"
                         style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid #E5E7EB;">
                @else
                    <div style="width: 110px; height: 110px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 36px; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                    </div>
                @endif
                <label title="Alterar foto" style="position: absolute; bottom: 4px; right: 4px; width: 26px; height: 26px; background: #004B8D; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; border: 2px solid #fff;">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    <form method="POST" action="{{ route('secretaria.alunos.uploadPhoto', $aluno) }}" enctype="multipart/form-data" style="display:none;" id="foto-form-{{ $aluno->id }}">
                        @csrf
                        <input type="file" name="photo" accept="image/*" onchange="document.getElementById('foto-form-{{ $aluno->id }}').submit()">
                    </form>
                </label>
            </div>
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $aluno->name }}</h1>
                <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Matrícula: {{ $aluno->registration_number }}</p>
            </div>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('secretaria.alunos.edit', $aluno) }}"
               style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                Editar
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

{{-- Responsáveis --}}
@if($aluno->responsavel_nome || $aluno->responsavel_2_nome)
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px; margin-bottom: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
    @foreach([
        ['label' => 'Responsável',    'nome' => $aluno->responsavel_nome,    'email' => $aluno->responsavel_email,    'tel' => $aluno->responsavel_telefone],
        ['label' => '2º Responsável', 'nome' => $aluno->responsavel_2_nome,  'email' => $aluno->responsavel_2_email,  'tel' => $aluno->responsavel_2_telefone],
    ] as $resp)
    @if($resp['nome'])
    <div>
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">{{ $resp['label'] }}</p>
        <p style="font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 4px;">{{ $resp['nome'] }}</p>
        @if($resp['email'])
        <a href="mailto:{{ $resp['email'] }}" style="font-size: 12px; color: #004B8D; text-decoration: none; display: block; margin-bottom: 2px;">{{ $resp['email'] }}</a>
        @endif
        @if($resp['tel'])
        <a href="tel:{{ preg_replace('/\D/', '', $resp['tel']) }}" style="font-size: 12px; color: #6B7280; text-decoration: none;">{{ $resp['tel'] }}</a>
        @endif
    </div>
    @endif
    @endforeach
</div>
@endif

{{-- Cards de info --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Nascimento</p>
        <p style="font-size: 15px; font-weight: 600; color: #111827; margin: 0;">{{ $aluno->birth_date->format('d/m/Y') }}</p>
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Perfil</p>
        @if($aluno->is_atypical)
            @if($aluno->is_publico_alvo)
                <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
            @else
                <span style="background: #FEF3C7; color: #92400E; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">Atípico</span>
            @endif
            @if($aluno->condition)
                <p style="font-size: 13px; color: #6B7280; margin: 6px 0 0;">{{ $aluno->condition }}</p>
            @endif
            {{-- Flags CID --}}
            <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px;">
                @foreach(config('transtornos') as $field => [$sigla, $nome])
                    @if($aluno->$field)
                        <span style="background: #F5EDE6; color: #7C3700; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;" title="{{ $nome }}">{{ $sigla }}</span>
                        @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                            <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px;">N{{ $aluno->tea_nivel_suporte }}</span>
                        @endif
                    @endif
                @endforeach
            </div>
        @else
            <span style="background: #F3F4F6; color: #6B7280; font-size: 12px; padding: 4px 10px; border-radius: 20px;">{{ term('nao_publico_alvo') }}</span>
        @endif
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Estudo de caso</p>
        @if($aluno->has_case_study)
            <span style="background: #ECFDF5; color: #065F46; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">✓ Preenchido</span>
        @else
            <span style="background: #FEF2F2; color: #991B1B; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">Pendente</span>
        @endif
    </div>
</div>

{{-- Documentos --}}
@php
    $docsAno    = $aluno->documents->where('year', date('Y'));
    $docsVisiveis = $docsAno->whereIn('type', ['paee', 'pei_consolidado']);
    $peiConsolidadoDoc = $docsAno->firstWhere('type', 'pei_consolidado');
@endphp
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0;">Documentos {{ date('Y') }}</h3>
        <div style="display: flex; gap: 8px;">
            @foreach(['paee' => 'PAEE'] as $tipo => $label)
                @unless($docsVisiveis->where('type', $tipo)->count())
                    <a href="{{ route('secretaria.alunos.documentos.create', [$aluno, 'type' => $tipo]) }}"
                       style="font-size: 12px; background: #F3F4F6; color: #374151; font-weight: 600; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                        + {{ $label }}
                    </a>
                @endunless
            @endforeach
        </div>
    </div>

    @if($errors->has('documento'))
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
            {{ $errors->first('documento') }}
        </div>
    @endif

    {{-- PAEE --}}
    @foreach($docsVisiveis->whereIn('type', ['paee']) as $doc)
        <a href="{{ route('secretaria.documentos.show', $doc) }}?back={{ urlencode(url()->current()) }}"
           style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; margin-bottom: 4px;"
           onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                </div>
                <div>
                    <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">{{ strtoupper(str_replace('_', ' ', $doc->type)) }}</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </a>
    @endforeach

    {{-- PEI Consolidado --}}
    <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
       style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; margin-bottom: 4px;"
       onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
            </div>
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">PEI</p>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                    {{ $peiConsolidadoDoc ? $peiConsolidadoDoc->updated_at->format('d/m/Y') : 'Não preenchido' }}
                </p>
            </div>
        </div>
        @unless($peiConsolidadoDoc)
            <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; background: #F3F4F6; color: #6B7280;">Em branco</span>
        @endunless
    </a>

    {{-- Metas Acadêmicas (PEI) --}}
    @can('pei.metas_gerenciar')
    <a href="{{ route('secretaria.alunos.metas-academicas.edit', $aluno) }}"
       style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 8px; text-decoration: none; margin-bottom: 4px;"
       onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="width: 32px; height: 32px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            </div>
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">Metas Acadêmicas</p>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Metas do PEI por matéria</p>
            </div>
        </div>
    </a>
    @endcan

    @if($docsVisiveis->whereIn('type', ['paee'])->isEmpty() && !$peiConsolidadoDoc)
        <p style="font-size: 13px; color: #9CA3AF;">Nenhum documento criado para {{ date('Y') }}.</p>
    @endif
</div>

{{-- Jornada Alimentar --}}
@php
    $school = auth()->user()->school;
    $temModuloAlimentar = !$school || $school->hasModule('seletividade');
    $foodItems = $aluno->foodItems ?? collect();
@endphp
@if($temModuloAlimentar && $foodItems->isNotEmpty())
<div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); padding: 24px; margin-bottom: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h3 style="font-size: 14px; font-weight: 600; color: var(--text-1); margin: 0;">Jornada Alimentar</h3>
        <a href="{{ route('secretaria.seletividade.show', $aluno) }}"
           style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 500;">Gerenciar →</a>
    </div>
    @php
        $statuses = \App\Models\StudentFoodItem::STATUSES;
        $categories = \App\Models\StudentFoodItem::CATEGORIES;
        $byCategory = $foodItems->groupBy('category');
    @endphp
    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        <thead>
            <tr style="background: var(--bg-sidebar);">
                <th style="text-align: left; padding: 8px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Categoria</th>
                <th style="text-align: left; padding: 8px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Alimentos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($byCategory as $catKey => $items)
            <tr style="border-top: 1px solid var(--border);">
                <td style="padding: 10px 14px; font-size: 12px; font-weight: 600; color: var(--text-2); white-space: nowrap; vertical-align: top;">
                    {{ $categories[$catKey] ?? ucfirst($catKey) }}
                </td>
                <td style="padding: 10px 14px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach($items as $item)
                        @php $s = $statuses[$item->status]; @endphp
                        <span style="font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 20px; background: {{ $s['bg'] }}; color: {{ $s['color'] }};" title="{{ $s['label'] }}">
                            {{ $item->name }}
                        </span>
                        @endforeach
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="display: flex; gap: 16px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border);">
        @foreach($statuses as $key => $s)
        @php $count = $foodItems->where('status', $key)->count(); @endphp
        @if($count > 0)
        <div style="display: flex; align-items: center; gap: 5px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $s['color'] }}; display: inline-block; flex-shrink: 0;"></span>
            <span style="font-size: 11px; color: var(--text-3);">{{ $s['label'] }}: <strong style="color: var(--text-1);">{{ $count }}</strong></span>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endif

{{-- Mural --}}
<x-observation-feed :aluno="$aluno" role="secretaria" />

{{-- Carregar laudos --}}
@php $aluno->load('laudos.uploader'); @endphp
<x-laudo-feed :aluno="$aluno" role="secretaria" />
@endsection