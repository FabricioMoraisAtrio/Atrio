@php
    $activePattern = $item['active'] ?? ($item['route'] . '.*');
    $children      = $item['children'] ?? [];
    $childActive   = collect($children)->contains(fn($c) => request()->routeIs($c['route']) || request()->routeIs($c['active'] ?? ($c['route'] . '.*')));
    $isActive      = request()->routeIs($item['route']) || request()->routeIs($activePattern) || $childActive;
    $badge         = $item['badge'] ?? null;
    $hasChildren   = !empty($children);
@endphp
<a href="{{ route($item['route']) }}"
   style="display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; margin-bottom: 2px; font-size: 14px; font-weight: 500; text-decoration: none;
          {{ $isActive ? 'background: var(--accent-bg,#E8F0F9); color: var(--accent,#004B8D);' : 'color: #6B7280;' }}">
    <div style="display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1;">
        @include('layouts.partials.icon', ['icon' => $item['icon'], 'active' => $isActive])
        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item['label'] }}</span>
    </div>
    @if($badge)
        <span style="flex-shrink: 0; margin-left: 8px; background: #EF4444; color: white; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 20px;">{{ $badge }}</span>
    @elseif($hasChildren)
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             style="{{ $isActive ? 'transform:rotate(90deg);' : '' }} transition: transform 0.15s;">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    @endif
</a>

@if($hasChildren && $isActive)
    @foreach($children as $child)
        @php
            if (isset($child['module']) && !$hasModule($child['module'])) continue;
            $childActivePattern = $child['active'] ?? ($child['route'] . '.*');
            $childIsActive = request()->routeIs($child['route']) || request()->routeIs($childActivePattern);
            $childHref = route($child['route'], $child['params'] ?? []);
        @endphp
        <a href="{{ $childHref }}"
           style="display: flex; align-items: center; gap: 10px; padding: 8px 12px 8px 36px; border-radius: 8px; margin-bottom: 2px; font-size: 13px; font-weight: 500; text-decoration: none;
                  {{ $childIsActive ? 'background: var(--accent-bg,#E8F0F9); color: var(--accent,#004B8D);' : 'color: #9CA3AF;' }}">
            @include('layouts.partials.icon', ['icon' => $child['icon'], 'active' => $childIsActive])
            {{ $child['label'] }}
        </a>
    @endforeach
@endif
