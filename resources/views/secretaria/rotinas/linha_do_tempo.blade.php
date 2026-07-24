@extends('layouts.app')
@section('title', 'Linha do Tempo')

@section('content')
<div style="max-width: 900px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Linha do Tempo</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">
            Acompanhe a evolução de cada {{ strtolower(term('aluno')) }}: metas, reuniões, laudos e observações em ordem cronológica.
        </p>
    </div>

    {{-- Filtros --}}
    <form method="GET" style="display: flex; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;">
        <input type="text" name="busca" value="{{ $busca }}" placeholder="Buscar {{ strtolower(term('aluno')) }}..."
               style="flex: 1; min-width: 200px; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--text-2); outline: none;">
        <select name="turma" onchange="this.form.submit()"
                style="border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--text-2); outline: none; cursor: pointer;">
            <option value="">Todas as {{ strtolower(term('turmas')) }}</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}" @selected($filtroTurma == $turma->id)>{{ $turma->name }}</option>
            @endforeach
        </select>
        <button type="submit" style="background: var(--accent); color: #fff; border: none; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Filtrar</button>
    </form>

    {{-- Lista de alunos --}}
    <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; overflow: hidden;">
        @forelse($alunos as $aluno)
            <a href="{{ route('secretaria.alunos.linha-do-tempo', $aluno) }}"
               style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 20px; text-decoration: none; {{ !$loop->last ? 'border-bottom: 1px solid var(--border-sub);' : '' }}"
               onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--accent-bg); color: var(--accent-text); font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ strtoupper(mb_substr($aluno->name, 0, 1)) }}
                    </div>
                    <p style="font-size: 14px; font-weight: 500; color: var(--text-1); margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $aluno->name }}</p>
                </div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-4)" stroke-width="2" style="flex-shrink: 0;"><path d="M9 18l6-6-6-6"/></svg>
            </a>
        @empty
            <div style="padding: 48px; text-align: center;">
                <p style="font-size: 14px; color: var(--text-4); margin: 0;">Nenhum {{ strtolower(term('aluno')) }} encontrado.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
