@extends('layouts.app')
@section('title', 'Usuários')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Usuários</h1>
        <p style="font-size: 13px; color: var(--text-4); margin: 0;">{{ $usuarios->count() }} usuários cadastrados</p>
    </div>
    <a href="{{ route('secretaria.usuarios.create') }}"
       style="background: var(--accent); color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Novo usuário
    </a>
</div>

@if(session('success'))
    <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--bg-subtle);">
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Usuário</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Turmas</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                <th style="padding: 12px 20px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
            <tr style="border-top: 1px solid var(--border-sub);"
                onmouseover="this.style.background='var(--bg-hover)'"
                onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @if($usuario->avatar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($usuario->avatar) }}"
                                 style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--accent-bg); color: var(--accent-text); font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-size: 14px; font-weight: 500; color: var(--text-1);">{{ $usuario->name }}</div>
                            <div style="font-size: 12px; color: var(--text-4);">{{ $usuario->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding: 14px 20px;">
                    @php
                        $role = $usuario->getRoleNames()->first();
                        $roleLabels = ['professor' => 'Professor', 'coordenador' => 'Coordenação', 'orientador' => 'Orientação Pedagógica', 'admin' => 'Administrador'];
                        $roleStyles = [
                            'professor'  => 'background: #E8F0F9; color: #004B8D;',
                            'coordenador'=> 'background: #E6F5F4; color: #009C8C;',
                            'orientador' => 'background: #F3E8FF; color: #7C3AED;',
                            'admin'      => 'background: #F5EDE6; color: #7C3700;',
                        ];
                    @endphp
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; {{ $roleStyles[$role] ?? 'background: #F3F4F6; color: var(--text-3);' }}">
                        {{ $roleLabels[$role] ?? ucfirst($role) }}
                    </span>
                </td>
                <td style="padding: 14px 20px; font-size: 13px; color: var(--text-3);">
                    {{ $usuario->schoolClasses->pluck('name')->join(', ') ?: '—' }}
                </td>
                <td style="padding: 14px 20px;">
                    @if($usuario->is_active)
                        <span style="background: var(--success-bg); color: var(--success); font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Ativo</span>
                    @else
                        <span style="background: var(--danger-bg); color: var(--danger); font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Inativo</span>
                    @endif
                </td>
                <td style="padding: 14px 20px; text-align: right;">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('secretaria.usuarios.edit', $usuario) }}"
                           style="font-size: 13px; color: var(--accent-text); text-decoration: none; font-weight: 500;">Editar</a>
                        <form method="POST" action="{{ route('secretaria.usuarios.destroy', $usuario) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="button" data-confirm="Remover usuário?"
                                    style="font-size: 13px; color: var(--danger); background: none; border: none; cursor: pointer; padding: 0;">
                                Remover
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-4); font-size: 14px;">
                    Nenhum usuário cadastrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection