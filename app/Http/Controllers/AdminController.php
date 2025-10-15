<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        // default supaya view tidak error meskipun tabel belum ada
        $totalBooks = 0;
        $availableBooks = 0;
        $borrowedBooks = 0;
        $totalPeminjaman = 0;
        $peminjamanPending = 0;
        $pendingList = collect([]);
        $books = collect([]);
        $totalBuku = Book::count();
        $bukuDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $bukuTersedia = $totalBuku - $bukuDipinjam;

        return view('admin.dashboard', compact('totalBuku', 'bukuDipinjam', 'bukuTersedia'));
        if (Schema::hasTable('books')) {
            try {
                $totalBooks = Book::count();
                $availableStatuses = ['Tersedia','tersedia','Available','available'];
                $borrowedStatuses  = ['Dipinjam','dipinjam','Borrowed','borrowed'];

                $availableBooks = Book::whereIn('status', $availableStatuses)->count();
                if ($availableBooks === 0) {
                    if (Schema::hasColumn('books', 'stock')) {
                        $availableBooks = Book::where('stock', '>', 0)->count();
                    } elseif (Schema::hasColumn('books', 'stok')) {
                        $availableBooks = Book::where('stok', '>', 0)->count();
                    }
                }
                $borrowedBooks = Book::whereIn('status', $borrowedStatuses)->count();
            } catch (\Throwable $e) {}

            try {
                $books = Book::orderBy('created_at', 'desc')->get();
            } catch (\Throwable $e) {
                $books = collect([]);
            }
        }

        if (Schema::hasTable('peminjaman')) {
            try {
                $totalPeminjaman = Peminjaman::count();
                $pendingStatuses = ['pending','Pending','requested','waiting','Requested'];
                $peminjamanPending = Peminjaman::whereIn('status', $pendingStatuses)->count();
                $pendingList = Peminjaman::whereIn('status', $pendingStatuses)->with('book')->get();

                if ($peminjamanPending === 0 && Schema::hasColumn('peminjaman', 'tanggal_kembali')) {
                    $pendingList = Peminjaman::whereNull('tanggal_kembali')->with('book')->get();
                    $peminjamanPending = $pendingList->count();
                }
            } catch (\Throwable $e) {
                $peminjamanPending = 0;
                $pendingList = collect([]);
            }
        }

        return view('admin.dashboard', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'totalPeminjaman',
            'peminjamanPending',
            'pendingList',
            'books'
        ));
    }

    /**
     * CRUD BUKU
     */
    public function indexBooks()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function createBook()
    {
        return view('admin.books.create');
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'lokasi_rak' => 'nullable|string|max:50',
            'tahun_terbit' => 'nullable|integer',
            'gambar_cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:available,borrowed,Tersedia,Dipinjam',
        ]);

        $coverPath = null;
        if ($request->hasFile('gambar_cover')) {
            $coverPath = $request->file('gambar_cover')->store('covers', 'public');
        }

        Book::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'isbn' => $request->isbn,
            'lokasi_rak' => $request->lokasi_rak,
            'tahun_terbit' => $request->tahun_terbit,
            'gambar_cover' => $coverPath,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function editBook($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'lokasi_rak' => 'nullable|string|max:50',
            'tahun_terbit' => 'nullable|integer',
            'gambar_cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:available,borrowed,Tersedia,Dipinjam',
        ]);

        $book = Book::findOrFail($id);

        $coverPath = $book->gambar_cover;
        if ($request->hasFile('gambar_cover')) {
            $coverPath = $request->file('gambar_cover')->store('covers', 'public');
        }

        $book->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori' => $request->kategori,
            'isbn' => $request->isbn,
            'lokasi_rak' => $request->lokasi_rak,
            'tahun_terbit' => $request->tahun_terbit,
            'gambar_cover' => $coverPath,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroyBook($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Approve peminjaman
     */
    public function approvePeminjaman($id)
    {
        if (!Schema::hasTable('peminjaman')) {
            return redirect()->route('admin.dashboard')->with('error', 'Tabel peminjaman tidak ditemukan.');
        }

        $p = Peminjaman::find($id);
        if (!$p) {
            return redirect()->route('admin.dashboard')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $p->status = 'approved';
        $p->save();

        return redirect()->route('admin.dashboard')->with('success', 'Peminjaman disetujui.');
    }
}
