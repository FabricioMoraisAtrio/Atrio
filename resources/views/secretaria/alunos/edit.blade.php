@extends('layouts.app')
@section('title', 'Editar Aluno')

@section('content')
<div style="max-width: 560px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.show', $aluno) }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para {{ $aluno->name }}
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">Editar aluno</h1>
    </div>

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
        <form method="POST" action="{{ route('secretaria.alunos.update', $aluno) }}">
            @csrf @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo</label>
                <input type="text" name="name" value="{{ old('name', $aluno->name) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('name')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Matrícula</label>
                <input type="text" name="registration_number" value="{{ old('registration_number', $aluno->registration_number) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('registration_number')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Data de nascimento</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $aluno->birth_date->format('Y-m-d')) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('birth_date')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            {{-- Atipicidade --}}
            <div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 4px;">
                    <input type="checkbox" name="is_atypical" value="1"
                           id="is_atypical"
                           {{ old('is_atypical', $aluno->is_atypical) ? 'checked' : '' }}
                           onchange="toggleAtypical(this.checked)">
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">Aluno com perfil de atipicidade</span>
                </label>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 0 26px;">Marque para habilitar os campos de condição</p>

                <div id="atypical_fields" style="{{ old('is_atypical', $aluno->is_atypical) ? '' : 'display:none;' }} margin-top: 20px;">

                    <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 16px 0 12px;">Condições identificadas</p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 16px;">
                        {{-- TEA com nível de suporte --}}
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6;"
                               onmouseover="this.style.borderColor='#004B8D'"
                               onmouseout="this.style.borderColor='#F3F4F6'">
                            <input type="checkbox" name="cid_autismo" value="1"
                                   id="cid_autismo_check"
                                   {{ old('cid_autismo', $aluno->cid_autismo) ? 'checked' : '' }}
                                   onchange="document.getElementById('tea_nivel_field').style.display = this.checked ? 'block' : 'none'">
                            <span style="font-size: 13px; color: #374151;">Autismo (TEA)</span>
                        </label>

                        @foreach([
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
                                       {{ old($field, $aluno->$field) ? 'checked' : '' }}>
                                <span style="font-size: 13px; color: #374151;">{{ $label }}</span>
                            </label>
                        @endforeach

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 8px; border: 1px solid #F3F4F6;"
                               onmouseover="this.style.borderColor='#004B8D'"
                               onmouseout="this.style.borderColor='#F3F4F6'">
                            <input type="checkbox" name="cid_outros" value="1"
                                   id="cid_outros_check"
                                   {{ old('cid_outros', $aluno->cid_outros) ? 'checked' : '' }}
                                   onchange="document.getElementById('condition_field').style.display = this.checked ? 'block' : 'none'">
                            <span style="font-size: 13px; color: #374151;">Outros</span>
                        </label>
                    </div>

                    {{-- Nível de suporte TEA --}}
                    <div id="tea_nivel_field" style="{{ old('cid_autismo', $aluno->cid_autismo) ? '' : 'display:none;' }} margin-bottom: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nível de suporte TEA</label>
                        <select name="tea_nivel_suporte"
                                style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                            <option value="">Não informado</option>
                            <option value="1" {{ old('tea_nivel_suporte', $aluno->tea_nivel_suporte) == '1' ? 'selected' : '' }}>Nível 1 — Requer suporte</option>
                            <option value="2" {{ old('tea_nivel_suporte', $aluno->tea_nivel_suporte) == '2' ? 'selected' : '' }}>Nível 2 — Requer suporte substancial</option>
                            <option value="3" {{ old('tea_nivel_suporte', $aluno->tea_nivel_suporte) == '3' ? 'selected' : '' }}>Nível 3 — Requer suporte muito substancial</option>
                        </select>
                    </div>

                    <div id="condition_field" style="{{ old('cid_outros', $aluno->cid_outros) ? '' : 'display:none;' }}">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Descreva a condição</label>
                        <input type="text" name="condition" value="{{ old('condition', $aluno->condition) }}"
                               placeholder="Descreva a condição ou CID..."
                               style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Atualizar
                </button>
                <a href="{{ route('secretaria.alunos.show', $aluno) }}"
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