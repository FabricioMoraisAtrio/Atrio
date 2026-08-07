@extends('marketing.layout')

@section('metaTitle', "Átrio — Sistema de Gestão da Inclusão Escolar (PEI, PAEE, Estudo de Caso)")
@section('metaDescription', "O Átrio centraliza Estudo de Caso, PAEE e PEI, acompanha a evolução por bimestre e mantém tudo em conformidade com a nova legislação. Agende uma demonstração.")

@section('content')
{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-badge">
        <span class="hero-badge-dot"></span>
        Conforme os Decretos 12.686 e 12.773/2025
    </div>

    <h1 class="hero-title">
        O apoio à inclusão começa pela<br>
        <span>avaliação pedagógica</span> — não pelo laudo
    </h1>

    <p class="hero-subtitle">
        O Átrio centraliza Estudo de Caso, PAEE e PEI, acompanha a evolução de cada estudante
        por bimestre e mantém tudo em conformidade com a nova legislação.
        Sua rede tem até <strong style="color:#fff;">maio de 2029</strong> para adequar os documentos.
    </p>

    <div class="hero-actions">
        <a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Quero%20agendar%20uma%20demonstra%C3%A7%C3%A3o%20do%20%C3%81trio." target="_blank" rel="noopener" class="btn btn-white btn-lg">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
            </svg>
            Agendar demonstração
        </a>
        <a href="{{ route('login') }}?perfil=escola" class="btn btn-ghost btn-lg">
            Acessar o sistema
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
            <div class="stat-number">4+</div>
            <div class="stat-label">Perfis de acesso, e customizáveis por escola</div>
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

{{-- ── O ÁTRIO POR DENTRO (PRODUTO) ── --}}
<section id="produto" class="produto-section">
    <div class="produto-head">
        <div class="section-tag">O sistema por dentro</div>
        <h2 class="section-title">Veja o Átrio funcionando —<br>não uma promessa de tela</h2>
        <p class="section-lead">
            Geradores de PEI mostram um modelo de documento e prometem a plataforma "em breve".
            Aqui você navega pelo perfil do estudante, pelo PEI por matéria e pela evolução por bimestre.
            É o sistema real, em uso.
        </p>
    </div>

    <div class="produto-stage">
        <div class="app-window">
            <div class="app-bar">
                <span class="app-dot" style="background:#FF5F57;"></span>
                <span class="app-dot" style="background:#FEBC2E;"></span>
                <span class="app-dot" style="background:#28C840;"></span>
                <span class="app-url">atriosystem.com.br/portal</span>
            </div>
            <div class="app-body">
                <aside class="app-side">
                    <div class="app-brand">ÁTRIO</div>
                    <div class="app-nav-item"><span class="dot"></span> Painel</div>
                    <div class="app-nav-item active"><span class="dot"></span> Estudantes</div>
                    <div class="app-nav-item"><span class="dot"></span> Documentos</div>
                    <div class="app-nav-item"><span class="dot"></span> Linha do Tempo</div>
                    <div class="app-nav-item"><span class="dot"></span> Adaptações</div>
                    <div class="app-nav-item"><span class="dot"></span> Configurações</div>
                </aside>
                <main class="app-main">
                    <div class="app-crumb">Estudantes &nbsp;›&nbsp; Perfil do estudante</div>
                    <div class="app-student">
                        <div class="app-avatar">JP</div>
                        <div>
                            <div class="app-student-name">João P. · 6º ano B</div>
                            <div class="app-badges">
                                <span class="app-badge blue">Público-alvo da Educação Especial</span>
                                <span class="app-badge teal">PEI ativo · 2026</span>
                            </div>
                        </div>
                    </div>

                    <div class="app-cards">
                        <div class="app-mini">
                            <div class="app-mini-label">PEI preenchido</div>
                            <div class="app-mini-value">68%</div>
                            <div class="app-bar-track"><span style="width:68%"></span></div>
                        </div>
                        <div class="app-mini">
                            <div class="app-mini-label">Metas ativas</div>
                            <div class="app-mini-value">12</div>
                            <div class="app-mini-hint">4 concluídas neste bimestre</div>
                        </div>
                        <div class="app-mini">
                            <div class="app-mini-label">Bimestre</div>
                            <div class="app-mini-value">2º <span>aberto</span></div>
                            <div class="app-mini-hint">Fecha em 30/06</div>
                        </div>
                    </div>

                    <div class="app-panel">
                        <div class="app-panel-head">
                            <span>Evolução das metas por bimestre</span>
                            <span class="app-panel-tag">Linha do Tempo</span>
                        </div>
                        <div class="app-chart">
                            <div class="app-col"><span style="height:32%"></span><em>1º bim</em></div>
                            <div class="app-col"><span style="height:54%"></span><em>2º bim</em></div>
                            <div class="app-col"><span style="height:71%"></span><em>3º bim</em></div>
                            <div class="app-col"><span style="height:88%"></span><em>4º bim</em></div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <div class="app-chip chip-a">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#009C8C" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            PEI consolidado gerado
        </div>
        <div class="app-chip chip-b">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Acesso registrado · LGPD
        </div>
    </div>

    <p class="produto-note">
        Representação da interface do Átrio. Agende uma demonstração para navegar com os dados da sua escola.
    </p>
</section>

{{-- ── COMPARATIVO ── --}}
<section id="comparativo" class="compare-section">
    <div class="section-inner">
        <div class="section-tag">Por que o Átrio</div>
        <h2 class="section-title">Um gerador de PEI avulso<br>não é um sistema de inclusão</h2>
        <p class="section-lead">
            Ferramentas soltas geram um documento. O Átrio conecta avaliação, planejamento,
            acompanhamento e conformidade — o processo inteiro, por aluno.
        </p>

        <div class="compare-grid">
            <div class="compare-col bad-col">
                <div class="compare-col-head">
                    <span class="compare-tag bad">Gerador avulso</span>
                    <h3>Um documento isolado</h3>
                </div>
                <ul class="compare-list">
                    <li>Gera um PEI solto, sem Estudo de Caso nem PAEE integrados</li>
                    <li>Depende de laudo e retrabalho manual a cada revisão</li>
                    <li>Não acompanha a evolução do estudante ao longo do ano</li>
                    <li>Sem controle de acesso por perfil nem rastreabilidade LGPD</li>
                    <li>Modelo de Word solto — sem respaldo do mínimo legal</li>
                </ul>
            </div>
            <div class="compare-col win">
                <div class="compare-col-head">
                    <span class="compare-tag good">Átrio</span>
                    <h3>O ecossistema da inclusão</h3>
                </div>
                <ul class="compare-list">
                    <li>Estudo de Caso, PAEE e PEI integrados e consolidados por estudante</li>
                    <li>Começa pela avaliação pedagógica — laudo é opcional</li>
                    <li>Linha do Tempo e evolução de metas por bimestre</li>
                    <li>Perfis por função, com registro de acesso e conformidade LGPD</li>
                    <li>Fundado em 5 bases legais (LBI, Decretos 2025, LDB, LGPD)</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA FINAL ── --}}
<section class="cta-section">
    <div class="section-inner">
        <h2 class="section-title">Pronto para organizar a inclusão<br>da sua escola?</h2>
        <p class="section-lead">
            Agende uma demonstração gratuita e veja o Átrio com o cenário da sua escola ou rede.
        </p>

        <div class="cta-cards">
            <a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Quero%20agendar%20uma%20demonstra%C3%A7%C3%A3o%20do%20%C3%81trio." target="_blank" rel="noopener" class="cta-card">
                <div class="cta-card-icon" style="background: rgba(22,163,74,0.55);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                    </svg>
                </div>
                <div class="cta-card-title">Agendar demonstração</div>
                <div class="cta-card-desc">Fale com um especialista no WhatsApp</div>
                <span class="cta-card-btn" style="background: #16A34A; color: #fff;">Conversar →</span>
            </a>
            <a href="{{ route('login') }}?perfil=escola" class="cta-card">
                <div class="cta-card-icon" style="background: rgba(0,75,141,0.5);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#A8D4FF" stroke-width="1.8">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div class="cta-card-title">Acessar o sistema</div>
                <div class="cta-card-desc">Para quem já é cliente</div>
                <span class="cta-card-btn" style="background: white; color: #004B8D;">Entrar →</span>
            </a>
        </div>

        <p class="section-lead" style="margin: 40px auto 0; font-size: 14px;">
            Prefere e-mail? <a href="mailto:suporte@atriosystem.com.br" style="color:#fff; font-weight:700; text-decoration:underline;">suporte@atriosystem.com.br</a>
        </p>
    </div>
</section>
@endsection
