@extends('layouts.main')

@section('title', 'Detail Buku: ' . $book->judul)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-10">
        <div class="container mx-auto max-w-5xl px-4">

            {{-- Tombol kembali --}}
            <div class="mb-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center rounded-full bg-white/70 px-4 py-2 text-blue-700 shadow transition-all duration-300 hover:bg-blue-100 hover:text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Katalog
                </a>
            </div>

            {{-- Card Detail Buku --}}
            <div
                class="rounded-2xl bg-white/90 p-8 shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl">
                <div class="flex flex-col gap-8 md:flex-row">

                    {{-- Cover Buku --}}
                    <div class="md:w-1/3">
                        <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}"
                            class="w-full rounded-xl object-cover shadow-md">
                        <div class="mt-4 text-center">
                            <span
                                class="{{ $book->stok > 0 ? 'bg-green-100 text-green-700 shadow-green-200' : 'bg-red-100 text-red-700 shadow-red-200' }} inline-block rounded-full px-5 py-2 text-sm font-bold shadow-md ring-1 ring-black/5">
                                {{ $book->stok > 0 ? '📗 Tersedia (' . $book->stok . ')' : '⛔ Stok Habis' }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Buku --}}
                    <div class="md:w-2/3">
                        <h1
                            class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-3xl font-extrabold leading-snug tracking-tight text-gray-900 text-transparent">
                            {{ $book->judul }}
                        </h1>
                        <p class="mt-2 text-lg italic text-gray-700">✍️ oleh <span
                                class="font-semibold">{{ $book->penulis }}</span></p>

                        {{-- Informasi Tambahan --}}
                        <div class="mt-6 border-t pt-6">
                            <div class="grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
                                <div class="rounded-xl bg-blue-50 p-4 shadow-sm transition hover:shadow-md">
                                    <h3 class="text-xs font-medium uppercase tracking-wider text-blue-700">Kategori</h3>
                                    <p class="mt-1 font-semibold text-gray-900">{{ $book->kategori }}</p>
                                </div>
                                <div class="rounded-xl bg-blue-50 p-4 shadow-sm transition hover:shadow-md">
                                    <h3 class="text-xs font-medium uppercase tracking-wider text-blue-700">Tahun Terbit</h3>
                                    <p class="mt-1 font-semibold text-gray-900">{{ $book->tahun_terbit }}</p>
                                </div>
                                <div class="rounded-xl bg-blue-50 p-4 shadow-sm transition hover:shadow-md">
                                    <h3 class="text-xs font-medium uppercase tracking-wider text-blue-700">ISBN</h3>
                                    <p class="mt-1 font-semibold text-gray-900">{{ $book->isbn ?? '-' }}</p>
                                </div>
                                <div class="rounded-xl bg-blue-50 p-4 shadow-sm transition hover:shadow-md">
                                    <h3 class="text-xs font-medium uppercase tracking-wider text-blue-700">Lokasi Rak</h3>
                                    <p class="mt-1 font-semibold text-gray-900">{{ $book->lokasi_rak }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi Buku --}}
                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-lg font-bold text-gray-900">📝 Deskripsi</h3>
                            <p class="mt-3 leading-relaxed text-gray-700">{{ $book->deskripsi }}</p>
                        </div>

                        {{-- Tombol Pinjam Buku --}}
                        {{--
                        <div class="mt-8">
                            @if ($book->stok > 0)
                                <a href="{{ route('peminjaman.create', $book->id) }}"
                                    class="inline-block w-full md:w-auto rounded-full bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-3 text-center text-lg font-semibold text-white shadow-lg hover:shadow-xl hover:scale-105 transition">
                                    📖 Pinjam Buku Ini
                                </a>
                            @else
                                <button disabled
                                    class="inline-block w-full md:w-auto cursor-not-allowed rounded-full bg-gray-400 px-8 py-3 text-center text-lg font-semibold text-white">
                                    Buku Sedang Dipinjam
                                </button>
                            @endif
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
