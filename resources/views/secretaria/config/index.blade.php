@extends('layouts.app')
@section('title', 'Configurações')

@section('content')
@php $tab = request('tab', 'escola'); @endphp

<div style="max-width: 760px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Configurações</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">Gerencie os dados da escola, terminologias e perfis de acesso.</p>
    </div>

    {{-- Tabs --}}
    <div style="display: flex; gap: 4px; border-bottom: 2px solid var(--border-sub); margin-bottom: 28px;">
        @foreach(['escola' => 'Escola', 'modulos' => 'Módulos', 'bimestres' => 'Bimestres', 'materias' => 'Matérias', 'perfis' => 'Perfis de Acesso'] as $t => $label)
            @if($t === 'perfis')
                <a href="{{ route('secretaria.config.perfis.index') }}"
                   style="padding: 10px 18px; font-size: 13px; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px;
                          {{ $tab === $t ? 'color:#004B8D; border-bottom-color:var(--accent);' : 'color:#9CA3AF;' }}">
                    {{ $label }}
                </a>
            @else
                <a href="{{ route('secretaria.config.index', ['tab' => $t]) }}"
                   style="padding: 10px 18px; font-size: 13px; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px;
                          {{ $tab === $t ? 'color:#004B8D; border-bottom-color:var(--accent);' : 'color:#9CA3AF;' }}">
                    {{ $label }}
                </a>
            @endif
        @endforeach
    </div>

    @if(session('success'))
        <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Tab: Escola --}}
    @if($tab === 'escola')
        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 28px;">
            <h2 style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0 0 20px;">Dados da Escola</h2>

            <form method="POST" action="{{ route('secretaria.config.escola.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome da Escola</label>
                    <input type="text" name="name" value="{{ old('name', $escola->name) }}"
                           style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Cor do Tema</label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="color" name="theme_color" value="{{ old('theme_color', $escola->theme_color ?? '#004B8D') }}"
                               style="width: 48px; height: 36px; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; padding: 2px;">
                        <span style="font-size: 13px; color: var(--text-3);">Cor usada nos destaques e botões do sistema</span>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Logo da Escola</label>

                    <img id="cfg-logo-preview-img"
                         src="{{ $escola->logo ? route('school.logo', ['filename' => basename($escola->logo)]) : '' }}"
                         style="height: 52px; max-width: 180px; object-fit: contain; border: 1px solid var(--border); border-radius: 8px; padding: 6px; background: var(--bg-subtle); margin-bottom: 10px;{{ $escola->logo ? '' : ' display:none;' }}">

                    <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <label for="cfg-logo-input" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid var(--border); border-radius: 8px; cursor: pointer; font-size: 13px; color: var(--text-2); background: var(--bg-subtle);">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                            </svg>
                            <span id="cfg-logo-label">{{ $escola->logo ? 'Trocar imagem…' : 'Escolher imagem…' }}</span>
                        </label>
                        <button type="button"
                                onclick="atrioRemovePhoto({inputId:'cfg-logo-input', removeFlagId:'remove_logo', previewId:'cfg-logo-preview-img', nameId:'cfg-logo-label'})"
                                style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:1px solid var(--border); border-radius:8px; font-size:13px; font-weight:600; color:var(--danger); background:transparent; cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            Remover
                        </button>
                    </div>
                    <input id="cfg-logo-input" type="file" name="logo" accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                           style="display: none;"
                           onchange="AtrioCropper.open(this, {aspect:null, previewId:'cfg-logo-preview-img', output:'png', name:'logo', removeFlagId:'remove_logo'})">
                    <p style="font-size: 11px; color: var(--text-4); margin-top: 6px;">PNG ou JPG, máx. 2MB. Aparece na barra lateral.</p>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <button type="submit"
                            style="background: var(--accent); color: var(--accent-contrast); border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        Salvar
                    </button>
                    <a href="{{ route('secretaria.dashboard') }}"
                       style="padding: 11px 24px; border-radius: 8px; border: 1px solid var(--border); background: transparent; color: var(--text-2); font-size: 13px; font-weight: 600; text-decoration: none;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    @endif

    {{-- Tab: Módulos --}}
    @if($tab === 'modulos')
        <form method="POST" action="{{ route('secretaria.config.modulos.update') }}">
            @csrf @method('PUT')
            <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 24px;">
                <p style="font-size: 13px; color: var(--text-3); margin: 0 0 18px;">
                    Ative apenas os módulos que a escola usa. Os desativados somem do menu e ficam bloqueados.
                </p>
                @php $ativos = $escola->modules; @endphp
                @foreach(\App\Models\School::availableModules() as $key => $label)
                    @php
                        $checked = is_null($ativos) || in_array($key, $ativos, true);
                        $lock    = in_array($key, ['configuracoes', 'painel']);
                    @endphp
                    <label style="display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid var(--border-sub); border-radius: 10px; margin-bottom: 8px; cursor: {{ $lock ? 'default' : 'pointer' }};">
                        <input type="checkbox" name="modules[]" value="{{ $key }}" @checked($checked) @disabled($lock)
                               style="width: 16px; height: 16px; accent-color: var(--accent); flex-shrink: 0;">
                        @if($lock)<input type="hidden" name="modules[]" value="{{ $key }}">@endif
                        <span style="font-size: 14px; color: var(--text-1); font-weight: 500; flex: 1;">{{ $label }}</span>
                        @if($lock)<span style="font-size: 11px; color: var(--text-4); background: var(--bg-subtle); padding: 2px 8px; border-radius: 20px;">essencial</span>@endif
                    </label>
                @endforeach
                <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                    <button type="submit" style="background: var(--accent); color: #fff; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Salvar módulos</button>
                </div>
            </div>
        </form>
    @endif

    {{-- Tab: Bimestres --}}
    @if($tab === 'bimestres')
        <form method="POST" action="{{ route('secretaria.config.bimestres.update') }}">
            @csrf @method('PUT')
            <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 24px;">
                <p style="font-size: 13px; color: var(--text-3); margin: 0 0 18px;">
                    Defina o período de cada bimestre. Essas datas liberam o <strong>fechamento de bimestre</strong> na Linha do Tempo no momento certo.
                </p>
                @foreach([1, 2, 3, 4] as $b)
                    <div style="display: grid; grid-template-columns: 60px 1fr 1fr; gap: 12px; align-items: end; margin-bottom: 12px;">
                        <span style="font-size: 13px; font-weight: 700; color: var(--text-1); padding-bottom: 9px;">{{ $b }}º bim.</span>
                        <div>
                            <label style="font-size: 11px; color: var(--text-4); display: block; margin-bottom: 4px;">Início</label>
                            <input type="date" name="bim[{{ $b }}][inicio]" value="{{ $settings['bim'.$b.'_inicio'] ?? '' }}"
                                   style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--text-2); box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="font-size: 11px; color: var(--text-4); display: block; margin-bottom: 4px;">Fim</label>
                            <input type="date" name="bim[{{ $b }}][fim]" value="{{ $settings['bim'.$b.'_fim'] ?? '' }}"
                                   style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--text-2); box-sizing: border-box;">
                        </div>
                    </div>
                @endforeach
                <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                    <button type="submit" style="background: var(--accent); color: #fff; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Salvar datas</button>
                </div>
            </div>
        </form>
    @endif

    {{-- Tab: Matérias --}}
    @if($tab === 'materias')
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Grade Curricular</h2>
                <p style="font-size: 12px; color: var(--text-4); margin: 0;">
                    {{ $subjects->count() }} matéria(s). As matérias do tipo <strong>Regente</strong> preenchem as metas socioemocionais e funcionais do PEI.
                </p>
            </div>
            <a href="{{ route('secretaria.subjects.create') }}"
               style="background: var(--accent); color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Nova matéria
            </a>
        </div>

        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-subtle);">
                        <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Ordem</th>
                        <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Matéria</th>
                        <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Tipo</th>
                        <th style="padding: 12px 20px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr style="border-top: 1px solid var(--border-sub);"
                        onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 14px 20px; font-size: 13px; color: var(--text-4);">{{ $subject->ordem }}</td>
                        <td style="padding: 14px 20px;">
                            <div style="font-size: 14px; font-weight: 600; color: var(--text-1);">{{ $subject->name }}</div>
                            <div style="font-size: 11px; color: var(--text-4);">{{ $subject->label_responsavel }}</div>
                        </td>
                        <td style="padding: 14px 20px;">
                            @if($subject->tipo === 'regente')
                                <span style="background: var(--teal-bg); color: var(--teal); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Regente</span>
                            @else
                                <span style="background: var(--accent-bg); color: var(--accent-text); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Disciplina</span>
                            @endif
                        </td>
                        <td style="padding: 14px 20px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                                <a href="{{ route('secretaria.subjects.edit', $subject) }}"
                                   style="font-size: 13px; color: var(--text-3); text-decoration: none;">Editar</a>
                                <form method="POST" action="{{ route('secretaria.subjects.destroy', $subject) }}" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" data-confirm="Remover a matéria {{ $subject->name }}?"
                                            style="font-size: 13px; color: var(--danger); background: none; border: none; cursor: pointer; padding: 0;">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: var(--text-4); font-size: 14px;">
                            Nenhuma matéria cadastrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif


</div>
@endsection
