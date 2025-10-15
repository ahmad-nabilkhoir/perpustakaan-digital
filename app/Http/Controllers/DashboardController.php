<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Book::count();
        $bukuDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $bukuTersedia = $totalBuku - $bukuDipinjam;

        // Peminjaman milik user yang sedang aktif
        $peminjamanSaya = Peminjaman::where('status', 'dipinjam')
                                ->where('email_peminjam', Auth::user()->email)
                                ->with('book')
                                ->get();

        return view('user.dashboard', compact(
            'totalBuku',
            'bukuDipinjam',
            'bukuTersedia',
            'peminjamanSaya'
        ));
    }
}
