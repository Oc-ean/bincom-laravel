<div class="flex flex-col h-full p-4">
    <div class="flex items-center gap-3 pb-5 mb-5 border-b border-gray-200">
        <div
            class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white grid place-items-center shadow-sm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 12 2 2 4-4" />
                <rect x="3" y="3" width="18" height="18" rx="2" />
            </svg>
        </div>
        <div class="leading-tight">
            <div class="text-sm font-bold">Bincom</div>
            <div class="text-xs text-gray-500">Election Results</div>
        </div>
    </div>

    <p class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold px-2 mb-2">Workspace</p>

    <nav class="flex flex-col gap-0.5">
        @php
            $items = [
                ['route' => 'home', 'label' => 'Overview', 'icon' => 'home'],
                ['route' => 'polling-unit', 'label' => 'Polling Unit', 'icon' => 'search'],
                ['route' => 'lga-sum', 'label' => 'LGA Total', 'icon' => 'sigma'],
                ['route' => 'add-polling-unit', 'label' => 'Add Polling Unit', 'icon' => 'plus'],
            ];
        @endphp

        @foreach($items as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
                class="relative flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13.5px] font-medium transition
                          {{ $active ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                @if($active)
                    <span class="absolute -left-4 top-2 bottom-2 w-[3px] rounded-r bg-blue-500"></span>
                @endif
                <span class="shrink-0">
                    @if($item['icon'] === 'home')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    @elseif($item['icon'] === 'search')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    @elseif($item['icon'] === 'sigma')
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 7V4H6l6 8-6 8h12v-3" />
                        </svg>
                    @else
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                    @endif
                </span>
                <span class="flex-1">{{ $item['label'] }}</span>
               
            </a>
        @endforeach
    </nav>

    <div class="mt-auto pt-4 border-t border-gray-200">
        <p class="text-[11px] text-gray-400 px-2">v1.0 · Delta State 2011</p>
    </div>
</div>