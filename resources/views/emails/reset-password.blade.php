<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light only">
    <title>Redefinição de senha — Átrio</title>
</head>
<body style="margin:0; padding:0; background:#F8FAFC; -webkit-text-size-adjust:100%;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFC; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px; font-family:Arial, Helvetica, sans-serif;">

                    <!-- Marca -->
                    <tr>
                        <td align="center" style="padding-bottom:24px;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" width="52" height="52" bgcolor="#004B8D" style="border-radius:14px; color:#ffffff; font-size:24px; font-weight:bold; line-height:52px;">&#x2B22;</td>
                                </tr>
                            </table>
                            <div style="font-size:22px; font-weight:bold; color:#004B8D; letter-spacing:3px; margin-top:14px;">ÁTRIO</div>
                            <div style="font-size:12px; color:#9CA3AF; margin-top:2px;">Gestão de Inclusão Escolar</div>
                        </td>
                    </tr>

                    <!-- Card -->
                    <tr>
                        <td style="background:#ffffff; border-radius:12px; padding:36px 32px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                            <h1 style="font-size:15px; font-weight:bold; color:#004B8D; letter-spacing:1px; text-transform:uppercase; margin:0 0 18px;">Redefinição de senha</h1>

                            <p style="font-size:14px; color:#374151; line-height:1.65; margin:0 0 14px;">Olá! Recebemos um pedido para redefinir a senha da sua conta no Átrio.</p>
                            <p style="font-size:14px; color:#374151; line-height:1.65; margin:0 0 26px;">Clique no botão abaixo para criar uma nova senha:</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" target="_blank"
                                           style="display:inline-block; background:#004B8D; color:#ffffff; text-decoration:none; font-size:13px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; padding:14px 36px; border-radius:6px;">
                                            Redefinir senha
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#6B7280; line-height:1.65; margin:26px 0 0;">
                                Este link expira em {{ $minutos }} minutos. Se você não solicitou a redefinição, ignore este e-mail — sua senha continua a mesma.
                            </p>

                            <hr style="border:none; border-top:1px solid #E5E7EB; margin:24px 0;">

                            <p style="font-size:12px; color:#9CA3AF; line-height:1.6; margin:0;">
                                Se o botão não funcionar, copie e cole este endereço no navegador:<br>
                                <a href="{{ $url }}" target="_blank" style="color:#004B8D; word-break:break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td align="center" style="padding-top:20px;">
                            <div style="font-size:11px; color:#9CA3AF; line-height:1.5;">&copy; {{ date('Y') }} Átrio System — Gestão de Inclusão Escolar</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
