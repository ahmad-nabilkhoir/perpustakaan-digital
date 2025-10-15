<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">

    {{-- Navbar --}}
    <nav class="fixed left-0 right-0 top-0 z-50 bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">

                {{-- Logo dan Judul --}}
                <div class="flex flex-col">
                    <a href="{{ route('home') }}"
                        class="text-xl font-extrabold text-white drop-shadow transition-transform hover:scale-105">
                        📚 Perpustakaan Jateng
                    </a>
                    <span class="text-xs text-blue-100">Sistem Manajemen Perpustakaan</span>
                </div>

                {{-- Menu Kanan --}}
                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                            class="rounded-full bg-green-500 px-5 py-2 text-sm font-medium text-white shadow-md transition-transform hover:scale-105 hover:bg-green-600 hover:shadow-lg">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="rounded-full bg-red-500 px-5 py-2 text-sm font-medium text-white shadow-md transition-transform hover:scale-105 hover:bg-red-600 hover:shadow-lg">
                                Logout
                            </button>
                        </form>
                    @else
                        {{-- Jika belum login --}}
                        {{-- 
                        <a href="{{ route('login') }}"
                            class="rounded-full bg-blue-500 px-5 py-2 text-sm font-medium text-white shadow-md hover:bg-blue-700 hover:shadow-lg transition-transform hover:scale-105">
                            Login Admin
                        </a> 
                        --}}
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Konten --}}
    <main class="pb-10 pt-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="mt-12 bg-gradient-to-r from-purple-600 to-blue-600 py-6 shadow-inner">
        <div class="mx-auto max-w-7xl px-4 text-center text-sm text-white">
            &copy; {{ date('Y') }} <span class="font-semibold">Perpustakaan Digital Jawa Tengah</span>
        </div>
    </footer>

</body>

</html>
