<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Student Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

<!-- Top Navbar -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <h1 class="text-xl font-bold text-blue-600">
            Student Portal
        </h1>

       <nav class="flex items-center gap-6">
    @auth
        <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-700">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">Login</a>
        <a href="{{ route('register') }}" class="bg-gray-100 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-200">Register</a>
    @endauth
</nav>
    </div>
</header>


<div class="flex flex-1">

<!-- Sidebar -->
@auth
<aside class="w-64 bg-white shadow-lg min-h-screen p-5">

    <h2 class="text-gray-500 uppercase text-sm mb-4">
        Navigation
    </h2>

    <ul class="space-y-2">

        <li>
            <a href="{{ route('user.dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-blue-100">
                Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('user.lessons.index') }}"
               class="block px-4 py-2 rounded hover:bg-blue-100">
                Courses
            </a>
        </li>

    </ul>

</aside>
@endauth


<!-- Main Content -->
<main class="flex-1 p-8">

    <div class="bg-white p-6 rounded-lg shadow">

        @yield('content')

    </div>

</main>

</div>

</body>
</html>