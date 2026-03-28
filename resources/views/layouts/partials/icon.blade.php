@php $color = $active ? '#004B8D' : '#9CA3AF'; @endphp
@if($icon === 'grid')
<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
    <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
</svg>
@elseif($icon === 'academic')
<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
    <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
</svg>
@elseif($icon === 'users')
<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
    <circle cx="9" cy="7" r="4"/>
    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
</svg>
@elseif($icon === 'user')
<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
    <circle cx="12" cy="7" r="4"/>
</svg>
@elseif($icon === 'doc')
<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
    <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
</svg>
@elseif($icon === 'laudo')
<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $color }}" stroke-width="2">
    <path d="M9 12h6M9 16h6M17 21H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
    <path d="M13 3v5h5"/>
</svg>
@endif