@extends('layouts.app')
@section('title', 'Editar Aluno')

@section('content')
<div style="max-width: 560px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.index') }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para {{ strtolower(term('alunos')) }}
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">Editar aluno</h1>
    </div>

    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 28px;">
        <form method="POST" action="{{ route('secretaria.alunos.update', $aluno) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Foto --}}
            <div style="margin-bottom: 24px; display: flex; align-items: center; gap: 16px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 2px solid var(--border); background: var(--accent-bg); display: flex; align-items: center; justify-content: center;">
                    @if($aluno->photo)
                        <img id="foto-preview" src="{{ route('alunos.foto', $aluno) }}" style="width:100%; height:100%; object-fit:cover;" alt="">
                    @else
                        <img id="foto-preview" src="" style="display:none; width:100%; height:100%; object-fit:cover;" alt="">
                        <span id="foto-inicial" style="font-size: 24px; font-weight: 700; color: var(--accent-text);">{{ strtoupper(substr($aluno->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <p style="font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 8px;">Foto do aluno</p>
                    <label for="foto-input-edit" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text-2); cursor: pointer;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        Alterar foto
                    </label>
                    <input type="file" id="foto-input-edit" name="photo" accept="image/*"
                           onchange="previewFoto(this)" style="display:none;">
                    <p id="foto-nome" style="font-size: 12px; color: var(--text-4); margin: 6px 0 0;">Nenhum arquivo selecionado</p>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo</label>
                <input type="text" name="name" value="{{ old('name', $aluno->name) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('name')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Matrícula</label>
                <input type="text" name="registration_number" value="{{ old('registration_number', $aluno->registration_number) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('registration_number')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Data de nascimento</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $aluno->birth_date->format('Y-m-d')) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('birth_date')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            {{-- Responsável 1 --}}
            <div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 16px 20px; margin-bottom: 16px;">
                <p style="font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Responsável</p>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); margin-bottom: 6px;">Nome completo</label>
                    <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome', $aluno->responsavel_nome) }}"
                           placeholder="Ex: Maria Silva Santos"
                           style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); margin-bottom: 6px;">E-mail</label>
                        <input type="email" name="responsavel_email" value="{{ old('responsavel_email', $aluno->responsavel_email) }}"
                               placeholder="email@exemplo.com"
                               style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('responsavel_email')<p style="font-size:11px;color:var(--danger);margin-top:3px;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); margin-bottom: 6px;">Telefone / WhatsApp</label>
                        <input type="text" name="responsavel_telefone" value="{{ old('responsavel_telefone', $aluno->responsavel_telefone) }}"
                               placeholder="(00) 00000-0000"
                               style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>
            </div>

            {{-- Responsável 2 --}}
            <div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 16px 20px; margin-bottom: 20px;">
                <p style="font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">2º Responsável <span style="font-weight:400;color:var(--text-4);">(opcional)</span></p>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); margin-bottom: 6px;">Nome completo</label>
                    <input type="text" name="responsavel_2_nome" value="{{ old('responsavel_2_nome', $aluno->responsavel_2_nome) }}"
                           placeholder="Opcional"
                           style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); margin-bottom: 6px;">E-mail</label>
                        <input type="email" name="responsavel_2_email" value="{{ old('responsavel_2_email', $aluno->responsavel_2_email) }}"
                               placeholder="email@exemplo.com"
                               style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                        @error('responsavel_2_email')<p style="font-size:11px;color:var(--danger);margin-top:3px;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); margin-bottom: 6px;">Telefone / WhatsApp</label>
                        <input type="text" name="responsavel_2_telefone" value="{{ old('responsavel_2_telefone', $aluno->responsavel_2_telefone) }}"
                               placeholder="(00) 00000-0000"
                               style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>
            </div>

            {{-- Turma --}}
            <div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                <p style="font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Turma</p>
                @if($aluno->schoolClasses->isNotEmpty())
                <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px;">
                    @foreach($aluno->schoolClasses as $turmaVinculada)
                    <span style="background: var(--accent-bg); color: var(--accent-text); font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                        {{ $turmaVinculada->name }} — {{ $turmaVinculada->shift }}
                    </span>
                    @endforeach
                </div>
                @endif
                <div style="display: flex; gap: 10px; align-items: center;">
                    <select name="school_class_id"
                            style="flex: 1; border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-2); outline: none; background: var(--bg-card);">
                        <option value="">{{ $aluno->schoolClasses->isNotEmpty() ? 'Trocar turma…' : 'Vincular turma…' }}</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}" {{ $aluno->schoolClasses->contains($t->id) ? 'selected' : '' }}>
                                {{ $t->name }} — {{ $t->shift }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <p style="font-size: 11px; color: var(--text-4); margin: 6px 0 0;">Selecionar uma nova turma substitui a atual.</p>
            </div>

            {{-- Atipicidade --}}
            <div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 4px;">
                    <input type="checkbox" name="is_atypical" value="1"
                           id="is_atypical"
                           {{ old('is_atypical', $aluno->is_atypical) ? 'checked' : '' }}
                           onchange="toggleAtypical(this.checked)">
                    <span style="font-size: 14px; font-weight: 600; color: var(--text-1);">{{ term('aluno') }} {{ strtolower(term('publico_alvo')) }} da Educação Especial</span>
                </label>
                <p style="font-size: 12px; color: var(--text-4); margin: 0 0 0 26px;">Marque para habilitar os campos de condição</p>

                <div id="atypical_fields" style="{{ old('is_atypical', $aluno->is_atypical) ? '' : 'display:none;' }} margin-top: 20px;">

                    @php
                        $publico_alvo_fields = \App\Models\Student::PUBLICO_ALVO_FIELDS;
                        $transtornos = config('transtornos');
                        $transtornosPA    = array_filter($transtornos, fn($k) => in_array($k, $publico_alvo_fields), ARRAY_FILTER_USE_KEY);
                        $transtornosOutros = array_filter($transtornos, fn($k) => !in_array($k, $publico_alvo_fields), ARRAY_FILTER_USE_KEY);
                    @endphp

                    {{-- Grupo: Público Alvo --}}
                    <div style="border-radius: 10px; border: 1.5px solid var(--purple); overflow: hidden; margin-bottom: 14px;">
                        <div style="background: var(--purple-bg); padding: 8px 12px; border-bottom: 1px solid var(--purple);">
                            <span style="font-size: 10px; font-weight: 700; color: var(--purple); letter-spacing: 0.8px; text-transform: uppercase;">Público Alvo — habilitam PEI</span>
                        </div>
                        <div style="padding: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                            @foreach($transtornosPA as $field => [$sigla, $nome])
                                <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--purple); background: var(--purple-bg); transition: border-color 0.15s;"
                                       onmouseover="this.style.borderColor='var(--purple)'; this.style.background='var(--purple-bg)'"
                                       onmouseout="this.style.borderColor='var(--purple)'; this.style.background='var(--purple-bg)'">
                                    <input type="checkbox" name="{{ $field }}" value="1"
                                           style="margin-top: 2px; flex-shrink: 0; accent-color: #7C3AED;"
                                           {{ old($field, $aluno->$field) ? 'checked' : '' }}
                                           @if($field === 'cid_autismo') id="cid_autismo_check" onchange="document.getElementById('tea_nivel_field').style.display = this.checked ? 'block' : 'none'" @endif>
                                    <div>
                                        <span style="font-size: 12px; font-weight: 700; color: var(--purple); display: block;">{{ $sigla }}</span>
                                        <span style="font-size: 11px; color: var(--text-3); line-height: 1.3;">{{ $nome }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Grupo: Outras condições --}}
                    <div style="border-radius: 10px; border: 1px solid var(--border); overflow: hidden; margin-bottom: 16px;">
                        <div style="background: var(--bg-subtle); padding: 8px 12px; border-bottom: 1px solid var(--border);">
                            <span style="font-size: 10px; font-weight: 700; color: var(--text-3); letter-spacing: 0.8px; text-transform: uppercase;">Outras condições atípicas</span>
                        </div>
                        <div style="padding: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                            @foreach($transtornosOutros as $field => [$sigla, $nome])
                                <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer; padding: 8px 10px; border-radius: 8px; border: 1px solid var(--border-sub); background: var(--bg-card);"
                                       onmouseover="this.style.borderColor='var(--accent)'; this.style.background='var(--bg-subtle)'"
                                       onmouseout="this.style.borderColor='var(--border-sub)'; this.style.background='var(--bg-card)'">
                                    <input type="checkbox" name="{{ $field }}" value="1"
                                           style="margin-top: 2px; flex-shrink: 0; accent-color: #004B8D;"
                                           {{ old($field, $aluno->$field) ? 'checked' : '' }}
                                           @if($field === 'cid_outros') id="cid_outros_check" onchange="document.getElementById('condition_field').style.display = this.checked ? 'block' : 'none'" @endif>
                                    <div>
                                        <span style="font-size: 12px; font-weight: 700; color: var(--accent-text); display: block;">{{ $sigla }}</span>
                                        <span style="font-size: 11px; color: var(--text-3); line-height: 1.3;">{{ $nome }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nível de suporte TEA --}}
                    <div id="tea_nivel_field" style="{{ old('cid_autismo', $aluno->cid_autismo) ? '' : 'display:none;' }} margin-bottom: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nível de suporte TEA</label>
                        <select name="tea_nivel_suporte"
                                style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;">
                            <option value="">Não informado</option>
                            <option value="1" {{ old('tea_nivel_suporte', $aluno->tea_nivel_suporte) == '1' ? 'selected' : '' }}>Nível 1</option>
                            <option value="2" {{ old('tea_nivel_suporte', $aluno->tea_nivel_suporte) == '2' ? 'selected' : '' }}>Nível 2</option>
                            <option value="3" {{ old('tea_nivel_suporte', $aluno->tea_nivel_suporte) == '3' ? 'selected' : '' }}>Nível 3</option>
                        </select>
                    </div>

                    <div id="condition_field" style="{{ old('cid_outros', $aluno->cid_outros) ? '' : 'display:none;' }}">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Descreva a condição</label>
                        <input type="text" name="condition" value="{{ old('condition', $aluno->condition) }}"
                               placeholder="Descreva a condição ou CID..."
                               style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit"
                        style="background: var(--accent); color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Atualizar
                </button>
                <a href="{{ route('secretaria.alunos.index') }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
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
            var preview = document.getElementById('foto-preview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            var inicial = document.getElementById('foto-inicial');
            if (inicial) inicial.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection