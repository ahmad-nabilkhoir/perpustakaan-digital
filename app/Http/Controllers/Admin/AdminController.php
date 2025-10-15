<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Peminjaman;

class AdminController extends Controller
{
    /**
     * Dashboard admin — menampilkan statistik & data terbaru
     */
    public function dashboard()
    {
        $totalBooks = Book::count();
        $availableBooks = Book::where('status', 'Tersedia')->count();
        $borrowedBooks = Book::where('status', 'Dipinjam')->count();

        $totalPeminjaman = Peminjaman::count();
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();

        $books = Book::latest()->take(10)->get();
        $pendingList = Peminjaman::where('status', 'pending')
            ->with('book')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'totalPeminjaman',
            'peminjamanPending',
            'books',
            'pendingList'
        ));
    }

    /**
     * List buku (admin)
     */
    public function manageBooks()
    {
        $books = Book::latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Form tambah buku
     */
    public function createBook()
    {
        return view('admin.books.create');
    }

    /**
     * Simpan buku baru
     */
    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'nullable|string|max:255',
            'kategori'     => 'nullable|string|max:255',
            'isbn'         => 'nullable|string|max:100',
            'lokasi_rak'   => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer',
            'deskripsi'    => 'nullable|string',
            'status'       => 'nullable|in:Tersedia,Dipinjam,Hilang',
            'gambar_cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar_cover')) {
            $validated['gambar_cover'] = $request->file('gambar_cover')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Edit buku
     */
    public function editBook(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update buku
     */
    public function updateBook(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'nullable|string|max:255',
            'kategori'     => 'nullable|string|max:255',
            'isbn'         => 'nullable|string|max:100',
            'lokasi_rak'   => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer',
            'deskripsi'    => 'nullable|string',
            'status'       => 'nullable|in:Tersedia,Dipinjam,Hilang',
            'gambar_cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar_cover')) {
            if ($book->gambar_cover) {
                Storage::disk('public')->delete($book->gambar_cover);
            }
            $validated['gambar_cover'] = $request->file('gambar_cover')->store('covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku
     */
    public function destroyBook(Book $book)
    {
        if ($book->gambar_cover) {
            Storage::disk('public')->delete($book->gambar_cover);
        }
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * List peminjaman (admin)
     */
    public function managePeminjaman()
    {
        $peminjaman = Peminjaman::with('book')->latest()->paginate(15);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Approve peminjaman
     */
    public function approvePeminjaman(Peminjaman $peminjaman)
    {
        $peminjaman->status = 'approved';
        $peminjaman->tanggal_kembali = now()->addDays(7);
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman disetujui.');
    }
}
