@extends('layouts.app')
@section('title', 'Meu perfil')

@section('content')
<div style="max-width: 560px;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Meu perfil</h1>
        <p style="font-size: 13px; color: #9CA3AF; margin: 0;">{{ auth()->user()->email }}</p>
    </div>

    @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Avatar --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
            <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 16px;">Foto de perfil</p>
            <div style="display: flex; align-items: center; gap: 16px;">
                @if($user->avatar)
                    <img id="avatar-preview"
                        src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}?v={{ $user->updated_at->timestamp }}"
                        style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                @else
                    <div id="avatar-preview"
                        style="width: 64px; height: 64px; border-radius: 50%; background: #004B8D; color: white; font-size: 24px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <label style="display: inline-flex; align-items: center; gap: 8px; background: #F3F4F6; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151; font-weight: 500;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                        Escolher foto
                        <input type="file" name="avatar" accept="image/*" style="display: none;"
                               onchange="previewAvatar(this)">
                    </label>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 6px 0 0;">JPG, PNG ou GIF. Máx 2MB.</p>
                    @error('avatar')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Senha --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
            <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 4px;">Alterar senha</p>
            <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 20px;">Deixe em branco para manter a senha atual.</p>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Senha atual</label>
                <input type="password" name="current_password"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('current_password')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nova senha</label>
                <input type="password" name="password"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('password')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Confirmar nova senha</label>
                <input type="password" name="password_confirmation"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>
        </div>

        {{-- Notificações --}}
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 24px;">
            <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 16px;">Preferências de notificação</p>

            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin-bottom: 16px;">
                <input type="checkbox" name="notify_document_pending" value="1"
                       {{ $user->notify_document_pending ? 'checked' : '' }}
                       style="margin-top: 2px; flex-shrink: 0;">
                <div>
                    <p style="font-size: 13px; font-weight: 500; color: #111827; margin: 0 0 2px;">Documentos pendentes</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Alertas sobre PEI, PAEE e Estudo de Caso não preenchidos</p>
                </div>
            </label>

            @hasrole('secretaria')
            <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; margin-bottom: 16px;">
                <input type="checkbox" name="notify_plan_expiring" value="1"
                       {{ $user->notify_plan_expiring ? 'checked' : '' }}
                       style="margin-top: 2px; flex-shrink: 0;">
                <div>
                    <p style="font-size: 13px; font-weight: 500; color: #111827; margin: 0 0 2px;">Vencimento de plano</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Alertas quando o plano da escola estiver próximo do vencimento</p>
                </div>
            </label>
            @endhasrole

            <div style="border-top: 1px solid #F3F4F6; padding-top: 16px; margin-top: 4px;">
                <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 12px;">Preferências de documentos</p>
                <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                    <input type="checkbox" name="pdf_preview" value="1"
                           {{ $user->pdf_preview ? 'checked' : '' }}
                           style="margin-top: 2px; flex-shrink: 0;">
                    <div>
                        <p style="font-size: 13px; font-weight: 500; color: #111827; margin: 0 0 2px;">Preview antes de baixar PDF</p>
                        <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Exibe uma pré-visualização do documento em pop-up antes do download</p>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit"
                style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Salvar alterações
        </button>
    </form>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const el = document.getElementById('avatar-preview');
            if (!el) return;

            if (el.tagName === 'IMG') {
                // já era uma imagem, só atualiza o src
                el.src = e.target.result;
            } else {
                // era o div com inicial — substitui por uma img
                const img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = e.target.result;
                img.style = 'width: 64px; height: 64px; border-radius: 50%; object-fit: cover; flex-shrink: 0;';
                el.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection