@extends('layouts.main')

@section('title', 'Form Peminjaman Buku')

@section('content')
    <div class="mb-4">
        <a href="{{ route('book.show', $book->id) }}" class="text-blue-600 hover:underline">&larr; Kembali ke Detail Buku</a>
    </div>

    <h1 class="mb-8 text-center text-3xl font-bold text-gray-800">Form Peminjaman Buku</h1>

    <div class="mx-auto max-w-4xl rounded-lg bg-white p-8 shadow-lg">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            <input type="hidden" name="buku_id" value="{{ $book->id }}">

            <div class="flex flex-col gap-8 md:flex-row">
                {{-- Bagian kiri: detail buku --}}
                <div class="text-center md:w-1/3">
                    <img src="{{ $book->cover_url }}" alt="Cover {{ $book->judul }}">

                    <h2 class="text-xl font-bold text-gray-900">{{ $book->judul }}</h2>
                    <p class="text-gray-600">oleh {{ $book->penulis }}</p>
                    <p class="mt-2 text-sm text-gray-500">Lokasi: {{ $book->lokasi_rak }}</p>
                    <div class="mt-4 rounded-lg bg-green-50 p-3 text-green-800">
                        <p class="font-semibold">Durasi Peminjaman</p>
                        <p>Selalu 7 hari dari tanggal pinjam yang dipilih</p>
                    </div>
                </div>

                {{-- Bagian kanan: form peminjam --}}
                <div class="md:w-2/3">
                    <h3 class="mb-6 text-2xl font-semibold">Data Peminjam</h3>

                    @if ($errors->any())
                        <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block">Ada beberapa kesalahan pada input Anda:</span>
                            <ul class="mt-2 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="nama_peminjam" class="block text-sm font-medium text-gray-700">
                                Nama Lengkap *
                            </label>
                            <input type="text" name="nama_peminjam" id="nama_peminjam"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('nama_peminjam') }}" required>
                        </div>

                        <div>
                            <label for="email_peminjam" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email_peminjam" id="email_peminjam"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('email_peminjam') }}" required>
                        </div>

                        <div>
                            <label for="telepon_peminjam" class="block text-sm font-medium text-gray-700">Nomor Telepon
                                *</label>
                            <input type="tel" name="telepon_peminjam" id="telepon_peminjam"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('telepon_peminjam') }}" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat_peminjam" class="block text-sm font-medium text-gray-700">Alamat Lengkap
                                *</label>
                            <textarea name="alamat_peminjam" id="alamat_peminjam" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('alamat_peminjam') }}</textarea>
                        </div>

                        {{-- Field pilih tanggal pinjam --}}
                        <div>
                            <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">
                                Tanggal Pinjam
                            </label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}" required>
                        </div>

                        {{-- Preview + hidden field tanggal kembali --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Batas Pengembalian</label>
                            <input type="text" id="preview_tanggal_kembali"
                                class="mt-1 block w-full rounded-md border-gray-200 bg-gray-100 shadow-sm" readonly>

                            <input type="hidden" name="tanggal_kembali" id="tanggal_kembali">
                        </div>
                    </div>

                    <div class="mt-6 rounded-lg bg-blue-50 p-4 text-sm text-blue-700">
                        <h4 class="font-bold">Ketentuan Peminjaman:</h4>
                        <ul class="mt-2 list-inside list-disc">
                            <li>Tanggal kembali otomatis 7 hari dari tanggal pinjam yang dipilih.</li>
                            <li>Denda keterlambatan Rp 1.000 per hari.</li>
                            <li>Peminjam bertanggung jawab atas kerusakan atau kehilangan buku.</li>
                        </ul>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="w-full rounded-lg bg-blue-600 px-8 py-3 text-lg font-bold text-white hover:bg-blue-700 md:w-auto">
                            Ajukan Peminjaman
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
        const previewTanggalKembali = document.getElementById('preview_tanggal_kembali');
        const tanggalKembaliHidden = document.getElementById('tanggal_kembali');

        function updateTanggalKembali() {
            if (!tanggalPinjamInput.value) return;
            let startDate = new Date(tanggalPinjamInput.value + "T00:00:00");
            startDate.setDate(startDate.getDate() + 7);

            let formatted = startDate.toISOString().split('T')[0];
            previewTanggalKembali.value = formatted;
            tanggalKembaliHidden.value = formatted;
        }

        // Jalankan sekali saat halaman pertama kali load
        updateTanggalKembali();
        tanggalPinjamInput.addEventListener('change', updateTanggalKembali);
    </script>
@endpush
