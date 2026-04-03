@extends('layouts.app')
@section('title', 'PEI Consolidado — ' . $aluno->name)

@section('content')
@php
    $c     = $peiConsolidado->content ?? [];
    $turma = $aluno->schoolClasses->first();
    $categorias = [
        'habilidades_academicas'      => ['label' => 'Habilidades Acadêmicas',      'cor' => '#004B8D'],
        'habilidades_socioemocionais' => ['label' => 'Habilidades Socioemocionais',  'cor' => '#009C8C'],
        'habilidades_funcionais'      => ['label' => 'Habilidades Funcionais',       'cor' => '#7C3700'],
    ];
    $colunas = [
        'realiza_sem_suporte' => 'Realiza sem suporte',
        'realiza_com_apoio'   => 'Realiza com apoio',
        'ainda_nao_realiza'   => 'Ainda não realiza',
        'nao_observado'       => 'Não observado',
    ];
@endphp

<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.alunos.show', $aluno) }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para {{ $aluno->name }}
    </a>
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 700; padding: 3px 12px; border-radius: 20px;">PEI</span>
                <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">{{ $aluno->name }}</h1>
            </div>
            <p style="font-size: 13px; color: #9CA3AF; margin: 0;">
                Plano Educacional Individualizado Consolidado · {{ date('Y') }}
                @if($turma) · {{ $turma->name }} {{ $turma->shift }} @endif
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            @if($peiConsolidado)
            <a href="{{ route('secretaria.documentos.pdf', $peiConsolidado) }}"
               style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Exportar PDF
            </a>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

{{-- Status dos PEIs individuais --}}
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">PEIs individuais dos professores</p>
    @if($peis->isEmpty())
        <p style="font-size: 13px; color: #9CA3AF;">Nenhum PEI individual preenchido ainda.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            @foreach($peis as $pei)
            @php $pc = $pei->content ?? []; @endphp
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 10px;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                    {{ strtoupper(substr($pei->author->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p style="font-size: 12px; font-weight: 600; color: #111827; margin: 0;">{{ $pei->author->name ?? '—' }}</p>
                    @if(!empty($pc['materia']))
                    <p style="font-size: 11px; color: #004B8D; margin: 0;">{{ $pc['materia'] }}</p>
                    @endif
                </div>
                <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
                    {{ $pei->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
                    {{ $pei->status === 'published' ? 'Publicado' : 'Rascunho' }}
                </span>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Inventário consolidado (somente leitura) --}}
@if($peis->isNotEmpty())
@foreach($categorias as $catKey => $cat)
    @php $itens = $inventario[$catKey] ?? []; @endphp
    @if(count($itens))
    <div style="border: 1px solid #F3F4F6; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
        <div style="padding: 12px 20px; border-bottom: 1px solid #F3F4F6; background: rgba(0,0,0,0.02);">
            <p style="font-size: 11px; font-weight: 700; color: {{ $cat['cor'] }}; letter-spacing: 1px; text-transform: uppercase; margin: 0;">
                {{ $cat['label'] }}
                <span style="font-size: 10px; font-weight: 400; color: #9CA3AF; text-transform: none; letter-spacing: 0;">— preenchido pelos professores</span>
            </p>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: #F9FAFB;">
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: #6B7280; width: 36%;">Metas / Objetivos</th>
                        @foreach($colunas as $colLabel)
                            <th style="text-align: center; padding: 10px 6px; font-size: 10px; font-weight: 600; color: #6B7280; width: 8%;">{{ $colLabel }}</th>
                        @endforeach
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: #6B7280; width: 14%;">Responsável</th>
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: #6B7280;">Observações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $i => $item)
                    <tr style="border-top: 1px solid #F3F4F6; {{ $i % 2 !== 0 ? 'background: #FAFAFA;' : '' }}">
                        <td style="padding: 10px 14px; color: #374151; font-size: 12px;">{{ $item['meta'] }}</td>
                        @foreach(array_keys($colunas) as $col)
                        <td style="text-align: center; padding: 10px 6px;">
                            @if(!empty($item[$col]))
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $cat['cor'] }}" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                            @endif
                        </td>
                        @endforeach
                        <td style="padding: 10px 14px; font-size: 11px; color: #374151;">{{ $item['responsavel'] ?? $item['_autor'] ?? '' }}</td>
                        <td style="padding: 10px 14px; font-size: 11px; color: #6B7280;">{{ $item['observacoes'] ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach
@endif

{{-- Formulário da secretaria --}}
<form method="POST" action="{{ route('secretaria.alunos.pei-consolidado.update', $aluno) }}">
    @csrf

    {{-- Necessidades Educacionais --}}
    <div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Necessidades Educacionais Especiais do(a) Estudante</p>
        <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 14px;">Descreva as necessidades identificadas e encaminhamentos para o período letivo.</p>
        <textarea name="necessidades_educacionais" rows="6"
                  placeholder="Descreva as necessidades educacionais especiais..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 13px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('necessidades_educacionais', $c['necessidades_educacionais'] ?? '') }}</textarea>
    </div>

    {{-- Adaptações e Adequações --}}
    <div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Adaptações e/ou Adequações no Processo de Avaliação</p>
        <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 14px;">Liste as adaptações aplicáveis ao processo avaliativo deste estudante.</p>
        <textarea name="adaptacoes_avaliacao" rows="4"
                  placeholder="Ex: Tempo extra, prova ampliada, avaliação oral..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 13px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('adaptacoes_avaliacao', $c['adaptacoes_avaliacao'] ?? '') }}</textarea>
    </div>

    {{-- Observações --}}
    <div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Observações não contempladas ao longo do PEI</p>
        <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 14px;">Informações adicionais relevantes que não foram contempladas nos campos anteriores.</p>
        <textarea name="observacoes" rows="4"
                  placeholder="Observações gerais..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 13px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('observacoes', $c['observacoes'] ?? '') }}</textarea>
    </div>

    <div style="display: flex; gap: 12px; align-items: center;">
        <button type="submit" name="status" value="draft"
                style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Salvar rascunho
        </button>
        <button type="submit" name="status" value="published"
                style="background: #065F46; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Publicar
        </button>
        <a href="{{ route('secretaria.alunos.show', $aluno) }}"
           style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
            Cancelar
        </a>
    </div>
</form>
@endsection
