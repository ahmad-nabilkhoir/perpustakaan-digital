@extends('layouts.admin')
@section('title', 'Manajemen Peminjaman')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Manajemen Peminjaman</h1>
        <button onclick="openModal('modal-tambah')" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            + Tambah Peminjaman
        </button>
    </div>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="mb-4 border-l-4 border-green-600 bg-green-100 p-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABEL PEMINJAMAN --}}
    <div class="overflow-x-auto rounded-lg bg-white p-6 shadow-md">
        <table class="w-full table-auto border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border p-2 text-center">No</th>
                    <th class="border p-2 text-left">Kode Buku</th>
                    <th class="border p-2 text-left">Judul Buku</th>
                    <th class="border p-2 text-left">Peminjam</th>
                    <th class="border p-2 text-left">Email</th>
                    <th class="border p-2 text-left">Telepon</th>
                    <th class="border p-2 text-center">Tanggal Pinjam</th>
                    <th class="border p-2 text-center">Status</th>
                    <th class="border p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2 text-center">{{ $loop->iteration }}</td>
                        <td class="border p-2">{{ $p->book->kode_buku ?? '-' }}</td>
                        <td class="border p-2">{{ $p->book->judul ?? '-' }}</td>
                        <td class="border p-2">{{ $p->nama_peminjam }}</td>
                        <td class="border p-2">{{ $p->email_peminjam }}</td>
                        <td class="border p-2">{{ $p->telepon_peminjam }}</td>
                        <td class="border p-2 text-center">{{ $p->tanggal_pinjam ?? '-' }}</td>
                        <td class="border p-2 text-center">
                            @php
                                $statusClass = match ($p->status) {
                                    'dipinjam' => 'bg-yellow-100 text-yellow-700',
                                    'dikembalikan' => 'bg-green-100 text-green-700',
                                    'ditolak' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="{{ $statusClass }} rounded-full px-3 py-1 text-xs font-semibold">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="border p-2 text-center">
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                {{-- Tombol Edit --}}
                                <button type="button"
                                    onclick='openEditModal(@json($p), "{{ route('admin.peminjaman.update', $p->id) }}")'
                                    class="rounded bg-yellow-500 px-3 py-1 text-xs text-white hover:bg-yellow-600">
                                    Edit
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.peminjaman.destroy', $p->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>

                                {{-- Tombol Kembalikan --}}
                                @if ($p->status === 'dipinjam')
                                    <form action="{{ route('admin.peminjaman.return', $p->id) }}" method="POST"
                                        onsubmit="return confirm('Tandai buku ini sudah dikembalikan?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="rounded bg-green-600 px-3 py-1 text-xs text-white hover:bg-green-700">
                                            Kembalikan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-4 text-center italic text-gray-500">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modal-tambah" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="mx-auto mt-20 w-full max-w-lg rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-bold">Tambah Peminjaman</h2>
            <form action="{{ route('admin.peminjaman.store') }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="dipinjam">

                {{-- Pilih Buku --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium">Pilih Buku</label>
                    <select name="buku_id" id="select-buku" class="w-full rounded border p-2">
                        <option value="">-- Pilih Buku --</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" data-kode="{{ $book->kode_buku }}">
                                {{ $book->kode_buku }} - {{ $book->judul }} (stok: {{ $book->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kode Buku Manual --}}
                <div class="mb-3">
                    <label class="block text-sm font-medium">Kode Buku</label>
                    <input type="text" id="input-kode-buku" name="kode_buku" class="w-full rounded border p-2"
                        placeholder="Misal: BK-001">
                    <p class="mt-1 text-xs text-gray-500">*Akan otomatis terisi jika memilih buku di atas.</p>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="w-full rounded border p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email_peminjam" class="w-full rounded border p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Telepon</label>
                    <input type="text" name="telepon_peminjam" class="w-full rounded border p-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="w-full rounded border p-2" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-tambah')"
                        class="rounded bg-gray-400 px-4 py-2 text-white hover:bg-gray-500">
                        Batal
                    </button>
                    <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modal-edit" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="mx-auto mt-20 w-full max-w-lg rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-bold">Edit Peminjaman</h2>
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm font-medium">Pilih Buku</label>
                    <select name="buku_id" id="edit-book" class="w-full rounded border p-2" required>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">
                                {{ $book->kode_buku }} - {{ $book->judul }} (stok: {{ $book->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" id="edit-nama" class="w-full rounded border p-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email_peminjam" id="edit-email" class="w-full rounded border p-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Telepon</label>
                    <input type="text" name="telepon_peminjam" id="edit-telepon" class="w-full rounded border p-2"
                        required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="edit-tanggal" class="w-full rounded border p-2"
                        required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-edit')"
                        class="rounded bg-gray-400 px-4 py-2 text-white hover:bg-gray-500">
                        Batal
                    </button>
                    <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Auto isi kode buku saat pilih dari dropdown
        document.getElementById('select-buku')?.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('input-kode-buku').value = selected.dataset.kode || '';
        });

        function openEditModal(item, updateUrl) {
            const form = document.getElementById('form-edit');
            form.action = updateUrl;
            document.getElementById('edit-book').value = item.buku_id;
            document.getElementById('edit-nama').value = item.nama_peminjam;
            document.getElementById('edit-email').value = item.email_peminjam;
            document.getElementById('edit-telepon').value = item.telepon_peminjam;
            document.getElementById('edit-tanggal').value = item.tanggal_pinjam;
            openModal('modal-edit');
        }
    </script>
@endsection
