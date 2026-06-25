@extends('layouts.app')
@section('title', 'Meu perfil')

@section('content')
<div style="max-width: 560px;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Meu perfil</h1>
        <p style="font-size: 13px; color: var(--text-4); margin: 0;">{{ auth()->user()->email }}</p>
    </div>

    @if(session('success'))
        <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Avatar --}}
        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 16px;">
            <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0 0 16px;">Foto de perfil</p>
            <div style="display: flex; align-items: center; gap: 16px;">
                @php $hasAvatar = (bool) $user->avatar; @endphp
                <div id="avatar-preview"
                    style="width: 64px; height: 64px; border-radius: 50%; background: var(--accent); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; background-size: cover; background-position: center;@if($hasAvatar) background-image: url('{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}?v={{ $user->updated_at->timestamp }}');@endif">
                    <span id="avatar-initial" style="font-size: 24px; font-weight: 700;@if($hasAvatar) display:none;@endif">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                <div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; background: var(--bg-subtle); padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 13px; color: var(--text-2); font-weight: 500;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                            </svg>
                            Escolher foto
                            <input type="file" name="avatar" id="avatar-input" accept="image/*" style="display: none;"
                                   onchange="AtrioCropper.open(this, {aspect:1, previewId:'avatar-preview', placeholderId:'avatar-initial', output:'jpeg', name:'avatar', removeFlagId:'remove_avatar'})">
                        </label>
                        <button type="button"
                                onclick="atrioRemovePhoto({inputId:'avatar-input', removeFlagId:'remove_avatar', previewId:'avatar-preview', placeholderId:'avatar-initial'})"
                                style="display:inline-flex; align-items:center; gap:6px; background:transparent; padding:8px 14px; border-radius:8px; border:1px solid var(--border); cursor:pointer; font-size:13px; color:var(--danger); font-weight:500;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            Remover foto
                        </button>
                    </div>
                    <p style="font-size: 12px; color: var(--text-4); margin: 6px 0 0;">JPG, PNG ou GIF. Máx 2MB.</p>
                    @error('avatar')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Senha --}}
        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 16px;">
            <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0 0 4px;">Alterar senha</p>
            <p style="font-size: 12px; color: var(--text-4); margin: 0 0 20px;">Deixe em branco para manter a senha atual.</p>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Senha atual</label>
                <input type="password" name="current_password"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('current_password')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nova senha</label>
                <input type="password" name="password"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
                @error('password')<p style="font-size: 12px; color: var(--danger); margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Confirmar nova senha</label>
                <input type="password" name="password_confirmation"
                       style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            </div>
        </div>

        {{-- Notificações --}}
        <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 24px;">
            <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0 0 16px;">Preferências de notificação</p>

            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin-bottom: 16px;">
                <input type="checkbox" name="notify_document_pending" value="1"
                       {{ $user->notify_document_pending ? 'checked' : '' }}
                       style="margin-top: 2px; flex-shrink: 0;">
                <div>
                    <p style="font-size: 13px; font-weight: 500; color: var(--text-1); margin: 0 0 2px;">Documentos pendentes</p>
                    <p style="font-size: 12px; color: var(--text-4); margin: 0;">Alertas sobre PEI, PAEE e Estudo de Caso não preenchidos</p>
                </div>
            </label>

            @hasrole('secretaria')
            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin-bottom: 16px;">
                <input type="checkbox" name="notify_plan_expiring" value="1"
                       {{ $user->notify_plan_expiring ? 'checked' : '' }}
                       style="margin-top: 2px; flex-shrink: 0;">
                <div>
                    <p style="font-size: 13px; font-weight: 500; color: var(--text-1); margin: 0 0 2px;">Vencimento de plano</p>
                    <p style="font-size: 12px; color: var(--text-4); margin: 0;">Alertas quando o plano da escola estiver próximo do vencimento</p>
                </div>
            </label>
            @endhasrole

            <div style="border-top: 1px solid var(--border-sub); padding-top: 16px; margin-top: 4px;">
                <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0 0 12px;">Preferências de documentos</p>
                <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                    <input type="checkbox" name="pdf_preview" value="1"
                           {{ $user->pdf_preview ? 'checked' : '' }}
                           style="margin-top: 2px; flex-shrink: 0;">
                    <div>
                        <p style="font-size: 13px; font-weight: 500; color: var(--text-1); margin: 0 0 2px;">Preview antes de baixar PDF</p>
                        <p style="font-size: 12px; color: var(--text-4); margin: 0;">Exibe uma pré-visualização do documento em pop-up antes do download</p>
                    </div>
                </label>
            </div>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="submit"
                    style="background: var(--accent); color: var(--accent-contrast); border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Salvar alterações
            </button>
            <a href="{{ route(auth()->user()->hasRole('professor') ? 'professor.dashboard' : 'secretaria.dashboard') }}"
               style="padding: 11px 24px; border-radius: 8px; border: 1px solid var(--border); background: transparent; color: var(--text-2); font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none;">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection