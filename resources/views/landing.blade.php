<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — Sistema de Gestão Inclusiva</title>
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
        }
    </style>
</head>
<body>

{{-- ── NAVEGAÇÃO ── --}}
<nav class="nav">
    <a href="#" class="nav-logo">
        <div class="nav-logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
            </svg>
        </div>
        <span class="nav-logo-text">Átrio</span>
    </a>

    <ul class="nav-links">
        <li><a href="#legislacao">Legislação</a></li>
        <li><a href="#funcionalidades">Funcionalidades</a></li>
        <li><a href="#documentos">Documentos</a></li>
        <li><a href="#como-funciona">Como funciona</a></li>
        <li><a href="#perfis">Perfis</a></li>
    </ul>

    <div class="nav-cta">
        <a href="{{ route('login') }}?perfil=escola" class="btn btn-primary">Acessar Sistema</a>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-badge">
        <span class="hero-badge-dot"></span>
        Gestão Escolar Inclusiva
    </div>

    <h1 class="hero-title">
        Cada aluno tem uma<br>
        <span>história que merece atenção</span>
    </h1>

    <p class="hero-subtitle">
        O Átrio centraliza toda a jornada dos estudantes com necessidades educacionais especiais,
        conectando equipe escolar e famílias em um único sistema.
    </p>

    <div class="hero-actions">
        <a href="{{ route('login') }}?perfil=escola" class="btn btn-white btn-lg">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
            Acessar o Sistema
        </a>
    </div>

    <div class="hero-scroll">
        <span>Saiba mais</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </div>
</section>

{{-- ── STATS ── --}}
<div class="stats-bar">
    <div class="stats-inner">
        <div class="stat-item">
            <div class="stat-number">3</div>
            <div class="stat-label">Tipos de documentos especializados</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">2</div>
            <div class="stat-label">Perfis de acesso distintos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">100%</div>
            <div class="stat-label">Digital e centralizado</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">PDF</div>
            <div class="stat-label">Exportação de documentos oficiais</div>
        </div>
    </div>
</div>

{{-- ── LEGISLAÇÃO ── --}}
<section id="legislacao" class="legislacao-section">
    <div class="section-inner">
        <div class="section-tag">Base Legal</div>
        <div class="leg-intro">
            <div class="leg-intro-text">
                <h2 class="section-title">Fundado na legislação<br>brasileira de inclusão</h2>
                <p class="section-lead" style="margin-bottom: 0;">
                    Os documentos gerados pelo Átrio — Estudo de Caso, PAEE e PEI — são previstos
                    e exigidos pela legislação federal. Cada instrumento reflete o que a lei determina
                    para garantir o direito à educação inclusiva de qualidade.
                </p>
            </div>
            <div class="leg-intro-visual">
                <div class="leg-pill">
                    <div class="leg-pill-badge">Lei 13.146/2015</div>
                    <div class="leg-pill-text"><strong>Lei Brasileira de Inclusão (LBI)</strong> — estabelece o direito à educação inclusiva e ao PEI para estudantes com deficiência.</div>
                </div>
                <div class="leg-pill">
                    <div class="leg-pill-badge">Decreto 12.686/2025</div>
                    <div class="leg-pill-text"><strong>Política Nacional de Educação Especial</strong> — regulamenta o Estudo de Caso, o PAEE e o PEI como instrumentos obrigatórios do AEE.</div>
                </div>
                <div class="leg-pill">
                    <div class="leg-pill-badge">Decreto 12.773/2025</div>
                    <div class="leg-pill-text"><strong>Reforço ao PEI</strong> — consolida o Plano Educacional Individualizado como documento central da inclusão escolar.</div>
                </div>
                <div class="leg-pill">
                    <div class="leg-pill-badge">Lei 9.394/1996</div>
                    <div class="leg-pill-text"><strong>LDB — Arts. 58 a 60</strong> — define o Atendimento Educacional Especializado e os serviços de apoio na rede regular.</div>
                </div>
                <div class="leg-pill">
                    <div class="leg-pill-badge">Lei 13.709/2018</div>
                    <div class="leg-pill-text"><strong>LGPD</strong> — dados clínicos e pedagógicos dos estudantes são dados sensíveis e exigem rastreabilidade e controle de acesso.</div>
                </div>
            </div>
        </div>

        <div class="leg-cards">
            <div class="leg-card">
                <div class="leg-card-header">
                    <div>
                        <div class="leg-card-law">Lei 13.146/2015 · Art. 28 &nbsp;|&nbsp; Decretos 12.686 e 12.773/2025</div>
                        <div class="leg-card-title">PEI — Plano Educacional Individualizado</div>
                    </div>
                </div>
                <p class="leg-card-desc">
                    A Lei Brasileira de Inclusão, em seu Art. 28, determina que os sistemas de ensino garantam a elaboração de plano educacional individualizado que contemple as especificidades, potencialidades e barreiras de cada estudante público-alvo da educação especial. Os Decretos nº 12.686/2025 e nº 12.773/2025 reforçam e regulamentam esse instrumento como obrigatório no âmbito do AEE.
                </p>
                <ul class="leg-card-items">
                    <li>Objetivos de curto, médio e longo prazo por estudante</li>
                    <li>Inventário de habilidades por disciplina e por professor</li>
                    <li>Adaptações curriculares e estratégias pedagógicas</li>
                    <li>Documento consolidado com assinaturas da equipe</li>
                </ul>
            </div>

            <div class="leg-card">
                <div class="leg-card-header">
                    <div>
                        <div class="leg-card-law">Decreto 12.686/2025</div>
                        <div class="leg-card-title">PAEE — Plano de Atendimento Educacional Especializado</div>
                    </div>
                </div>
                <p class="leg-card-desc">
                    O Decreto nº 12.686/2025, que institui a Política Nacional de Educação Especial, prevê o PAEE como instrumento de planejamento do profissional de AEE, definindo os objetivos do atendimento especializado, os recursos e estratégias utilizados, a organização do atendimento e os critérios de avaliação e monitoramento do plano.
                </p>
                <ul class="leg-card-items">
                    <li>Perfil do estudante e necessidades identificadas</li>
                    <li>Objetivos geral e específicos do AEE</li>
                    <li>Tecnologias assistivas, adaptações e metodologias</li>
                    <li>Frequência, duração, local e critérios de avaliação</li>
                </ul>
            </div>

            <div class="leg-card">
                <div class="leg-card-header">
                    <div>
                        <div class="leg-card-law">Decreto 12.686/2025</div>
                        <div class="leg-card-title">Estudo de Caso — Avaliação Pedagógica Inicial</div>
                    </div>
                </div>
                <p class="leg-card-desc">
                    O Decreto nº 12.686/2025 estabelece que o AEE deve ser precedido de uma avaliação pedagógica do estudante, realizada pela equipe escolar, que identifique suas barreiras, potencialidades e necessidades educacionais específicas. Esse documento fundamenta a elaboração do PAEE e do PEI.
                </p>
                <ul class="leg-card-items">
                    <li>Histórico escolar e contexto familiar</li>
                    <li>Barreiras identificadas e potencialidades</li>
                    <li>Diagnóstico pedagógico e objetivos de aprendizagem</li>
                    <li>Encaminhamentos e estratégias sugeridas à equipe</li>
                </ul>
            </div>

            <div class="leg-card">
                <div class="leg-card-header">
                    <div>
                        <div class="leg-card-law">LBI · Art. 28, XIII &nbsp;|&nbsp; Decreto 12.686/2025</div>
                        <div class="leg-card-title">Adaptações para Avaliação</div>
                    </div>
                </div>
                <p class="leg-card-desc">
                    A Lei Brasileira de Inclusão garante ao estudante com deficiência adaptações nas formas de avaliação — tempo ampliado, uso de ledor, transcritor e outros recursos. O Decreto 12.686/2025 reforça que essas adaptações devem ser planejadas, registradas e aplicadas de forma consistente em todas as avaliações.
                </p>
                <ul class="leg-card-items">
                    <li>Registro individualizado de adaptações por estudante</li>
                    <li>Tempo ampliado, sala separada, ledor, transcritor</li>
                    <li>Exportação por turma para aplicação nas provas</li>
                    <li>Histórico de adaptações por período letivo</li>
                </ul>
            </div>

            <div class="leg-card">
                <div class="leg-card-header">
                    <div>
                        <div class="leg-card-law">LDB · Art. 59, III &nbsp;|&nbsp; Decreto 12.686/2025</div>
                        <div class="leg-card-title">Equipe Multiprofissional e Colaboração</div>
                    </div>
                </div>
                <p class="leg-card-desc">
                    A LDB determina que os sistemas de ensino assegurem professores com especialização para o AEE e professores do ensino regular capacitados para a integração dos estudantes. O Decreto 12.686/2025 reforça a atuação colaborativa entre profissionais do AEE, professores regentes e equipe gestora na elaboração e execução dos planos.
                </p>
                <ul class="leg-card-items">
                    <li>Perfis distintos: AEE, professor, coordenação, orientação</li>
                    <li>Cada professor preenche o PEI da sua disciplina</li>
                    <li>Equipe responsável registrada em cada documento</li>
                    <li>Assinaturas e responsabilidades formalizadas</li>
                </ul>
            </div>

            <div class="leg-card">
                <div class="leg-card-header">
                    <div>
                        <div class="leg-card-law">LGPD · Arts. 11 e 46</div>
                        <div class="leg-card-title">Proteção de Dados do Estudante</div>
                    </div>
                </div>
                <p class="leg-card-desc">
                    Diagnósticos, laudos e dados clínicos de estudantes são dados sensíveis nos termos da Lei nº 13.709/2018, exigindo medidas técnicas e administrativas de segurança, controle de acesso por finalidade e rastreabilidade de todas as operações de tratamento.
                </p>
                <ul class="leg-card-items">
                    <li>Acesso restrito por perfil com permissões específicas</li>
                    <li>Registro de acesso a documentos com data e usuário</li>
                    <li>Exportação PDF com identificação de autoria</li>
                    <li>Nota de conformidade LGPD em todos os documentos gerados</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ── FUNCIONALIDADES ── --}}
<section id="funcionalidades">
    <div class="section-inner">
        <div class="section-tag">Funcionalidades</div>
        <h2 class="section-title">Tudo que a sua equipe precisa,<br>em um só lugar</h2>
        <p class="section-lead">
            Do cadastro do aluno à emissão do documento final, o Átrio acompanha cada etapa
            do processo de inclusão escolar.
        </p>

        <div class="features-grid">

            <div class="feature-card">
                <div class="feature-icon" style="background: #E8F0F9;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="1.8">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <div class="feature-title">Gestão de Alunos</div>
                <div class="feature-desc">
                    Cadastro completo com diagnóstico, responsáveis, turma e histórico escolar. Controle de laudos e documentos em um único perfil.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #EFF6E8;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3D7A27" stroke-width="1.8">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <div class="feature-title">Documentos Pedagógicos</div>
                <div class="feature-desc">
                    Criação e gestão de Estudo de Caso, PAEE e PEI com exportação dos documentos oficiais em PDF.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #FFF4E6;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#C77A00" stroke-width="1.8">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div class="feature-title">Laudos e Diagnósticos</div>
                <div class="feature-desc">
                    Upload e organização de laudos médicos com identificação automática por CID. Histórico completo de registros clínicos do aluno.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #F0F5FF;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div class="feature-title">Rotinas de Trabalho</div>
                <div class="feature-desc">
                    Hub centralizado de rotinas: acompanhamento de documentação de todos os alunos, adaptações de prova e gestão de atendimentos.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #F5F0FF;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="1.8">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                    </svg>
                </div>
                <div class="feature-title">Observações e Registros</div>
                <div class="feature-desc">
                    Registros diários de observações pedagógicas e comportamentais. Histórico organizado por data e perfil de quem registrou.
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon" style="background: #E8FAF7;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#009C8C" stroke-width="1.8">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </div>
                <div class="feature-title">PEI Consolidado</div>
                <div class="feature-desc">
                    O Plano Educacional Individualizado reúne automaticamente as contribuições de todos os professores em um documento final unificado.
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── DOCUMENTOS ── --}}
<section id="documentos" class="docs-showcase">
    <div class="section-inner">
        <div class="section-tag">Documentos</div>
        <h2 class="section-title">Documentação oficial<br>padronizada e digital</h2>
        <p class="section-lead">
            Todos os documentos seguem os modelos institucionais e podem ser exportados em PDF
            com os dados preenchidos automaticamente.
        </p>

        <div class="docs-grid">
            <div class="doc-pill">
                <div class="doc-dot" style="background: #004B8D;"></div>
                <div class="doc-info">
                    <div class="doc-name">Estudo de Caso</div>
                    <div class="doc-desc">Caracterização completa do estudante</div>
                </div>
            </div>
            <div class="doc-pill">
                <div class="doc-dot" style="background: #009C8C;"></div>
                <div class="doc-info">
                    <div class="doc-name">PAEE</div>
                    <div class="doc-desc">Plano de Atendimento Educacional Especializado</div>
                </div>
            </div>
            <div class="doc-pill">
                <div class="doc-dot" style="background: #7C3AED;"></div>
                <div class="doc-info">
                    <div class="doc-name">PEI</div>
                    <div class="doc-desc">Plano Educacional Individualizado consolidado</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── COMO FUNCIONA ── --}}
<section id="como-funciona">
    <div class="section-inner">
        <div class="section-tag">Como funciona</div>
        <h2 class="section-title">Do cadastro ao documento final</h2>
        <p class="section-lead">
            Um fluxo pensado para a realidade da equipe escolar, com cada perfil contribuindo
            na etapa certa.
        </p>

        <div class="steps-grid">
            <div class="step">
                <div class="step-circle">1</div>
                <div class="step-title">Cadastro do Aluno</div>
                <div class="step-desc">A secretaria registra o aluno com dados completos, diagnóstico e responsáveis.</div>
            </div>
            <div class="step">
                <div class="step-circle">2</div>
                <div class="step-title">Estudo de Caso</div>
                <div class="step-desc">A equipe pedagógica elabora a caracterização e análise inicial do estudante.</div>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <div class="step-title">PEI por Professor</div>
                <div class="step-desc">Cada professor preenche o inventário de habilidades da sua disciplina.</div>
            </div>
            <div class="step">
                <div class="step-circle">4</div>
                <div class="step-title">PEI Consolidado</div>
                <div class="step-desc">O sistema une todas as contribuições no documento final para exportação.</div>
            </div>
        </div>
    </div>
</section>

{{-- ── PERFIS ── --}}
<section id="perfis" class="roles-section">
    <div class="section-inner">
        <div class="section-tag">Perfis de acesso</div>
        <h2 class="section-title">Cada perfil no lugar certo</h2>
        <p class="section-lead">
            O Átrio adapta a interface e as funcionalidades de acordo com o papel de cada usuário dentro da escola.
        </p>

        <div class="roles-grid">

            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: rgba(0,75,141,0.3);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60CFCA" stroke-width="1.8">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="role-name">Administrador</div>
                        <div class="role-sub">Gestão completa do sistema</div>
                    </div>
                </div>
                <ul class="role-features">
                    <li>Cadastro e gestão de alunos, turmas e usuários</li>
                    <li>Acesso a todos os documentos de todos os alunos</li>
                    <li>Gerenciamento de laudos e diagnósticos</li>
                    <li>Elaboração do Estudo de Caso e PAEE</li>
                    <li>Exportação de documentos em PDF</li>
                </ul>
            </div>

            <div class="role-card">
                <div class="role-header">
                    <div class="role-icon" style="background: rgba(60,91,219,0.25);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#91A7FF" stroke-width="1.8">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="role-name">Professor</div>
                        <div class="role-sub">Foco nos alunos da disciplina</div>
                    </div>
                </div>
                <ul class="role-features">
                    <li>Acesso ao perfil dos alunos da sua turma</li>
                    <li>Preenchimento do PEI com metas e objetivos</li>
                    <li>Observações pedagógicas por aluno</li>
                    <li>Adequação curricular por disciplina</li>
                    <li>Exportação do PEI em PDF</li>
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- ── CTA FINAL ── --}}
<section class="cta-section">
    <div class="section-inner">
        <h2 class="section-title">Pronto para acessar o sistema?</h2>
        <p class="section-lead">
            Escolha seu perfil de acesso e entre no Átrio.
        </p>

        <div class="cta-cards">
            <a href="{{ route('login') }}?perfil=escola" class="cta-card">
                <div class="cta-card-icon" style="background: rgba(0,75,141,0.5);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#A8D4FF" stroke-width="1.8">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div class="cta-card-title">Acessar o Sistema</div>
                <div class="cta-card-desc">Administradores e professores</div>
                <span class="cta-card-btn" style="background: white; color: #004B8D;">Entrar →</span>
            </a>
        </div>
    </div>
</section>

{{-- ── FOOTER ── --}}
<footer class="footer">
    <div class="footer-brand">
        <div class="footer-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
            </svg>
        </div>
        <span class="footer-name">Átrio</span>
    </div>

    <div class="footer-links">
        <a href="{{ route('termos') }}">Termos de uso</a>
        <a href="{{ route('privacidade') }}">Privacidade</a>
        <a href="{{ route('suporte') }}">Suporte</a>
    </div>

    <div class="footer-copy">© {{ date('Y') }} Sistema Átrio</div>
</footer>

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
</script>

</body>
</html>
