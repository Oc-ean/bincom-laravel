<header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-8 flex items-center gap-4 sticky top-0 z-10">
    <button class="lg:hidden w-10 h-10 rounded-lg border border-gray-200 text-gray-600 grid place-items-center hover:bg-gray-100"
            onclick="openDrawer()" aria-label="Open menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
    </button>

    <div class="flex-1 min-w-0">
        <h2 class="text-base font-semibold truncate">@yield('title', 'Overview')</h2>
        <p class="hidden sm:block text-xs text-gray-500 truncate">@yield('subtitle', 'Choose a task to begin')</p>
    </div>

    <button class="hidden sm:grid w-9 h-9 place-items-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100" aria-label="Notifications">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
    </button>
    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white text-xs font-bold grid place-items-center shadow-sm" title="Assessor">
        BA
    </div>
</header>