@php
    /** @var string $titulo */
    $saudacao   = $saudacao   ?? null;
    $paragrafos = $paragrafos ?? [];
    $itens      = $itens      ?? [];
    $dados      = $dados      ?? [];
    $acaoTexto  = $acaoTexto  ?? null;
    $acaoUrl    = $acaoUrl    ?? null;
    $rodape     = $rodape     ?? 'Mensagem automática do Sistema Átrio.';
    $topo       = $topo       ?? '#004B8D';

    // Escapa e converte **negrito** com segurança.
    $fmt = function ($s) {
        return preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', e($s));
    };
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light only">
    <title>{{ $titulo }} — Átrio</title>
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
                        <td style="background:#ffffff; border-radius:12px; padding:0; box-shadow:0 1px 3px rgba(0,0,0,0.08); overflow:hidden;">
                            <div style="height:4px; background:{{ $topo }};"></div>
                            <div style="padding:32px;">
                                <h1 style="font-size:15px; font-weight:bold; color:{{ $topo }}; letter-spacing:1px; text-transform:uppercase; margin:0 0 18px;">{{ $titulo }}</h1>

                                @if($saudacao)
                                    <p style="font-size:14px; color:#374151; line-height:1.65; margin:0 0 14px;">{{ $saudacao }}</p>
                                @endif

                                @foreach($paragrafos as $p)
                                    <p style="font-size:14px; color:#374151; line-height:1.65; margin:0 0 14px;">{!! $fmt($p) !!}</p>
                                @endforeach

                                @if(!empty($itens))
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:4px 0 16px;">
                                        @foreach($itens as $it)
                                            <tr><td style="font-size:14px; color:#374151; line-height:1.6; padding:4px 0;">• {!! $fmt($it) !!}</td></tr>
                                        @endforeach
                                    </table>
                                @endif

                                @if(!empty($dados))
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:4px 0 16px; border:1px solid #E5E7EB; border-radius:8px;">
                                        @foreach($dados as $label => $valor)
                                            <tr>
                                                <td style="font-size:12px; color:#6B7280; padding:9px 12px; border-bottom:1px solid #F3F4F6; width:38%; vertical-align:top;">{{ $label }}</td>
                                                <td style="font-size:13px; color:#111827; padding:9px 12px; border-bottom:1px solid #F3F4F6;">{{ $valor }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif

                                @if($acaoTexto && $acaoUrl)
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:10px;">
                                        <tr>
                                            <td align="center">
                                                <a href="{{ $acaoUrl }}" target="_blank"
                                                   style="display:inline-block; background:#004B8D; color:#ffffff; text-decoration:none; font-size:13px; font-weight:bold; letter-spacing:2px; text-transform:uppercase; padding:14px 32px; border-radius:6px;">
                                                    {{ $acaoTexto }}
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                <hr style="border:none; border-top:1px solid #E5E7EB; margin:24px 0 16px;">
                                <p style="font-size:12px; color:#9CA3AF; line-height:1.6; margin:0;">{{ $rodape }}</p>
                            </div>
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
