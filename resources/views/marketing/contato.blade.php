@extends('marketing.layout')

@section('metaTitle', 'Contato — Fale com o Átrio')
@section('metaDescription', 'Fale com um especialista do Átrio pelo WhatsApp ou e-mail e agende uma demonstração com o cenário da sua escola ou rede.')

@section('content')

{{-- ── CONTATO ── --}}
<section class="cta-section" style="min-height: 78vh; display:flex; align-items:center;">
    <div class="section-inner">
        <div class="section-tag" style="color:#60CFCA;">Contato</div>
        <h2 class="section-title">Fale com um especialista do Átrio</h2>
        <p class="section-lead" style="margin: 0 auto 48px;">
            Agende uma demonstração gratuita e veja o Átrio com o cenário da sua escola ou rede.
            Respondemos rápido — e a conversa não tem compromisso.
        </p>

        <div class="cta-cards">
            <a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Quero%20agendar%20uma%20demonstra%C3%A7%C3%A3o%20do%20%C3%81trio." target="_blank" rel="noopener" class="cta-card">
                <div class="cta-card-icon" style="background: rgba(22,163,74,0.55);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                    </svg>
                </div>
                <div class="cta-card-title">WhatsApp</div>
                <div class="cta-card-desc">Resposta rápida com um especialista</div>
                <span class="cta-card-btn" style="background: #16A34A; color: #fff;">(42) 9 8842-3965 →</span>
            </a>

            <a href="mailto:suporte@atriosystem.com.br?subject=Quero%20conhecer%20o%20%C3%81trio" class="cta-card">
                <div class="cta-card-icon" style="background: rgba(0,75,141,0.5);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#A8D4FF" stroke-width="1.8">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <polyline points="2 6 12 13 22 6"/>
                    </svg>
                </div>
                <div class="cta-card-title">E-mail</div>
                <div class="cta-card-desc">Para propostas e dúvidas detalhadas</div>
                <span class="cta-card-btn" style="background: white; color: #004B8D;">suporte@atriosystem.com.br →</span>
            </a>

            <a href="{{ route('login') }}?perfil=escola" class="cta-card">
                <div class="cta-card-icon" style="background: rgba(96,207,202,0.28);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#60CFCA" stroke-width="1.8">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                </div>
                <div class="cta-card-title">Já é cliente?</div>
                <div class="cta-card-desc">Acesse o sistema da sua escola</div>
                <span class="cta-card-btn" style="background: rgba(255,255,255,0.14); color: #fff;">Entrar →</span>
            </a>
        </div>

        <p class="section-lead" style="margin: 44px auto 0; font-size: 13px; color: rgba(255,255,255,0.55);">
            Atendemos professores, profissionais do AEE, escolas particulares e redes públicas
            (inclusive por inexigibilidade de licitação — Lei 14.133/2021).
        </p>
    </div>
</section>

@endsection
