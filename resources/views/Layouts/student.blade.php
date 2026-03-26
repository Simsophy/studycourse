<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Area')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    @php
        $student = auth()->user();
        $studentName = $student->name ?? $student->username ?? 'Student';
    @endphp

    <div class="flex min-h-screen overflow-hidden">
        <aside id="studentSidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-700/60 bg-slate-800 text-slate-100 shadow-2xl transition-transform duration-300 lg:static lg:translate-x-0">
            <div class="flex h-full flex-col">
                <div class="px-6 py-5 border-b border-slate-700/70">
                    <a href="{{ auth()->check() ? route('user.dashboard') : route('login') }}" class="font-black text-3xl tracking-tight text-white">
                        Edu<span class="text-cyan-300">Portal</span>
                    </a>
                </div>

                <div class="px-4 py-4 border-b border-slate-700/70 bg-slate-900/40">
                    <div class="flex items-center gap-3 rounded-2xl bg-slate-700/40 p-3">
                        <div class="w-11 h-11 rounded-full bg-cyan-500 text-slate-900 font-black flex items-center justify-center">
                            {{ strtoupper(substr($studentName, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm truncate">{{ $studentName }}</p>
                            <p class="text-xs text-emerald-300">● Online</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-5 space-y-2 overflow-y-auto">
                    <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.25em] text-slate-400">Menu</p>

                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('user.dashboard') ? 'bg-cyan-500 text-slate-900' : 'text-slate-200 hover:bg-slate-700/70' }} transition">
                        <span>🏠</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('user.lessons.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('user.lessons.*') || request()->routeIs('user.courses.lessons.*') ? 'bg-indigo-500/70 text-white' : 'text-slate-200 hover:bg-slate-700/70' }} transition">
                        <span>📖</span>
                        <span>My Lessons</span>
                    </a>

                    <a href="{{ route('user.settings') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('user.settings') ? 'bg-amber-500/80 text-slate-900' : 'text-slate-200 hover:bg-slate-700/70' }} transition">
                        <span>⚙️</span>
                        <span>Settings</span>
                    </a>
                </nav>

                @auth
                    <div class="p-4 border-t border-slate-700/70">
                        <a href="{{ route('user.logout.form') }}" class="block w-full rounded-xl bg-rose-500 px-3 py-2.5 text-sm font-bold text-white text-center hover:bg-rose-600 transition">
                            Logout
                        </a>
                    </div>
                @endauth
            </div>
        </aside>

        <div id="studentOverlay" class="fixed inset-0 z-30 hidden bg-slate-950/45 lg:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <button id="studentSidebarToggle" class="inline-flex lg:hidden w-10 h-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 bg-white">
                            ☰
                        </button>
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 truncate">@yield('title', 'Student Area')</h1>
                    </div>

                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ route('user.logout.form') }}" class="hidden sm:block rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-600 hover:bg-rose-600 hover:text-white transition">
                                Logout
                            </a>
                        @endauth

                        <div class="flex items-center gap-2 text-sm rounded-xl border border-slate-200 bg-white p-1">
                        <a href="{{ route('locale.switch', 'en') }}"
                           class="px-3 py-1 rounded-lg transition {{ app()->getLocale() === 'en' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-500 hover:text-slate-700' }}">
                            EN
                        </a>
                        <a href="{{ route('locale.switch', 'kh') }}"
                           class="px-3 py-1 rounded-lg transition {{ app()->getLocale() === 'kh' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-slate-500 hover:text-slate-700' }}">
                            KH
                        </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (() => {
            const toggleBtn = document.getElementById('studentSidebarToggle');
            const sidebar = document.getElementById('studentSidebar');
            const overlay = document.getElementById('studentOverlay');

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