@extends('layouts.admin')
@section('title', 'Tambah Buku')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
            <h1 class="text-3xl font-bold text-gray-800">📚 Tambah Buku</h1>
            <a href="{{ route('admin.books.index') }}"
                class="inline-block rounded-lg bg-gray-700 px-4 py-2 text-white shadow-md transition hover:bg-gray-800">
                ← Kembali
            </a>
        </div>

        {{-- Form Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xl">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Grid untuk input (responsive) --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Judul --}}
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300"
                            required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Penulis --}}
                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                        <input type="text" name="penulis" id="penulis" value="{{ old('penulis') }}"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300"
                            required>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">
                    </div>

                    {{-- ISBN --}}
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">
                    </div>

                    {{-- Tahun Terbit --}}
                    <div>
                        <label for="tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit') }}"
                            placeholder="Contoh: 2023"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">
                    </div>

                    {{-- Lokasi Rak --}}
                    <div>
                        <label for="lokasi_rak" class="block text-sm font-medium text-gray-700">Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" id="lokasi_rak" value="{{ old('lokasi_rak') }}"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok') }}"
                            class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">
                    </div>
                </div>

                {{-- Cover Image --}}
                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-700">Gambar Cover</label>
                    <input type="file" name="cover_image" id="cover_image"
                        class="mt-1 block w-full rounded-lg border border-gray-300 p-2 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-200">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="mt-1 w-full rounded-lg border border-gray-300 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-300">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        💾 Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
