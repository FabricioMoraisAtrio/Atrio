@extends('marketing.layout')

@section('metaTitle', "A Plataforma — Módulos do Átrio (Estudo de Caso, PAEE, PEI e mais)")
@section('metaDescription', "Conheça os módulos do Átrio: gestão de alunos, documentos pedagógicos, PEI consolidado, Linha do Tempo, adaptações para prova e perfis de acesso.")

@section('content')
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
                <div class="feature-title">Acompanhamento e Evolução</div>
                <div class="feature-desc">
                    Linha do Tempo do aluno e evolução das metas do PEI por bimestre, com abertura e fechamento de bimestre que congela e registra o resultado.
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

{{-- ── MÓDULOS (uma página por função) ── --}}
<section id="modulos" class="legislacao-section">
    <div class="section-inner">
        <div class="section-tag">Módulos</div>
        <h2 class="section-title">Cada função tem a sua página</h2>
        <p class="section-lead">
            O que cada documento é, o que reúne e em que base legal se apoia.
            Clique para abrir a página do módulo.
        </p>

        <div class="leg-cards">
            @foreach (\App\Support\Modulos::all() as $slug => $mod)
                <a class="leg-card" href="{{ route('modulo', $slug) }}" style="text-decoration:none;">
                    <div class="leg-card-header"><div>
                        <div class="leg-card-law">{{ $mod['law'] }}</div>
                        <div class="leg-card-title">{{ $mod['nav'] }}</div>
                    </div></div>
                    <p class="leg-card-desc">{{ $mod['tagline'] }} {{ $mod['desc'] }}</p>
                    <ul class="leg-card-items">
                        @foreach ($mod['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    <span style="display:inline-block; margin-top:16px; font-size:12px; font-weight:700; color:var(--blue);">Ver módulo →</span>
                </a>
            @endforeach
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
