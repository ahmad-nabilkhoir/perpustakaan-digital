<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function create(Book $book)
    {
        if ($book->status == 'Dipinjam') {
            return redirect()->route('home')->with('error', 'Buku ini sedang tidak tersedia.');
        }
        return view('peminjaman', compact('book'));
    }

    public function store(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'buku_id' => 'required|exists:books,id',
            'nama_peminjam' => 'required|string|max:255',
            'email_peminjam' => 'required|email',
            'telepon_peminjam' => 'required|string|min:10',
            'alamat_peminjam' => 'required|string',
            'tanggal_pinjam' => 'nullable|date|after_or_equal:today',
        ]);

        $book = Book::findOrFail($request->buku_id);

        if ($book->status == 'Dipinjam') {
            return redirect()->route('home')->with('error', 'Maaf, buku ini sudah dipinjam orang lain.');
        }

        // ✅ Jika user isi tanggal pinjam, pakai itu. Jika tidak, pakai hari ini.
        $tanggalPinjam = $request->filled('tanggal_pinjam')
            ? Carbon::parse($request->tanggal_pinjam)
            : now();

        // ✅ Tanggal kembali selalu +7 hari dari tanggal pinjam
        $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

        $peminjaman = Peminjaman::create([
            'buku_id'         => $book->id,
            'nama_peminjam'   => $request->nama_peminjam,
            'email_peminjam'  => $request->email_peminjam,
            'telepon_peminjam'=> $request->telepon_peminjam,
            'alamat_peminjam' => $request->alamat_peminjam,
            'tanggal_pinjam'  => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status'          => 'Pending',
        ]);

        // 📩 Kirim notifikasi WA ke admin
        $nomorAdmin = '6282225832575';
        $message = "Halo Admin Perpustakaan Jawa Tengah,\n\nAda pengajuan peminjaman buku:\n\n" .
                "*Nama:* {$peminjaman->nama_peminjam}\n" .
                "*Email:* {$peminjaman->email_peminjam}\n" .
                "*Judul Buku:* {$book->judul}\n" .
                "*Tanggal Pinjam:* {$tanggalPinjam->format('d-m-Y')}\n" .
                "*Batas Pengembalian:* {$tanggalKembali->format('d-m-Y')}\n\n" .
                "Mohon segera diproses.";

        $whatsappUrl = 'https://api.whatsapp.com/send?phone=' . $nomorAdmin . '&text=' . urlencode($message);

        return redirect()->away($whatsappUrl);
    }
}
