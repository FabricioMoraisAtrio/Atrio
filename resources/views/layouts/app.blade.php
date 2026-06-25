<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ════════ TOKENS DE TEMA ════════
           Tudo no sistema usa estas variáveis. Cada tema redefine os tokens.
           O acento (--accent*) é injetado a partir da cor da escola mais abaixo. */
        :root {
            /* superfícies */
            --bg-page:   #EEF4FB;
            --bg-card:   #FFFFFF;
            --bg-subtle: #F0F6FD;
            --bg-hover:  #E2EDF8;
            /* bordas */
            --border:    #C8DDF0;
            --border-sub:#D8E9F5;
            /* texto */
            --text-1:    #111827;
            --text-2:    #374151;
            --text-3:    #6B7280;
            --text-4:    #9CA3AF;
            /* acento (sobrescrito pela cor da escola) */
            --accent:        #004B8D;
            --accent-text:   #004B8D;
            --accent-bg:     #E8F0F9;
            --accent-strong: #003366;
            --accent-contrast:#FFFFFF;
            /* status */
            --success:#065F46; --success-bg:#ECFDF5; --success-border:#6EE7B7;
            --danger: #991B1B; --danger-bg: #FEF2F2; --danger-border: #FECACA;
            --warning:#92400E; --warning-bg:#FFFBEB; --warning-border:#FDE68A;
            --info:   #1E40AF; --info-bg:   #EFF6FF; --info-border:   #BFDBFE;
            /* fundos sólidos de botão (texto branco) */
            --danger-solid:#DC2626; --success-solid:#059669;
            /* acentos de categoria (tipos de documento) */
            --teal:#009C8C;   --teal-bg:#E6F5F4;
            --brown:#7C3700;  --brown-bg:#F5EDE6;
            --purple:#6D28D9; --purple-bg:#F3E8FF;
        }

        /* ── ESCURO ── */
        [data-theme="dark"] {
            --bg-page:   #1C2B40;
            --bg-card:   #243352;
            --bg-subtle: #2A3B58;
            --bg-hover:  rgba(255,255,255,0.07);
            --border:    #384F6E;
            --border-sub:#2D4265;
            --text-1:    #F0F6FF;
            --text-2:    #C8D8EE;
            --text-3:    #8AAAC8;
            --text-4:    #6090B4;
            --accent:        #2F6FB5;
            --accent-text:   #7FB3F0;
            --accent-bg:     rgba(77,159,255,0.18);
            --accent-strong: #4D9FFF;
            --accent-contrast:#FFFFFF;
            --success:#6EDDB8; --success-bg:rgba(6,95,70,0.28);  --success-border:rgba(110,221,184,0.30);
            --danger: #FCA5A5; --danger-bg: rgba(153,27,27,0.28); --danger-border: rgba(252,165,165,0.30);
            --warning:#FCD34D; --warning-bg:rgba(146,64,14,0.25); --warning-border:rgba(253,230,138,0.22);
            --info:   #93C5FD; --info-bg:   rgba(59,130,246,0.14);--info-border:   rgba(59,130,246,0.35);
            --danger-solid:#E5484D; --success-solid:#10B981;
            --teal:#5EEAD4;   --teal-bg:rgba(0,156,140,0.16);
            --brown:#FCD34D;  --brown-bg:rgba(124,55,0,0.22);
            --purple:#C4B5FD; --purple-bg:rgba(109,40,217,0.20);
        }

        /* ── ESCURO SUAVE (slate) ── */
        [data-theme="slate"] {
            --bg-page:   #1E222B;
            --bg-card:   #272C37;
            --bg-subtle: #2E3440;
            --bg-hover:  rgba(255,255,255,0.06);
            --border:    #3B4250;
            --border-sub:#333A47;
            --text-1:    #ECEFF4;
            --text-2:    #C7CDD9;
            --text-3:    #9AA3B2;
            --text-4:    #6E7787;
            --accent:        #4A7FBE;
            --accent-text:   #93BBF5;
            --accent-bg:     rgba(108,168,255,0.16);
            --accent-strong: #6CA8FF;
            --accent-contrast:#FFFFFF;
            --success:#5FD1A8; --success-bg:rgba(15,122,82,0.24);  --success-border:rgba(95,209,168,0.28);
            --danger: #F4A6A0; --danger-bg: rgba(180,35,24,0.24);  --danger-border: rgba(244,166,160,0.28);
            --warning:#EAC15B; --warning-bg:rgba(154,103,0,0.24);  --warning-border:rgba(234,193,91,0.22);
            --info:   #8FB8F5; --info-bg:   rgba(29,78,216,0.16);  --info-border:   rgba(143,184,245,0.30);
            --danger-solid:#DB5A5A; --success-solid:#1DB47E;
            --teal:#52D7C4;   --teal-bg:rgba(0,156,140,0.16);
            --brown:#EAC15B;  --brown-bg:rgba(124,55,0,0.22);
            --purple:#BBA6F2; --purple-bg:rgba(109,40,217,0.20);
        }

        /* ── ALTO CONTRASTE ── */
        [data-theme="contrast"] {
            --bg-page:   #000000;
            --bg-card:   #0B0B0B;
            --bg-subtle: #161616;
            --bg-hover:  #222222;
            --border:    #5C5C5C;
            --border-sub:#444444;
            --text-1:    #FFFFFF;
            --text-2:    #ECECEC;
            --text-3:    #CFCFCF;
            --text-4:    #ABABAB;
            --accent:        #2E7FD6;
            --accent-text:   #A9D3FF;
            --accent-bg:     rgba(108,182,255,0.22);
            --accent-strong: #6CB6FF;
            --accent-contrast:#FFFFFF;
            --success:#5DF0A0; --success-bg:rgba(93,240,160,0.18); --success-border:rgba(93,240,160,0.45);
            --danger: #FF8585; --danger-bg: rgba(255,133,133,0.18); --danger-border: rgba(255,133,133,0.45);
            --warning:#FFE066; --warning-bg:rgba(255,224,102,0.16); --warning-border:rgba(255,224,102,0.45);
            --info:   #8CC8FF; --info-bg:   rgba(140,200,255,0.16); --info-border:   rgba(140,200,255,0.45);
            --danger-solid:#FF4D4D; --success-solid:#22C55E;
            --teal:#5EEAD4;   --teal-bg:rgba(94,234,212,0.16);
            --brown:#FFE066;  --brown-bg:rgba(255,224,102,0.16);
            --purple:#D6C2FF; --purple-bg:rgba(214,194,255,0.18);
        }

        /* ── BASE ── */
        body { background: var(--bg-page) !important; color: var(--text-1) !important; }
        aside { background: var(--bg-card) !important; border-color: var(--border) !important; }
        header { background: var(--bg-card) !important; border-color: var(--border) !important; }
        main { background: var(--bg-page) !important; }

        /* ── DARK: estrutura ── */
        [data-theme="dark"] aside  { background: #1A2740 !important; border-color: #304560 !important; }
        [data-theme="dark"] header { background: #1C2A44 !important; border-color: #304560 !important; }
        [data-theme="dark"] table  { border-color: #304560 !important; }
        [data-theme="dark"] thead tr { background: #1A2740 !important; }
        [data-theme="dark"] td, [data-theme="dark"] th { border-color: #304560 !important; }
        [data-theme="dark"] tbody tr { background: transparent !important; }
        [data-theme="dark"] tbody tr:hover td { background: rgba(96,165,250,0.06) !important; }

                        /* ════ REDE DE SEGURANÇA (token-based, todos os temas) ════
           Cobre cores fixas remanescentes em style="..." não migradas
           (ex.: junto de gradientes) e atributos stroke de SVG. */

        /* texto */
        [style*="color: #0D1F36"],[style*="color:#0D1F36"],[style*="color: #0F172A"],[style*="color:#0F172A"],[style*="color: #111827"],[style*="color:#111827"],[style*="color: #1F2937"],[style*="color:#1F2937"],[style*="color: #1a1a1a"],[style*="color:#1a1a1a"],[style*="color: #1A1A1A"],[style*="color:#1A1A1A"],[style*="color: #222"],[style*="color:#222"],[style*="color: #333"],[style*="color:#333"] { color: var(--text-1) !important; }
        [style*="color: #374151"],[style*="color:#374151"],[style*="color: #4B5563"],[style*="color:#4B5563"],[style*="color: #2C4A6E"],[style*="color:#2C4A6E"],[style*="color: #3e3e3a"],[style*="color:#3e3e3a"],[style*="color: #444"],[style*="color:#444"],[style*="color: #555"],[style*="color:#555"] { color: var(--text-2) !important; }
        [style*="color: #6B7280"],[style*="color:#6B7280"],[style*="color: #5A7FA8"],[style*="color:#5A7FA8"],[style*="color: #666"],[style*="color:#666"] { color: var(--text-3) !important; }
        [style*="color: #9CA3AF"],[style*="color:#9CA3AF"],[style*="color: #8EB3D4"],[style*="color:#8EB3D4"],[style*="color: #888"],[style*="color:#888"],[style*="color: #D1D5DB"],[style*="color:#D1D5DB"],[style*="color: #aaa"],[style*="color:#aaa"],[style*="color: #bbb"],[style*="color:#bbb"],[style*="color: #ccc"],[style*="color:#ccc"],[style*="color: #cbcbcb"],[style*="color:#cbcbcb"] { color: var(--text-4) !important; }
        [style*="color: #004B8D"],[style*="color:#004B8D"] { color: var(--accent-text) !important; }
        [style*="color: #003366"],[style*="color:#003366"] { color: var(--accent-strong) !important; }
        [style*="color: #065F46"],[style*="color:#065F46"],[style*="color: #059669"],[style*="color:#059669"],[style*="color: #166534"],[style*="color:#166534"],[style*="color: #3D7A27"],[style*="color:#3D7A27"],[style*="color: #10B981"],[style*="color:#10B981"],[style*="color: #047857"],[style*="color:#047857"],[style*="color: #15803D"],[style*="color:#15803D"],[style*="color: #16a34a"],[style*="color:#16a34a"] { color: var(--success) !important; }
        [style*="color: #991B1B"],[style*="color:#991B1B"],[style*="color: #DC2626"],[style*="color:#DC2626"],[style*="color: #EF4444"],[style*="color:#EF4444"],[style*="color: #B42318"],[style*="color:#B42318"],[style*="color: #B91C1C"],[style*="color:#B91C1C"],[style*="color: #e11d48"],[style*="color:#e11d48"] { color: var(--danger) !important; }
        [style*="color: #92400E"],[style*="color:#92400E"],[style*="color: #B45309"],[style*="color:#B45309"],[style*="color: #C77A00"],[style*="color:#C77A00"],[style*="color: #9A6700"],[style*="color:#9A6700"],[style*="color: #f59e0b"],[style*="color:#f59e0b"],[style*="color: #9a3412"],[style*="color:#9a3412"],[style*="color: #D97706"],[style*="color:#D97706"],[style*="color: #A16207"],[style*="color:#A16207"] { color: var(--warning) !important; }
        [style*="color: #1E40AF"],[style*="color:#1E40AF"],[style*="color: #1D4ED8"],[style*="color:#1D4ED8"],[style*="color: #3B5BDB"],[style*="color:#3B5BDB"],[style*="color: #2563EB"],[style*="color:#2563EB"],[style*="color: #1E3A8A"],[style*="color:#1E3A8A"] { color: var(--info) !important; }
        [style*="color: #009C8C"],[style*="color:#009C8C"],[style*="color: #007a6e"],[style*="color:#007a6e"],[style*="color: #0d9488"],[style*="color:#0d9488"],[style*="color: #0F766E"],[style*="color:#0F766E"],[style*="color: #60CFCA"],[style*="color:#60CFCA"] { color: var(--teal) !important; }
        [style*="color: #7C3700"],[style*="color:#7C3700"],[style*="color: #8B4513"],[style*="color:#8B4513"] { color: var(--brown) !important; }
        [style*="color: #6D28D9"],[style*="color:#6D28D9"],[style*="color: #7C3AED"],[style*="color:#7C3AED"],[style*="color: #7E22CE"],[style*="color:#7E22CE"],[style*="color: #5B21B6"],[style*="color:#5B21B6"],[style*="color: #8B5CF6"],[style*="color:#8B5CF6"],[style*="color: #9333EA"],[style*="color:#9333EA"] { color: var(--purple) !important; }

        /* fundo */
        [style*="background: #fff"],[style*="background:#fff"],[style*="background: white"],[style*="background:white"],[style*="background: #FFFFFF"],[style*="background:#FFFFFF"],[style*="background: #ffffff"],[style*="background:#ffffff"] { background: var(--bg-card) !important; }
        [style*="background: #F8FAFC"],[style*="background:#F8FAFC"],[style*="background: #F9FAFB"],[style*="background:#F9FAFB"],[style*="background: #FAFAFA"],[style*="background:#FAFAFA"],[style*="background: #fafafa"],[style*="background:#fafafa"],[style*="background: #F3F4F6"],[style*="background:#F3F4F6"],[style*="background: #F0F5FB"],[style*="background:#F0F5FB"],[style*="background: #F0F6FD"],[style*="background:#F0F6FD"],[style*="background: #F8FAFF"],[style*="background:#F8FAFF"],[style*="background: #FAFBFC"],[style*="background:#FAFBFC"],[style*="background: #F4F8FD"],[style*="background:#F4F8FD"] { background: var(--bg-subtle) !important; }
        [style*="background: #EEF4FB"],[style*="background:#EEF4FB"],[style*="background: #F0F4F8"],[style*="background:#F0F4F8"] { background: var(--bg-page) !important; }
        [style*="background: #E2EDF8"],[style*="background:#E2EDF8"],[style*="background: #F1F5F9"],[style*="background:#F1F5F9"],[style*="background: #E2EAF4"],[style*="background:#E2EAF4"] { background: var(--bg-hover) !important; }
        [style*="background: #E8F0F9"],[style*="background:#E8F0F9"],[style*="background: #D6E8F8"],[style*="background:#D6E8F8"],[style*="background: #EAF2FB"],[style*="background:#EAF2FB"] { background: var(--accent-bg) !important; }
        [style*="background: #004B8D"],[style*="background:#004B8D"] { background: var(--accent) !important; }
        [style*="background: #ECFDF5"],[style*="background:#ECFDF5"],[style*="background: #D1FAE5"],[style*="background:#D1FAE5"],[style*="background: #F0FDF4"],[style*="background:#F0FDF4"],[style*="background: #DCFCE7"],[style*="background:#DCFCE7"],[style*="background: #EFF6E8"],[style*="background:#EFF6E8"] { background: var(--success-bg) !important; }
        [style*="background: #FEF2F2"],[style*="background:#FEF2F2"],[style*="background: #FEE2E2"],[style*="background:#FEE2E2"] { background: var(--danger-bg) !important; }
        [style*="background: #FFFBEB"],[style*="background:#FFFBEB"],[style*="background: #FEF3C7"],[style*="background:#FEF3C7"],[style*="background: #FFF4E6"],[style*="background:#FFF4E6"],[style*="background: #FEFCE8"],[style*="background:#FEFCE8"],[style*="background: #FFF8E1"],[style*="background:#FFF8E1"] { background: var(--warning-bg) !important; }
        [style*="background: #EFF6FF"],[style*="background:#EFF6FF"],[style*="background: #DBEAFE"],[style*="background:#DBEAFE"],[style*="background: #F0F5FF"],[style*="background:#F0F5FF"],[style*="background: #E8F0FE"],[style*="background:#E8F0FE"] { background: var(--info-bg) !important; }
        [style*="background: #E6F5F4"],[style*="background:#E6F5F4"],[style*="background: #F0FDFB"],[style*="background:#F0FDFB"],[style*="background: #E8FAF7"],[style*="background:#E8FAF7"] { background: var(--teal-bg) !important; }
        [style*="background: #F5EDE6"],[style*="background:#F5EDE6"],[style*="background: #FFF7ED"],[style*="background:#FFF7ED"] { background: var(--brown-bg) !important; }
        [style*="background: #F3E8FF"],[style*="background:#F3E8FF"],[style*="background: #EDE9FE"],[style*="background:#EDE9FE"],[style*="background: #F5F3FF"],[style*="background:#F5F3FF"],[style*="background: #F0EBF8"],[style*="background:#F0EBF8"],[style*="background: #FAFAFF"],[style*="background:#FAFAFF"],[style*="background: #fdf4ff"],[style*="background:#fdf4ff"] { background: var(--purple-bg) !important; }
        [style*="background: #EF4444"],[style*="background:#EF4444"],[style*="background: #DC2626"],[style*="background:#DC2626"],[style*="background: #DC3545"],[style*="background:#DC3545"] { background: var(--danger-solid) !important; }
        [style*="background: #10B981"],[style*="background:#10B981"],[style*="background: #28A745"],[style*="background:#28A745"] { background: var(--success-solid) !important; }

        /* bordas — formas realmente usadas no sistema */
        [style*="border:1px solid #E5E7EB"],[style*="border: 1px solid #E5E7EB"],[style*="border:2px solid #E5E7EB"],[style*="border: 2px solid #E5E7EB"],[style*="border-bottom:1px solid #E5E7EB"],[style*="border-bottom: 1px solid #E5E7EB"],
        [style*="border-bottom:2px solid #E5E7EB"],[style*="border-bottom: 2px solid #E5E7EB"],[style*="border-top:1px solid #E5E7EB"],[style*="border-top: 1px solid #E5E7EB"],[style*="border-left:2px solid #E5E7EB"],[style*="border-left: 2px solid #E5E7EB"],
        [style*="border-right:1px solid #E5E7EB"],[style*="border-right: 1px solid #E5E7EB"],[style*="border-color:#E5E7EB"],[style*="border-color: #E5E7EB"],[style*="border:1px solid #D1D5DB"],[style*="border: 1px solid #D1D5DB"],
        [style*="border:2px solid #D1D5DB"],[style*="border: 2px solid #D1D5DB"],[style*="border-bottom:1px solid #D1D5DB"],[style*="border-bottom: 1px solid #D1D5DB"],[style*="border-bottom:2px solid #D1D5DB"],[style*="border-bottom: 2px solid #D1D5DB"],
        [style*="border-top:1px solid #D1D5DB"],[style*="border-top: 1px solid #D1D5DB"],[style*="border-left:2px solid #D1D5DB"],[style*="border-left: 2px solid #D1D5DB"],[style*="border-right:1px solid #D1D5DB"],[style*="border-right: 1px solid #D1D5DB"],
        [style*="border-color:#D1D5DB"],[style*="border-color: #D1D5DB"],[style*="border:1px solid #C8DDF0"],[style*="border: 1px solid #C8DDF0"],[style*="border:2px solid #C8DDF0"],[style*="border: 2px solid #C8DDF0"],
        [style*="border-bottom:1px solid #C8DDF0"],[style*="border-bottom: 1px solid #C8DDF0"],[style*="border-bottom:2px solid #C8DDF0"],[style*="border-bottom: 2px solid #C8DDF0"],[style*="border-top:1px solid #C8DDF0"],[style*="border-top: 1px solid #C8DDF0"],
        [style*="border-left:2px solid #C8DDF0"],[style*="border-left: 2px solid #C8DDF0"],[style*="border-right:1px solid #C8DDF0"],[style*="border-right: 1px solid #C8DDF0"],[style*="border-color:#C8DDF0"],[style*="border-color: #C8DDF0"],
        [style*="border:1px solid #E2EAF4"],[style*="border: 1px solid #E2EAF4"],[style*="border:2px solid #E2EAF4"],[style*="border: 2px solid #E2EAF4"],[style*="border-bottom:1px solid #E2EAF4"],[style*="border-bottom: 1px solid #E2EAF4"],
        [style*="border-bottom:2px solid #E2EAF4"],[style*="border-bottom: 2px solid #E2EAF4"],[style*="border-top:1px solid #E2EAF4"],[style*="border-top: 1px solid #E2EAF4"],[style*="border-left:2px solid #E2EAF4"],[style*="border-left: 2px solid #E2EAF4"],
        [style*="border-right:1px solid #E2EAF4"],[style*="border-right: 1px solid #E2EAF4"],[style*="border-color:#E2EAF4"],[style*="border-color: #E2EAF4"],[style*="border:1px solid #ddd"],[style*="border: 1px solid #ddd"],
        [style*="border:2px solid #ddd"],[style*="border: 2px solid #ddd"],[style*="border-bottom:1px solid #ddd"],[style*="border-bottom: 1px solid #ddd"],[style*="border-bottom:2px solid #ddd"],[style*="border-bottom: 2px solid #ddd"],
        [style*="border-top:1px solid #ddd"],[style*="border-top: 1px solid #ddd"],[style*="border-left:2px solid #ddd"],[style*="border-left: 2px solid #ddd"],[style*="border-right:1px solid #ddd"],[style*="border-right: 1px solid #ddd"],
        [style*="border-color:#ddd"],[style*="border-color: #ddd"],[style*="border:1px solid #dddddd"],[style*="border: 1px solid #dddddd"],[style*="border:2px solid #dddddd"],[style*="border: 2px solid #dddddd"],
        [style*="border-bottom:1px solid #dddddd"],[style*="border-bottom: 1px solid #dddddd"],[style*="border-bottom:2px solid #dddddd"],[style*="border-bottom: 2px solid #dddddd"],[style*="border-top:1px solid #dddddd"],[style*="border-top: 1px solid #dddddd"],
        [style*="border-left:2px solid #dddddd"],[style*="border-left: 2px solid #dddddd"],[style*="border-right:1px solid #dddddd"],[style*="border-right: 1px solid #dddddd"],[style*="border-color:#dddddd"],[style*="border-color: #dddddd"],
        [style*="border:1px solid #E0E0E0"],[style*="border: 1px solid #E0E0E0"],[style*="border:2px solid #E0E0E0"],[style*="border: 2px solid #E0E0E0"],[style*="border-bottom:1px solid #E0E0E0"],[style*="border-bottom: 1px solid #E0E0E0"],
        [style*="border-bottom:2px solid #E0E0E0"],[style*="border-bottom: 2px solid #E0E0E0"],[style*="border-top:1px solid #E0E0E0"],[style*="border-top: 1px solid #E0E0E0"],[style*="border-left:2px solid #E0E0E0"],[style*="border-left: 2px solid #E0E0E0"],
        [style*="border-right:1px solid #E0E0E0"],[style*="border-right: 1px solid #E0E0E0"],[style*="border-color:#E0E0E0"],[style*="border-color: #E0E0E0"],[style*="border:1px solid #C5D8F0"],[style*="border: 1px solid #C5D8F0"],
        [style*="border:2px solid #C5D8F0"],[style*="border: 2px solid #C5D8F0"],[style*="border-bottom:1px solid #C5D8F0"],[style*="border-bottom: 1px solid #C5D8F0"],[style*="border-bottom:2px solid #C5D8F0"],[style*="border-bottom: 2px solid #C5D8F0"],
        [style*="border-top:1px solid #C5D8F0"],[style*="border-top: 1px solid #C5D8F0"],[style*="border-left:2px solid #C5D8F0"],[style*="border-left: 2px solid #C5D8F0"],[style*="border-right:1px solid #C5D8F0"],[style*="border-right: 1px solid #C5D8F0"],
        [style*="border-color:#C5D8F0"],[style*="border-color: #C5D8F0"] { border-color: var(--border) !important; }
        [style*="border:1px solid #F3F4F6"],[style*="border: 1px solid #F3F4F6"],[style*="border:2px solid #F3F4F6"],[style*="border: 2px solid #F3F4F6"],[style*="border-bottom:1px solid #F3F4F6"],[style*="border-bottom: 1px solid #F3F4F6"],
        [style*="border-bottom:2px solid #F3F4F6"],[style*="border-bottom: 2px solid #F3F4F6"],[style*="border-top:1px solid #F3F4F6"],[style*="border-top: 1px solid #F3F4F6"],[style*="border-left:2px solid #F3F4F6"],[style*="border-left: 2px solid #F3F4F6"],
        [style*="border-right:1px solid #F3F4F6"],[style*="border-right: 1px solid #F3F4F6"],[style*="border-color:#F3F4F6"],[style*="border-color: #F3F4F6"],[style*="border:1px solid #F9FAFB"],[style*="border: 1px solid #F9FAFB"],
        [style*="border:2px solid #F9FAFB"],[style*="border: 2px solid #F9FAFB"],[style*="border-bottom:1px solid #F9FAFB"],[style*="border-bottom: 1px solid #F9FAFB"],[style*="border-bottom:2px solid #F9FAFB"],[style*="border-bottom: 2px solid #F9FAFB"],
        [style*="border-top:1px solid #F9FAFB"],[style*="border-top: 1px solid #F9FAFB"],[style*="border-left:2px solid #F9FAFB"],[style*="border-left: 2px solid #F9FAFB"],[style*="border-right:1px solid #F9FAFB"],[style*="border-right: 1px solid #F9FAFB"],
        [style*="border-color:#F9FAFB"],[style*="border-color: #F9FAFB"],[style*="border:1px solid #eee"],[style*="border: 1px solid #eee"],[style*="border:2px solid #eee"],[style*="border: 2px solid #eee"],
        [style*="border-bottom:1px solid #eee"],[style*="border-bottom: 1px solid #eee"],[style*="border-bottom:2px solid #eee"],[style*="border-bottom: 2px solid #eee"],[style*="border-top:1px solid #eee"],[style*="border-top: 1px solid #eee"],
        [style*="border-left:2px solid #eee"],[style*="border-left: 2px solid #eee"],[style*="border-right:1px solid #eee"],[style*="border-right: 1px solid #eee"],[style*="border-color:#eee"],[style*="border-color: #eee"],
        [style*="border:1px solid #eeeeee"],[style*="border: 1px solid #eeeeee"],[style*="border:2px solid #eeeeee"],[style*="border: 2px solid #eeeeee"],[style*="border-bottom:1px solid #eeeeee"],[style*="border-bottom: 1px solid #eeeeee"],
        [style*="border-bottom:2px solid #eeeeee"],[style*="border-bottom: 2px solid #eeeeee"],[style*="border-top:1px solid #eeeeee"],[style*="border-top: 1px solid #eeeeee"],[style*="border-left:2px solid #eeeeee"],[style*="border-left: 2px solid #eeeeee"],
        [style*="border-right:1px solid #eeeeee"],[style*="border-right: 1px solid #eeeeee"],[style*="border-color:#eeeeee"],[style*="border-color: #eeeeee"],[style*="border:1px solid #eaeaea"],[style*="border: 1px solid #eaeaea"],
        [style*="border:2px solid #eaeaea"],[style*="border: 2px solid #eaeaea"],[style*="border-bottom:1px solid #eaeaea"],[style*="border-bottom: 1px solid #eaeaea"],[style*="border-bottom:2px solid #eaeaea"],[style*="border-bottom: 2px solid #eaeaea"],
        [style*="border-top:1px solid #eaeaea"],[style*="border-top: 1px solid #eaeaea"],[style*="border-left:2px solid #eaeaea"],[style*="border-left: 2px solid #eaeaea"],[style*="border-right:1px solid #eaeaea"],[style*="border-right: 1px solid #eaeaea"],
        [style*="border-color:#eaeaea"],[style*="border-color: #eaeaea"],[style*="border:1px solid #D8E9F5"],[style*="border: 1px solid #D8E9F5"],[style*="border:2px solid #D8E9F5"],[style*="border: 2px solid #D8E9F5"],
        [style*="border-bottom:1px solid #D8E9F5"],[style*="border-bottom: 1px solid #D8E9F5"],[style*="border-bottom:2px solid #D8E9F5"],[style*="border-bottom: 2px solid #D8E9F5"],[style*="border-top:1px solid #D8E9F5"],[style*="border-top: 1px solid #D8E9F5"],
        [style*="border-left:2px solid #D8E9F5"],[style*="border-left: 2px solid #D8E9F5"],[style*="border-right:1px solid #D8E9F5"],[style*="border-right: 1px solid #D8E9F5"],[style*="border-color:#D8E9F5"],[style*="border-color: #D8E9F5"],
        [style*="border:1px solid #F0F0F0"],[style*="border: 1px solid #F0F0F0"],[style*="border:2px solid #F0F0F0"],[style*="border: 2px solid #F0F0F0"],[style*="border-bottom:1px solid #F0F0F0"],[style*="border-bottom: 1px solid #F0F0F0"],
        [style*="border-bottom:2px solid #F0F0F0"],[style*="border-bottom: 2px solid #F0F0F0"],[style*="border-top:1px solid #F0F0F0"],[style*="border-top: 1px solid #F0F0F0"],[style*="border-left:2px solid #F0F0F0"],[style*="border-left: 2px solid #F0F0F0"],
        [style*="border-right:1px solid #F0F0F0"],[style*="border-right: 1px solid #F0F0F0"],[style*="border-color:#F0F0F0"],[style*="border-color: #F0F0F0"] { border-color: var(--border-sub) !important; }
        [style*="border:1px solid #004B8D"],[style*="border: 1px solid #004B8D"],[style*="border:2px solid #004B8D"],[style*="border: 2px solid #004B8D"],[style*="border-bottom:1px solid #004B8D"],[style*="border-bottom: 1px solid #004B8D"],
        [style*="border-bottom:2px solid #004B8D"],[style*="border-bottom: 2px solid #004B8D"],[style*="border-top:1px solid #004B8D"],[style*="border-top: 1px solid #004B8D"],[style*="border-left:2px solid #004B8D"],[style*="border-left: 2px solid #004B8D"],
        [style*="border-right:1px solid #004B8D"],[style*="border-right: 1px solid #004B8D"],[style*="border-color:#004B8D"],[style*="border-color: #004B8D"] { border-color: var(--accent) !important; }
        [style*="border:1px solid #6EE7B7"],[style*="border: 1px solid #6EE7B7"],[style*="border:2px solid #6EE7B7"],[style*="border: 2px solid #6EE7B7"],[style*="border-bottom:1px solid #6EE7B7"],[style*="border-bottom: 1px solid #6EE7B7"],
        [style*="border-bottom:2px solid #6EE7B7"],[style*="border-bottom: 2px solid #6EE7B7"],[style*="border-top:1px solid #6EE7B7"],[style*="border-top: 1px solid #6EE7B7"],[style*="border-left:2px solid #6EE7B7"],[style*="border-left: 2px solid #6EE7B7"],
        [style*="border-right:1px solid #6EE7B7"],[style*="border-right: 1px solid #6EE7B7"],[style*="border-color:#6EE7B7"],[style*="border-color: #6EE7B7"],[style*="border:1px solid #A7F3D0"],[style*="border: 1px solid #A7F3D0"],
        [style*="border:2px solid #A7F3D0"],[style*="border: 2px solid #A7F3D0"],[style*="border-bottom:1px solid #A7F3D0"],[style*="border-bottom: 1px solid #A7F3D0"],[style*="border-bottom:2px solid #A7F3D0"],[style*="border-bottom: 2px solid #A7F3D0"],
        [style*="border-top:1px solid #A7F3D0"],[style*="border-top: 1px solid #A7F3D0"],[style*="border-left:2px solid #A7F3D0"],[style*="border-left: 2px solid #A7F3D0"],[style*="border-right:1px solid #A7F3D0"],[style*="border-right: 1px solid #A7F3D0"],
        [style*="border-color:#A7F3D0"],[style*="border-color: #A7F3D0"],[style*="border:1px solid #86EFAC"],[style*="border: 1px solid #86EFAC"],[style*="border:2px solid #86EFAC"],[style*="border: 2px solid #86EFAC"],
        [style*="border-bottom:1px solid #86EFAC"],[style*="border-bottom: 1px solid #86EFAC"],[style*="border-bottom:2px solid #86EFAC"],[style*="border-bottom: 2px solid #86EFAC"],[style*="border-top:1px solid #86EFAC"],[style*="border-top: 1px solid #86EFAC"],
        [style*="border-left:2px solid #86EFAC"],[style*="border-left: 2px solid #86EFAC"],[style*="border-right:1px solid #86EFAC"],[style*="border-right: 1px solid #86EFAC"],[style*="border-color:#86EFAC"],[style*="border-color: #86EFAC"],
        [style*="border:1px solid #BBF7D0"],[style*="border: 1px solid #BBF7D0"],[style*="border:2px solid #BBF7D0"],[style*="border: 2px solid #BBF7D0"],[style*="border-bottom:1px solid #BBF7D0"],[style*="border-bottom: 1px solid #BBF7D0"],
        [style*="border-bottom:2px solid #BBF7D0"],[style*="border-bottom: 2px solid #BBF7D0"],[style*="border-top:1px solid #BBF7D0"],[style*="border-top: 1px solid #BBF7D0"],[style*="border-left:2px solid #BBF7D0"],[style*="border-left: 2px solid #BBF7D0"],
        [style*="border-right:1px solid #BBF7D0"],[style*="border-right: 1px solid #BBF7D0"],[style*="border-color:#BBF7D0"],[style*="border-color: #BBF7D0"] { border-color: var(--success-border) !important; }
        [style*="border:1px solid #FECACA"],[style*="border: 1px solid #FECACA"],[style*="border:2px solid #FECACA"],[style*="border: 2px solid #FECACA"],[style*="border-bottom:1px solid #FECACA"],[style*="border-bottom: 1px solid #FECACA"],
        [style*="border-bottom:2px solid #FECACA"],[style*="border-bottom: 2px solid #FECACA"],[style*="border-top:1px solid #FECACA"],[style*="border-top: 1px solid #FECACA"],[style*="border-left:2px solid #FECACA"],[style*="border-left: 2px solid #FECACA"],
        [style*="border-right:1px solid #FECACA"],[style*="border-right: 1px solid #FECACA"],[style*="border-color:#FECACA"],[style*="border-color: #FECACA"],[style*="border:1px solid #FCA5A5"],[style*="border: 1px solid #FCA5A5"],
        [style*="border:2px solid #FCA5A5"],[style*="border: 2px solid #FCA5A5"],[style*="border-bottom:1px solid #FCA5A5"],[style*="border-bottom: 1px solid #FCA5A5"],[style*="border-bottom:2px solid #FCA5A5"],[style*="border-bottom: 2px solid #FCA5A5"],
        [style*="border-top:1px solid #FCA5A5"],[style*="border-top: 1px solid #FCA5A5"],[style*="border-left:2px solid #FCA5A5"],[style*="border-left: 2px solid #FCA5A5"],[style*="border-right:1px solid #FCA5A5"],[style*="border-right: 1px solid #FCA5A5"],
        [style*="border-color:#FCA5A5"],[style*="border-color: #FCA5A5"],[style*="border:1px solid #FEE2E2"],[style*="border: 1px solid #FEE2E2"],[style*="border:2px solid #FEE2E2"],[style*="border: 2px solid #FEE2E2"],
        [style*="border-bottom:1px solid #FEE2E2"],[style*="border-bottom: 1px solid #FEE2E2"],[style*="border-bottom:2px solid #FEE2E2"],[style*="border-bottom: 2px solid #FEE2E2"],[style*="border-top:1px solid #FEE2E2"],[style*="border-top: 1px solid #FEE2E2"],
        [style*="border-left:2px solid #FEE2E2"],[style*="border-left: 2px solid #FEE2E2"],[style*="border-right:1px solid #FEE2E2"],[style*="border-right: 1px solid #FEE2E2"],[style*="border-color:#FEE2E2"],[style*="border-color: #FEE2E2"] { border-color: var(--danger-border) !important; }
        [style*="border:1px solid #FDE68A"],[style*="border: 1px solid #FDE68A"],[style*="border:2px solid #FDE68A"],[style*="border: 2px solid #FDE68A"],[style*="border-bottom:1px solid #FDE68A"],[style*="border-bottom: 1px solid #FDE68A"],
        [style*="border-bottom:2px solid #FDE68A"],[style*="border-bottom: 2px solid #FDE68A"],[style*="border-top:1px solid #FDE68A"],[style*="border-top: 1px solid #FDE68A"],[style*="border-left:2px solid #FDE68A"],[style*="border-left: 2px solid #FDE68A"],
        [style*="border-right:1px solid #FDE68A"],[style*="border-right: 1px solid #FDE68A"],[style*="border-color:#FDE68A"],[style*="border-color: #FDE68A"],[style*="border:1px solid #FCD34D"],[style*="border: 1px solid #FCD34D"],
        [style*="border:2px solid #FCD34D"],[style*="border: 2px solid #FCD34D"],[style*="border-bottom:1px solid #FCD34D"],[style*="border-bottom: 1px solid #FCD34D"],[style*="border-bottom:2px solid #FCD34D"],[style*="border-bottom: 2px solid #FCD34D"],
        [style*="border-top:1px solid #FCD34D"],[style*="border-top: 1px solid #FCD34D"],[style*="border-left:2px solid #FCD34D"],[style*="border-left: 2px solid #FCD34D"],[style*="border-right:1px solid #FCD34D"],[style*="border-right: 1px solid #FCD34D"],
        [style*="border-color:#FCD34D"],[style*="border-color: #FCD34D"],[style*="border:1px solid #FEF08A"],[style*="border: 1px solid #FEF08A"],[style*="border:2px solid #FEF08A"],[style*="border: 2px solid #FEF08A"],
        [style*="border-bottom:1px solid #FEF08A"],[style*="border-bottom: 1px solid #FEF08A"],[style*="border-bottom:2px solid #FEF08A"],[style*="border-bottom: 2px solid #FEF08A"],[style*="border-top:1px solid #FEF08A"],[style*="border-top: 1px solid #FEF08A"],
        [style*="border-left:2px solid #FEF08A"],[style*="border-left: 2px solid #FEF08A"],[style*="border-right:1px solid #FEF08A"],[style*="border-right: 1px solid #FEF08A"],[style*="border-color:#FEF08A"],[style*="border-color: #FEF08A"],
        [style*="border:1px solid #FED7AA"],[style*="border: 1px solid #FED7AA"],[style*="border:2px solid #FED7AA"],[style*="border: 2px solid #FED7AA"],[style*="border-bottom:1px solid #FED7AA"],[style*="border-bottom: 1px solid #FED7AA"],
        [style*="border-bottom:2px solid #FED7AA"],[style*="border-bottom: 2px solid #FED7AA"],[style*="border-top:1px solid #FED7AA"],[style*="border-top: 1px solid #FED7AA"],[style*="border-left:2px solid #FED7AA"],[style*="border-left: 2px solid #FED7AA"],
        [style*="border-right:1px solid #FED7AA"],[style*="border-right: 1px solid #FED7AA"],[style*="border-color:#FED7AA"],[style*="border-color: #FED7AA"] { border-color: var(--warning-border) !important; }
        [style*="border:1px solid #BFDBFE"],[style*="border: 1px solid #BFDBFE"],[style*="border:2px solid #BFDBFE"],[style*="border: 2px solid #BFDBFE"],[style*="border-bottom:1px solid #BFDBFE"],[style*="border-bottom: 1px solid #BFDBFE"],
        [style*="border-bottom:2px solid #BFDBFE"],[style*="border-bottom: 2px solid #BFDBFE"],[style*="border-top:1px solid #BFDBFE"],[style*="border-top: 1px solid #BFDBFE"],[style*="border-left:2px solid #BFDBFE"],[style*="border-left: 2px solid #BFDBFE"],
        [style*="border-right:1px solid #BFDBFE"],[style*="border-right: 1px solid #BFDBFE"],[style*="border-color:#BFDBFE"],[style*="border-color: #BFDBFE"],[style*="border:1px solid #93C5FD"],[style*="border: 1px solid #93C5FD"],
        [style*="border:2px solid #93C5FD"],[style*="border: 2px solid #93C5FD"],[style*="border-bottom:1px solid #93C5FD"],[style*="border-bottom: 1px solid #93C5FD"],[style*="border-bottom:2px solid #93C5FD"],[style*="border-bottom: 2px solid #93C5FD"],
        [style*="border-top:1px solid #93C5FD"],[style*="border-top: 1px solid #93C5FD"],[style*="border-left:2px solid #93C5FD"],[style*="border-left: 2px solid #93C5FD"],[style*="border-right:1px solid #93C5FD"],[style*="border-right: 1px solid #93C5FD"],
        [style*="border-color:#93C5FD"],[style*="border-color: #93C5FD"] { border-color: var(--info-border) !important; }
        [style*="border:1px solid #CCECE9"],[style*="border: 1px solid #CCECE9"],[style*="border:2px solid #CCECE9"],[style*="border: 2px solid #CCECE9"],[style*="border-bottom:1px solid #CCECE9"],[style*="border-bottom: 1px solid #CCECE9"],
        [style*="border-bottom:2px solid #CCECE9"],[style*="border-bottom: 2px solid #CCECE9"],[style*="border-top:1px solid #CCECE9"],[style*="border-top: 1px solid #CCECE9"],[style*="border-left:2px solid #CCECE9"],[style*="border-left: 2px solid #CCECE9"],
        [style*="border-right:1px solid #CCECE9"],[style*="border-right: 1px solid #CCECE9"],[style*="border-color:#CCECE9"],[style*="border-color: #CCECE9"] { border-color: var(--teal) !important; }
        [style*="border:1px solid #D8B4FE"],[style*="border: 1px solid #D8B4FE"],[style*="border:2px solid #D8B4FE"],[style*="border: 2px solid #D8B4FE"],[style*="border-bottom:1px solid #D8B4FE"],[style*="border-bottom: 1px solid #D8B4FE"],
        [style*="border-bottom:2px solid #D8B4FE"],[style*="border-bottom: 2px solid #D8B4FE"],[style*="border-top:1px solid #D8B4FE"],[style*="border-top: 1px solid #D8B4FE"],[style*="border-left:2px solid #D8B4FE"],[style*="border-left: 2px solid #D8B4FE"],
        [style*="border-right:1px solid #D8B4FE"],[style*="border-right: 1px solid #D8B4FE"],[style*="border-color:#D8B4FE"],[style*="border-color: #D8B4FE"],[style*="border:1px solid #DDD6FE"],[style*="border: 1px solid #DDD6FE"],
        [style*="border:2px solid #DDD6FE"],[style*="border: 2px solid #DDD6FE"],[style*="border-bottom:1px solid #DDD6FE"],[style*="border-bottom: 1px solid #DDD6FE"],[style*="border-bottom:2px solid #DDD6FE"],[style*="border-bottom: 2px solid #DDD6FE"],
        [style*="border-top:1px solid #DDD6FE"],[style*="border-top: 1px solid #DDD6FE"],[style*="border-left:2px solid #DDD6FE"],[style*="border-left: 2px solid #DDD6FE"],[style*="border-right:1px solid #DDD6FE"],[style*="border-right: 1px solid #DDD6FE"],
        [style*="border-color:#DDD6FE"],[style*="border-color: #DDD6FE"],[style*="border:1px solid #E9D5FF"],[style*="border: 1px solid #E9D5FF"],[style*="border:2px solid #E9D5FF"],[style*="border: 2px solid #E9D5FF"],
        [style*="border-bottom:1px solid #E9D5FF"],[style*="border-bottom: 1px solid #E9D5FF"],[style*="border-bottom:2px solid #E9D5FF"],[style*="border-bottom: 2px solid #E9D5FF"],[style*="border-top:1px solid #E9D5FF"],[style*="border-top: 1px solid #E9D5FF"],
        [style*="border-left:2px solid #E9D5FF"],[style*="border-left: 2px solid #E9D5FF"],[style*="border-right:1px solid #E9D5FF"],[style*="border-right: 1px solid #E9D5FF"],[style*="border-color:#E9D5FF"],[style*="border-color: #E9D5FF"] { border-color: var(--purple) !important; }

        /* ícones SVG (atributo stroke) */
        [stroke="#004B8D"] { stroke: var(--accent-text) !important; }
        [stroke="#111827"],[stroke="#1B1B18"] { stroke: var(--text-1) !important; }
        [stroke="#374151"] { stroke: var(--text-2) !important; }
        [stroke="#6B7280"] { stroke: var(--text-3) !important; }
        [stroke="#9CA3AF"],[stroke="#D1D5DB"] { stroke: var(--text-4) !important; }
        [stroke="#3D7A27"],[stroke="#059669"] { stroke: var(--success) !important; }
        [stroke="#EF4444"],[stroke="#DC2626"] { stroke: var(--danger) !important; }
        [stroke="#92400E"],[stroke="#C77A00"],[stroke="#B45309"] { stroke: var(--warning) !important; }
        [stroke="#3B5BDB"],[stroke="#1E40AF"],[stroke="#1D4ED8"],[stroke="#91A7FF"],[stroke="#A8D4FF"] { stroke: var(--info) !important; }
        [stroke="#009C8C"],[stroke="#60CFCA"] { stroke: var(--teal) !important; }
        [stroke="#7C3700"] { stroke: var(--brown) !important; }
        [stroke="#6D28D9"],[stroke="#7C3AED"],[stroke="#7E22CE"] { stroke: var(--purple) !important; }

        /* accent-color de checkbox/radio segue o acento da escola */
        [style*="accent-color: #004B8D"],[style*="accent-color:#004B8D"] { accent-color: var(--accent) !important; }

        /* ── INPUTS ── */
        input, textarea, select { color: var(--text-1) !important; }
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) input  { background: transparent !important; }
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) textarea { background: var(--bg-subtle) !important; border-color: var(--border) !important;
            padding-left: 12px !important; padding-right: 12px !important; border-radius: 8px; }
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) select { background: var(--bg-subtle) !important; border-color: var(--border) !important; }
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) input[type="date"] { background: var(--bg-subtle) !important; color-scheme: dark; }
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) input::placeholder,
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) textarea::placeholder { color: var(--text-4) !important; }

        /* ── MURAL DE OBSERVAÇÕES ── */
        :is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .observation-feed-card { background: var(--bg-card) !important; border-color: var(--border) !important; }

        /* ── HOVER de botões com acento/ação (claro e escuro) ── */
        button[style*="var(--accent)"]:hover, a[style*="var(--accent)"]:hover,
        button[style*="var(--danger-solid)"]:hover, a[style*="var(--danger-solid)"]:hover,
        button[style*="var(--success-solid)"]:hover, a[style*="var(--success-solid)"]:hover { filter: brightness(1.08); }

        /* ── BOTÃO TEMA ── */
        #theme-toggle {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg-subtle);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: var(--text-3);
            transition: background 0.15s;
        }
        #theme-toggle:hover { background: var(--bg-hover) !important; }
    </style>
    @php
        /* Cor da escola = acento PRIMÁRIO em todos os temas.
           Para cada tema derivamos: fill (preenchimento de botão), text (acento
           sobre fundo da página), bg (tinta sutil), strong (hover/ênfase) e
           contrast (texto sobre o fill). Cores escuras clareiam no escuro;
           cores claras escurecem para virar texto legível no claro. */
        $schoolTheme = null;
        $A = [];
        if (auth()->check()) {
            $rawColor = auth()->user()->school?->theme_color;
            if ($rawColor && preg_match('/^#[0-9A-Fa-f]{6}$/', $rawColor)) {
                $schoolTheme = $rawColor;
                $r = hexdec(substr($rawColor, 1, 2));
                $g = hexdec(substr($rawColor, 3, 2));
                $b = hexdec(substr($rawColor, 5, 2));
                $lumOf = fn($a) => (0.299 * $a[0] + 0.587 * $a[1] + 0.114 * $a[2]) / 255;
                // mistura com branco (t>0) ou preto (t<0) → array [r,g,b]
                $mix = function ($t) use ($r, $g, $b) {
                    $f = $t >= 0
                        ? fn($c) => (int) round($c + (255 - $c) * $t)
                        : fn($c) => (int) round($c * (1 + $t));
                    return [$f($r), $f($g), $f($b)];
                };
                $hex      = fn($a) => sprintf('#%02x%02x%02x', $a[0], $a[1], $a[2]);
                $rgba     = fn($al) => "rgba($r,$g,$b,$al)";
                $contrast = fn($a) => $lumOf($a) > 0.55 ? '#10141B' : '#FFFFFF';
                $lum      = $lumOf([$r, $g, $b]);

                $lFill = [$r, $g, $b];
                $lText = $lum > 0.55 ? $mix(-0.45) : [$r, $g, $b];
                $A['light'] = ['fill' => $hex($lFill), 'text' => $hex($lText), 'bg' => $hex($mix(0.86)), 'strong' => $hex($mix(-0.30)), 'contrast' => $contrast($lFill)];

                $dt    = $lum < 0.40 ? 0.22 : ($lum > 0.70 ? -0.18 : 0.0);
                $dFill = $mix($dt);
                $A['dark'] = ['fill' => $hex($dFill), 'text' => $hex($lum < 0.55 ? $mix(0.55) : $mix(0.25)), 'bg' => $rgba('0.18'), 'strong' => $hex($mix(min($dt + 0.22, 0.85))), 'contrast' => $contrast($dFill)];

                $ct    = $lum < 0.40 ? 0.30 : ($lum > 0.70 ? -0.10 : 0.10);
                $cFill = $mix($ct);
                $A['contrast'] = ['fill' => $hex($cFill), 'text' => $hex($lum < 0.55 ? $mix(0.65) : $mix(0.40)), 'bg' => $rgba('0.24'), 'strong' => $hex($mix(min($ct + 0.25, 0.90))), 'contrast' => $contrast($cFill)];
            }
        }
    @endphp
    @if($schoolTheme)
    <style>
        :root {
            --accent:{{ $A['light']['fill'] }}; --accent-text:{{ $A['light']['text'] }};
            --accent-bg:{{ $A['light']['bg'] }}; --accent-strong:{{ $A['light']['strong'] }};
            --accent-contrast:{{ $A['light']['contrast'] }};
        }
        [data-theme="dark"], [data-theme="slate"] {
            --accent:{{ $A['dark']['fill'] }}; --accent-text:{{ $A['dark']['text'] }};
            --accent-bg:{{ $A['dark']['bg'] }}; --accent-strong:{{ $A['dark']['strong'] }};
            --accent-contrast:{{ $A['dark']['contrast'] }};
        }
        [data-theme="contrast"] {
            --accent:{{ $A['contrast']['fill'] }}; --accent-text:{{ $A['contrast']['text'] }};
            --accent-bg:{{ $A['contrast']['bg'] }}; --accent-strong:{{ $A['contrast']['strong'] }};
            --accent-contrast:{{ $A['contrast']['contrast'] }};
        }
    </style>
    @endif
    <script>
        // Aplica tema antes do render para evitar flash
        (function() {
            const saved = localStorage.getItem('atrio-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside style="width: 240px; border-right: 1px solid var(--border); display: flex; flex-direction: column; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40;">

        {{-- Logo --}}
        <div style="padding: 24px 20px 20px; border-bottom: 1px solid var(--border-sub);">
            @php
                $roleDashboardMap = ['admin' => 'secretaria.dashboard', 'coordenador' => 'secretaria.dashboard', 'orientador' => 'secretaria.dashboard', 'professor' => 'professor.dashboard'];
                $dashboardRoute = auth()->check() ? ($roleDashboardMap[auth()->user()->getRoleNames()->first()] ?? 'secretaria.dashboard') : 'secretaria.dashboard';
            @endphp
            <a href="{{ route($dashboardRoute) }}"
               style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                @auth
                    @php $school = auth()->user()->school; @endphp
                    @if($school?->logo)
                        <img src="{{ route('school.logo', ['filename' => basename($school->logo)]) }}"
                             style="height: 40px; object-fit: contain; max-width: 120px; flex-shrink: 0;">
                        <div style="min-width: 0;">
                            <div style="font-size: 13px; font-weight: 700; color: var(--accent-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $school->name }}</div>
                            <div style="font-size: 10px; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase;">Portal Institucional</div>
                        </div>
                    @else
                        <div style="width: 36px; height: 36px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                            </svg>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-size: 15px; font-weight: 700; color: var(--accent-text); letter-spacing: 0.5px;">ÁTRIO</div>
                            <div style="font-size: 10px; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase;">
                                {{ $school?->name ?? 'Portal Institucional' }}
                            </div>
                        </div>
                    @endif
                @else
                    <div style="width: 36px; height: 36px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                            <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 15px; font-weight: 700; color: var(--accent-text); letter-spacing: 0.5px;">ÁTRIO</div>
                        <div style="font-size: 10px; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase;">Portal Institucional</div>
                    </div>
                @endauth
            </a>
        </div>

        <nav style="flex: 1; padding: 16px 12px; overflow-y: auto; display: flex; flex-direction: column;">
            @auth
                @php
                    $school     = auth()->user()->school;
                    $hasModule  = fn(string $k) => !$school || $school->hasModule($k);
                    $pendCacheKey = 'pendentes_count_' . session('school_id');
                    $pendentesCount = 0;
                @endphp

                @hasanyrole(['admin','coordenador','orientador'])
                    @php
                        $pendentesCount = \Illuminate\Support\Facades\Cache::remember(
                            $pendCacheKey, now()->addMinutes(5),
                            fn() => \App\Models\Student::where('is_atypical', true)
                                ->with(['documents' => fn($q) => $q->where('year', date('Y'))->select('id','student_id','type')])
                                ->get()
                                ->filter(fn($a) => count(array_diff(['estudo_caso','pei','paee'], $a->documents->pluck('type')->toArray())) > 0)
                                ->count()
                        );
                        $isAdmin = auth()->user()->hasRole('admin');
                        $items = [
                            ['route' => 'secretaria.dashboard',                  'icon' => 'home',    'label' => 'Início'],
                            ['route' => 'secretaria.painel',                     'icon' => 'grid',    'label' => 'Painel de Acompanhamento', 'module' => 'painel'],
                            ['route' => 'secretaria.turmas.index',               'icon' => 'academic','label' => term('turmas'),        'module' => 'turmas'],
                            ['route' => 'secretaria.alunos.index',               'icon' => 'users',   'label' => 'Cadastro de ' . term('alunos'), 'module' => 'alunos'],
                            ['route' => 'secretaria.rotinas.documentos.index',   'icon' => 'rotina',  'label' => 'Documentos de Inclusão', 'module' => 'documentos', 'badge' => $pendentesCount ?: null],
                            ['route' => 'secretaria.rotinas.adaptacoes',         'icon' => 'rotina',  'label' => 'Adaptações para Prova', 'module' => 'adaptacoes'],
                            ['route' => 'secretaria.seletividade.index',         'icon' => 'food',    'label' => 'Jornada Alimentar', 'module' => 'seletividade'],
                        ];
                        $footerItems = [
                            ['route' => 'secretaria.usuarios.index',             'icon' => 'user',    'label' => 'Usuários',            'module' => 'usuarios'],
                            [
                                'route'    => 'secretaria.config.index',
                                'icon'     => 'config',
                                'label'    => 'Configurações',
                                'active'   => 'secretaria.config.*',
                                'module'   => 'configuracoes',
                            ],
                            ['route' => 'secretaria.logs.index', 'icon' => 'log', 'label' => 'Registro de Acessos', 'module' => 'configuracoes', 'admin_only' => true],
                        ];
                        // Coordenador/orientador não vê Configurações nem Logs
                        if (!$isAdmin) {
                            $footerItems = array_filter($footerItems, fn($i) =>
                                ($i['module'] ?? '') !== 'configuracoes' && empty($i['admin_only'])
                            );
                        }
                    @endphp
                @endhasanyrole

                @hasrole('professor')
                    @php
                        $items = [
                            ['route' => 'professor.dashboard',    'icon' => 'home',     'label' => 'Início'],
                            ['route' => 'professor.painel',       'icon' => 'grid',     'label' => 'Painel de Acompanhamento'],
                            ['route' => 'professor.turmas.index', 'icon' => 'academic', 'label' => 'Turmas'],
                        ];
                        $footerItems = [];
                    @endphp
                @endhasrole

                @php
                    if (!isset($items) && auth()->check()) {
                        $schoolId = session('school_id');
                        if (auth()->user()->roles()->where('name', 'like', "s{$schoolId}_%")->exists()) {
                            $items = [
                                ['route' => 'secretaria.dashboard',                  'icon' => 'home',    'label' => 'Início'],
                                ['route' => 'secretaria.painel',                     'icon' => 'grid',    'label' => 'Painel de Acompanhamento', 'module' => 'painel'],
                                ['route' => 'secretaria.turmas.index',               'icon' => 'academic','label' => term('turmas'),       'module' => 'turmas'],
                                ['route' => 'secretaria.alunos.index',               'icon' => 'users',   'label' => 'Cadastro de ' . term('alunos'), 'module' => 'alunos'],
                                ['route' => 'secretaria.rotinas.documentos.index',   'icon' => 'rotina',  'label' => 'Documentos de Inclusão', 'module' => 'documentos'],
                                ['route' => 'secretaria.rotinas.adaptacoes',         'icon' => 'rotina',  'label' => 'Adaptações para Prova', 'module' => 'adaptacoes'],
                                ['route' => 'secretaria.seletividade.index',         'icon' => 'food',    'label' => 'Jornada Alimentar', 'module' => 'seletividade'],
                            ];
                            $footerItems = [
                                ['route' => 'secretaria.usuarios.index',             'icon' => 'user',    'label' => 'Usuários',           'module' => 'usuarios'],
                            ];
                        }
                    }
                @endphp

                @foreach($items ?? [] as $item)
                    @continue(isset($item['module']) && !$hasModule($item['module']))
                    @include('layouts.partials.sidebar-item', ['item' => $item, 'hasModule' => $hasModule])
                @endforeach

                @if(!empty($footerItems))
                    <div style="margin-top: auto;">
                        @foreach($footerItems as $item)
                            @continue(isset($item['module']) && !$hasModule($item['module']))
                            @include('layouts.partials.sidebar-item', ['item' => $item, 'hasModule' => $hasModule])
                        @endforeach
                    </div>
                @endif
            @endauth
        </nav>

        @auth
        <div style="padding: 16px 12px; border-top: 1px solid var(--border-sub);">
            <a href="{{ route('profile.edit') }}"
               style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; text-decoration: none; color: var(--text-2);">
                @if(auth()->user()->avatar)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}"
                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                @else
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 13px; font-weight: 500; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 11px; color: var(--text-4);">
                        @php
                            $roleLabels = [
                                'admin'       => 'Administrador',
                                'coordenador' => 'Coordenação',
                                'orientador'  => 'Orientação Pedagógica',
                                'professor'   => 'Professor',
                            ];
                            $sidebarRole = auth()->user()->getRoleNames()->first();
                            $sidebarRoleLabel = $roleLabels[$sidebarRole] ?? null;
                            if (!$sidebarRoleLabel && $sidebarRole && str_starts_with($sidebarRole, 's')) {
                                $sidebarRoleLabel = \App\Models\SchoolRole::where('spatie_role', $sidebarRole)->value('name');
                            }
                            echo $sidebarRoleLabel ?? $sidebarRole;
                        @endphp
                    </div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 4px;">
                @csrf
                <button type="submit"
                        style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; border: none; background: none; cursor: pointer; font-size: 13px; color: var(--danger); text-align: left;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Sair
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <div style="margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh;">

        <header style="border-bottom: 1px solid var(--border); padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    <span style="color: var(--text-1); font-weight: 500;">@yield('title')</span>
                @endif
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <button id="theme-toggle" onclick="toggleTheme()" title="Alternar tema">
                    <svg id="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                        <circle cx="12" cy="12" r="5"/>
                        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                    </svg>
                    <svg id="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </button>
            </div>
        </header>

        <main style="flex: 1; padding: 32px;">
            @if(session('success'))
                <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

{{-- ── MODAL DE CONFIRMAÇÃO GLOBAL ── --}}
<div id="confirm-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
    {{-- Overlay --}}
    <div id="confirm-overlay"
         onclick="closeConfirm()"
         style="position:absolute;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);"></div>

    {{-- Card --}}
    <div style="position:relative;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;
                padding:32px 28px;width:100%;max-width:380px;margin:0 16px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);z-index:1;">

        {{-- Ícone --}}
        <div style="width:48px;height:48px;border-radius:14px;background:var(--danger-bg);
                    display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
            </svg>
        </div>

        <p id="confirm-title"
           style="font-size:16px;font-weight:700;color:var(--text-1);text-align:center;margin:0 0 8px;"></p>
        <p id="confirm-desc"
           style="font-size:13px;color:var(--text-3);text-align:center;margin:0 0 28px;line-height:1.5;">
            Esta ação não pode ser desfeita.
        </p>

        <div style="display:flex;gap:10px;">
            <button onclick="closeConfirm()"
                    style="flex:1;padding:11px;border-radius:8px;border:1px solid var(--border);
                           background:transparent;color:var(--text-2);font-size:13px;font-weight:600;cursor:pointer;">
                Cancelar
            </button>
            <button id="confirm-ok"
                    style="flex:1;padding:11px;border-radius:8px;border:none;
                           background:var(--danger-solid);color:white;font-size:13px;font-weight:600;cursor:pointer;">
                Remover
            </button>
        </div>
    </div>
</div>

<script>
// Cores da escola injetadas pelo servidor (usadas ao voltar para o tema claro)
const SCHOOL_ACCENT    = @json($schoolTheme ?? null);
const SCHOOL_ACCENT_BG = @json($accentBg ?? null);

function updateIcons(theme) {
    const sun  = document.getElementById('icon-sun');
    const moon = document.getElementById('icon-moon');
    if (!sun || !moon) return;
    sun.style.display  = theme === 'dark' ? 'block' : 'none';
    moon.style.display = theme === 'dark' ? 'none'  : 'block';
}

function applySchoolTheme() {
    if (!SCHOOL_ACCENT) return;
    const root = document.documentElement;
    root.style.setProperty('--accent',    SCHOOL_ACCENT);
    root.style.setProperty('--accent-bg', SCHOOL_ACCENT_BG || SCHOOL_ACCENT);
}

function removeSchoolTheme() {
    const root = document.documentElement;
    root.style.removeProperty('--accent');
    root.style.removeProperty('--accent-bg');
}

// Armazena os handlers inline originais para restaurar no tema claro
const _hoverStore = new WeakMap();

function applyHoverBehavior(theme) {
    if (theme === 'dark') {
        document.querySelectorAll('[onmouseover]').forEach(el => {
            _hoverStore.set(el, {
                over: el.getAttribute('onmouseover'),
                out:  el.getAttribute('onmouseout'),
                bg:   el.style.background,
            });
            el.setAttribute('data-hover-stored', '1');
            el.removeAttribute('onmouseover');
            el.removeAttribute('onmouseout');
        });
    } else {
        // Restaura handlers e backgrounds ao voltar para o tema claro
        document.querySelectorAll('[data-hover-stored]').forEach(el => {
            const stored = _hoverStore.get(el);
            if (stored) {
                if (stored.over) el.setAttribute('onmouseover', stored.over);
                if (stored.out)  el.setAttribute('onmouseout',  stored.out);
                el.style.background = stored.bg;
                _hoverStore.delete(el);
            }
            el.removeAttribute('data-hover-stored');
        });
    }
}

function toggleTheme() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const next   = isDark ? 'light' : 'dark';

    if (next === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        removeSchoolTheme();
    } else {
        document.documentElement.removeAttribute('data-theme');
        applySchoolTheme();
    }

    applyHoverBehavior(next);
    localStorage.setItem('atrio-theme', next);
    updateIcons(next);
}

// ── FOCUS DARK MODE — intercepta onfocus/onblur hardcoded ──
document.addEventListener('focusin', function(e) {
    const el = e.target;
    if (!['INPUT','TEXTAREA','SELECT'].includes(el.tagName)) return;
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        el.style.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim();
    }
});
document.addEventListener('focusout', function(e) {
    const el = e.target;
    if (!['INPUT','TEXTAREA','SELECT'].includes(el.tagName)) return;
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        el.style.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
    }
});

// ── MODAL DE CONFIRMAÇÃO ──
function openConfirm(title, desc, onOk) {
    const modal = document.getElementById('confirm-modal');
    document.getElementById('confirm-title').textContent = title;
    const descEl = document.getElementById('confirm-desc');
    descEl.textContent = desc || 'Esta ação não pode ser desfeita.';
    const okBtn = document.getElementById('confirm-ok');
    okBtn.onclick = function () { closeConfirm(); onOk(); };
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeConfirm() {
    document.getElementById('confirm-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Intercepta todos os botões com data-confirm
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        const title = btn.getAttribute('data-confirm') || 'Confirmar remoção';
        const form  = btn.closest('form');
        openConfirm(title, 'Esta ação não pode ser desfeita.', function () {
            if (form) form.submit();
        });
    }, true);
});

// Fecha com ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeConfirm();
});

// ── INICIALIZA ──
(function () {
    const saved       = localStorage.getItem('atrio-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme       = saved || (prefersDark ? 'dark' : 'light');

    if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        applySchoolTheme();
    }
    updateIcons(theme);

    // Aplica remoção dos hover handlers após o DOM estar pronto
    if (theme === 'dark') {
        document.addEventListener('DOMContentLoaded', () => applyHoverBehavior('dark'));
    }
})();
</script>
</body>
</html>