@extends('layouts.app')
@section('title', 'Usuários')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Usuários</h1>
        <p style="font-size: 13px; color: #9CA3AF; margin: 0;">{{ $usuarios->count() }} usuários cadastrados</p>
    </div>
    <a href="{{ route('secretaria.usuarios.create') }}"
       style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Novo usuário
    </a>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F9FAFB;">
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Usuário</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Turmas</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                <th style="padding: 12px 20px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
            <tr style="border-top: 1px solid #F9FAFB;"
                onmouseover="this.style.background='#FAFAFA'"
                onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @if($usuario->avatar)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($usuario->avatar) }}"
                                 style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-size: 14px; font-weight: 500; color: #111827;">{{ $usuario->name }}</div>
                            <div style="font-size: 12px; color: #9CA3AF;">{{ $usuario->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding: 14px 20px;">
                    @php
                        $role = $usuario->getRoleNames()->first();
                        $roleLabels = ['professor' => 'Professor', 'pai' => 'Responsável', 'coordenador' => 'Coordenação', 'orientador' => 'Orientação Pedagógica'];
                        $roleStyles = [
                            'professor'  => 'background: #E8F0F9; color: #004B8D;',
                            'pai'        => 'background: #F5EDE6; color: #7C3700;',
                            'coordenador'=> 'background: #E6F5F4; color: #009C8C;',
                            'orientador' => 'background: #F3E8FF; color: #7C3AED;',
                        ];
                    @endphp
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; {{ $roleStyles[$role] ?? 'background: #F3F4F6; color: #6B7280;' }}">
                        {{ $roleLabels[$role] ?? ucfirst($role) }}
                    </span>
                </td>
                <td style="padding: 14px 20px; font-size: 13px; color: #6B7280;">
                    {{ $usuario->schoolClasses->pluck('name')->join(', ') ?: '—' }}
                </td>
                <td style="padding: 14px 20px;">
                    @if($usuario->is_active)
                        <span style="background: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Ativo</span>
                    @else
                        <span style="background: #FEF2F2; color: #991B1B; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Inativo</span>
                    @endif
                </td>
                <td style="padding: 14px 20px; text-align: right;">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('secretaria.usuarios.edit', $usuario) }}"
                           style="font-size: 13px; color: #004B8D; text-decoration: none; font-weight: 500;">Editar</a>
                        <form method="POST" action="{{ route('secretaria.usuarios.destroy', $usuario) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Remover usuário?')"
                                    style="font-size: 13px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 0;">
                                Remover
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 48px; text-align: center; color: #9CA3AF; font-size: 14px;">
                    Nenhum usuário cadastrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection