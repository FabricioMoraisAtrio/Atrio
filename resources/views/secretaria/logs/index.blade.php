@extends('layouts.app')
@section('title', 'Registro de Acessos')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Registro de Acessos</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">Histórico de exportações e edições de documentos</p>
    </div>
    <div style="display: flex; gap: 8px; align-items: center;">
        <span style="font-size: 13px; color: var(--text-3);">{{ number_format($logs->total()) }} registro(s)</span>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-bottom: 20px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 12px; align-items: end;">
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Estudante</label>
            <input type="text" name="aluno" value="{{ request('aluno') }}" placeholder="Nome do aluno..."
                   style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-1); background: var(--bg-card); outline: none; box-sizing: border-box;">
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Usuário</label>
            <select name="usuario" style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-1); background: var(--bg-card); outline: none;">
                <option value="">Todos</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}" {{ request('usuario') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Ação</label>
            <select name="acao" style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-1); background: var(--bg-card); outline: none;">
                <option value="">Todas</option>
                <option value="created"  {{ request('acao') === 'created'  ? 'selected' : '' }}>Incluído</option>
                <option value="deleted"  {{ request('acao') === 'deleted'  ? 'selected' : '' }}>Removido</option>
                <option value="exported" {{ request('acao') === 'exported' ? 'selected' : '' }}>Exportado (PDF/Word)</option>
                <option value="edited"   {{ request('acao') === 'edited'   ? 'selected' : '' }}>Editado</option>
                <option value="viewed"   {{ request('acao') === 'viewed'   ? 'selected' : '' }}>Visualizado</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Período</label>
            <div style="display: flex; gap: 6px; align-items: center;">
                <input type="date" name="de" value="{{ request('de') }}"
                       style="flex: 1; border: 1px solid var(--border); border-radius: 8px; padding: 8px 10px; font-size: 12px; color: var(--text-1); background: var(--bg-card); outline: none;">
                <span style="font-size: 11px; color: var(--text-3);">até</span>
                <input type="date" name="ate" value="{{ request('ate') }}"
                       style="flex: 1; border: 1px solid var(--border); border-radius: 8px; padding: 8px 10px; font-size: 12px; color: var(--text-1); background: var(--bg-card); outline: none;">
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit"
                    style="background: var(--accent); color: white; border: none; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                Filtrar
            </button>
            @if(request()->hasAny(['aluno','usuario','acao','de','ate']))
                <a href="{{ route('secretaria.logs.index') }}"
                   style="display: flex; align-items: center; padding: 8px 14px; border-radius: 8px; font-size: 13px; color: var(--text-3); border: 1px solid var(--border); text-decoration: none; white-space: nowrap;">
                    Limpar
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Tabela --}}
<div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden;">
    @if($logs->isEmpty())
        <div style="padding: 60px; text-align: center; color: var(--text-3); font-size: 14px;">
            Nenhum registro encontrado.
        </div>
    @else
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--bg-sidebar);">
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Data / Hora</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Usuário</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Estudante</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Documento</th>
                <th style="text-align: center; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Ação</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            @php
                $actionConfig = match($log->action) {
                    'created'  => ['label' => 'Incluído',     'bg' => '#ECFDF5', 'color' => '#065F46'],
                    'deleted'  => ['label' => 'Removido',     'bg' => '#FEF2F2', 'color' => '#991B1B'],
                    'exported' => ['label' => 'Exportado',    'bg' => '#E8F0F9', 'color' => '#004B8D'],
                    'edited'   => ['label' => 'Editado',      'bg' => '#FEF3C7', 'color' => '#92400E'],
                    'viewed'   => ['label' => 'Visualizado',  'bg' => '#F3F4F6', 'color' => '#374151'],
                    default    => ['label' => ucfirst($log->action), 'bg' => '#F3F4F6', 'color' => '#374151'],
                };
                $entidades   = ['aluno' => 'Cadastro de aluno', 'laudo' => 'Laudo', 'usuario' => 'Usuário'];
                $isEntidade  = array_key_exists($log->document_type, $entidades);
                $docType     = $log->document_type ? strtoupper(str_replace('_', ' ', $log->document_type)) : '—';
                $studentName = $log->student_name ?? '—';
                $userName    = $log->user?->name ?? '—';
            @endphp
            <tr style="border-top: 1px solid var(--border);"
                onmouseover="this.style.background='var(--bg-sidebar)'" onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px; white-space: nowrap;">
                    <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0;">{{ $log->accessed_at->format('d/m/Y') }}</p>
                    <p style="font-size: 11px; color: var(--text-3); margin: 0;">{{ $log->accessed_at->format('H:i:s') }}</p>
                </td>
                <td style="padding: 14px 20px;">
                    <p style="font-size: 13px; color: var(--text-1); margin: 0; font-weight: 500;">{{ $userName }}</p>
                </td>
                <td style="padding: 14px 20px;">
                    <p style="font-size: 13px; color: var(--text-1); margin: 0;">{{ $studentName }}</p>
                </td>
                <td style="padding: 14px 20px;">
                    @if($isEntidade)
                        <span style="font-size: 13px; color: var(--text-1);">{{ $entidades[$log->document_type] }}</span>
                    @elseif($log->document)
                        <a href="{{ route('secretaria.documentos.show', $log->document) }}"
                           style="font-size: 13px; color: var(--accent-text); text-decoration: none; font-weight: 500;">
                            {{ $docType }} · {{ $log->document_year }}
                        </a>
                    @else
                        <span style="font-size: 13px; color: var(--text-3);">{{ $docType }} · {{ $log->document_year }} <span style="font-style: italic;">(removido)</span></span>
                    @endif
                </td>
                <td style="padding: 14px 20px; text-align: center;">
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
                                 background: {{ $actionConfig['bg'] }}; color: {{ $actionConfig['color'] }};">
                        {{ $actionConfig['label'] }}
                    </span>
                </td>
                <td style="padding: 14px 20px;">
                    <span style="font-size: 12px; color: var(--text-3); font-family: monospace;">{{ $log->ip ?? '—' }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Paginação --}}
    @if($logs->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
        <p style="font-size: 12px; color: var(--text-3); margin: 0;">
            Exibindo {{ $logs->firstItem() }}–{{ $logs->lastItem() }} de {{ $logs->total() }} registros
        </p>
        <div style="display: flex; gap: 4px;">
            @if($logs->onFirstPage())
                <span style="padding: 6px 12px; border-radius: 6px; font-size: 13px; color: var(--text-3); border: 1px solid var(--border);">&laquo;</span>
            @else
                <a href="{{ $logs->previousPageUrl() }}" style="padding: 6px 12px; border-radius: 6px; font-size: 13px; color: var(--text-1); border: 1px solid var(--border); text-decoration: none;">&laquo;</a>
            @endif
            @foreach($logs->getUrlRange(max(1, $logs->currentPage()-2), min($logs->lastPage(), $logs->currentPage()+2)) as $page => $url)
                @if($page == $logs->currentPage())
                    <span style="padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; background: var(--accent); color: #fff; border: 1px solid var(--accent);">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 6px 12px; border-radius: 6px; font-size: 13px; color: var(--text-1); border: 1px solid var(--border); text-decoration: none;">{{ $page }}</a>
                @endif
            @endforeach
            @if($logs->hasMorePages())
                <a href="{{ $logs->nextPageUrl() }}" style="padding: 6px 12px; border-radius: 6px; font-size: 13px; color: var(--text-1); border: 1px solid var(--border); text-decoration: none;">&raquo;</a>
            @else
                <span style="padding: 6px 12px; border-radius: 6px; font-size: 13px; color: var(--text-3); border: 1px solid var(--border);">&raquo;</span>
            @endif
        </div>
    </div>
    @endif
    @endif
</div>
@endsection
