@extends('marketing.layout')

@section('metaTitle', "Dúvidas Frequentes — Átrio")
@section('metaDescription', "As perguntas mais comuns sobre o Átrio: legislação 2025, laudo médico, LGPD, uso pela equipe e contratação.")

@section('content')
{{-- ── FAQ ── --}}
<section id="faq">
    <div class="section-inner">
        <div class="section-tag">Perguntas frequentes</div>
        <h2 class="section-title">Tirando as dúvidas mais comuns</h2>
        <p class="section-lead">
            O que as escolas mais perguntam antes de começar com o Átrio.
        </p>

        <div class="faq-list">
            <div class="faq-item">
                <button type="button" class="faq-q">O Átrio está de acordo com a nova legislação de 2025?</button>
                <div class="faq-a"><p>Sim. Os documentos (Estudo de Caso, PAEE e PEI) seguem o que preveem a LBI (Lei 13.146/2015), os Decretos 12.686 e 12.773/2025, a LDB e a LGPD. A avaliação pedagógica é a porta de entrada — o laudo médico é opcional, não obrigatório.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Precisamos do laudo médico para começar?</button>
                <div class="faq-a"><p>Não. O apoio parte da avaliação pedagógica do estudante (Estudo de Caso), feita pela própria equipe escolar. O laudo, quando existe, é anexado como complemento — mas o aluno não precisa esperar por ele para ter o PEI.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Os dados dos alunos ficam seguros? E a LGPD?</button>
                <div class="faq-a"><p>Sim. Diagnósticos e laudos são dados sensíveis: o acesso é restrito por perfil e permissão, cada acesso a documento é registrado (data e usuário) e os PDFs saem com identificação de autoria e nota de conformidade LGPD.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">A equipe vai conseguir usar sem dificuldade?</button>
                <div class="faq-a"><p>Sim. Cada perfil (coordenação, AEE, professor) vê apenas o que precisa, e o PEI é preenchido de forma colaborativa — cada professor cuida da sua matéria e o sistema consolida tudo automaticamente. Fazemos a demonstração guiada.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Já temos documentos análogos. Precisamos refazer tudo?</button>
                <div class="faq-a"><p>Não. Redes com documentos análogos ao PAEE e ao PEI têm prazo para adequá-los à nova norma. O Átrio ajuda nessa transição, estruturando os documentos no padrão exigido e mantendo o histórico por aluno.</p></div>
            </div>
            <div class="faq-item">
                <button type="button" class="faq-q">Como funciona a contratação?</button>
                <div class="faq-a"><p>Fale com um especialista pelo WhatsApp ou e-mail e agende uma demonstração. Apresentamos o sistema com o cenário da sua escola ou rede e montamos a melhor forma de começar.</p></div>
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
