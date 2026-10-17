<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bincom Election Results') · Bincom</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden lg:flex flex-col w-64 shrink-0 bg-white border-r border-gray-200 sticky top-0 h-screen">
            @include('partials.sidebar')
        </aside>

        <div id="mobile-drawer"
             class="fixed inset-0 z-50 hidden lg:hidden"
             x-data>
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeDrawer()"></div>
            <aside class="relative w-72 max-w-[85vw] h-full bg-white shadow-xl flex flex-col">
                <div class="flex justify-end p-3">
                    <button onclick="closeDrawer()"
                            class="w-9 h-9 grid place-items-center rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                @include('partials.sidebar')
            </aside>
        </div>

        <div class="flex-1 flex flex-col min-w-0">
            @include('partials.topbar')
            <main class="flex-1 px-4 sm:px-8 py-6 max-w-6xl w-full mx-auto">
                @yield('content')
            </main>
            <footer class="px-4 sm:px-8 py-5 border-t border-gray-200 bg-white text-center text-xs text-gray-500">
                Delta State 2011 Election · Interview Test
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
</html>