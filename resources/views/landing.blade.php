<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — Portal de Acesso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #F0F5FB; min-height: 100vh; font-family: sans-serif; }

        .landing-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        /* ── Logotipo ── */
        .logo-area { text-align: center; margin-bottom: 56px; }
        .logo-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 64px; height: 64px;
            background: #004B8D; border-radius: 18px;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(0,75,141,0.25);
        }
        .logo-title {
            font-size: 28px; font-weight: 800; color: #004B8D;
            letter-spacing: 4px; text-transform: uppercase;
        }
        .logo-sub {
            font-size: 11px; color: #94A3B8;
            letter-spacing: 2px; text-transform: uppercase;
            margin-top: 6px;
        }

        /* ── Grid de cards ── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            width: 100%;
            max-width: 560px;
        }

        @media (max-width: 480px) {
            .cards-grid { grid-template-columns: 1fr; }
        }

        .card {
            background: #fff;
            border: 1px solid #E2EAF4;
            border-radius: 16px;
            padding: 32px 20px;
            text-align: center;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            transition: transform 0.15s, box-shadow 0.15s, border-color 0.15s;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,75,141,0.12);
            border-color: #004B8D;
        }

        .card-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }

        .card-label {
            font-size: 15px; font-weight: 700; color: #0F172A;
            letter-spacing: 0.3px;
        }
        .card-desc {
            font-size: 12px; color: #94A3B8; line-height: 1.5;
        }
        .card-btn {
            margin-top: 4px;
            display: inline-block;
            padding: 9px 24px;
            border-radius: 8px;
            font-size: 12px; font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: #004B8D; color: #fff;
            text-decoration: none;
        }
        .card:hover .card-btn { background: #003870; }

        /* ── Rodapé ── */
        .footer {
            margin-top: 56px; text-align: center;
            font-size: 11px; color: #CBD5E1;
            letter-spacing: 1px; text-transform: uppercase;
        }
    </style>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
</head>
<body>

<div class="landing-wrap">

    {{-- Logo --}}
    <div class="logo-area">
        <div class="logo-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
            </svg>
        </div>
        <div class="logo-title">ÁTRIO</div>
        <div class="logo-sub">Sistema de Gestão Inclusiva</div>
    </div>

    {{-- Cards --}}
    <div class="cards-grid">

        {{-- Escola --}}
        <a href="{{ route('login') }}?perfil=escola" class="card">
            <div class="card-icon" style="background: #E8F0F9;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="1.8">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <div class="card-label">Escola</div>
            <div class="card-desc">Secretaria, professores e administração</div>
            <span class="card-btn">Acessar</span>
        </a>

        {{-- Responsável --}}
        <a href="{{ route('login') }}?perfil=responsavel" class="card">
            <div class="card-icon" style="background: #F3E8FF;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <div class="card-label">Responsável</div>
            <div class="card-desc">Acompanhe o desenvolvimento do seu filho</div>
            <span class="card-btn" style="background: #7C3AED;">Acessar</span>
        </a>

    </div>

    {{-- Footer --}}
    <div class="footer">© {{ date('Y') }} Sistema Átrio</div>

</div>

</body>
</html>
