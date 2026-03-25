<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .admin-grid-bg {
            background-image:
                radial-gradient(circle at 20% 10%, rgba(99, 102, 241, 0.12), transparent 22%),
                radial-gradient(circle at 80% 0%, rgba(168, 85, 247, 0.08), transparent 25%),
                linear-gradient(to bottom, #f8fafc, #f1f5f9);
        }
    </style>
</head>
<body class="admin-grid-bg text-slate-900">
@php
    $adminUser = Auth::guard('admin')->user();
    $adminDisplayName = $adminUser->name ?? $adminUser->username ?? 'Admin';
@endphp

<div class="flex min-h-screen overflow-hidden">
    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-indigo-700/30 bg-gradient-to-b from-slate-900 via-indigo-950 to-slate-900 text-slate-100 shadow-2xl transition-transform duration-300 lg:static lg:translate-x-0">
        <div class="flex h-full flex-col">
            <div class="p-6 border-b border-slate-800/80">
                <h2 class="font-black text-3xl tracking-tight bg-gradient-to-r from-indigo-300 to-cyan-200 bg-clip-text text-transparent">
                    EduPlatform
                </h2>
                <p class="text-xs text-slate-300 uppercase tracking-[0.22em] mt-2">{{ __('ui.admin_panel') }}</p>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-1.5 overflow-y-auto">
                @yield('sidebar')
            </nav>

            <div class="px-5 py-4 border-t border-slate-800/80">
                <p class="text-[11px] text-slate-500 uppercase tracking-[0.2em] mb-2">System Status</p>
                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-xs text-emerald-300">
                    ● All services operational
                </div>
            </div>
        </div>
    </aside>

    <div id="adminOverlay" class="fixed inset-0 z-30 hidden bg-slate-950/45 backdrop-blur-[1px] lg:hidden"></div>

    <div class="flex-1 flex flex-col overflow-y-auto lg:ml-0">
        <header class="backdrop-blur bg-white/85 border-b border-slate-200/80 h-20 flex justify-between items-center px-4 sm:px-8 sticky top-0 z-20">
            <div class="flex items-center gap-3 min-w-0">
                <button id="adminSidebarToggle" type="button" class="inline-flex lg:hidden w-10 h-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                    ☰
                </button>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 truncate">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ now()->format('l, d M Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 min-w-[260px]">
                    <span class="text-slate-400 text-sm">🔎</span>
                    <input type="text" placeholder="Search here..." class="w-full text-sm bg-transparent border-0 focus:ring-0 focus:outline-none text-slate-700 placeholder:text-slate-400">
                </div>

                <div class="flex items-center gap-2 text-sm rounded-xl border border-slate-200 bg-white p-1">
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="px-3 py-1.5 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-500 hover:text-slate-700' }}">
                        EN
                    </a>
                    <a href="{{ route('locale.switch', 'kh') }}"
                       class="px-3 py-1.5 rounded-lg transition {{ app()->getLocale() === 'kh' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-500 hover:text-slate-700' }}">
                        KH
                    </a>
                </div>

                @auth('admin')
                    <div class="hidden md:flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr($adminDisplayName, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 leading-tight">{{ __('ui.hello') }}</p>
                            <p class="text-sm font-semibold text-slate-800 leading-tight">{{ $adminDisplayName }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-2 rounded-xl text-sm font-semibold transition-all hover:bg-rose-600 hover:text-white active:scale-95 shadow-sm">
                            {{ __('ui.logout') }}
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        <main class="p-5 md:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    (() => {
        const toggleBtn = document.getElementById('adminSidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');

        if (!toggleBtn || !sidebar || !overlay) return;

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };

        toggleBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        overlay.addEventListener('click', closeSidebar);
    })();
</script>
</body>
</html>