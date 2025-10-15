<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Tailwind & Alpine.js --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7f9;
            margin: 0;
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="flex bg-gray-100">

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 transform bg-[#1e293b] text-white shadow-xl transition-transform duration-300 ease-in-out lg:static lg:flex-shrink-0 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- Header Sidebar -->
        <div class="flex items-center justify-between border-b border-gray-700 px-5 py-4">
            <div class="flex items-center gap-2 text-lg font-bold tracking-wide">
                <i class="fa fa-layer-group text-blue-400"></i>
                <span>Admin Panel</span>
            </div>

            <!-- Tombol close di mobile -->
            <button class="text-gray-300 hover:text-white lg:hidden" @click="sidebarOpen = false">
                <i class="fa fa-times text-xl"></i>
            </button>
        </div>

        <!-- Menu Navigasi -->
        <nav class="mt-4 space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard')
                    ? 'bg-blue-600 text-white font-semibold shadow-inner'
                    : 'hover:bg-gray-700 text-gray-300' }} flex items-center rounded-r-full px-5 py-3 transition">
                <i class="fa fa-home w-5"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            {{-- Kelola Buku --}}
            <a href="{{ route('admin.books.index') }}"
                class="{{ request()->routeIs('admin.books.*')
                    ? 'bg-blue-600 text-white font-semibold shadow-inner'
                    : 'hover:bg-gray-700 text-gray-300' }} flex items-center rounded-r-full px-5 py-3 transition">
                <i class="fa fa-book w-5"></i>
                <span class="ml-3">Kelola Buku</span>
            </a>

            {{-- Peminjaman --}}
            <a href="{{ route('admin.peminjaman.index') }}"
                class="{{ request()->routeIs('admin.peminjaman.*')
                    ? 'bg-blue-600 text-white font-semibold shadow-inner'
                    : 'hover:bg-gray-700 text-gray-300' }} flex items-center rounded-r-full px-5 py-3 transition">
                <i class="fa fa-handshake w-5"></i>
                <span class="ml-3">Peminjaman</span>
            </a>

            <!-- Logout -->
            <div class="mt-6 border-t border-gray-700 pt-3">
                <a href="{{ route('logout') }}"
                    class="flex items-center rounded-r-full px-5 py-3 text-gray-300 transition hover:bg-gray-700"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out-alt w-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>
    </aside>

    <!-- Overlay (Mobile) -->
    <div class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition.opacity></div>

    <!-- Main Content -->
    <main class="min-h-screen flex-1 p-4 transition-all sm:p-6 lg:ml-64">
        <!-- Tombol toggle sidebar (mobile) -->
        <button
            class="mb-4 inline-flex items-center gap-2 rounded-lg bg-[#1e293b] px-4 py-2 text-white shadow-md transition hover:bg-gray-800 lg:hidden"
            @click="sidebarOpen = true">
            <i class="fa fa-bars"></i>
            <span class="font-medium">Menu</span>
        </button>

        {{-- Konten Dinamis --}}
        <div class="mx-auto w-full max-w-5xl">
            @yield('content')
        </div>
    </main>



</body>

</html>
