<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — Suporte</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
</head>
<body style="background: #F8FAFC; font-family: sans-serif; color: #111827;">

<div style="max-width: 600px; margin: 0 auto; padding: 48px 24px;">
    <div style="margin-bottom: 32px;">
        <a href="{{ route('login') }}" style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>

    <h1 style="font-size: 28px; font-weight: 700; color: #004B8D; margin: 0 0 8px;">Suporte</h1>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0 0 32px;">Estamos aqui para ajudar. Entre em contato pelos canais abaixo.</p>

    <div style="display: flex; flex-direction: column; gap: 16px;">

        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 44px; height: 44px; background: #E8F0F9; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 4px;">E-mail</p>
                <a href="mailto:suporte@atrio.com.br" style="font-size: 14px; color: #004B8D; text-decoration: none;">suporte@atriosystem.com.br</a>
            </div>
        </div>

        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 44px; height: 44px; background: #E6F5F4; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#009C8C" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.22 1.18 2 2 0 012.18 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.72 6.72l1.28-1.28a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 4px;">WhatsApp</p>
                <a href="https://wa.me/5542988423965" target="_blank" style="font-size: 14px; color: #009C8C; text-decoration: none;">(42) 9 8842-3965</a>
            </div>
        </div>

        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 44px; height: 44px; background: #F5EDE6; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3700" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 4px;">Horário de atendimento</p>
                <p style="font-size: 14px; color: #374151; margin: 0;">Segunda a sexta, das 8h às 18h</p>
            </div>
        </div>

    </div>

    <p style="font-size: 12px; color: #9CA3AF; text-align: center; margin-top: 32px;">
        Sistema Átrio — Portal de Gestão Educacional Inclusiva
    </p>
</div>
</body>
</html>