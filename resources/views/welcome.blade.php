@extends('layouts.main')

@section('title', 'Perpustakaan Digital Jawa Tengah')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-10">
        <div class="container mx-auto max-w-7xl px-4">

            {{-- Bagian Pencarian --}}
            <div
                class="mb-10 rounded-2xl bg-white/90 p-6 shadow-lg backdrop-blur-md transition hover:scale-[1.01] hover:shadow-xl">
                <h3 class="mb-4 text-xl font-bold text-gray-800">🔍 Cari Buku</h3>
                <form action="{{ route('home') }}" method="GET">
                    <div class="flex flex-col gap-4 md:flex-row">
                        <input type="text" name="search"
                            class="flex-grow rounded-xl border-gray-300 bg-gray-50 px-4 py-2 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                            placeholder="Cari berdasarkan judul, penulis, atau deskripsi..."
                            value="{{ request('search') }}">

                        <select name="kategori"
                            class="rounded-xl border-gray-300 bg-gray-50 px-4 py-2 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <option value="">📚 Semua Kategori</option>
                            @foreach ($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}</option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-2 font-medium text-white shadow-md transition hover:scale-105 hover:shadow-lg">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Bagian Katalog Buku --}}
            <h2
                class="mb-8 bg-gradient-to-r from-blue-700 to-purple-700 bg-clip-text text-center text-3xl font-extrabold tracking-tight text-transparent">
                📖 Katalog Buku ({{ $books->count() }} buku)
            </h2>

            @if ($books->isEmpty())
                <div class="rounded-2xl bg-white/90 p-12 text-center shadow-lg backdrop-blur-md">
                    <h3 class="text-2xl font-semibold text-gray-800">😔 Tidak ada buku ditemukan</h3>
                    <p class="mt-3 text-gray-500">Coba ubah kata kunci pencarian atau filter kategori Anda.</p>
                </div>
            @else
                <div class="animate-fadeIn grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($books as $book)
                        <div
                            class="group flex flex-col overflow-hidden rounded-2xl bg-white/90 shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl">
                            <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}"
                                class="h-56 w-full object-cover transition duration-300 group-hover:brightness-105">
                            <div class="flex flex-grow flex-col p-6">
                                <h3
                                    class="line-clamp-2 text-lg font-bold text-gray-900 transition group-hover:text-purple-700">
                                    {{ $book->judul }}
                                </h3>
                                <p class="mt-1 text-sm italic text-gray-600">✍️ {{ $book->penulis }}</p>

                                <div class="mt-4 flex items-center gap-2">
                                    <span
                                        class="rounded-full bg-gradient-to-r from-blue-100 to-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 shadow-sm">
                                        {{ $book->kategori }}
                                    </span>
                                    <span class="text-gray-300">&bull;</span>
                                    <span class="text-xs text-gray-500">{{ $book->lokasi_rak }}</span>
                                </div>

                                <p class="mt-3 line-clamp-3 flex-grow text-sm text-gray-700">{{ $book->deskripsi }}</p>

                                <div class="mt-4 flex items-center justify-between">
                                    <span
                                        class="{{ $book->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full px-3 py-1 text-xs font-semibold">
                                        {{ $book->stok > 0 ? '📗 Stok: ' . $book->stok : '⛔ Stok Habis' }}
                                    </span>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('book.show', $book->id) }}"
                                        class="block w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-md transition hover:scale-105 hover:shadow-lg">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Tambahkan animasi sederhana --}}
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-in-out;
        }
    </style>
@endsection
