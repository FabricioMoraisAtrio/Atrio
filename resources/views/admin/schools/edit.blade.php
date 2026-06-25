@extends('admin.layouts.app')
@section('title', 'Editar Escola')

@section('content')
<div class="max-w-2xl">

    <h2 class="text-lg font-semibold text-gray-800 mb-6">Editar — {{ $school->name }}</h2>

    {{-- Abas --}}
    <div style="display: flex; gap: 2px; border-bottom: 2px solid #E5E7EB; margin-bottom: 0;">
        @foreach([
            'escola'         => 'Escola',
            'aparencia'      => 'Aparência',
            'modulos'        => 'Módulos',
            'terminologias'  => 'Terminologias',
            'materias'       => 'Matérias',
        ] as $id => $label)
        <button type="button" onclick="switchTab('{{ $id }}')" id="tab-{{ $id }}"
                style="padding: 9px 18px; font-size: 13px; font-weight: 600; border: none; background: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; color: #6B7280; transition: color .15s;">
            {{ $label }}
        </button>
        @endforeach
    </div>

    @if(session('success'))
    <div style="background:#ECFDF5;border:1px solid #6EE7B7;color:#065F46;font-size:13px;border-radius:8px;padding:10px 14px;margin-top:16px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- ═══ FORM PRINCIPAL (Escola + Aparência + Módulos) ═══ --}}
    <form method="POST" action="{{ route('admin.schools.update', $school) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- ══ ABA: ESCOLA ══ --}}
        <div id="panel-escola" class="tab-panel">
            <div class="bg-white rounded-b-xl rounded-tr-xl border border-t-0 border-gray-200 p-6 space-y-5">

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nome da escola</label>
                    <input type="text" name="name" value="{{ old('name', $school->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Plano</label>
                        <select name="plan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="pro"        {{ old('plan', $school->plan) === 'pro'        ? 'selected' : '' }}>Pro (mensal)</option>
                            <option value="enterprise" {{ old('plan', $school->plan) === 'enterprise' ? 'selected' : '' }}>Enterprise (anual)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Status do plano</label>
                        <select name="plan_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="active"    {{ old('plan_status', $school->plan_status) === 'active'    ? 'selected' : '' }}>Ativo</option>
                            <option value="suspended" {{ old('plan_status', $school->plan_status) === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                            <option value="cancelled" {{ old('plan_status', $school->plan_status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Expira em</label>
                        <input type="date" name="plan_expires_at"
                               value="{{ old('plan_expires_at', $school->plan_expires_at?->format('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Máx. alunos</label>
                        <input type="number" name="max_students"
                               value="{{ old('max_students', $school->max_students) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $school->is_active) ? 'checked' : '' }} class="rounded">
                        <span class="text-sm text-gray-700">Escola ativa</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Observações internas</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">{{ old('notes', $school->notes) }}</textarea>
                </div>

                @if($secretaria)
                <div style="border-top: 1px solid #E5E7EB; padding-top: 20px;">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Administrador</p>
                    <input type="hidden" name="secretaria_id" value="{{ $secretaria->id }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nome</label>
                            <input type="text" name="secretaria_name"
                                   value="{{ old('secretaria_name', $secretaria->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                            @error('secretaria_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">E-mail</label>
                            <input type="email" name="secretaria_email"
                                   value="{{ old('secretaria_email', $secretaria->email) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                            @error('secretaria_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- ══ ABA: APARÊNCIA ══ --}}
        <div id="panel-aparencia" class="tab-panel" style="display:none;">
            <div class="bg-white rounded-b-xl rounded-tr-xl border border-t-0 border-gray-200 p-6 space-y-6">

                {{-- Logo --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Logo da escola</p>
                    <div id="logo-preview-wrapper" style="margin-bottom: 12px;{{ $school->logo ? '' : ' display:none;' }}">
                        <p style="font-size:11px;color:#9CA3AF;margin-bottom:6px;">Logo atual</p>
                        <img id="logo-preview-img" src="{{ $school->logo ? route('school.logo', ['filename' => basename($school->logo)]) : '' }}"
                             style="height: 48px; max-width: 180px; object-fit: contain; border: 1px solid #E5E7EB; border-radius: 8px; padding: 6px;">
                    </div>
                    <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <label for="logo-input" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border: 1px solid #D1D5DB; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151; background: #F9FAFB;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                            </svg>
                            <span id="logo-label">{{ $school->logo ? 'Trocar imagem…' : 'Escolher imagem…' }}</span>
                        </label>
                        <button type="button"
                                onclick="atrioRemovePhoto({inputId:'logo-input', removeFlagId:'remove_logo', previewId:'logo-preview-img', wrapperId:'logo-preview-wrapper', nameId:'logo-label'})"
                                style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border:1px solid #D1D5DB; border-radius:8px; font-size:13px; color:#B42318; background:#fff; cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            Remover
                        </button>
                    </div>
                    <input id="logo-input" type="file" name="logo" accept=".svg,.png,.jpg,.jpeg,image/svg+xml,image/png,image/jpeg"
                           style="display: none;"
                           onchange="AtrioCropper.open(this, {aspect:null, previewId:'logo-preview-img', wrapperId:'logo-preview-wrapper', output:'png', name:'logo', removeFlagId:'remove_logo'})">
                    <p style="font-size:11px;color:#9CA3AF;margin-top:6px;">SVG, PNG ou JPG</p>
                    @error('logo')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
                </div>

                {{-- Cor do tema --}}
                <div style="border-top: 1px solid #E5E7EB; padding-top: 20px;">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Cor do tema</p>
                    <p class="text-xs text-gray-400 mb-3">Define a cor de destaque do painel para os usuários desta escola.</p>

                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px;" id="preset-swatches">
                        @foreach([
                            '#004B8D' => 'Azul Átrio (padrão)',
                            '#16A34A' => 'Verde',
                            '#7C3AED' => 'Roxo',
                            '#DC2626' => 'Vermelho',
                            '#EA580C' => 'Laranja',
                            '#0891B2' => 'Turquesa',
                            '#BE185D' => 'Rosa',
                            '#92400E' => 'Marrom',
                        ] as $hex => $name)
                        <button type="button" onclick="selectColor('{{ $hex }}')" title="{{ $name }}" data-color="{{ $hex }}"
                                style="width: 30px; height: 30px; border-radius: 50%; background: {{ $hex }}; border: 2px solid transparent; cursor: pointer; transition: transform .1s;"
                                onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                        </button>
                        @endforeach
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="color" id="color-picker" value="{{ old('theme_color', $school->theme_color ?? '#004B8D') }}"
                               onchange="selectColor(this.value)"
                               style="width: 36px; height: 36px; border-radius: 8px; cursor: pointer; border: 1px solid #D1D5DB; padding: 2px;">
                        <input type="text" name="theme_color" id="color-hex"
                               value="{{ old('theme_color', $school->theme_color ?? '#004B8D') }}"
                               maxlength="7" placeholder="#004B8D"
                               oninput="syncColor(this.value)"
                               style="border: 1px solid #D1D5DB; border-radius: 8px; padding: 7px 12px; font-size: 13px; width: 110px; font-family: monospace; outline: none;">
                        <span style="font-size: 12px; color: #9CA3AF;">código hexadecimal</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══ ABA: MÓDULOS ══ --}}
        <div id="panel-modulos" class="tab-panel" style="display:none;">
            <div class="bg-white rounded-b-xl rounded-tr-xl border border-t-0 border-gray-200 p-6">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Módulos habilitados</p>
                <p class="text-xs text-gray-400 mb-4">Defina quais rotinas estarão disponíveis para os usuários desta escola. Deixar tudo desmarcado habilita todos.</p>
                @php $activeModules = old('modules', $school->modules); @endphp
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    @foreach($availableModules as $key => $label)
                    <label style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; border: 1px solid #E5E7EB; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151;"
                           onmouseover="this.style.borderColor='#6B7280'" onmouseout="this.style.borderColor='#E5E7EB'">
                        <input type="checkbox" name="modules[]" value="{{ $key }}"
                               {{ ($activeModules === null || in_array($key, (array) $activeModules)) ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Botão de salvar (Escola + Aparência + Módulos) --}}
        <div id="save-main" style="padding: 16px 0; display: flex; gap: 12px; align-items: center;">
            <button type="submit"
                    class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                Salvar alterações
            </button>
            <a href="{{ route('admin.schools.show', $school) }}"
               class="text-sm text-gray-500 hover:underline">Cancelar</a>
        </div>

    </form>

    {{-- ══ ABA: MATÉRIAS (form separado) ══ --}}
    <div id="panel-materias" class="tab-panel" style="display:none;">
        <div class="bg-white rounded-b-xl rounded-tr-xl border border-t-0 border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Matérias</p>
            <p class="text-xs text-gray-400 mb-4">Disciplinas e componentes curriculares configurados para esta escola.</p>

            {{-- Lista de matérias existentes --}}
            @if($subjects->isEmpty())
                <p style="font-size: 13px; color: #9CA3AF; font-style: italic; margin-bottom: 24px;">Nenhuma matéria cadastrada.</p>
            @else
            <div style="border: 1px solid #E5E7EB; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
                @foreach($subjects as $subject)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; {{ !$loop->last ? 'border-bottom: 1px solid #F3F4F6;' : '' }}">
                    <div>
                        <span style="font-size: 13px; font-weight: 600; color: #111827;">{{ $subject->name }}</span>
                        <span style="font-size: 11px; color: #9CA3AF; margin-left: 8px;">{{ $subject->slug }}</span>
                        <span style="font-size: 11px; color: #6B7280; margin-left: 6px; background: #F3F4F6; padding: 1px 6px; border-radius: 4px;">{{ $subject->tipo }}</span>
                        <span style="font-size: 11px; color: #9CA3AF; margin-left: 6px;">ordem {{ $subject->ordem }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.schools.materias.destroy', [$school, $subject]) }}"
                          onsubmit="return confirm('Remover \'{{ addslashes($subject->name) }}\'?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="font-size: 12px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px;"
                                onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='none'">
                            Remover
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Formulário de nova matéria --}}
            <form method="POST" action="{{ route('admin.schools.materias.store', $school) }}">
                @csrf
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Nova matéria</p>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nome</label>
                        <input type="text" name="name" placeholder="Ex: Matemática"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Slug</label>
                        <input type="text" name="slug" placeholder="Ex: matematica"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Rótulo do responsável</label>
                        <input type="text" name="label_responsavel" placeholder="Ex: Professor(a)"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                        <select name="tipo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="disciplina">Disciplina</option>
                            <option value="regente">Regente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Ordem</label>
                        <input type="number" name="ordem" value="0" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                </div>
                <button type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                    Adicionar matéria
                </button>
            </form>
        </div>
    </div>

    {{-- ══ ABA: TERMINOLOGIAS (form separado) ══ --}}
    <form id="panel-terminologias" class="tab-panel" style="display:none;"
          method="POST" action="{{ route('admin.schools.terminologias.update', $school) }}">
        @csrf @method('PUT')

        <div class="bg-white rounded-b-xl rounded-tr-xl border border-t-0 border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Terminologias</p>
            <p class="text-xs text-gray-400 mb-4">Personalize os termos exibidos para os usuários desta escola. Deixe em branco para usar o padrão.</p>

            @php
            $termDefs = [
                ['key' => 'aluno',            'label' => 'Aluno (singular)',      'default' => 'Aluno'],
                ['key' => 'alunos',           'label' => 'Aluno (plural)',        'default' => 'Alunos'],
                ['key' => 'turma',            'label' => 'Turma (singular)',      'default' => 'Turma'],
                ['key' => 'turmas',           'label' => 'Turma (plural)',        'default' => 'Turmas'],
                ['key' => 'laudo',            'label' => 'Laudo (singular)',      'default' => 'Laudo'],
                ['key' => 'laudos',           'label' => 'Laudo (plural)',        'default' => 'Laudos'],
                ['key' => 'professor',        'label' => 'Professor (singular)',  'default' => 'Professor'],
                ['key' => 'professores',      'label' => 'Professor (plural)',    'default' => 'Professores'],
                ['key' => 'coordenador',      'label' => 'Coordenador',          'default' => 'Coordenador'],
                ['key' => 'orientador',       'label' => 'Orientador',           'default' => 'Orientador'],
                ['key' => 'documento',        'label' => 'Documento (singular)', 'default' => 'Documento'],
                ['key' => 'documentos',       'label' => 'Documento (plural)',   'default' => 'Documentos'],
                ['key' => 'publico_alvo',     'label' => 'Público Alvo',         'default' => 'Público Alvo'],
                ['key' => 'nao_publico_alvo', 'label' => 'Não Público Alvo',     'default' => 'Não público alvo'],
            ];
            @endphp

            <div class="grid grid-cols-2 gap-4">
                @foreach($termDefs as $td)
                <div>
                    <label class="block text-xs text-gray-500 mb-1">
                        {{ $td['label'] }}
                        <span class="text-gray-300 font-normal"> — padrão: {{ $td['default'] }}</span>
                    </label>
                    <input type="text" name="term_{{ $td['key'] }}"
                           value="{{ old('term_'.$td['key'], $settings['term_'.$td['key']] ?? '') }}"
                           placeholder="{{ $td['default'] }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>
                @endforeach
            </div>

            <div style="padding-top: 20px; border-top: 1px solid #F3F4F6; margin-top: 20px;">
                <button type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                    Salvar terminologias
                </button>
            </div>
        </div>
    </form>

</div>

<script>
const TABS = ['escola', 'aparencia', 'modulos', 'terminologias', 'materias'];

function switchTab(active) {
    TABS.forEach(id => {
        const panel = document.getElementById('panel-' + id);
        const tab   = document.getElementById('tab-'   + id);
        const isActive = id === active;

        panel.style.display = isActive ? '' : 'none';
        tab.style.color       = isActive ? '#111827' : '#6B7280';
        tab.style.borderColor = isActive ? '#111827' : 'transparent';
    });

    // Oculta botão de salvar principal em abas com form próprio
    const saveMain = document.getElementById('save-main');
    if (saveMain) saveMain.style.display = ['terminologias', 'materias'].includes(active) ? 'none' : 'flex';

    sessionStorage.setItem('schoolTab', active);
}

// Restaura aba ativa após redirect
const savedTab = sessionStorage.getItem('schoolTab') ?? 'escola';
switchTab(savedTab);

// Cor do tema
function selectColor(hex) {
    document.getElementById('color-hex').value = hex;
    document.getElementById('color-picker').value = hex;
    document.querySelectorAll('#preset-swatches button').forEach(btn => {
        btn.style.border = btn.dataset.color === hex ? '2px solid #111827' : '2px solid transparent';
    });
}
function syncColor(val) {
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.getElementById('color-picker').value = val;
        document.querySelectorAll('#preset-swatches button').forEach(btn => {
            btn.style.border = btn.dataset.color.toLowerCase() === val.toLowerCase() ? '2px solid #111827' : '2px solid transparent';
        });
    }
}
selectColor(document.getElementById('color-hex').value || '#004B8D');

// Salva a aba antes de submeter qualquer form
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
        const activeTab = TABS.find(id => document.getElementById('panel-' + id).style.display !== 'none');
        if (activeTab) sessionStorage.setItem('schoolTab', activeTab);
    });
});
</script>
@endsection
