@extends('layouts.app')
@section('title', 'Novo Usuário')

@section('content')
<div style="max-width: 520px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.usuarios.index') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para usuários
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">Novo usuário</h1>
    </div>

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
        <form method="POST" action="{{ route('secretaria.usuarios.store') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('name')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('email')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Senha</label>
                <input type="text" name="admin_password" value="{{ old('admin_password') }}"
                       placeholder="Mínimo 6 caracteres"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('password')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Perfil</label>
                <select name="role" id="role"
                        onchange="toggleRole(this.value)"
                        style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                    <option value="">Selecione</option>
                    @php $roleLabels = ['professor' => 'Professor', 'pai' => 'Responsável', 'coordenador' => 'Coordenação', 'orientador' => 'Orientação Pedagógica']; @endphp
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                            {{ $roleLabels[$role->name] ?? ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                @error('role')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            {{-- Turmas (professor) --}}
            <div id="turmas_section" style="{{ old('role') === 'professor' ? '' : 'display:none;' }} border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 8px;">Matéria</p>
                <select name="subject"
                        style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box; margin-bottom: 20px;"
                        onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                    <option value="">Selecione a matéria</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->slug }}" {{ old('subject') === $subject->slug ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>

                <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Turmas</p>
                <div style="display: flex; flex-direction: column; gap: 6px; max-height: 200px; overflow-y: auto;">
                    @foreach($turmas as $turma)
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6; cursor: pointer;"
                               onmouseover="this.style.borderColor='#004B8D'"
                               onmouseout="this.style.borderColor='#F3F4F6'">
                            <input type="checkbox" name="school_class_ids[]" value="{{ $turma->id }}"
                                   {{ in_array($turma->id, old('school_class_ids', [])) ? 'checked' : '' }}>
                            <span style="font-size: 13px; color: #374151;">{{ $turma->name }} — {{ $turma->shift }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Filhos (pai) --}}
            <div id="alunos_section" style="{{ old('role') === 'pai' ? '' : 'display:none;' }} border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Filho(s) vinculado(s)</p>
                <div style="display: flex; flex-direction: column; gap: 6px; max-height: 200px; overflow-y: auto;">
                    @foreach($alunos as $aluno)
                        <label style="display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6; cursor: pointer;"
                               onmouseover="this.style.borderColor='#009C8C'"
                               onmouseout="this.style.borderColor='#F3F4F6'">
                            <input type="checkbox" name="student_ids[]" value="{{ $aluno->id }}"
                                   {{ in_array($aluno->id, old('student_ids', [])) ? 'checked' : '' }}>
                            <span style="font-size: 13px; color: #374151;">{{ $aluno->name }} — {{ $aluno->registration_number }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Criar usuário
                </button>
                <a href="{{ route('secretaria.usuarios.index') }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRole(value) {
    document.getElementById('turmas_section').style.display = value === 'professor' ? 'block' : 'none';
    document.getElementById('alunos_section').style.display = value === 'pai' ? 'block' : 'none';
}
</script>
@endsection