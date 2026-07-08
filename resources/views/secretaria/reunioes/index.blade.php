@extends('layouts.app')
@section('title', 'Reuniões — ' . $aluno->name)

@php use App\Models\Meeting; @endphp

@section('content')
<div style="max-width: 820px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.show', $aluno) }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para {{ $aluno->name }}
        </a>
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Reuniões</h1>
                <p style="font-size: 13px; color: var(--text-3); margin: 0;">Registros de reuniões de <strong>{{ $aluno->name }}</strong>.</p>
            </div>
            <a href="{{ route('secretaria.alunos.reunioes.create', $aluno) }}"
               style="flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; background: var(--accent); color: #fff;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Nova reunião
            </a>
        </div>
    </div>

    @forelse($reunioes as $reuniao)
        <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 18px 20px; margin-bottom: 14px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; background: var(--accent-bg); color: var(--accent-text);">
                        {{ Meeting::TIPOS[$reuniao->tipo] ?? 'Reunião' }}
                    </span>
                    <span style="font-size: 14px; font-weight: 700; color: var(--text-1);">{{ $reuniao->data->format('d/m/Y') }}</span>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('secretaria.alunos.reunioes.edit', [$aluno, $reuniao]) }}"
                       style="font-size: 12px; color: var(--accent-text); text-decoration: none; font-weight: 600;">Editar</a>
                    <form method="POST" action="{{ route('secretaria.alunos.reunioes.destroy', [$aluno, $reuniao]) }}" style="display: inline;">
                        @csrf @method('DELETE')
                        <button type="button" data-confirm="Excluir esta reunião?"
                                style="font-size: 12px; color: var(--danger); background: none; border: none; cursor: pointer; padding: 0;">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>

            <div style="font-size: 13px; color: var(--text-2); line-height: 1.6;">
                <p style="margin: 0 0 6px;"><strong style="color: var(--text-3);">Participantes:</strong> {{ $reuniao->participantes }}</p>
                @if($reuniao->pauta)
                    <p style="margin: 0 0 6px;"><strong style="color: var(--text-3);">Pauta:</strong> {{ $reuniao->pauta }}</p>
                @endif
                @if($reuniao->encaminhamentos)
                    <p style="margin: 0 0 6px;"><strong style="color: var(--text-3);">Encaminhamentos:</strong> {{ $reuniao->encaminhamentos }}</p>
                @endif
                @if($reuniao->observacoes)
                    <p style="margin: 0 0 6px;"><strong style="color: var(--text-3);">Observações:</strong> {{ $reuniao->observacoes }}</p>
                @endif
            </div>

            @if($reuniao->creator)
                <p style="font-size: 11px; color: var(--text-4); margin: 10px 0 0;">Registrado por {{ $reuniao->creator->name }}</p>
            @endif
        </div>
    @empty
        <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 48px; text-align: center;">
            <p style="font-size: 14px; color: var(--text-4); margin: 0 0 16px;">Nenhuma reunião registrada para {{ $aluno->name }}.</p>
            <a href="{{ route('secretaria.alunos.reunioes.create', $aluno) }}"
               style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; background: var(--accent); color: #fff;">
                Registrar primeira reunião
            </a>
        </div>
    @endforelse
</div>
@endsection
