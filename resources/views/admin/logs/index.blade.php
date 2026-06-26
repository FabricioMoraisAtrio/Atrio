@extends('admin.layouts.app')
@section('title', 'Logs / Auditoria')

@section('content')
@php
    $acaoLabel = ['created'=>'Criou','updated'=>'Editou','deleted'=>'Excluiu','viewed'=>'Visualizou','exported'=>'Exportou','downloaded'=>'Baixou'];
    $acaoCor   = ['created'=>'var(--adm-green)','updated'=>'var(--adm-accent)','deleted'=>'var(--adm-red)','viewed'=>'var(--adm-text-2)','exported'=>'var(--adm-amber)','downloaded'=>'var(--adm-amber)'];
@endphp

{{-- Filtros --}}
<form method="GET" class="adm-card" style="padding:14px 18px; margin-bottom:14px; display:flex; flex-wrap:wrap; gap:12px; align-items:end;">
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Escola</label>
        <select name="school_id" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
            <option value="">Todas</option>
            @foreach($schools as $id => $nome)<option value="{{ $id }}" {{ request('school_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>@endforeach
        </select>
    </div>
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Ação</label>
        <select name="action" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
            <option value="">Todas</option>
            @foreach($actions as $a)<option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ $acaoLabel[$a] ?? ucfirst($a) }}</option>@endforeach
        </select>
    </div>
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">De</label>
        <input type="date" name="from" value="{{ request('from') }}" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
    </div>
    <div>
        <label style="display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;">Até</label>
        <input type="date" name="to" value="{{ request('to') }}" style="border:1px solid var(--adm-border); border-radius:8px; padding:7px 10px; font-size:13px;">
    </div>
    <button type="submit" class="adm-btn adm-btn-ghost">Filtrar</button>
    @if(request()->hasAny(['school_id','action','from','to']))
        <a href="{{ route('admin.logs.index') }}" style="font-size:12px; color:var(--adm-text-3); text-decoration:none; align-self:center;">limpar</a>
    @endif
</form>

{{-- Tabela --}}
<div class="adm-card" style="overflow:hidden;">
    <table style="width:100%; border-collapse:collapse; font-size:12.5px;">
        <thead>
            <tr style="background:var(--adm-border-2); text-align:left;">
                <th style="padding:11px 18px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Data/hora</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Escola</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Usuário</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Ação</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Alvo</th>
                <th style="padding:11px 14px; font-size:11px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr style="border-top:1px solid var(--adm-border-2);">
                <td style="padding:10px 18px; color:var(--adm-text-2); white-space:nowrap;">{{ optional($log->accessed_at)->format('d/m/Y H:i') ?? $log->created_at?->format('d/m/Y H:i') }}</td>
                <td style="padding:10px 14px; color:var(--adm-text); font-weight:600;">{{ $schools[$log->school_id] ?? '—' }}</td>
                <td style="padding:10px 14px; color:var(--adm-text-2);">{{ $users[$log->user_id] ?? '—' }}</td>
                <td style="padding:10px 14px;">
                    <span style="font-size:11px; font-weight:700; color:{{ $acaoCor[$log->action] ?? 'var(--adm-text-2)' }};">{{ $acaoLabel[$log->action] ?? ucfirst($log->action) }}</span>
                </td>
                <td style="padding:10px 14px; color:var(--adm-text-2);">
                    {{ $log->student_name ?? '—' }}@if($log->document_type) <span style="color:var(--adm-text-3);">· {{ strtoupper($log->document_type) }} {{ $log->document_year }}</span>@endif
                </td>
                <td style="padding:10px 14px; color:var(--adm-text-3);">{{ $log->ip ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="padding:28px; text-align:center; color:var(--adm-text-3); font-style:italic;">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $logs->links() }}</div>
@endsection
