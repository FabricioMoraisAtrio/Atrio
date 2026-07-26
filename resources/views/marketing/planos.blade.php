@extends('marketing.layout')

@section('metaTitle', "Planos — Átrio para professores, escolas e redes")
@section('metaDescription', "Planos sob medida: do profissional que atua com os alunos à rede que precisa padronizar a inclusão. Fale com um especialista.")

@section('content')
{{-- ── PLANOS / CONTRATAÇÃO ── --}}
<section id="planos" class="compare-section">
    <div class="section-inner">
        <div class="section-tag">Planos</div>
        <h2 class="section-title">Planos sob medida<br>para a sua realidade</h2>
        <p class="section-lead">
            Do profissional que atua direto com os alunos à rede que precisa padronizar
            toda a inclusão. Fale com a gente e montamos o plano ideal.
        </p>

        <div class="compare-grid">
            <div class="compare-col">
                <div class="compare-col-head">
                    <span class="compare-tag good">Professor(a) / AEE</span>
                </div>
                <h3 style="margin-bottom: 8px;">Para quem atua com os alunos</h3>
                <p style="font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 20px;">
                    Organize os documentos e o acompanhamento da sua sala ou do seu atendimento.
                </p>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--teal);font-weight:800;">✓</span> PEI por matéria com metas e adaptações</li>
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--teal);font-weight:800;">✓</span> Observações e acompanhamento por bimestre</li>
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--teal);font-weight:800;">✓</span> Adaptações para prova e exportação em PDF</li>
                </ul>
                <a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Sou%20professor(a)%20e%20quero%20conhecer%20o%20plano%20do%20%C3%81trio." target="_blank" rel="noopener" class="btn btn-outline btn-lg" style="width:100%; justify-content:center;">Falar sobre o plano</a>
            </div>

            <div class="compare-col win">
                <div class="compare-col-head">
                    <span class="compare-tag good">Escola ou Rede</span>
                </div>
                <h3 style="margin-bottom: 8px;">Gestão completa da inclusão</h3>
                <p style="font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 20px;">
                    Padronize documentos, perfis e conformidade em toda a instituição.
                </p>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--blue);font-weight:800;">✓</span> Estudo de Caso, PAEE e PEI integrados</li>
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--blue);font-weight:800;">✓</span> Perfis por função + rastreabilidade LGPD</li>
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--blue);font-weight:800;">✓</span> Linha do Tempo, bimestres e módulos por escola</li>
                    <li style="display:flex;gap:10px;font-size:13px;color:var(--slate);line-height:1.5;"><span style="color:var(--blue);font-weight:800;">✓</span> Documentos com a identidade da instituição</li>
                </ul>
                <a href="https://wa.me/5542988423965?text=Ol%C3%A1!%20Represento%20uma%20escola%2Frede%20e%20quero%20uma%20demonstra%C3%A7%C3%A3o%20do%20%C3%81trio." target="_blank" rel="noopener" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;">Agendar demonstração</a>
            </div>
        </div>

        <p style="text-align:center; font-size:13px; color:var(--muted); margin-top:28px;">
            Valores sob consulta — cada escola tem o seu contexto. Fale com um especialista pelo WhatsApp ou por <a href="mailto:suporte@atriosystem.com.br" style="color:var(--blue); font-weight:700;">suporte@atriosystem.com.br</a>.
        </p>
    </div>
</section>
@endsection
