@extends('layouts.app')

@section('title', 'Overview')
@section('subtitle', 'Choose a task to begin')

@section('content')
<div class="space-y-6">

    {{-- Hero --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 flex items-center gap-6 shadow-sm">
        <div class="flex-1">
            <span class="inline-block text-[11px] font-semibold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full mb-3">
                2011 · Delta State
            </span>
            <h1 class="text-2xl font-semibold">Election Results Workspace</h1>
            <p class="text-sm text-gray-600 mt-2 max-w-2xl">
                Browse, verify and record polling unit results. All figures are derived
                from individual polling unit declarations and cross-checked against
                official LGA announcements.
            </p>
        </div>
        <div class="hidden sm:grid w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600 place-items-center shrink-0">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @include('partials.stat-tile', [
            'label' => 'LGAs', 'value' => $stats['lgas'], 'hint' => 'Delta State', 'tone' => 'brand',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/></svg>'
        ])
        @include('partials.stat-tile', [
            'label' => 'Wards', 'value' => $stats['wards'], 'hint' => 'Across all LGAs', 'tone' => 'neutral',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>'
        ])
        @include('partials.stat-tile', [
            'label' => 'Polling Units', 'value' => $stats['polling_units'], 'hint' => 'Registered in dataset', 'tone' => 'neutral',
            'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>'
        ])
    </div>

    {{-- Tasks --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold">Available Tasks</h3>
            <span class="text-xs text-gray-500">Pick one to begin</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $tasks = [
                    ['route' => 'polling-unit',  'title' => 'Polling Unit Result',
                     'desc'  => 'Look up an individual polling unit and view the breakdown of votes by party.',
                     'icon'  => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>'],
                    ['route' => 'lga-sum',  'title' => 'LGA Total',
                     'desc'  => 'See the summed total of all polling units under a chosen Local Government Area.',
                     'icon'  => '<path d="M18 7V4H6l6 8-6 8h12v-3"/>'],
                    ['route' => 'add-polling-unit',  'title' => 'Add Polling Unit',
                     'desc'  => 'Record results for every party in a brand-new polling unit.',
                     'icon'  => '<path d="M12 5v14M5 12h14"/>'],
                ];
            @endphp
            @foreach($tasks as $t)
                <a href="{{ route($t['route']) }}"
                   class="group flex flex-col p-5 bg-white border border-gray-200 rounded-2xl shadow-sm transition hover:border-blue-500 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 grid place-items-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $t['icon'] !!}</svg>
                        </div>
                    </div>
                    <h4 class="text-sm font-semibold mt-2">{{ $t['title'] }}</h4>
                    <p class="text-xs text-gray-500 mt-1 flex-1">{{ $t['desc'] }}</p>
                    <div class="flex items-center gap-1.5 text-blue-600 text-xs font-medium mt-3">
                        <span>Open</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-0.5 transition"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>
@endsection