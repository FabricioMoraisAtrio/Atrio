@extends('layouts.app')
@section('title', 'Novo Aluno')

@section('content')
<div style="max-width: 560px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.index') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para alunos
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">Novo aluno</h1>
    </div>

    @if($errors->has('limit'))
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first('limit') }}
        </div>
    @endif

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
        <form method="POST" action="{{ route('secretaria.alunos.store') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('name')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Matrícula</label>
                <input type="text" name="registration_number" value="{{ old('registration_number') }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('registration_number')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Data de nascimento</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('birth_date')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Turma</label>
                <select name="school_class_id"
                        style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                    <option value="">Sem turma por enquanto</option>
                    @foreach($turmas as $turma)
                        <option value="{{ $turma->id }}" {{ old('school_class_id') == $turma->id ? 'selected' : '' }}>
                            {{ $turma->name }} — {{ $turma->shift }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Atipicidade --}}
            <div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 4px;">
                    <input type="checkbox" name="is_atypical" value="1"
                           id="is_atypical" {{ old('is_atypical') ? 'checked' : '' }}
                           onchange="toggleAtypical(this.checked)">
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">Aluno com perfil de atipicidade</span>
                </label>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 0 26px;">Marque para habilitar os campos de condição</p>

                <div id="atypical_fields" style="{{ old('is_atypical') ? '' : 'display:none;' }} margin-top: 20px;">

                    <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Condições identificadas</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px;">
                        @foreach([
                            'cid_autismo'                 => 'Autismo (TEA)',
                            'cid_tdah'                    => 'TDAH',
                            'cid_down'                    => 'Síndrome de Down',
                            'cid_deficiencia_intelectual' => 'Deficiência Intelectual',
                            'cid_deficiencia_visual'      => 'Deficiência Visual',
                            'cid_deficiencia_auditiva'    => 'Deficiência Auditiva',
                        ] as $field => $label)
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6;"
                                   onmouseover="this.style.borderColor='#004B8D'"
                                   onmouseout="this.style.borderColor='#F3F4F6'">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                       {{ old($field) ? 'checked' : '' }}>
                                <span style="font-size: 13px; color: #374151;">{{ $label }}</span>
                            </label>
                        @endforeach

                        {{-- Outros — ativa campo de texto --}}
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6;"
                               onmouseover="this.style.borderColor='#004B8D'"
                               onmouseout="this.style.borderColor='#F3F4F6'">
                            <input type="checkbox" name="cid_outros" value="1"
                                   id="cid_outros_check"
                                   {{ old('cid_outros') ? 'checked' : '' }}
                                   onchange="document.getElementById('condition_field').style.display = this.checked ? 'block' : 'none'">
                            <span style="font-size: 13px; color: #374151;">Outros</span>
                        </label>
                    </div>

                    {{-- Campo de texto só aparece quando "Outros" marcado --}}
                    <div id="condition_field" style="{{ old('cid_outros') ? '' : 'display:none;' }}">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Descreva a condição</label>
                        <input type="text" name="condition" value="{{ old('condition') }}"
                               placeholder="Descreva a condição ou CID..."
                               style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Cadastrar aluno
                </button>
                <a href="{{ route('secretaria.alunos.index') }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAtypical(checked) {
    document.getElementById('atypical_fields').style.display = checked ? 'block' : 'none';
    if (!checked) {
        document.getElementById('condition_field').style.display = 'none';
        document.getElementById('cid_outros_check').checked = false;
    }
}
</script>
@endsection