<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Ambil semua peminjaman + buku terkait
        $peminjaman = Peminjaman::with('book')->latest()->get();
        $books = Book::where('stok', '>', 0)->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:books,id',
            'nama_peminjam' => 'required|string|max:255',
            'email_peminjam' => 'required|email',
            'telepon_peminjam' => 'required|string|max:20',
            'tanggal_pinjam' => 'required|date',
            'kode_buku' => 'nullable|string',
        ]);

        $validated['status'] = 'dipinjam';

        // Simpan data
        $peminjaman = Peminjaman::create($validated);

        // Kurangi stok buku
        $book = Book::find($validated['buku_id']);
        if ($book && $book->stok > 0) {
            $book->decrement('stok');
            if ($book->stok <= 0) {
                $book->update(['status' => 'Dipinjam']);
            }
        }

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan & stok berkurang.');
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:books,id',
            'nama_peminjam' => 'required|string|max:255',
            'email_peminjam' => 'required|email',
            'telepon_peminjam' => 'required|string|max:20',
            'tanggal_pinjam' => 'required|date',
            'kode_buku' => 'nullable|string',
        ]);

        // Jika buku diganti, kembalikan stok lama lalu kurangi stok baru
            // Jika buku diganti, kembalikan stok lama lalu kurangi stok baru
        if ($peminjaman->buku_id != $validated['buku_id']) {
            $oldBook = Book::find($peminjaman->buku_id);
        if ($oldBook) {
            $oldBook->increment('stok');
            $oldBook->update(['status' => $oldBook->stok > 0 ? 'Tersedia' : 'Stok Habis']);
        }

        $newBook = Book::find($validated['buku_id']);
        if ($newBook && $newBook->stok > 0) {
            $newBook->decrement('stok');
            $newBook->update(['status' => $newBook->stok <= 0 ? 'Stok Habis' : 'Tersedia']);
        }
    }
        $peminjaman->update($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // Kembalikan stok buku
        $book = $peminjaman->book;
        if ($book) {
            $book->increment('stok');
            if ($book->stok > 0) {
                $book->update(['status' => 'Tersedia']);
            }
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus & stok dikembalikan.');
    }

    public function approve(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => Carbon::now()->format('Y-m-d')
        ]);

        $book = $peminjaman->book;
        if ($book && $book->stok > 0) {
            $book->decrement('stok');
            if ($book->stok <= 0) {
                $book->update(['status' => 'Dipinjam']);
            }
        }

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman disetujui!');
    }

    public function reject(Peminjaman $peminjaman)
    {
        $peminjaman->update(['status' => 'ditolak']);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman ditolak.');
    }

    public function returnBook(Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => Carbon::now()->format('Y-m-d')
        ]);

        $book = $peminjaman->book;
        if ($book) {
            $book->increment('stok');
            if ($book->stok > 0) {
                $book->update(['status' => 'Tersedia']);
            }
        }

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan!');
    }
}
