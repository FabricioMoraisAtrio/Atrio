@extends('layouts.app')
@section('title', 'Configurações')

@section('content')
@php $tab = request('tab', 'escola'); @endphp

<div style="max-width: 760px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Configurações</h1>
        <p style="font-size: 13px; color: #6B7280; margin: 0;">Gerencie os dados da escola, terminologias e perfis de acesso.</p>
    </div>

    {{-- Tabs --}}
    <div style="display: flex; gap: 4px; border-bottom: 2px solid #F3F4F6; margin-bottom: 28px;">
        @foreach(['escola' => 'Escola', 'materias' => 'Matérias', 'perfis' => 'Perfis de Acesso'] as $t => $label)
            @if($t === 'perfis')
                <a href="{{ route('secretaria.config.perfis.index') }}"
                   style="padding: 10px 18px; font-size: 13px; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px;
                          {{ $tab === $t ? 'color:#004B8D; border-bottom-color:#004B8D;' : 'color:#9CA3AF;' }}">
                    {{ $label }}
                </a>
            @else
                <a href="{{ route('secretaria.config.index', ['tab' => $t]) }}"
                   style="padding: 10px 18px; font-size: 13px; font-weight: 600; text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px;
                          {{ $tab === $t ? 'color:#004B8D; border-bottom-color:#004B8D;' : 'color:#9CA3AF;' }}">
                    {{ $label }}
                </a>
            @endif
        @endforeach
    </div>

    @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Tab: Escola --}}
    @if($tab === 'escola')
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
            <h2 style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 20px;">Dados da Escola</h2>

            <form method="POST" action="{{ route('secretaria.config.escola.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome da Escola</label>
                    <input type="text" name="name" value="{{ old('name', $escola->name) }}"
                           style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Cor do Tema</label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="color" name="theme_color" value="{{ old('theme_color', $escola->theme_color ?? '#004B8D') }}"
                               style="width: 48px; height: 36px; border: 1px solid #E5E7EB; border-radius: 6px; cursor: pointer; padding: 2px;">
                        <span style="font-size: 13px; color: #6B7280;">Cor usada nos destaques e botões do sistema</span>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Logo da Escola</label>

                    <div id="cfg-logo-preview-wrapper" style="{{ $escola->logo ? '' : 'display:none;' }} margin-bottom: 10px;">
                        <img id="cfg-logo-preview-img"
                             src="{{ $escola->logo ? route('school.logo', ['filename' => basename($escola->logo)]) : '' }}"
                             style="height: 52px; max-width: 180px; object-fit: contain; border: 1px solid #E5E7EB; border-radius: 8px; padding: 6px; background: #F9FAFB;">
                    </div>

                    <label for="cfg-logo-input" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid #D1D5DB; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151; background: #F9FAFB;"
                           onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                        <span id="cfg-logo-label">{{ $escola->logo ? 'Trocar imagem…' : 'Escolher imagem…' }}</span>
                    </label>
                    <input id="cfg-logo-input" type="file" name="logo" accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                           style="display: none;"
                           onchange="previewCfgLogo(this)">
                    <p style="font-size: 11px; color: #9CA3AF; margin-top: 6px;">PNG ou JPG, máx. 2MB. Aparece na barra lateral.</p>
                </div>
                <script>
                function previewCfgLogo(input) {
                    if (input.files && input.files[0]) {
                        document.getElementById('cfg-logo-label').textContent = input.files[0].name;
                        const reader = new FileReader();
                        reader.onload = e => {
                            document.getElementById('cfg-logo-preview-img').src = e.target.result;
                            document.getElementById('cfg-logo-preview-wrapper').style.display = '';
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
                </script>

                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Salvar
                </button>
            </form>
        </div>
    @endif

    {{-- Tab: Matérias --}}
    @if($tab === 'materias')
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <div>
                <h2 style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 4px;">Grade Curricular</h2>
                <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                    {{ $subjects->count() }} matéria(s). As matérias do tipo <strong>Regente</strong> preenchem as metas socioemocionais e funcionais do PEI.
                </p>
            </div>
            <a href="{{ route('secretaria.subjects.create') }}"
               style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Nova matéria
            </a>
        </div>

        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #F9FAFB;">
                        <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Ordem</th>
                        <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Matéria</th>
                        <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Tipo</th>
                        <th style="padding: 12px 20px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr style="border-top: 1px solid #F9FAFB;"
                        onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 14px 20px; font-size: 13px; color: #9CA3AF;">{{ $subject->ordem }}</td>
                        <td style="padding: 14px 20px;">
                            <div style="font-size: 14px; font-weight: 600; color: #111827;">{{ $subject->name }}</div>
                            <div style="font-size: 11px; color: #9CA3AF;">{{ $subject->label_responsavel }}</div>
                        </td>
                        <td style="padding: 14px 20px;">
                            @if($subject->tipo === 'regente')
                                <span style="background: #E6F5F4; color: #009C8C; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Regente</span>
                            @else
                                <span style="background: #E8F0F9; color: #004B8D; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Disciplina</span>
                            @endif
                        </td>
                        <td style="padding: 14px 20px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                                <a href="{{ route('secretaria.subjects.edit', $subject) }}"
                                   style="font-size: 13px; color: #6B7280; text-decoration: none;">Editar</a>
                                <form method="POST" action="{{ route('secretaria.subjects.destroy', $subject) }}" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" data-confirm="Remover a matéria {{ $subject->name }}?"
                                            style="font-size: 13px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 0;">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: #9CA3AF; font-size: 14px;">
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
