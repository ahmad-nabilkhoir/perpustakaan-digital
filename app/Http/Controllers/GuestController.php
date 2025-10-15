<?php

namespace App\Http\Controllers;

use App\Models\Buku; 
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Menampilkan halaman utama (katalog buku).
     */
    public function index()
    {
        // 1. Ambil semua data buku dari database
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('status', 'Tersedia')->count();
        $sedangDipinjam = Buku::where('status', 'Dipinjam')->count();
        $semuaBuku = Buku::latest()->get(); // Ambil semua buku, urutkan dari yang terbaru

        // 2. Kirim data tersebut ke view
        return view('guest.index', [
            'totalBuku' => $totalBuku,
            'bukuTersedia' => $bukuTersedia,
            'sedangDipinjam' => $sedangDipinjam,
            'semuaBuku' => $semuaBuku,
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu buku.
     */
    public function show($id)
    {
        // 1. Cari buku berdasarkan ID yang dikirim. Jika tidak ada, tampilkan error 404.
        $buku = Buku::findOrFail($id);

        // 2. Kirim data buku tersebut ke view detail
        return view('guest.show', compact('buku'));
    }
}