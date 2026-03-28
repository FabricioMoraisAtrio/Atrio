<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — Esqueceu sua senha</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            const theme = localStorage.getItem('atrio-theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>
</head>
<body style="background: var(--bg-page, #F8FAFC); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: sans-serif;">

<div style="width: 100%; max-width: 400px; padding: 0 24px;">
    <div style="text-align: center; margin-bottom: 32px;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 52px; height: 52px; background: #004B8D; border-radius: 14px; margin-bottom: 16px;">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
            </svg>
        </div>
        <h1 style="font-size: 20px; font-weight: 700; color: #004B8D; letter-spacing: 2px; text-transform: uppercase; margin: 0;">Redefinir senha</h1>
        <p style="font-size: 13px; color: #9CA3AF; margin-top: 8px;">Informe seu e-mail para receber o link de redefinição.</p>
    </div>

    <div style="background: var(--bg-card, #fff); border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

        @if(session('success'))
            <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">E-mail institucional</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="exemplo@escola.edu.br"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <button type="submit"
                    style="width: 100%; background: #004B8D; color: white; border: none; padding: 14px; font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border-radius: 6px; cursor: pointer;">
                Enviar link
            </button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('login') }}" style="font-size: 13px; color: #9CA3AF; text-decoration: none;">
            ← Voltar para o login
        </a>
    </div>
</div>
</body>
</html>