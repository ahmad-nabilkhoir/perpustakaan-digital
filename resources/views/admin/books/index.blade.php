@extends('layouts.admin')

@section('title', 'Kelola Buku')

@section('content')
    <div class="flex justify-center px-4 py-8 sm:px-6">
        <div class="w-full max-w-6xl">

            {{-- Header --}}
            <div class="mb-6 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                <h1 class="flex items-center gap-2 text-3xl font-extrabold text-gray-800">
                    📚 Kelola Buku
                </h1>
                <a href="{{ route('admin.books.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700 hover:shadow-lg">
                    <i class="fa fa-plus"></i> Tambah Buku
                </a>
            </div>

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg border-l-4 border-green-600 bg-green-100 p-4 text-green-800 shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Tabel Buku --}}
            <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white p-4 shadow-lg sm:p-6">
                <table class="w-full table-auto border-collapse text-sm sm:text-base">
                    <thead>
                        <tr class="bg-gradient-to-r from-indigo-100 to-indigo-200 text-left text-gray-700">
                            <th class="px-4 py-3 text-center font-bold">No</th>
                            <th class="px-4 py-3 font-bold">Judul</th>
                            <th class="px-4 py-3 font-bold">Penulis</th>
                            <th class="px-4 py-3 font-bold">Kategori</th>
                            <th class="px-4 py-3 font-bold">Tahun</th>
                            <th class="px-4 py-3 text-center font-bold">Stok</th>
                            <th class="px-4 py-3 text-center font-bold">Status</th>
                            <th class="px-4 py-3 text-center font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-t transition hover:bg-gray-100">
                                <td class="px-4 py-3 text-center">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $book->judul }}</td>
                                <td class="px-4 py-3">{{ $book->penulis ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $book->kategori ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $book->tahun_terbit ?? '-' }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-blue-700">{{ $book->stok ?? 0 }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $status = ($book->stok ?? 0) == 0 ? 'stok habis' : 'tersedia';
                                        $statusClass = $status === 'tersedia' ? 'bg-green-600' : 'bg-red-600';
                                    @endphp

                                    <span
                                        class="{{ $statusClass }} inline-flex min-w-[80px] items-center justify-center rounded-full px-3 py-1 text-xs font-semibold text-white">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.books.edit', $book) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-yellow-500 px-3 py-1 text-xs font-semibold text-white shadow hover:bg-yellow-600">
                                            <i class="fa fa-pen"></i> Edit
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus buku ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs font-semibold text-white shadow hover:bg-red-700">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center italic text-gray-500">
                                    Belum ada buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
