<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik
        $totalBooks = Book::count();
        $availableBooks = Book::where('status', 'tersedia')->count();
        $borrowedBooks = Book::where('status', 'dipinjam')->count();

        // Buku terbaru (misal 5 terakhir)
        $books = Book::orderBy('created_at', 'desc')->take(5)->get();

        // Peminjaman aktif (status dipinjam)
        $pendingList = Peminjaman::where('status', 'dipinjam')
            ->with('book') // eager load buku
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'availableBooks',
            'borrowedBooks',
            'books',
            'pendingList'
        ));
    }
}
