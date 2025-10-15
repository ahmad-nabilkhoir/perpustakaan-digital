@extends('layouts.admin')

@section('title', 'Edit Buku')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6">

        {{-- Card Form --}}
        <div class="mx-auto max-w-2xl rounded-2xl bg-white p-8 shadow-lg">
            <h2 class="mb-6 text-2xl font-extrabold text-gray-800">✏️ Edit Buku</h2>

            <form action="{{ route('admin.books.update', $book) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $book->judul) }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>

                {{-- Penulis --}}
                <div>
                    <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                    <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $book->penulis) }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $book->kategori) }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                {{-- Lokasi Rak --}}
                <div>
                    <label for="lokasi_rak" class="block text-sm font-medium text-gray-700">Lokasi Rak</label>
                    <input type="text" name="lokasi_rak" id="lokasi_rak"
                        value="{{ old('lokasi_rak', $book->lokasi_rak) }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                {{-- Stok --}}
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stok" id="stok" min="0"
                        value="{{ old('stok', $book->stok ?? 0) }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="Tersedia" @selected($book->status == 'Tersedia')>Tersedia</option>
                        <option value="Dipinjam" @selected($book->status == 'Dipinjam')>Dipinjam</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-between">
                    <a href="{{ route('admin.books.index') }}"
                        class="rounded-lg bg-gray-300 px-4 py-2 text-gray-700 transition hover:bg-gray-400">
                        ← Kembali
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-green-600 px-5 py-2 text-white shadow transition hover:bg-green-700">
                        💾 Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
