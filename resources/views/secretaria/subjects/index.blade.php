@extends('layouts.app')
@section('title', 'Matérias')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-1);margin:0 0 4px;">Matérias</h1>
        <p style="font-size:13px;color:var(--text-4);margin:0;">{{ $subjects->count() }} matéria(s) cadastrada(s)</p>
    </div>
    <a href="{{ route('secretaria.subjects.create') }}"
       style="background:var(--accent);color:white;text-decoration:none;padding:10px 18px;border-radius:8px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Nova matéria
    </a>
</div>

@if(session('success'))
    <div style="background:var(--success-bg);border:1px solid var(--success-border);color:var(--success);font-size:13px;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background:var(--bg-card);border-radius:12px;border:1px solid var(--border-sub);overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:var(--bg-subtle);">
                <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Ordem</th>
                <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Matéria</th>
                <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Label Responsável</th>
                <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Tipo</th>
                <th style="text-align:center;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Metas</th>
                <th style="padding:12px 20px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $subject)
            <tr style="border-top:1px solid var(--border-sub);"
                onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
                <td style="padding:14px 20px;font-size:13px;color:var(--text-4);">{{ $subject->ordem }}</td>
                <td style="padding:14px 20px;">
                    <div style="font-size:14px;font-weight:600;color:var(--text-1);">{{ $subject->name }}</div>
                    <div style="font-size:11px;color:var(--text-4);">{{ $subject->slug }}</div>
                </td>
                <td style="padding:14px 20px;font-size:13px;color:var(--text-2);">{{ $subject->label_responsavel }}</td>
                <td style="padding:14px 20px;">
                    @if($subject->tipo === 'regente')
                        <span style="background:var(--teal-bg);color:var(--teal);font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">Regente</span>
                    @else
                        <span style="background:var(--accent-bg);color:var(--accent-text);font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">Disciplina</span>
                    @endif
                </td>
                <td style="padding:14px 20px;text-align:center;">
                    <span style="font-size:13px;font-weight:600;color:var(--text-2);">{{ $subject->inventory_items_count }}</span>
                </td>
                <td style="padding:14px 20px;text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;">
                        <a href="{{ route('secretaria.subjects.show', $subject) }}"
                           style="font-size:13px;color:var(--accent-text);text-decoration:none;font-weight:500;">Metas</a>
                        <a href="{{ route('secretaria.subjects.edit', $subject) }}"
                           style="font-size:13px;color:var(--text-3);text-decoration:none;">Editar</a>
                        <form method="POST" action="{{ route('secretaria.subjects.destroy', $subject) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" data-confirm="Remover matéria?"
                                    style="font-size:13px;color:var(--danger);background:none;border:none;cursor:pointer;padding:0;">
                                Remover
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:48px;text-align:center;color:var(--text-4);font-size:14px;">
                    Nenhuma matéria cadastrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
