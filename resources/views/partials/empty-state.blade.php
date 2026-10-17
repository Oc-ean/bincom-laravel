@props(['title' => 'Nothing here yet', 'description' => null])

<div class="border border-dashed border-gray-300 rounded-xl bg-gray-50 text-center py-10 px-5">
    <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-white border border-gray-200 grid place-items-center text-gray-400">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
    </div>
    <h4 class="text-sm font-semibold">{{ $title }}</h4>
    @if($description)
        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">{{ $description }}</p>
    @endif
    @if(!$slot->isEmpty())<div class="mt-4">{{ $slot }}</div>@endif
</div>