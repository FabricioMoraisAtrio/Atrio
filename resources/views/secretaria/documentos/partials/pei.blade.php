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
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Objetivos pedagógicos</label>
    <textarea name="objetivos" rows="4"
              placeholder="Defina os objetivos específicos para este aluno..."
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
