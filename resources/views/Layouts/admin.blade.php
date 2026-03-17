<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

<div class="flex h-screen overflow-hidden">
    <aside class="w-72 bg-slate-900 text-white flex flex-col shadow-xl">
        <div class="p-6">
            <h2 class="font-bold text-2xl tracking-tight text-indigo-400">EduPlatform</h2>
            <p class="text-xs text-slate-400 uppercase tracking-widest mt-1">Admin Panel</p>
        </div>
        
        <nav class="flex-1 px-4 py-2 space-y-1">
            @yield('sidebar')
        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white border-b border-slate-200 h-16 flex justify-between items-center px-8 sticky top-0 z-10">
            <h1 class="text-xl font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            
            <div class="flex items-center gap-4">
                @auth('admin')
                    <span class="text-sm text-slate-500 mr-2">Hello, {{ Auth::guard('admin')->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-1.5 rounded-lg text-sm font-medium transition-all hover:bg-rose-600 hover:text-white active:scale-95">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        <main class="p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>
</body>
</html>