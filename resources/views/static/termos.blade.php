<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — Termos de Uso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
</head>
<body style="background: #F8FAFC; font-family: sans-serif; color: #111827;">

<div style="max-width: 720px; margin: 0 auto; padding: 48px 24px;">
    <div style="margin-bottom: 32px;">
        <a href="{{ route('home') }}" style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>

    <h1 style="font-size: 28px; font-weight: 700; color: #004B8D; margin: 0 0 8px;">Termos de Uso</h1>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0 0 32px;">Última atualização: {{ date('d/m/Y') }}</p>

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 32px; line-height: 1.8;">

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">1. Aceitação dos termos</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">Ao acessar e utilizar o Sistema Átrio, você concorda com estes Termos de Uso. Caso não concorde, não utilize o sistema.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">2. Descrição do serviço</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">O Sistema Átrio é uma plataforma de gestão educacional inclusiva destinada a escolas e instituições de ensino. O sistema permite o cadastro de estudantes, criação de documentos pedagógicos e comunicação entre equipes escolares.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">3. Uso responsável</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">O usuário é responsável por manter a confidencialidade de suas credenciais de acesso e por todas as atividades realizadas em sua conta. É proibido compartilhar senhas ou acessar contas de terceiros.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">4. Dados e privacidade</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">O tratamento de dados pessoais segue nossa <a href="{{ route('privacidade') }}" style="color: #004B8D;">Política de Privacidade</a> e está em conformidade com a Lei Geral de Proteção de Dados (LGPD — Lei nº 13.709/2018).</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">5. Contato</h2>
        <p style="font-size: 14px; color: #374151; margin: 0;">Dúvidas sobre estes termos? Entre em contato pela nossa <a href="{{ route('suporte') }}" style="color: #004B8D;">página de suporte</a>.</p>
    </div>
</div>
</body>
</html>