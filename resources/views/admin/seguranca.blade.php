@extends('admin.layouts.app')
@section('title', 'Segurança')

@section('content')
<div style="max-width:640px;">

    @if(session('success'))
        <div style="background:var(--adm-green-bg); color:var(--adm-green); border:1px solid var(--adm-green); border-radius:10px; padding:11px 16px; margin-bottom:16px; font-size:14px;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:var(--adm-red-bg); color:var(--adm-red); border:1px solid var(--adm-red); border-radius:10px; padding:11px 16px; margin-bottom:16px; font-size:14px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="background:var(--adm-card); border:1px solid var(--adm-border); border-radius:14px; padding:24px;">
        <h2 style="font-size:16px; font-weight:700; color:var(--adm-text); margin:0 0 4px;">Verificação em duas etapas (2FA)</h2>

        @if($admin->hasTwoFactor())
            <p style="font-size:13px; color:var(--adm-green); font-weight:600; margin:0 0 14px;">● Ativada</p>
            <p style="font-size:14px; color:var(--adm-text-2); margin:0 0 18px;">
                A cada login no painel será exigido um código do seu app autenticador. Para desativar, confirme com um código atual.
            </p>
            <form method="POST" action="{{ route('admin.security.disable') }}" style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
                @csrf
                <div>
                    <label style="display:block; font-size:12px; color:var(--adm-text-3); margin-bottom:4px;">Código do app</label>
                    <input type="text" name="code" inputmode="numeric" maxlength="6" required placeholder="000000"
                           style="width:140px; padding:9px 12px; border:1px solid var(--adm-border-2); border-radius:9px; background:var(--adm-bg); color:var(--adm-text); text-align:center; letter-spacing:0.3em;">
                </div>
                <button type="submit" class="adm-btn adm-btn-ghost" style="color:var(--adm-red); border-color:var(--adm-red);">Desativar 2FA</button>
            </form>

        @else
            <p style="font-size:14px; color:var(--adm-text-2); margin:0 0 16px;">
                Proteja o acesso ao painel exigindo um código temporário do app autenticador (Google Authenticator, Authy, Microsoft Authenticator, etc.).
            </p>

            <ol style="font-size:14px; color:var(--adm-text-2); margin:0 0 16px; padding-left:20px; line-height:1.9;">
                <li>Abra seu app autenticador e escolha <b>adicionar conta → inserir chave manualmente</b>.</li>
                <li>Informe a conta <b>{{ $admin->email }}</b> e a chave abaixo.</li>
                <li>Digite o código de 6 dígitos gerado para confirmar.</li>
            </ol>

            <div style="background:var(--adm-bg); border:1px dashed var(--adm-border-2); border-radius:10px; padding:14px 16px; margin-bottom:14px;">
                <div style="font-size:12px; color:var(--adm-text-3); margin-bottom:6px;">Chave (digite no app)</div>
                <code style="font-size:18px; font-weight:700; letter-spacing:0.18em; color:var(--adm-text); word-break:break-all;">{{ trim(chunk_split($secret, 4, ' ')) }}</code>
            </div>

            <p style="font-size:13px; margin:0 0 18px;">
                <a href="{{ $uri }}" style="color:var(--adm-accent);">Abrir direto no app (no celular)</a>
            </p>

            <form method="POST" action="{{ route('admin.security.enable') }}" style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
                @csrf
                <div>
                    <label style="display:block; font-size:12px; color:var(--adm-text-3); margin-bottom:4px;">Código de confirmação</label>
                    <input type="text" name="code" inputmode="numeric" maxlength="6" autofocus required placeholder="000000"
                           style="width:140px; padding:9px 12px; border:1px solid var(--adm-border-2); border-radius:9px; background:var(--adm-bg); color:var(--adm-text); text-align:center; letter-spacing:0.3em;">
                </div>
                <button type="submit" class="adm-btn adm-btn-primary">Ativar 2FA</button>
            </form>
        @endif
    </div>
</div>
@endsection
