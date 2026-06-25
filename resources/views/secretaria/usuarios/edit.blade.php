@extends('layouts.app')
@section('title', 'Editar Usuário')

@section('content')
<div style="max-width: 520px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.usuarios.index') }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para usuários
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">Editar — {{ $usuario->name }}</h1>
    </div>

    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 28px;">
        <form method="POST" action="{{ route('secretaria.usuarios.update', $usuario) }}">
            @csrf @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('name')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('email')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nova senha <span style="color: #CBCBCB; font-weight: 400; text-transform: none; letter-spacing: 0;">(deixe em branco para manter)</span></label>
                <input type="password" name="password"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('password')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Perfil de acesso</label>
                @php $roleAtual = old('role', $usuario->getRoleNames()->first()); @endphp
                <select name="role" id="select-role"
                        onchange="toggleTurmas(this.value)"
                        style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    @foreach($roles as $role)
                        <option value="{{ $role->spatie_role }}" {{ $roleAtual === $role->spatie_role ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $usuario->is_active) ? 'checked' : '' }}>
                    <span style="font-size: 13px; color: var(--text-2);">Usuário ativo</span>
                </label>
            </div>

            @php $isProfessor = old('role', $usuario->getRoleNames()->first()) === 'professor'; @endphp
                <div id="bloco-professor" style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 20px; margin-bottom: 20px; display: {{ $isProfessor ? 'block' : 'none' }};">
                    <p style="font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 8px;">Matéria</p>
                    @php $subjectAtual = old('subject', $usuario->schoolClasses->first()?->pivot->subject); @endphp
                    <select name="subject"
                            style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box; margin-bottom: 20px;"
                            onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        <option value="">Selecione a matéria</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->slug }}" {{ $subjectAtual === $subject->slug ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>

                    <p style="font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Turmas</p>
                    <div style="display: flex; flex-direction: column; gap: 6px; max-height: 200px; overflow-y: auto;">
                        @foreach($turmas as $turma)
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-sub); cursor: pointer;"
                                   onmouseover="this.style.borderColor='var(--accent)'"
                                   onmouseout="this.style.borderColor='var(--border-sub)'">
                                <input type="checkbox" name="school_class_ids[]" value="{{ $turma->id }}"
                                       {{ $usuario->schoolClasses->contains($turma->id) ? 'checked' : '' }}>
                                <span style="font-size: 13px; color: var(--text-2);">{{ $turma->name }} — {{ $turma->shift }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit"
                        style="background: var(--accent); color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Atualizar
                </button>
                <a href="{{ route('secretaria.usuarios.index') }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTurmas(role) {
    document.getElementById('bloco-professor').style.display = role === 'professor' ? 'block' : 'none';
}
</script>
@endsection