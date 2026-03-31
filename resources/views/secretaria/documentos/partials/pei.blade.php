@php
    $content = $documento->content ?? [];

    $todasAdaptacoes = [
        'Tempo extra na prova',
        'Prova com fonte ampliada',
        'Prova com imagens de apoio',
        'Avaliação oral',
        'Redução de questões',
        'Questões objetivas (sem dissertativas)',
        'Material concreto/manipulativo',
        'Texto de apoio',
        'Prova em braille',
        'Intérprete de Libras',
        'Sala separada',
        'Leitura em voz alta pelo professor',
        'Uso de calculadora',
        'Apoio de escriba',
        'Adaptação de conteúdo',
        'Gravação de resposta (áudio)',
        'Prova digitalizada',
        'Sem limite de tempo',
    ];

    // Suporta tanto array (novo) quanto string (legado)
    $selecionadas = $content['adaptacoes'] ?? [];
    if (is_string($selecionadas)) {
        $selecionadas = array_filter(array_map('trim', explode(',', $selecionadas)));
    }
    $selecionadas = array_values($selecionadas);
@endphp

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Disciplina / Matéria</label>
    <input type="text" name="materia" value="{{ old('materia', $content['materia'] ?? '') }}"
           placeholder="Ex: Matemática, Português, Ciências..."
           style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
</div>

{{-- ── BLOCO 1: NÍVEL DE DESENVOLVIMENTO ── --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 16px;">Nível atual de desenvolvimento</p>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Acadêmico</label>
        <textarea name="nivel_academico" rows="3"
                  placeholder="Descreva o nível atual de aprendizagem e desempenho acadêmico..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('nivel_academico', $content['nivel_academico'] ?? '') }}</textarea>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Comunicação</label>
        <textarea name="nivel_comunicacao" rows="3"
                  placeholder="Descreva como o aluno se comunica, vocabulário, compreensão oral e escrita..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('nivel_comunicacao', $content['nivel_comunicacao'] ?? '') }}</textarea>
    </div>

    <div>
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Socioemocional</label>
        <textarea name="nivel_socioemocional" rows="3"
                  placeholder="Descreva o desenvolvimento emocional, interações sociais, autorregulação..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('nivel_socioemocional', $content['nivel_socioemocional'] ?? '') }}</textarea>
    </div>
</div>

{{-- ── BLOCO 2: METAS ESTRUTURADAS ── --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Metas do período letivo</p>
    <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 16px;">Descreva metas específicas e mensuráveis — inclua o critério de alcance (ex.: "o aluno será capaz de... com X% de acerto").</p>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Curto prazo (bimestre)</label>
        <textarea name="metas_curto_prazo" rows="3"
                  placeholder="Ex.: Identificar as letras do alfabeto com 80% de acerto até o final do bimestre."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('metas_curto_prazo', $content['metas_curto_prazo'] ?? '') }}</textarea>
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Médio prazo (semestre)</label>
        <textarea name="metas_medio_prazo" rows="3"
                  placeholder="Ex.: Produzir textos simples de até 5 linhas com apoio de figura ao final do semestre."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('metas_medio_prazo', $content['metas_medio_prazo'] ?? '') }}</textarea>
    </div>

    <div>
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Longo prazo (ano letivo)</label>
        <textarea name="metas_longo_prazo" rows="3"
                  placeholder="Ex.: Ler e interpretar textos do cotidiano com autonomia ao final do ano letivo."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('metas_longo_prazo', $content['metas_longo_prazo'] ?? '') }}</textarea>
    </div>
</div>

{{-- ── BLOCO 3: ESTRATÉGIAS E SUPORTE ESPECIALIZADO ── --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 16px;">Estratégias e suporte especializado</p>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Estratégias pedagógicas</label>
        <textarea name="estrategias_pedagogicas" rows="4"
                  placeholder="Descreva as estratégias utilizadas em sala: recursos visuais, rotina estruturada, pareamento, modelagem..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('estrategias_pedagogicas', $content['estrategias_pedagogicas'] ?? '') }}</textarea>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">AEE — Frequência e horário</label>
            <input type="text" name="aee_frequencia"
                   value="{{ old('aee_frequencia', $content['aee_frequencia'] ?? '') }}"
                   placeholder="Ex.: 2x por semana, terças e quintas 10h"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">CAA — Recursos de comunicação</label>
            <input type="text" name="caa_recursos"
                   value="{{ old('caa_recursos', $content['caa_recursos'] ?? '') }}"
                   placeholder="Ex.: Prancha de comunicação, PECS, tablet com app..."
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
    </div>
</div>

{{-- ── OBJETIVOS PEDAGÓGICOS (mantido por compatibilidade) ── --}}
<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Objetivos pedagógicos gerais</label>
    <textarea name="objetivos" rows="4"
              placeholder="Síntese dos objetivos pedagógicos para este aluno nesta disciplina..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('objetivos', $content['objetivos'] ?? '') }}</textarea>
</div>

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px;">Adaptações curriculares</label>

    <div id="tags-container" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px;">
        @foreach($todasAdaptacoes as $tag)
            @php $ativa = in_array($tag, $selecionadas); @endphp
            <button type="button"
                    onclick="toggleTag(this, '{{ $tag }}')"
                    data-tag="{{ $tag }}"
                    style="padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500; cursor: pointer; border: 1.5px solid; transition: all 0.15s;
                        {{ $ativa ? 'background: #004B8D; color: white; border-color: #004B8D;' : 'background: white; color: #6B7280; border-color: #D1D5DB;' }}">
                {{ $tag }}
            </button>
        @endforeach
    </div>

    {{-- Campo oculto que armazena os valores selecionados --}}
    <input type="hidden" name="adaptacoes" id="adaptacoes-input"
           value="{{ old('adaptacoes', json_encode($selecionadas)) }}">

    <div style="font-size: 12px; color: #9CA3AF;">
        <span id="tags-count">{{ count($selecionadas) }}</span> adaptação(ões) selecionada(s)
    </div>
</div>

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Adaptações de avaliação</label>
    <textarea name="avaliacao" rows="3"
              placeholder="Como as avaliações serão adaptadas?"
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('avaliacao', $content['avaliacao'] ?? '') }}</textarea>
</div>

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Observações livres</label>
    <textarea name="observacoes_livres" rows="3"
              placeholder="Informações adicionais relevantes sobre o aluno, família ou contexto..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('observacoes_livres', $content['observacoes_livres'] ?? '') }}</textarea>
</div>

<script>
(function() {
    // Inicializa o array de tags selecionadas a partir do campo oculto
    var input = document.getElementById('adaptacoes-input');
    var selected = [];
    try { selected = JSON.parse(input.value) || []; } catch(e) { selected = []; }

    window.toggleTag = function(btn, tag) {
        var idx = selected.indexOf(tag);
        if (idx === -1) {
            selected.push(tag);
            btn.style.background = '#004B8D';
            btn.style.color = 'white';
            btn.style.borderColor = '#004B8D';
        } else {
            selected.splice(idx, 1);
            btn.style.background = 'white';
            btn.style.color = '#6B7280';
            btn.style.borderColor = '#D1D5DB';
        }
        input.value = JSON.stringify(selected);
        document.getElementById('tags-count').textContent = selected.length;
    };
})();
</script>
