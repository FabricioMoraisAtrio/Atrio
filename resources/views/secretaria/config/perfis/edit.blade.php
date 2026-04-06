@extends('layouts.app')
@section('title', 'Editar Perfil')

@section('content')
<div style="max-width: 660px;">

    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.config.perfis.index') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Perfis de Acesso
        </a>
        <div style="display: flex; align-items: center; gap: 10px;">
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">{{ $perfil->name }}</h1>
            @if($perfil->is_system)
                <span style="font-size: 11px; font-weight: 600; color: #6B7280; background: #F3F4F6; padding: 3px 10px; border-radius: 20px;">Sistema</span>
            @endif
        </div>
    </div>

    @if($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.config.perfis.update', $perfil) }}">
        @csrf @method('PUT')

        {{-- Identificação --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
            <h2 style="font-size: 13px; font-weight: 700; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 20px;">Identificação</h2>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome do Cargo</label>
                <input type="text" name="name" value="{{ old('name', $perfil->name) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @if($perfil->is_system)
                    <p style="font-size: 11px; color: #9CA3AF; margin-top: 4px;">Renomear altera apenas o rótulo exibido no sistema — o perfil interno continua sendo <code>{{ $perfil->spatie_role }}</code>.</p>
                @endif
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Cor de Identificação</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="color" name="color" value="{{ old('color', $perfil->color) }}"
                           style="width: 48px; height: 36px; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer; padding: 2px;">
                    <span style="font-size: 13px; color: #6B7280;">Cor exibida nos badges do sistema</span>
                </div>
            </div>
        </div>

        {{-- Permissões --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 20px;">
            <h2 style="font-size: 13px; font-weight: 700; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 6px;">Permissões</h2>

            @if($perfil->slug === 'admin')
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; color: #1E40AF;">
                    O Administrador possui todas as permissões do sistema por padrão e não pode ser restringido.
                </div>
                @php $allPerms = collect($groups)->flatMap(fn($p) => array_keys($p))->toArray(); @endphp
                @foreach($groups as $groupName => $perms)
                    <div style="margin-bottom: 16px;">
                        <p style="font-size: 11px; font-weight: 700; color: #374151; letter-spacing: 0.5px; text-transform: uppercase; margin: 0 0 8px;">{{ $groupName }}</p>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            @foreach($perms as $perm => $label)
                                <div style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; background: #F9FAFB; opacity: 0.8;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                    <span style="font-size: 13px; color: #374151;">{{ $label }}</span>
                                    {{-- hidden inputs para que o form envie todas as permissões --}}
                                    <input type="hidden" name="permissions[]" value="{{ $perm }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(! $loop->last)<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 16px;">@endif
                @endforeach
            @else
                <p style="font-size: 13px; color: #6B7280; margin: 0 0 20px;">Selecione as permissões que este perfil terá acesso.</p>
                @php $activePerms = old('permissions', $perfil->permissions ?? []); @endphp

                @foreach($groups as $groupName => $perms)
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <p style="font-size: 11px; font-weight: 700; color: #374151; letter-spacing: 0.5px; text-transform: uppercase; margin: 0;">{{ $groupName }}</p>
                            <button type="button" onclick="toggleGroup(this)"
                                    style="font-size: 11px; color: #004B8D; background: none; border: none; cursor: pointer;">
                                Marcar todos
                            </button>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach($perms as $perm => $label)
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6;"
                                       onmouseover="this.style.borderColor='#004B8D'" onmouseout="this.style.borderColor='#F3F4F6'">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                           {{ in_array($perm, $activePerms) ? 'checked' : '' }}>
                                    <span style="font-size: 13px; color: #374151;">{{ $label }}</span>
                                    <span style="font-size: 11px; color: #9CA3AF; margin-left: auto;">{{ $perm }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @if(! $loop->last)<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 20px;">@endif
                @endforeach
            @endif
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit"
                    style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Salvar Alterações
            </button>
            <a href="{{ route('secretaria.config.perfis.index') }}"
               style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function toggleGroup(btn) {
    const group = btn.closest('div').nextElementSibling;
    const checkboxes = group.querySelectorAll('input[type="checkbox"]');
    const allChecked = [...checkboxes].every(c => c.checked);
    checkboxes.forEach(c => c.checked = !allChecked);
    btn.textContent = allChecked ? 'Marcar todos' : 'Desmarcar todos';
}
</script>
@endsection
