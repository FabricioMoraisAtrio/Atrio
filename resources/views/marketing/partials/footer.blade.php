{{-- ── RODAPÉ (identidade Átrio: fecho + selo de conformidade + contato) ── --}}
<footer class="foot">

    {{-- Fecho / chamada final --}}
    <div class="foot-cta">
        <div>
            <h3>Organize a inclusão da sua escola</h3>
            <p>Comece pela avaliação pedagógica — o laudo é opcional. Veja o Átrio com o cenário da sua rede.</p>
        </div>
        <div class="foot-cta-actions">
            <a href="{{ route('contato') }}" class="btn btn-primary btn-lg">Agendar demonstração</a>
            <a href="{{ route('login') }}?perfil=escola" class="btn btn-ghost btn-lg">Entrar</a>
        </div>
    </div>

    {{-- Corpo --}}
    <div class="foot-main">
        <div class="foot-brand-zone">
            <div class="footer-brand">
                <div class="footer-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                        <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                    </svg>
                </div>
                <span class="footer-name">Átrio</span>
            </div>
            <p class="foot-mission">
                Do Estudo de Caso ao PEI consolidado, com acompanhamento por bimestre —
                fundado na legislação brasileira de inclusão.
            </p>
            <div class="foot-seal">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                <div>
                    <strong>Em conformidade</strong>
                    <span>Decretos 12.686 e 12.773/2025 · LGPD</span>
                </div>
            </div>
        </div>

        <div class="foot-cols">
            <div class="foot-col">
                <div class="foot-h">O processo</div>
                @foreach (\App\Support\Modulos::all() as $slug => $mod)
                    <a href="{{ route('modulo', $slug) }}">{{ $mod['nav'] }}</a>
                @endforeach
            </div>
            <div class="foot-col">
                <div class="foot-h">Institucional</div>
                <a href="{{ route('legislacao') }}">Base legal</a>
                <a href="{{ route('planos') }}">Planos</a>
                <a href="{{ route('duvidas') }}">Dúvidas</a>
                <a href="{{ route('contato') }}">Contato</a>
            </div>
            <div class="foot-col">
                <div class="foot-h">Fale com a gente</div>
                <a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Quero%20conhecer%20o%20%C3%81trio." target="_blank" rel="noopener" class="foot-contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.8 14.13c-.24.68-1.42 1.31-1.95 1.36-.53.05-1.03.24-3.48-.72-2.94-1.16-4.79-4.15-4.94-4.35-.14-.2-1.16-1.55-1.16-2.96 0-1.4.74-2.09 1-2.38.26-.29.57-.36.76-.36.19 0 .38 0 .55.01.18.01.42-.07.65.5.24.58.81 2 .88 2.15.07.14.12.31.02.51-.1.2-.15.31-.29.48-.14.17-.3.38-.43.51-.14.14-.29.29-.12.58.17.29.74 1.22 1.59 1.98 1.09.97 2.01 1.27 2.3 1.42.29.14.46.12.63-.07.17-.19.72-.84.91-1.13.19-.29.38-.24.65-.14.27.1 1.7.8 1.99.95.29.14.48.22.55.34.07.12.07.7-.17 1.38z"/></svg>
                    WhatsApp
                </a>
                <a href="mailto:suporte@atriosystem.com.br" class="foot-contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2 6 12 13 22 6"/></svg>
                    suporte@atriosystem.com.br
                </a>
                <a href="tel:+5542988423965" class="foot-contact-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.81.36 1.6.7 2.34a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.74-1.74a2 2 0 012.11-.45c.74.34 1.53.57 2.34.7A2 2 0 0122 16.92z"/></svg>
                    (42) 9 8842-3965
                </a>
            </div>
        </div>
    </div>

    {{-- Rodapé fino --}}
    <div class="foot-fine">
        <span>© {{ date('Y') }} Átrio · Sistema de gestão da inclusão escolar</span>
        <span>
            <a href="{{ route('termos') }}">Termos</a><span class="sep">·</span><a href="{{ route('privacidade') }}">Privacidade</a><span class="sep">·</span><a href="{{ route('suporte') }}">Suporte</a>
        </span>
    </div>
</footer>
