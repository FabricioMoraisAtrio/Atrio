@extends('layouts.app')
@section('title', 'Perfis de Acesso')

@section('content')
<div style="max-width: 760px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 28px;">
        <a href="{{ route('secretaria.config.index') }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Configurações
        </a>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Perfis de Acesso</h1>
                <p style="font-size: 13px; color: var(--text-3); margin: 0;">Crie e gerencie os cargos e permissões da sua escola.</p>
            </div>
            <a href="{{ route('secretaria.config.perfis.create') }}"
               style="background: var(--accent); color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Novo Perfil
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Lista de Perfis --}}
    <div style="display: flex; flex-direction: column; gap: 10px;">
        @forelse($roles as $perfil)
            @php $count = $perfil->usersCount(); @endphp
            <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 18px 20px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $perfil->color }};"></div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 14px; font-weight: 600; color: var(--text-1);">{{ $perfil->name }}</span>
                            @if($perfil->is_system)
                                <span style="font-size: 10px; font-weight: 600; color: var(--text-3); background: var(--bg-subtle); padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Sistema</span>
                            @endif
                        </div>
                        <div style="font-size: 12px; color: var(--text-4); margin-top: 2px;">
                            {{ count($perfil->permissions ?? []) }} permissão(ões) &nbsp;·&nbsp;
                            {{ $count }} usuário(s)
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('secretaria.config.perfis.edit', $perfil) }}"
                       style="padding: 7px 14px; border-radius: 7px; font-size: 12px; font-weight: 600; text-decoration: none; color: var(--accent-text); border: 1px solid var(--border);">
                        Editar
                    </a>
                    @if(! in_array($perfil->slug, ['admin', 'professor']))
                        <form method="POST" action="{{ route('secretaria.config.perfis.destroy', $perfil) }}">
                            @csrf @method('DELETE')
                            <button type="button"
                                    data-confirm="Remover perfil &ldquo;{{ $perfil->name }}&rdquo;?"
                                    style="padding: 7px 14px; border-radius: 7px; font-size: 12px; font-weight: 600; border: 1px solid var(--danger-border); color: var(--danger); background: transparent; cursor: pointer;">
                                Remover
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 48px; color: var(--text-4); font-size: 14px;">
                Nenhum perfil criado ainda.
            </div>
        @endforelse
    </div>

</div>
@endsection
