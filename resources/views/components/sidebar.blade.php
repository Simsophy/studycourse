<aside class="w-72 bg-slate-900 text-slate-300 border-r border-slate-800 flex flex-col h-screen sticky top-0">
    <div class="p-6 border-b border-slate-800/50">
        <h2 class="font-bold text-xl text-white tracking-tight flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <span class="text-white text-sm">EP</span>
            </div>
            EduPlatform <span class="text-indigo-400 text-xs font-medium">Admin</span>
        </h2>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
        {{ $slot }}
    </nav>

    <div class="p-4 border-t border-slate-800">
        <div class="flex items-center gap-3 px-2 py-2 rounded-lg bg-slate-800/50">
            <div class="w-8 h-8 rounded-full bg-indigo-500 flex-shrink-0 flex items-center justify-center text-white font-bold text-xs">
                {{ substr(auth('admin')->user()->username, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ auth('admin')->user()->username }}</p>
                <p class="text-xs text-slate-500 truncate">Administrator</p>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Optional: Smooth scrollbar for the sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 10px;
    }
</style>