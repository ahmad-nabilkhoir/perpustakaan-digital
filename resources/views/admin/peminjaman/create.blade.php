@extends('layouts.admin')
@section('title', 'Tambah Peminjaman')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Tambah Peminjaman</h1>

    <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-semibold">Judul Buku</label>
            <select name="book_id" class="w-full rounded border p-2">
                @foreach ($books as $book)
                    <option value="{{ $book->id }}">{{ $book->judul }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold">Nama Peminjam</label>
            <input type="text" name="nama_peminjam" class="w-full rounded border p-2" required>
        </div>
        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email_peminjam" class="w-full rounded border p-2" required>
        </div>
        <div>
            <label class="block font-semibold">Telepon</label>
            <input type="text" name="telepon_peminjam" class="w-full rounded border p-2" required>
        </div>
        <div>
            <label class="block font-semibold">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="w-full rounded border p-2" required>
        </div>
        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Simpan
        </button>
    </form>
@endsection
