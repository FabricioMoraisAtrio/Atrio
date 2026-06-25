@extends('layouts.app')
@section('title', 'Novo Perfil')

@section('content')
<div style="max-width: 660px;">

    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.config.perfis.index') }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Perfis de Acesso
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">Novo Perfil</h1>
    </div>

    @if($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.config.perfis.store') }}">
        @csrf

        {{-- Identificação --}}
        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 16px;">
            <h2 style="font-size: 13px; font-weight: 700; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 20px;">Identificação</h2>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome do Cargo</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Coordenador Pedagógico"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Cor de Identificação</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="color" name="color" value="{{ old('color', '#6B7280') }}"
                           style="width: 48px; height: 36px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; padding: 2px;">
                    <span style="font-size: 13px; color: var(--text-3);">Cor exibida nos badges do sistema</span>
                </div>
            </div>
        </div>

        {{-- Permissões --}}
        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h2 style="font-size: 13px; font-weight: 700; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0;">Permissões</h2>
                <button type="button" onclick="toggleAll(this)"
                        style="font-size: 12px; font-weight: 600; color: var(--accent-text); background: var(--info-bg); border: 1px solid var(--info-border); padding: 5px 12px; border-radius: 6px; cursor: pointer;">
                    Marcar tudo
                </button>
            </div>

            @foreach($groups as $groupName => $perms)
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                        <p style="font-size: 11px; font-weight: 700; color: var(--text-2); letter-spacing: 0.5px; text-transform: uppercase; margin: 0;">{{ $groupName }}</p>
                        <button type="button" onclick="toggleGroup(this)"
                                style="font-size: 11px; color: var(--accent-text); background: none; border: none; cursor: pointer;">
                            Marcar todos
                        </button>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @foreach($perms as $perm => $label)
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-sub);"
                                   onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border-sub)'">
                                <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                       {{ in_array($perm, old('permissions', [])) ? 'checked' : '' }}>
                                <span style="font-size: 13px; color: var(--text-2);">{{ $label }}</span>
                                <span style="font-size: 11px; color: var(--text-4); margin-left: auto;">{{ $perm }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @if(! $loop->last)<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 20px;">@endif
            @endforeach
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit"
                    style="background: var(--accent); color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Criar Perfil
            </button>
            <a href="{{ route('secretaria.config.perfis.index') }}"
               style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function toggleAll(btn) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="permissions[]"]');
    const allChecked = [...checkboxes].every(c => c.checked);
    checkboxes.forEach(c => c.checked = !allChecked);
    btn.textContent = allChecked ? 'Marcar tudo' : 'Desmarcar tudo';
}

function toggleGroup(btn) {
    const group = btn.closest('div').nextElementSibling;
    const checkboxes = group.querySelectorAll('input[type="checkbox"]');
    const allChecked = [...checkboxes].every(c => c.checked);
    checkboxes.forEach(c => c.checked = !allChecked);
    btn.textContent = allChecked ? 'Marcar todos' : 'Desmarcar todos';
}
</script>
@endsection
