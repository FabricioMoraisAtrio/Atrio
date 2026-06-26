@extends('layouts.app')
@section('title', 'Pré-visualização da importação')

@section('content')
@php
    $badge = [
        'criar'     => ['Criar', 'var(--success)', 'var(--success-bg)'],
        'atualizar' => ['Atualizar', 'var(--info)', 'var(--info-bg)'],
        'erro'      => ['Erro', 'var(--danger)', 'var(--danger-bg)'],
    ];
    $aplicaveis = $resumo['criar'] + $resumo['atualizar'];
@endphp

<div style="max-width:920px;">
    <div style="margin-bottom:8px;">
        <a href="{{ route('secretaria.alunos.importar') }}" style="font-size:13px; color:var(--accent-text); text-decoration:none;">← Enviar outro arquivo</a>
    </div>
    <h1 style="font-size:22px; font-weight:800; color:var(--text-1); margin:0 0 4px;">Pré-visualização</h1>
    <p style="font-size:13px; color:var(--text-3); margin:0 0 18px;">{{ $nome }} · {{ $resumo['total'] }} linha(s). Confira antes de confirmar — nada foi gravado ainda.</p>

    {{-- Resumo --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:14px; margin-bottom:18px;">
        @foreach([['Criar',$resumo['criar'],'var(--success)'],['Atualizar',$resumo['atualizar'],'var(--info)'],['Com erro',$resumo['erro'],'var(--danger)']] as $c)
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:16px 18px;">
            <div style="font-size:12px; font-weight:600; color:var(--text-3); margin-bottom:4px;">{{ $c[0] }}</div>
            <div style="font-size:22px; font-weight:800; color:{{ $c[2] }};">{{ $c[1] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Tabela --}}
    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; overflow:hidden; margin-bottom:18px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:var(--bg-subtle); text-align:left;">
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:var(--text-3); text-transform:uppercase;">#</th>
                    <th style="padding:10px 12px; font-size:11px; font-weight:700; color:var(--text-3); text-transform:uppercase;">Nome</th>
                    <th style="padding:10px 12px; font-size:11px; font-weight:700; color:var(--text-3); text-transform:uppercase;">Matrícula</th>
                    <th style="padding:10px 12px; font-size:11px; font-weight:700; color:var(--text-3); text-transform:uppercase;">Turma</th>
                    <th style="padding:10px 12px; font-size:11px; font-weight:700; color:var(--text-3); text-transform:uppercase;">Ação</th>
                    <th style="padding:10px 16px; font-size:11px; font-weight:700; color:var(--text-3); text-transform:uppercase;">Observações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $r)
                @php $b = $badge[$r['_action']]; @endphp
                <tr style="border-top:1px solid var(--border-sub);">
                    <td style="padding:9px 16px; color:var(--text-4);">{{ $r['_line'] }}</td>
                    <td style="padding:9px 12px; font-weight:600; color:var(--text-1);">{{ $r['name'] ?? '—' }}</td>
                    <td style="padding:9px 12px; color:var(--text-2);">{{ $r['registration_number'] ?? '—' }}</td>
                    <td style="padding:9px 12px; color:var(--text-2);">{{ $r['turma'] ?? '—' }}</td>
                    <td style="padding:9px 12px;"><span style="font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; color:{{ $b[1] }}; background:{{ $b[2] }};">{{ $b[0] }}</span></td>
                    <td style="padding:9px 16px; color:var(--danger); font-size:12px;">{{ implode(' · ', $r['_errors']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($aplicaveis > 0)
    <form method="POST" action="{{ route('secretaria.alunos.importar.confirmar') }}" style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:18px 20px;">
        @csrf
        <label style="display:flex; align-items:flex-start; gap:10px; font-size:13px; color:var(--text-2); margin-bottom:16px; cursor:pointer;">
            <input type="checkbox" name="confirma" value="1" required style="margin-top:2px;">
            <span>Confirmo a importação de {{ $aplicaveis }} aluno(s) ({{ $resumo['criar'] }} novo(s), {{ $resumo['atualizar'] }} atualização(ões)). Linhas com erro são ignoradas.</span>
        </label>
        <button type="submit" style="background:var(--accent); color:var(--accent-contrast); border:none; padding:11px 24px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Confirmar importação</button>
    </form>
    @else
    <div style="background:var(--warning-bg); border:1px solid var(--warning-border); color:var(--warning); font-size:13px; border-radius:10px; padding:14px 16px;">
        Nenhuma linha válida para importar. Verifique se a planilha tem as colunas de <strong>nome</strong> e <strong>matrícula</strong>.
    </div>
    @endif
</div>
@endsection
