<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $books = $query->latest()->get();
        $kategoriList = Book::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        $totalBuku = Book::count();
        $bukuTersedia = Book::where('status', 'Tersedia')->count();
        $sedangDipinjam = Book::where('status', 'Dipinjam')->count();

        return view('welcome', compact('books', 'kategoriList', 'totalBuku', 'bukuTersedia', 'sedangDipinjam'));
    }

    public function show(Book $book)
    {
        return view('book-detail', compact('book'));
    }

    /**
     * Simpan buku baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'isbn' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'lokasi_rak' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|string',
            'stok' => 'nullable|integer|min:0', 
        ]);

        if ($request->hasFile('cover_image')) {
            $filename = uniqid() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->storeAs('public/covers', $filename);
            $validated['cover_image'] = 'covers/' . $filename;
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Update buku yang ada
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'isbn' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'lokasi_rak' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|string',
            'stok' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama kalau ada
            if ($book->cover_image && Storage::exists('public/' . $book->cover_image)) {
                Storage::delete('public/' . $book->cover_image);
            }
            $filename = uniqid() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->storeAs('public/covers', $filename);
            $validated['cover_image'] = 'covers/' . $filename;
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui');
    }

    /**
     * Hapus buku
     */
    public function destroy(Book $book)
    {
        if ($book->cover_image && Storage::exists('public/' . $book->cover_image)) {
            Storage::delete('public/' . $book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus');
    }
}
