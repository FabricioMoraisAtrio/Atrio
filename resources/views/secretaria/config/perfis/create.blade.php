@extends('layouts.app')
@section('title', 'Novo Perfil')

@section('content')
<div style="max-width: 660px;">

    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.config.perfis.index') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Perfis de Acesso
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">Novo Perfil</h1>
    </div>

    @if($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.config.perfis.store') }}">
        @csrf

        {{-- Identificação --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
            <h2 style="font-size: 13px; font-weight: 700; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 20px;">Identificação</h2>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome do Cargo</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Coordenador Pedagógico"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Cor de Identificação</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="color" name="color" value="{{ old('color', '#6B7280') }}"
                           style="width: 48px; height: 36px; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer; padding: 2px;">
                    <span style="font-size: 13px; color: #6B7280;">Cor exibida nos badges do sistema</span>
                </div>
            </div>
        </div>

        {{-- Permissões --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h2 style="font-size: 13px; font-weight: 700; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0;">Permissões</h2>
                <button type="button" onclick="toggleAll(this)"
                        style="font-size: 12px; font-weight: 600; color: #004B8D; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 5px 12px; border-radius: 6px; cursor: pointer;">
                    Marcar tudo
                </button>
            </div>

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
                                       {{ in_array($perm, old('permissions', [])) ? 'checked' : '' }}>
                                <span style="font-size: 13px; color: #374151;">{{ $label }}</span>
                                <span style="font-size: 11px; color: #9CA3AF; margin-left: auto;">{{ $perm }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @if(! $loop->last)<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 20px;">@endif
            @endforeach
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit"
                    style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Criar Perfil
            </button>
            <a href="{{ route('secretaria.config.perfis.index') }}"
               style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
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
