<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $perfil = request('perfil');
        $perfilLabel = match($perfil) {
            'escola'      => 'Acesso — Escola',
            'responsavel' => 'Acesso — Responsável',
            default       => 'Acesso ao Sistema',
        };
        $perfilSub = match($perfil) {
            'escola'      => 'Secretaria, professores e administração',
            'responsavel' => 'Área do responsável',
            default       => 'Portal de Gestão Inclusiva',
        };
    @endphp
    <title>Átrio — {{ $perfilLabel }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: #F8FAFC; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: sans-serif;">

<div style="width: 100%; max-width: 400px; padding: 0 24px;">

    {{-- Logo --}}
    <div style="text-align: center; margin-bottom: 40px;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: #004B8D; border-radius: 14px; margin-bottom: 16px;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
            </svg>
        </div>
        <h1 style="font-size: 22px; font-weight: 800; color: #004B8D; letter-spacing: 3px; text-transform: uppercase; margin: 0;">
            SISTEMA ÁTRIO
        </h1>
        <p style="font-size: 11px; color: #9CA3AF; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px;">
            {{ $perfilSub }}
        </p>
        <a href="{{ route('home') }}" style="display: inline-block; margin-top: 10px; font-size: 11px; color: #9CA3AF; text-decoration: none; letter-spacing: 0.5px;">
            ← Voltar à página inicial
        </a>
    </div>

    {{-- Card --}}
    <div style="background: #fff; border-radius: 12px; padding: 36px; box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04);">

        @if($errors->any())
            <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                    E-mail Institucional
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="exemplo@escola.edu.br"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#004B8D'"
                       onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="margin-bottom: 8px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                    Senha
                </label>
                
                <input type="password" name="password"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#004B8D'"
                       onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="text-align: right; margin-bottom: 28px;">
    <a href="{{ route('password.request') }}" style="font-size: 11px; color: #9CA3AF; letter-spacing: 0.5px; text-transform: uppercase; text-decoration: none;">
        Esqueceu a senha?
    </a>
</div>

            <button type="submit"
                    style="width: 100%; background: #004B8D; color: white; border: none; padding: 14px; font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: 6px; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.background='#003366'"
                    onmouseout="this.style.background='#004B8D'">
                Acessar Sistema
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <div style="text-align: center; margin-top: 32px;">
    <div style="display: flex; justify-content: center; gap: 24px; margin-bottom: 12px;">
        <a href="{{ route('termos') }}" style="font-size: 11px; color: #9CA3AF; text-decoration: none; letter-spacing: 0.5px; text-transform: uppercase;">Termos</a>
        <a href="{{ route('privacidade') }}" style="font-size: 11px; color: #9CA3AF; text-decoration: none; letter-spacing: 0.5px; text-transform: uppercase;">Privacidade</a>
        <a href="{{ route('suporte') }}" style="font-size: 11px; color: #9CA3AF; text-decoration: none; letter-spacing: 0.5px; text-transform: uppercase;">Suporte</a>
    </div>
    <p style="font-size: 11px; color: #D1D5DB; letter-spacing: 1px; text-transform: uppercase;">
        © {{ date('Y') }} Sistema Átrio
    </p>
</div>
</div>

</body>
</html>