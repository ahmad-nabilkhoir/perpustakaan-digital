@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="container mx-auto space-y-8 px-3 py-6 sm:px-6 sm:py-8">

        {{-- Header --}}
        <h1 class="flex items-center gap-2 text-center text-3xl font-extrabold text-gray-800 sm:text-left">
            <i class="fa fa-chart-line text-blue-500"></i>
            Dashboard Admin
        </h1>

        {{-- Daftar Buku Terbaru --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-md">
            <div class="mb-5 flex flex-col items-center justify-between gap-3 sm:flex-row">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-800">
                    <i class="fa fa-book text-purple-500"></i> Daftar Buku Terbaru
                </h2>
                <a href="{{ route('admin.books.index') }}"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white shadow transition hover:bg-indigo-700 sm:w-auto">
                    Kelola Buku
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse overflow-hidden rounded-lg text-sm">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Judul</th>
                            <th class="px-4 py-3 text-left">Penulis</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($books as $b)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $b->judul ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $b->penulis ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $b->kategori ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        // Status otomatis berdasarkan stok
                                        $status = ($b->stok ?? 0) == 0 ? 'stok habis' : 'tersedia';
                                        $badgeClass =
                                            $status === 'tersedia'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700';
                                    @endphp

                                    <span
                                        class="{{ $badgeClass }} inline-flex min-w-[80px] items-center justify-center rounded-full px-3 py-1 text-xs font-semibold">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center text-gray-500">
                                    Belum ada buku yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
