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
        <form method="POST" action="{{ route('secretaria.alunos.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Foto --}}
            <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 16px;">
                <div id="foto-preview-wrapper" style="width: 64px; height: 64px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 24px; font-weight: 700; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; border: 2px solid #E5E7EB;">
                    <img id="foto-preview" src="" style="display:none; width:100%; height:100%; object-fit:cover;" alt="">
                    <span id="foto-inicial">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                </div>
                <div>
                    <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 8px;">Foto do aluno</p>
                    <label for="foto-input" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 13px; font-weight: 600; color: #374151; cursor: pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        Escolher foto
                    </label>
                    <input type="file" id="foto-input" name="photo" accept="image/*"
                           onchange="previewFoto(this)" style="display:none;">
                    <p id="foto-nome" style="font-size: 12px; color: #9CA3AF; margin: 6px 0 0;">Nenhum arquivo selecionado</p>
                </div>
            </div>

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

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo do responsável</label>
                <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome') }}"
                       placeholder="Ex: Maria Silva Santos"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo do 2º responsável</label>
                <input type="text" name="responsavel_2_nome" value="{{ old('responsavel_2_nome') }}"
                       placeholder="Opcional"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
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
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">Aluno público alvo da Educação Especial</span>
                </label>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 0 26px;">Marque para habilitar os campos de condição</p>

                <div id="atypical_fields" style="{{ old('is_atypical') ? '' : 'display:none;' }} margin-top: 20px;">

                    <p style="font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Condições identificadas</p>

                    @php $transtornos = config('transtornos'); @endphp
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-bottom: 16px;">
                        @foreach($transtornos as $field => [$sigla, $nome])
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 10px; border-radius: 8px; border: 1px solid #F3F4F6;"
                                   onmouseover="this.style.borderColor='#004B8D'"
                                   onmouseout="this.style.borderColor='#F3F4F6'">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                       {{ old($field) ? 'checked' : '' }}
                                       @if($field === 'cid_autismo') id="cid_autismo_check" onchange="document.getElementById('tea_nivel_field').style.display = this.checked ? 'block' : 'none'" @endif
                                       @if($field === 'cid_outros') id="cid_outros_check" onchange="document.getElementById('condition_field').style.display = this.checked ? 'block' : 'none'" @endif>
                                <div>
                                    <span style="font-size: 12px; font-weight: 700; color: #004B8D;">{{ $sigla }}</span>
                                    <span style="font-size: 11px; color: #6B7280; display: block; line-height: 1.3;">{{ $nome }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Nível de suporte TEA --}}
                    <div id="tea_nivel_field" style="{{ old('cid_autismo') ? '' : 'display:none;' }} margin-bottom: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nível de suporte TEA</label>
                        <select name="tea_nivel_suporte"
                                style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                            <option value="">Não informado</option>
                            <option value="1" {{ old('tea_nivel_suporte') == '1' ? 'selected' : '' }}>Nível 1</option>
                            <option value="2" {{ old('tea_nivel_suporte') == '2' ? 'selected' : '' }}>Nível 2</option>
                            <option value="3" {{ old('tea_nivel_suporte') == '3' ? 'selected' : '' }}>Nível 3</option>
                        </select>
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
function previewFoto(input) {
    if (input.files && input.files[0]) {
        var nome = document.getElementById('foto-nome');
        if (nome) nome.textContent = input.files[0].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-preview').src = e.target.result;
            document.getElementById('foto-preview').style.display = 'block';
            document.getElementById('foto-inicial').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection