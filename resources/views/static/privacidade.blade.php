<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — Política de Privacidade</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: #F8FAFC; font-family: sans-serif; color: #111827;">

<div style="max-width: 720px; margin: 0 auto; padding: 48px 24px;">
    <div style="margin-bottom: 32px;">
        <a href="{{ route('login') }}" style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar
        </a>
    </div>

    <h1 style="font-size: 28px; font-weight: 700; color: #004B8D; margin: 0 0 8px;">Política de Privacidade</h1>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0 0 32px;">Última atualização: {{ date('d/m/Y') }}</p>

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 32px; line-height: 1.8;">

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">1. Dados coletados</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">O Sistema Átrio coleta dados necessários para o funcionamento da plataforma, incluindo: nome, e-mail, informações de alunos cadastrados pelas instituições e registros de acesso a documentos (logs LGPD).</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">2. Finalidade do uso</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">Os dados coletados são utilizados exclusivamente para: prestação do serviço de gestão educacional, comunicação entre usuários da plataforma, envio de notificações relacionadas ao sistema e cumprimento de obrigações legais.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">3. Compartilhamento de dados</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">Não compartilhamos dados pessoais com terceiros, exceto quando necessário para a prestação do serviço (ex: provedores de infraestrutura) ou por obrigação legal.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">4. Segurança</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">Todos os acessos a documentos sensíveis são registrados em log (DocumentAccessLog) em conformidade com a LGPD. As senhas são armazenadas com criptografia bcrypt.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">5. Direitos do titular</h2>
        <p style="font-size: 14px; color: #374151; margin: 0 0 24px;">De acordo com a LGPD, você tem direito a: acessar seus dados, corrigir informações incorretas, solicitar a exclusão de dados e revogar consentimentos. Para exercer esses direitos, entre em contato pelo nosso <a href="{{ route('suporte') }}" style="color: #004B8D;">suporte</a>.</p>

        <h2 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px;">6. Retenção de dados</h2>
        <p style="font-size: 14px; color: #374151; margin: 0;">Os dados são mantidos enquanto a escola possuir contrato ativo com o Sistema Átrio. Após o encerramento do contrato, os dados são excluídos em até 90 dias.</p>
    </div>
</div>
</body>
</html>