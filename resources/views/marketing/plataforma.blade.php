@extends('marketing.layout')

@section('metaTitle', "A Plataforma — Módulos do Átrio (Estudo de Caso, PAEE, PEI e mais)")
@section('metaDescription', "Conheça os módulos do Átrio: gestão de estudantes, documentos pedagógicos, PEI consolidado, Linha do Tempo, adaptações para prova e perfis de acesso.")

@section('content')

{{-- ── MÓDULOS (uma página por função) ── --}}
<section id="modulos" class="legislacao-section">
    <div class="section-inner">
        <div class="section-tag">A Plataforma</div>
        <h1 class="section-title">Cada função da inclusão tem a sua página</h1>
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
                <div class="step-title">Cadastro do Estudante</div>
                <div class="step-desc">A secretaria registra o estudante com dados completos, diagnóstico e responsáveis.</div>
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
