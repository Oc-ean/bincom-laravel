@props(['label', 'value', 'hint' => null, 'tone' => 'brand', 'icon' => null])

@php
    $iconBg = match($tone) {
        'success' => 'bg-emerald-50 text-emerald-600',
        'warn'    => 'bg-amber-50 text-amber-600',
        'neutral' => 'bg-gray-100 text-gray-600',
        default   => 'bg-blue-50 text-blue-600',
    };
@endphp

<div class="flex gap-3.5 p-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
    @if($icon)
        <div class="w-9 h-9 rounded-xl grid place-items-center shrink-0 {{ $iconBg }}">
            {!! $icon !!}
        </div>
    @endif
    <div class="min-w-0">
        <p class="text-[11px] uppercase tracking-wider font-semibold text-gray-500">{{ $label }}</p>
        <p class="text-xl font-semibold mt-1 tabular-nums">{{ $value }}</p>
        @if($hint)<p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>@endif
    </div>
</div>