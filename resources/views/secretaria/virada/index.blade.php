@extends('layouts.app')
@section('title', 'Preparar próximo ano')

@section('content')
@php
    $inp = 'border:1px solid var(--border); border-radius:8px; padding:8px 10px; font-size:13px; color:var(--text-1); background:var(--bg-card); box-sizing:border-box;';
@endphp

<div style="max-width:920px;">
    <div style="margin-bottom:8px;">
        <a href="{{ route('secretaria.turmas.index') }}" style="font-size:13px; color:var(--accent-text); text-decoration:none;">← Turmas</a>
    </div>
    <h1 style="font-size:22px; font-weight:800; color:var(--text-1); margin:0 0 4px;">Preparar o ano letivo de {{ $target }}</h1>
    <p style="font-size:13px; color:var(--text-3); margin:0 0 18px;">
        Cria as turmas de {{ $target }} e matricula os alunos de {{ $current }} conforme o mapeamento abaixo.
        Os dados do aluno (condição, laudos, observações) seguem automaticamente; documentos e PEI de {{ $current }} ficam preservados como histórico.
    </p>

    @if($jaExiste)
        <div style="background:var(--warning-bg); border:1px solid var(--warning-border); color:var(--warning); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:18px;">
            Já existem turmas em {{ $target }}. Você pode rodar mesmo assim — nada é duplicado (alunos/turmas já existentes são mantidos).
        </div>
    @endif

    @if($errors->any())
        <div style="background:var(--danger-bg); border:1px solid var(--danger-border); color:var(--danger); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:18px;">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    @if($turmas->isEmpty())
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:32px; text-align:center; color:var(--text-3);">
            Não há turmas em {{ $current }} para promover.
        </div>
    @else
    <form method="POST" action="{{ route('secretaria.turmas.virada.confirmar') }}">
        @csrf
        @foreach($turmas as $turma)
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:18px 20px; margin-bottom:14px;">
            <div style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:16px; justify-content:space-between; margin-bottom:12px;">
                <div>
                    <div style="font-size:15px; font-weight:700; color:var(--text-1);">{{ $turma->name }}</div>
                    <div style="font-size:12px; color:var(--text-4);">{{ $turma->shift }} · {{ $turma->students->count() }} aluno(s) · {{ $current }}</div>
                </div>
                <div>
                    <label style="display:block; font-size:11px; font-weight:600; color:var(--text-3); text-transform:uppercase; letter-spacing:.4px; margin-bottom:5px;">Turma destino em {{ $target }}</label>
                    <input type="text" name="dest[{{ $turma->id }}]" value="{{ $turma->name }}" style="{{ $inp }} width:200px;">
                </div>
            </div>

            @if($turma->students->isNotEmpty())
            <details>
                <summary style="cursor:pointer; font-size:12px; font-weight:600; color:var(--accent-text); list-style:none;">Ajustar alunos (promovido / retido / saiu)</summary>
                <div style="margin-top:10px; border-top:1px solid var(--border-sub); padding-top:10px;">
                    @foreach($turma->students as $aluno)
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:6px 0;">
                        <span style="font-size:13px; color:var(--text-2);">{{ $aluno->name }}</span>
                        <select name="status[{{ $aluno->id }}]" style="{{ $inp }} padding:6px 8px;">
                            <option value="promovido">Promovido (vai p/ destino)</option>
                            <option value="retido">Retido (mantém na turma atual)</option>
                            <option value="saiu">Saiu / transferido (não matricular)</option>
                        </select>
                    </div>
                    @endforeach
                </div>
            </details>
            @endif
        </div>
        @endforeach

        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:18px 20px; margin-top:18px;">
            <label style="display:flex; align-items:flex-start; gap:10px; font-size:13px; color:var(--text-2); margin-bottom:16px; cursor:pointer;">
                <input type="checkbox" name="confirma" value="1" required style="margin-top:2px;">
                <span>Confirmo a preparação do ano {{ $target }}. As turmas e matrículas serão criadas; nada de {{ $current }} é apagado.</span>
            </label>
            <div style="display:flex; gap:10px;">
                <button type="submit"
                        style="background:var(--accent); color:var(--accent-contrast); border:none; padding:11px 24px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                    Preparar {{ $target }}
                </button>
                <a href="{{ route('secretaria.turmas.index') }}"
                   style="padding:11px 24px; border-radius:8px; border:1px solid var(--border); background:transparent; color:var(--text-2); font-size:13px; font-weight:600; text-decoration:none;">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
    @endif
</div>
@endsection
