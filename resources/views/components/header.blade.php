<header class="bg-white border-b border-slate-200 h-20 flex justify-between items-center px-8 sticky top-0 z-20">
    <div class="flex items-center gap-4">
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">
            {{ $slot }}
        </h1>
    </div>

    <div class="flex items-center gap-6">
        @auth('admin')
            <div class="hidden md:flex flex-col text-right">
                <span class="text-sm font-semibold text-slate-700 leading-none">
                    {{ auth('admin')->user()->username }}
                </span>
                <span class="text-xs text-green-500 font-medium">Online</span>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-2 bg-rose-50 text-rose-600 border border-rose-100 px-4 py-2 rounded-xl font-medium transition-all hover:bg-rose-600 hover:text-white hover:border-rose-600 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        @endauth
    </div>
</header>