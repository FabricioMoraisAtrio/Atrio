@extends('layouts.app')
@section('title', 'Jornada Alimentar — ' . $aluno->name)

@section('content')

<style>
@media print {
    aside, header, nav, .no-print { display: none !important; }
    body, main { background: #fff !important; }
    main { padding: 24px !important; }
    * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>

{{-- Cabeçalho --}}
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;" class="no-print">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('secretaria.seletividade.index') }}"
           style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--text-3); text-decoration: none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Jornada Alimentar
        </a>
        <span style="color: var(--text-3); font-size: 13px;">/</span>
        <span style="font-size: 13px; color: var(--text-1); font-weight: 500;">{{ $aluno->name }}</span>
    </div>
    <a href="{{ route('secretaria.seletividade.export.individual', $aluno) }}"
       style="display: flex; align-items: center; gap: 7px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; color: var(--text-2); text-decoration: none; cursor: pointer;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/>
        </svg>
        Exportar / Imprimir
    </a>
</div>

@if(session('success'))
    <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;">

    {{-- Perfil alimentar por categoria --}}
    <div>
        @forelse($categories as $catKey => $catLabel)
            @php $items = $byCategory->get($catKey, collect()); @endphp
            <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; margin-bottom: 12px; overflow: hidden;">
                <div style="padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: {{ $items->isNotEmpty() ? '1px solid var(--border)' : 'none' }};">
                    <span style="font-size: 13px; font-weight: 700; color: var(--text-1);">{{ $catLabel }}</span>
                    @if($items->isNotEmpty())
                        <span style="font-size: 11px; color: var(--text-3);">{{ $items->count() }} item(ns)</span>
                    @else
                        <span style="font-size: 11px; color: var(--text-3); font-style: italic;">Nenhum registrado</span>
                    @endif
                </div>
                @if($items->isNotEmpty())
                <div style="padding: 8px 12px; display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach($items as $item)
                    @php $s = $statuses[$item->status]; @endphp
                    <div style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px 5px 12px; border-radius: 20px; background: {{ $s['bg'] }}; border: 1px solid transparent;">
                        <span style="font-size: 12px; font-weight: 500; color: {{ $s['color'] }};">{{ $item->name }}</span>
                        @if($item->notes)
                            <span style="font-size: 10px; color: {{ $s['color'] }}; opacity: 0.7;" title="{{ $item->notes }}">· {{ Str::limit($item->notes, 30) }}</span>
                        @endif
                        <span style="font-size: 9px; font-weight: 700; color: {{ $s['color'] }}; opacity: 0.6; text-transform: uppercase; letter-spacing: 0.5px; background: rgba(0,0,0,0.06); padding: 1px 5px; border-radius: 10px;">{{ $s['label'] }}</span>
                        <form method="POST" action="{{ route('secretaria.seletividade.destroy', $item) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; cursor: pointer; color: {{ $s['color'] }}; opacity: 0.5; padding: 0; font-size: 13px; line-height: 1; display: flex; align-items: center;"
                                    title="Remover" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        @empty
        @endforelse
    </div>

    {{-- Formulário de adição --}}
    <div class="no-print" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; position: sticky; top: 20px;">
        <h3 style="font-size: 14px; font-weight: 700; color: var(--text-1); margin: 0 0 16px;">Registrar alimento</h3>
        <form method="POST" action="{{ route('secretaria.seletividade.store', $aluno) }}">
            @csrf
            @if($errors->any())
                <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; font-size: 12px; color: var(--danger);">
                    {{ $errors->first() }}
                </div>
            @endif

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Alimento</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Arroz, Banana, Frango..."
                       style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; background: var(--bg-card); color: var(--text-1); outline: none; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Categoria</label>
                <select name="category"
                        style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; background: var(--bg-card); color: var(--text-1); outline: none;">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Status</label>
                <div style="display: flex; gap: 8px;">
                    @foreach($statuses as $key => $s)
                    <label style="flex: 1; cursor: pointer;">
                        <input type="radio" name="status" value="{{ $key }}" {{ old('status', 'aceita') === $key ? 'checked' : '' }} style="display: none;" class="status-radio">
                        <div class="status-btn" data-status="{{ $key }}"
                             style="text-align: center; padding: 8px 4px; border-radius: 8px; border: 2px solid {{ old('status','aceita') === $key ? $s['color'] : 'var(--border)' }}; background: {{ old('status','aceita') === $key ? $s['bg'] : 'transparent' }}; transition: all 0.15s;">
                            <div style="font-size: 11px; font-weight: 700; color: {{ $s['color'] }};">{{ $s['label'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Observação <span style="font-weight: 400; text-transform: none;">(opcional)</span></label>
                <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Ex: só cru, sem casca..."
                       style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; background: var(--bg-card); color: var(--text-1); outline: none; box-sizing: border-box;">
            </div>

            <button type="submit"
                    style="width: 100%; background: var(--accent); color: white; border: none; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Registrar
            </button>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.status-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const statuses = {
            aceita:  { bg: 'var(--success-bg)', color: 'var(--success)' },
            tolera:  { bg: 'var(--warning-bg)', color: 'var(--warning)' },
            recusa:  { bg: 'var(--danger-bg)',  color: 'var(--danger)' },
        };
        document.querySelectorAll('.status-btn').forEach(function(btn) {
            const s = statuses[btn.dataset.status];
            btn.style.border = '2px solid var(--border)';
            btn.style.background = 'transparent';
        });
        const active = document.querySelector('.status-btn[data-status="' + this.value + '"]');
        const s = statuses[this.value];
        active.style.border = '2px solid ' + s.color;
        active.style.background = s.bg;
    });
});
</script>
@endsection
