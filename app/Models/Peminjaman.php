<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'buku_id',
        'nama_peminjam',
        'email_peminjam',
        'telepon_peminjam',
        'alamat_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'buku_id');
    }
}

