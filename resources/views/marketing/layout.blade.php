<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('metaTitle', 'Átrio — Sistema de Gestão da Inclusão Escolar (PEI, PAEE, Estudo de Caso)')</title>
    <meta name="description" content="@yield('metaDescription', 'O Átrio centraliza Estudo de Caso, PAEE e PEI em um só lugar, em conformidade com a nova legislação de educação especial (Decretos 12.686 e 12.773/2025). Agende uma demonstração.')">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Átrio">
    <meta property="og:title" content="Átrio — a inclusão da sua escola organizada e em conformidade com a lei">
    <meta property="og:description" content="Estudo de Caso, PAEE e PEI integrados, com acompanhamento por bimestre e rastreabilidade LGPD. Agende uma demonstração.">
    <meta property="og:url" content="https://atriosystem.com.br">
    <meta property="og:image" content="{{ asset('favicon-32x32.png') }}">
    <meta name="twitter:card" content="summary">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:      #004B8D;
            --blue-dark: #003366;
            --blue-mid:  #0062BC;
            --blue-soft: #E8F0F9;
            --blue-pale: #F0F5FB;
            --teal:      #009C8C;
            --ink:       #0D1F36;
            --slate:     #2C4A6E;
            --muted:     #5A7FA8;
            --border:    #C8DDF0;
            --white:     #FFFFFF;
        }

        /* O scroll suave é feito via JS (requestAnimationFrame) para funcionar em
           todas as máquinas, inclusive com "Reduzir movimento" ligado. Por isso
           NÃO usamos scroll-behavior: smooth aqui (ele respeita reduce-motion). */

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--white);
            color: var(--ink);
            line-height: 1.6;
        }

        /* ── NAV ── */
        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 36px; height: 36px;
            background: var(--blue);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .nav-logo-text {
            font-size: 17px;
            font-weight: 800;
            color: var(--blue);
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }
        .nav-links a {
            font-size: 13px;
            font-weight: 500;
            color: var(--slate);
            text-decoration: none;
            letter-spacing: 0.3px;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--blue); }
        .nav-cta {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-outline {
            background: transparent;
            color: var(--blue);
            border: 1.5px solid var(--border);
        }
        .btn-outline:hover {
            background: var(--blue-soft);
            border-color: var(--blue);
        }
        .btn-primary {
            background: var(--blue);
            color: var(--white);
        }
        .btn-primary:hover { background: var(--blue-dark); }
        .btn-lg {
            padding: 14px 32px;
            font-size: 13px;
            border-radius: 10px;
        }
        .btn-white {
            background: var(--white);
            color: var(--blue);
        }
        .btn-white:hover { background: var(--blue-soft); }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(145deg, #002D5A 0%, #004B8D 45%, #0062BC 100%);
            min-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 80px 24px 100px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0,156,140,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 100px;
            padding: 6px 16px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 32px;
        }
        .hero-badge-dot {
            width: 6px; height: 6px;
            background: #00D4C0;
            border-radius: 50%;
        }
        .hero-title {
            font-size: clamp(36px, 5vw, 60px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            letter-spacing: -0.5px;
            max-width: 720px;
            margin-bottom: 24px;
        }
        .hero-title span {
            color: #60CFCA;
        }
        .hero-subtitle {
            font-size: 17px;
            color: rgba(255,255,255,0.7);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 48px;
        }
        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .hero-scroll {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.4);
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(6px); }
        }

        /* ── SECTION BASE ── */
        section { padding: 96px 40px; }
        .section-inner {
            max-width: 1100px;
            margin: 0 auto;
        }
        .section-tag {
            font-size: 11px;
            font-weight: 700;
            color: var(--blue);
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 800;
            color: var(--ink);
            line-height: 1.2;
            margin-bottom: 16px;
        }
        .section-lead {
            font-size: 16px;
            color: var(--muted);
            max-width: 560px;
            line-height: 1.7;
            margin-bottom: 56px;
        }

        /* ── STATS ── */
        .stats-bar {
            background: linear-gradient(180deg, #003972 0%, #002D5A 100%);
            padding: 40px 40px;
        }
        .stats-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            text-align: center;
        }
        .stat-item {}
        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: #60CFCA;
            letter-spacing: -1px;
        }
        .stat-label {
            font-size: 12px;
            color: rgba(255,255,255,0.55);
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* ── FEATURES GRID ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .feature-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px 28px;
            transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        }
        .feature-card:hover {
            box-shadow: 0 8px 32px rgba(0,75,141,0.10);
            transform: translateY(-3px);
            border-color: rgba(0,75,141,0.3);
        }
        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .feature-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 10px;
        }
        .feature-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.65;
        }

        /* ── DOCUMENTS SHOWCASE ── */
        .docs-showcase {
            background: var(--blue-pale);
        }
        .docs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .doc-pill {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .doc-pill:hover {
            border-color: var(--blue);
            box-shadow: 0 4px 16px rgba(0,75,141,0.08);
        }
        .doc-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .doc-info {}
        .doc-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
        }
        .doc-desc {
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* ── HOW IT WORKS ── */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            position: relative;
        }
        .steps-grid::before {
            content: '';
            position: absolute;
            top: 28px;
            left: calc(12.5% + 24px);
            right: calc(12.5% + 24px);
            height: 2px;
            background: linear-gradient(to right, var(--blue-soft), var(--blue-soft));
            border-top: 2px dashed var(--border);
        }
        .step {
            text-align: center;
            position: relative;
        }
        .step-circle {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: var(--white);
            border: 2px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 18px;
            font-weight: 800;
            color: var(--blue);
            position: relative;
            z-index: 1;
            transition: background 0.2s, border-color 0.2s;
        }
        .step:hover .step-circle {
            background: var(--blue);
            border-color: var(--blue);
            color: var(--white);
        }
        .step-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }
        .step-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── ROLES ── */
        .roles-section {
            background: var(--ink);
        }
        .roles-section .section-title { color: var(--white); }
        .roles-section .section-tag { color: #60CFCA; }
        .roles-section .section-lead { color: rgba(255,255,255,0.55); }
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .role-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 32px 28px;
            transition: background 0.2s, border-color 0.2s;
        }
        .role-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.2);
        }
        .role-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .role-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .role-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
        }
        .role-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        .role-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .role-features li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: rgba(255,255,255,0.65);
            line-height: 1.5;
        }
        .role-features li::before {
            content: '';
            width: 16px; height: 16px;
            flex-shrink: 0;
            margin-top: 1px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2360CFCA' stroke-width='2.5'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }

        /* ── CTA FINAL ── */
        .cta-section {
            background: linear-gradient(135deg, #003366 0%, #004B8D 100%);
            text-align: center;
            padding: 96px 40px;
        }
        .cta-section .section-title { color: var(--white); margin-bottom: 16px; }
        .cta-section .section-lead { color: rgba(255,255,255,0.65); margin: 0 auto 48px; }
        .cta-cards {
            display: flex;
            gap: 24px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .cta-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 32px 40px;
            width: 260px;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        .cta-card:hover {
            background: rgba(255,255,255,0.16);
            transform: translateY(-4px);
        }
        .cta-card-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .cta-card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
        }
        .cta-card-desc {
            font-size: 12px;
            color: rgba(255,255,255,0.55);
            line-height: 1.5;
        }
        .cta-card-btn {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
            margin-top: 4px;
        }

        /* ── LEGISLAÇÃO ── */
        .legislacao-section {
            background: #F8FAFD;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .leg-intro {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: center;
            margin-bottom: 64px;
        }
        .leg-intro-text {}
        .leg-intro-visual {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .leg-pill {
            background: var(--white);
            border: 1px solid var(--border);
            border-left: 3px solid var(--blue);
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .leg-pill:hover {
            box-shadow: 0 4px 16px rgba(0,75,141,0.09);
            transform: translateX(4px);
        }
        .leg-pill-badge {
            font-size: 10px;
            font-weight: 800;
            color: var(--blue);
            background: #E8F0F9;
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
            letter-spacing: 0.5px;
        }
        .leg-pill-text {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }
        .leg-pill-text strong {
            color: var(--ink);
            font-weight: 700;
        }
        .leg-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .leg-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px 24px;
            transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        }
        .leg-card:hover {
            box-shadow: 0 6px 24px rgba(0,75,141,0.09);
            transform: translateY(-3px);
            border-color: rgba(0,75,141,0.25);
        }
        .leg-card-header {
            margin-bottom: 16px;
        }
        .leg-card-law {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--blue);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
            opacity: 0.85;
        }
        .leg-card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .leg-card-desc {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 16px;
        }
        .leg-card-items {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .leg-card-items li {
            font-size: 11.5px;
            color: #374151;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }
        .leg-card-items li::before {
            content: '';
            width: 14px; height: 14px;
            flex-shrink: 0;
            margin-top: 1px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004B8D' stroke-width='2.5'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }

        /* ── FOOTER ── */
        .footer {
            background: #060F1B;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-icon {
            width: 28px; height: 28px;
            background: var(--blue);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .footer-name {
            font-size: 14px;
            font-weight: 800;
            color: rgba(255,255,255,0.7);
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .footer-links {
            display: flex;
            gap: 24px;
        }
        .footer-links a {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            text-decoration: none;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: rgba(255,255,255,0.6); }
        .footer-copy {
            font-size: 11px;
            color: rgba(255,255,255,0.2);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── CTA COMERCIAL / WHATSAPP ── */
        .btn-whatsapp { background: #16A34A; color: #fff; }
        .btn-whatsapp:hover { background: #128C3E; }
        .btn-ghost { background: rgba(255,255,255,0.08); color: #fff; border: 1.5px solid rgba(255,255,255,0.35); }
        .btn-ghost:hover { background: rgba(255,255,255,0.16); }
        .wa-float {
            position: fixed; right: 22px; bottom: 22px; z-index: 200;
            width: 58px; height: 58px; border-radius: 50%;
            background: #16A34A; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(22,163,74,0.42); text-decoration: none;
            transition: transform 0.2s;
        }
        .wa-float:hover { transform: scale(1.08); }

        /* ── COMPARATIVO ── */
        .compare-section { background: var(--blue-pale); }
        .compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .compare-col { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px 28px; }
        .compare-col.win { border: 2px solid var(--blue); box-shadow: 0 12px 40px rgba(0,75,141,0.10); }
        .compare-col-head { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
        .compare-tag { font-size: 10.5px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; }
        .compare-tag.bad { background: #FEF2F2; color: #B42318; }
        .compare-tag.good { background: var(--blue-soft); color: var(--blue); }
        .compare-col h3 { font-size: 17px; font-weight: 800; color: var(--ink); }
        .compare-list { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .compare-list li { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--slate); line-height: 1.5; }
        .compare-list li::before { content: ''; width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; background-size: contain; background-repeat: no-repeat; }
        .compare-col.bad-col .compare-list li::before { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23B42318' stroke-width='2.5'%3E%3Cline x1='18' y1='6' x2='6' y2='18'/%3E%3Cline x1='6' y1='6' x2='18' y2='18'/%3E%3C/svg%3E"); }
        .compare-col.win .compare-list li::before { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23004B8D' stroke-width='2.5'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E"); }

        /* ── FAQ ── */
        .faq-list { display: flex; flex-direction: column; gap: 12px; max-width: 780px; }
        .faq-item { border: 1px solid var(--border); border-radius: 12px; background: var(--white); overflow: hidden; }
        .faq-q { width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 18px 22px; font-size: 14px; font-weight: 700; color: var(--ink); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .faq-q::after { content: '+'; font-size: 22px; color: var(--blue); flex-shrink: 0; transition: transform 0.2s; line-height: 1; }
        .faq-item.open .faq-q::after { transform: rotate(45deg); }
        .faq-a { max-height: 0; overflow: hidden; transition: max-height 0.25s ease; }
        .faq-item.open .faq-a { max-height: 340px; }
        .faq-a p { padding: 0 22px 18px; font-size: 13px; color: var(--muted); line-height: 1.7; }

        @media (max-width: 780px) { .compare-grid { grid-template-columns: 1fr; } }

        /* ── PRODUTO / SHOWCASE ── */
        .produto-section { background: linear-gradient(180deg, #EDF3FB 0%, #FFFFFF 72%); overflow: hidden; }
        .produto-head { text-align: center; max-width: 660px; margin: 0 auto 56px; }
        .produto-head .section-lead { margin: 0 auto; }
        .produto-stage { position: relative; max-width: 960px; margin: 0 auto; }
        .app-window {
            background: #fff; border: 1px solid var(--border); border-radius: 16px;
            box-shadow: 0 30px 80px rgba(0,45,90,0.20); overflow: hidden;
        }
        .app-bar {
            height: 42px; background: #F1F6FC; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 8px; padding: 0 16px;
        }
        .app-dot { width: 11px; height: 11px; border-radius: 50%; }
        .app-url {
            margin-left: 16px; font-size: 11px; color: var(--muted);
            background: #fff; border: 1px solid var(--border); border-radius: 20px; padding: 4px 14px;
        }
        .app-body { display: grid; grid-template-columns: 196px 1fr; min-height: 430px; }
        .app-side { background: #0C2138; padding: 18px 12px; display: flex; flex-direction: column; gap: 4px; }
        .app-brand { color: #fff; font-weight: 800; letter-spacing: 3px; font-size: 14px; padding: 6px 10px 16px; }
        .app-nav-item {
            display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: 9px;
            font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,0.55);
        }
        .app-nav-item .dot { width: 8px; height: 8px; border-radius: 3px; background: currentColor; opacity: 0.7; }
        .app-nav-item.active { background: rgba(96,207,202,0.16); color: #fff; font-weight: 700; }
        .app-main { padding: 22px 26px; background: #F7FAFD; }
        .app-crumb { font-size: 11px; color: var(--muted); letter-spacing: 0.3px; margin-bottom: 18px; }
        .app-student { display: flex; align-items: center; gap: 16px; margin-bottom: 22px; }
        .app-avatar {
            width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
            background: linear-gradient(135deg, #004B8D, #0062BC);
            color: #fff; font-weight: 800; font-size: 17px;
            display: flex; align-items: center; justify-content: center;
        }
        .app-student-name { font-size: 16px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
        .app-badges { display: flex; gap: 8px; flex-wrap: wrap; }
        .app-badge { font-size: 10.5px; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: 0.3px; }
        .app-badge.blue { background: var(--blue-soft); color: var(--blue); }
        .app-badge.teal { background: #E1F7F3; color: #00806F; }
        .app-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
        .app-mini { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 14px 16px; }
        .app-mini-label { font-size: 10.5px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }
        .app-mini-value { font-size: 24px; font-weight: 800; color: var(--ink); line-height: 1; }
        .app-mini-value span { font-size: 11px; font-weight: 700; color: var(--teal); }
        .app-mini-hint { font-size: 10.5px; color: var(--muted); margin-top: 8px; }
        .app-bar-track { height: 6px; background: var(--blue-soft); border-radius: 6px; margin-top: 10px; overflow: hidden; }
        .app-bar-track span { display: block; height: 100%; background: linear-gradient(90deg, #009C8C, #00C4AE); border-radius: 6px; }
        .app-panel { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 16px 18px; }
        .app-panel-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .app-panel-head > span:first-child { font-size: 12.5px; font-weight: 700; color: var(--ink); }
        .app-panel-tag { font-size: 10px; font-weight: 700; color: var(--blue); background: var(--blue-soft); padding: 3px 9px; border-radius: 20px; }
        .app-chart { display: flex; align-items: flex-end; gap: 18px; height: 96px; padding: 0 6px; }
        .app-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; height: 100%; justify-content: flex-end; }
        .app-col span { width: 100%; max-width: 46px; background: linear-gradient(180deg, #0062BC, #004B8D); border-radius: 6px 6px 0 0; }
        .app-col em { font-size: 10.5px; color: var(--muted); font-style: normal; }
        .app-chip {
            position: absolute; background: #fff; border: 1px solid var(--border);
            border-radius: 12px; padding: 11px 15px; display: flex; align-items: center; gap: 9px;
            font-size: 12px; font-weight: 700; color: var(--ink);
            box-shadow: 0 14px 36px rgba(0,45,90,0.16);
        }
        .app-chip svg { flex-shrink: 0; }
        .chip-a { top: 38px; right: -22px; }
        .chip-b { bottom: 46px; left: -26px; }
        .produto-note { text-align: center; font-size: 12px; color: var(--muted); margin-top: 36px; }

        /* ── MEGA NAV ── */
        .nav-toggle { display: none; background: none; border: none; cursor: pointer; padding: 6px; color: var(--blue); }
        .nav-menu { display: flex; align-items: center; gap: 32px; }
        .has-mega { position: relative; }
        .nav-trigger {
            display: inline-flex; align-items: center; gap: 6px; padding: 0;
            background: none; border: none; cursor: pointer; font-family: inherit;
            font-size: 13px; font-weight: 500; color: var(--slate); letter-spacing: 0.3px;
            transition: color 0.2s;
        }
        .nav-trigger:hover { color: var(--blue); }
        .nav-trigger .chev { transition: transform 0.2s; }
        .mega {
            position: absolute; top: calc(100% + 18px); left: 0;
            width: 520px; background: #fff; border: 1px solid var(--border); border-radius: 16px;
            box-shadow: 0 26px 64px rgba(0,45,90,0.18); padding: 14px;
            opacity: 0; visibility: hidden; transform: translateY(8px);
            transition: opacity 0.18s, transform 0.18s; z-index: 120;
        }
        .mega.mega-sm { width: 340px; }
        .mega.mega-right { left: auto; right: 0; }
        .mega::before { content: ''; position: absolute; top: -18px; left: 0; right: 0; height: 18px; }
        .mega-head {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            padding: 11px 14px; margin-bottom: 8px; border-radius: 10px;
            background: var(--blue-pale); text-decoration: none;
            font-size: 12px; font-weight: 700; color: var(--blue); letter-spacing: 0.3px;
            transition: background 0.15s;
        }
        .mega-head:hover { background: var(--blue-soft); }
        .mega-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; }
        .mega-sm .mega-grid { grid-template-columns: 1fr; }
        .mega-item { display: flex; align-items: flex-start; gap: 12px; padding: 11px 12px; border-radius: 10px; text-decoration: none; transition: background 0.15s; }
        .mega-item:hover { background: var(--blue-pale); }
        .mega-ico { width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0; background: var(--blue-soft); display: flex; align-items: center; justify-content: center; }
        .mega-item-body { display: block; min-width: 0; }
        .mega-item-t { display: block; font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 2px; }
        .mega-item-d { display: block; font-size: 11.5px; color: var(--muted); line-height: 1.45; }
        body.nav-open { overflow: hidden; }

        @media (min-width: 901px) {
            .has-mega:hover .mega, .has-mega:focus-within .mega { opacity: 1; visibility: visible; transform: translateY(0); }
            .has-mega:hover .nav-trigger .chev, .has-mega:focus-within .nav-trigger .chev { transform: rotate(180deg); }
        }
        @media (max-width: 900px) {
            .nav-toggle { display: inline-flex; }
            .nav-menu {
                position: fixed; top: 64px; left: 0; right: 0; bottom: 0; z-index: 95;
                flex-direction: column; align-items: stretch; gap: 0; padding: 12px 22px 40px;
                background: #fff; border-top: 1px solid var(--border); overflow-y: auto;
                transform: translateX(100%); transition: transform 0.24s;
            }
            .nav-menu.open { transform: translateX(0); }
            .nav-links { display: flex; flex-direction: column; align-items: stretch; gap: 0; width: 100%; }
            .nav-links > li > a { display: block; padding: 15px 2px; font-size: 15px; border-bottom: 1px solid var(--blue-pale); }
            .has-mega { position: static; }
            .nav-trigger { width: 100%; justify-content: space-between; padding: 15px 2px; font-size: 15px; border-bottom: 1px solid var(--blue-pale); }
            .mega { position: static; width: 100%; opacity: 1; visibility: visible; transform: none; box-shadow: none; border: none; border-radius: 0; padding: 4px 0; max-height: 0; overflow: hidden; transition: max-height 0.28s; }
            .has-mega.open .mega { max-height: 1200px; padding: 8px 0 14px; }
            .has-mega.open .nav-trigger .chev { transform: rotate(180deg); }
            .mega-head { margin-bottom: 6px; }
            .mega-grid { grid-template-columns: 1fr; }
            .nav-cta { flex-direction: column; align-items: stretch; gap: 10px; margin-top: 22px; }
            .nav-cta .btn { justify-content: center; padding: 13px 20px; }
        }

        /* ── FOOTER RICO ── */
        .footer-rich { background: #060F1B; padding: 64px 40px 30px; }
        .foot-inner { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1.7fr 1fr 1fr 1fr; gap: 40px; }
        .foot-brand-col .footer-brand { margin-bottom: 16px; }
        .foot-desc { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.7; max-width: 300px; margin-bottom: 22px; }
        .foot-actions { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
        .foot-link-strong { font-size: 13px; font-weight: 700; color: #60CFCA; text-decoration: none; }
        .foot-link-strong:hover { color: #8fe0dc; }
        .foot-wa { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; color: #34D058; text-decoration: none; }
        .foot-col { display: flex; flex-direction: column; gap: 12px; }
        .foot-h { font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.9); margin-bottom: 6px; }
        .foot-col a { font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
        .foot-col a:hover { color: #fff; }
        .foot-bottom { max-width: 1100px; margin: 48px auto 0; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
        .foot-legal { font-size: 11.5px; color: rgba(255,255,255,0.38); line-height: 1.6; max-width: 640px; }
        .foot-bottom-links { display: flex; gap: 24px; }
        .foot-bottom-links a { font-size: 11.5px; color: rgba(255,255,255,0.5); text-decoration: none; white-space: nowrap; transition: color 0.2s; }
        .foot-bottom-links a:hover { color: #fff; }
        .foot-copy { max-width: 1100px; margin: 20px auto 0; font-size: 11px; color: rgba(255,255,255,0.25); letter-spacing: 0.5px; }
        @media (max-width: 900px) {
            .footer-rich { padding: 48px 28px 28px; }
            .foot-inner { grid-template-columns: 1fr 1fr; gap: 32px 24px; }
        }
        @media (max-width: 580px) {
            .footer-rich { padding: 40px 20px 24px; }
            .foot-inner { grid-template-columns: 1fr; gap: 28px; }
            .foot-bottom { flex-direction: column; align-items: flex-start; gap: 14px; }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            section { padding: 72px 28px; }
            .stats-bar { padding: 32px 28px; }
            .features-grid { grid-template-columns: 1fr 1fr; }
            .docs-grid { grid-template-columns: 1fr 1fr; }
            .steps-grid { grid-template-columns: 1fr 1fr; gap: 24px; }
            .steps-grid::before { display: none; }
            .roles-grid { grid-template-columns: 1fr; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            /* legislação */
            .leg-intro { grid-template-columns: 1fr; gap: 32px; margin-bottom: 40px; }
            .leg-cards { grid-template-columns: 1fr 1fr; gap: 14px; }
            .leg-card { padding: 20px 18px; }
            .leg-card-desc { display: none; }
            .cta-cards { flex-direction: column; align-items: center; }
            /* produto */
            .app-cards { grid-template-columns: 1fr 1fr; }
            .app-chip { display: none; }
        }
        @media (max-width: 580px) {
            section { padding: 56px 16px; }
            .stats-bar { padding: 28px 16px; }
            .nav { padding: 0 16px; }
            .hero { padding: 64px 16px 80px; }
            .features-grid { grid-template-columns: 1fr; }
            .docs-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr 1fr; }
            .roles-grid { grid-template-columns: 1fr; }
            .stats-inner { grid-template-columns: 1fr 1fr; gap: 16px; }
            .stat-number { font-size: 28px; }
            .hero-actions { flex-direction: column; align-items: center; }
            .footer { flex-direction: column; text-align: center; padding: 28px 16px; }
            .footer-links { justify-content: center; }
            /* legislação mobile */
            .leg-intro { grid-template-columns: 1fr; gap: 24px; margin-bottom: 28px; }
            .leg-cards { grid-template-columns: 1fr; gap: 10px; }
            .leg-card { padding: 16px 16px; border-radius: 10px; }
            .leg-card-desc { display: none; }
            .leg-card-title { font-size: 13px; margin-bottom: 8px; }
            .leg-card-law { font-size: 10px; }
            .leg-card-items { gap: 5px; }
            .leg-card-items li { font-size: 11px; }
            .leg-pill { padding: 10px 14px; gap: 10px; }
            .leg-pill-text { font-size: 11px; }
            .section-title { font-size: clamp(22px, 6vw, 32px); }
            .section-lead { font-size: 14px; margin-bottom: 36px; }
            .cta-card { width: 100%; max-width: 320px; padding: 24px 28px; }
            /* produto mobile */
            .app-body { grid-template-columns: 1fr; }
            .app-side { display: none; }
            .app-cards { grid-template-columns: 1fr; }
            .app-main { padding: 18px 16px; }
        }
    
        /* ── NAV CTA (mega) ── */
        .nav-wa { width:38px;height:38px;border-radius:50%;border:1.5px solid var(--border);display:inline-flex;align-items:center;justify-content:center;color:#16A34A;flex-shrink:0;text-decoration:none;transition:all .2s; }
        .nav-wa:hover { background:#F0FDF4;border-color:#16A34A; }
        .nav-enter { display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--slate);text-decoration:none;transition:color .2s;white-space:nowrap; }
        .nav-enter:hover { color:var(--blue); }
        section[id] { scroll-margin-top: 84px; }
        .leg-card[id] { scroll-margin-top: 92px; }

        /* ── MEGA "O SISTEMA" (featured, autoral) ── */
        .mega-feature { width: 640px; }
        .mega-feature-grid { display: grid; grid-template-columns: 226px 1fr; gap: 12px; }
        .mega-feature-card {
            background: linear-gradient(160deg, #00305F 0%, #004B8D 58%, #0062BC 100%);
            border-radius: 12px; padding: 18px 16px; text-decoration: none; overflow: hidden;
            display: flex; flex-direction: column; gap: 9px; position: relative;
        }
        .mega-feature-card::after { content:''; position:absolute; top:-40px; right:-40px; width:150px; height:150px; background:radial-gradient(circle, rgba(96,207,202,0.22) 0%, transparent 70%); pointer-events:none; }
        .mff-badge { font-size: 10px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #7fded9; }
        .mff-title { font-size: 16px; font-weight: 800; color: #fff; line-height: 1.2; }
        .mff-sub { font-size: 11.5px; color: rgba(255,255,255,0.72); line-height: 1.5; }
        .mff-flow { display: flex; align-items: center; flex-wrap: wrap; gap: 5px; margin-top: 4px; }
        .mff-flow span { font-size: 10px; font-weight: 600; color: #fff; background: rgba(255,255,255,0.13); padding: 3px 8px; border-radius: 6px; }
        .mff-flow i { color: rgba(255,255,255,0.5); font-style: normal; font-size: 11px; }
        .mff-cta { margin-top: auto; padding-top: 6px; font-size: 12px; font-weight: 700; color: #fff; display: inline-flex; align-items: center; gap: 6px; }
        .mega-mods-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); padding: 6px 10px 8px; }
        .mega-mods-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; align-content: start; }
        .mega-mod { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 9px; text-decoration: none; transition: background 0.15s; }
        .mega-mod:hover { background: var(--blue-pale); }
        .mega-mod-t { font-size: 12.5px; font-weight: 600; color: var(--ink); }
        @media (min-width: 901px) {
            .mega-feature { left: 50%; transform: translateX(-50%) translateY(8px); }
            .has-mega:hover .mega-feature, .has-mega:focus-within .mega-feature { transform: translateX(-50%) translateY(0); }
        }
        @media (max-width: 900px) {
            .mega-feature { width: 100%; }
            .mega-feature-grid { grid-template-columns: 1fr; }
            .mega-feature-card { margin-bottom: 8px; }
            .mega-mods-grid { grid-template-columns: 1fr; }
        }

        /* ── RODAPÉ ÁTRIO (autoral) ── */
        .foot { background: #060F1B; }
        .foot-cta { max-width: 1100px; margin: 0 auto; padding: 48px 40px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .foot-cta h3 { font-size: 24px; font-weight: 800; color: #fff; letter-spacing: -0.3px; margin-bottom: 8px; }
        .foot-cta p { font-size: 14px; color: rgba(255,255,255,0.55); max-width: 460px; line-height: 1.6; }
        .foot-cta-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .foot-main { max-width: 1100px; margin: 0 auto; padding: 56px 40px 40px; display: grid; grid-template-columns: 1.5fr 2fr; gap: 56px; }
        .foot-brand-zone .footer-brand { margin-bottom: 18px; }
        .foot-mission { font-size: 13.5px; color: rgba(255,255,255,0.55); line-height: 1.75; max-width: 340px; margin-bottom: 24px; }
        .foot-seal { display: inline-flex; align-items: center; gap: 12px; background: rgba(96,207,202,0.08); border: 1px solid rgba(96,207,202,0.25); border-radius: 12px; padding: 12px 16px; }
        .foot-seal svg { color: #60CFCA; flex-shrink: 0; }
        .foot-seal strong { display: block; font-size: 12px; font-weight: 800; color: #fff; letter-spacing: 0.3px; }
        .foot-seal span { display: block; font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; }
        .foot-cols { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
        .foot-contact-item { display: flex; align-items: center; gap: 9px; font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
        .foot-contact-item svg { color: #60CFCA; flex-shrink: 0; }
        .foot-contact-item:hover { color: #fff; }
        .foot-fine { border-top: 1px solid rgba(255,255,255,0.08); max-width: 1100px; margin: 0 auto; padding: 22px 40px; display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; font-size: 11.5px; color: rgba(255,255,255,0.3); }
        .foot-fine a { color: rgba(255,255,255,0.45); text-decoration: none; transition: color 0.2s; }
        .foot-fine a:hover { color: #fff; }
        .foot-fine .sep { margin: 0 8px; opacity: 0.4; }
        @media (max-width: 780px) {
            .foot-cta { padding: 36px 24px; flex-direction: column; align-items: flex-start; }
            .foot-main { grid-template-columns: 1fr; gap: 36px; padding: 40px 24px 32px; }
            .foot-cols { grid-template-columns: 1fr 1fr; gap: 28px; }
            .foot-fine { padding: 20px 24px; flex-direction: column; align-items: flex-start; }
        }
        @media (max-width: 480px) { .foot-cols { grid-template-columns: 1fr; } }

    </style>
</head>
<body>

@include('marketing.partials.nav')

@yield('content')

@include('marketing.partials.footer')

<script>
    // Scroll suave próprio (requestAnimationFrame) — funciona em todas as máquinas,
    // inclusive com a opção "Reduzir movimento" do sistema ligada (que desativa o
    // smooth nativo e causava o "teletransporte").
    function atrioSmoothScroll(targetY, duration) {
        var startY = window.pageYOffset || document.documentElement.scrollTop;
        var diff = targetY - startY;
        if (Math.abs(diff) < 2) { window.scrollTo(0, targetY); return; }
        var startTime = null;
        function easeInOutQuad(t) { return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; }
        function step(now) {
            if (startTime === null) startTime = now;
            var elapsed = now - startTime;
            var p = Math.min(elapsed / duration, 1);
            window.scrollTo(0, Math.round(startY + diff * easeInOutQuad(p)));
            if (elapsed < duration) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var hash = this.getAttribute('href');
            var nav  = document.querySelector('.nav');
            var navHeight = nav ? nav.offsetHeight : 0;

            if (hash.length <= 1) {
                e.preventDefault();
                atrioSmoothScroll(0, 600);
                return;
            }

            var target = document.querySelector(hash);
            if (target) {
                e.preventDefault();
                var top = target.getBoundingClientRect().top + (window.pageYOffset || document.documentElement.scrollTop) - navHeight;
                atrioSmoothScroll(top, 600);
            }
        });
    });

    // FAQ (accordion)
    document.querySelectorAll('.faq-q').forEach(function (q) {
        q.addEventListener('click', function () {
            this.closest('.faq-item').classList.toggle('open');
        });
    });

    // Menu mobile (hamburger) + mega-menu (accordion no mobile)
    (function(){
        var toggle = document.querySelector('.nav-toggle');
        var menu   = document.querySelector('.nav-menu');
        if (toggle && menu) {
            toggle.addEventListener('click', function(){
                var open = menu.classList.toggle('open');
                document.body.classList.toggle('nav-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }
        document.querySelectorAll('.nav-trigger').forEach(function(t){
            t.addEventListener('click', function(){
                if (window.matchMedia('(max-width: 900px)').matches) {
                    this.closest('.has-mega').classList.toggle('open');
                }
            });
        });
        if (menu) {
            menu.querySelectorAll('a[href]').forEach(function(a){
                a.addEventListener('click', function(){
                    menu.classList.remove('open');
                    document.body.classList.remove('nav-open');
                    if (toggle) toggle.setAttribute('aria-expanded','false');
                });
            });
        }
    })();
</script>

@stack('scripts')

{{-- ── WHATSAPP FLUTUANTE ── --}}
<a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Quero%20conhecer%20o%20%C3%81trio." target="_blank" rel="noopener" class="wa-float" aria-label="Falar no WhatsApp">
    <svg width="30" height="30" viewBox="0 0 24 24" fill="#fff">
        <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.8 14.13c-.24.68-1.42 1.31-1.95 1.36-.53.05-1.03.24-3.48-.72-2.94-1.16-4.79-4.15-4.94-4.35-.14-.2-1.16-1.55-1.16-2.96 0-1.4.74-2.09 1-2.38.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.42-.07.65.5.24.58.81 2 .88 2.15.07.14.12.31.02.51-.1.2-.15.31-.29.48-.14.17-.3.38-.43.51-.14.14-.29.29-.12.58.17.29.74 1.22 1.59 1.98 1.09.97 2.01 1.27 2.3 1.42.29.14.46.12.63-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.65-.14.27.1 1.7.8 1.99.95.29.14.48.22.55.34.07.12.07.7-.17 1.38z"/>
    </svg>
</a>

</body>
</html>
