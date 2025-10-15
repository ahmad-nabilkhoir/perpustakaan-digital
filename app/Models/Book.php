<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'kategori',
        'isbn',
        'tahun_terbit',
        'lokasi_rak',
        'deskripsi',
        'cover_image',
        'status',
        'stok',
    ];

    /**
     * Relasi one-to-many ke model Peminjaman.
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Accessor untuk cover_url (path lengkap untuk <img src>)
     */
   public function getCoverUrlAttribute()
{
    if ($this->cover_image) {
        return asset('storage/' . $this->cover_image);
    }
    return asset('images/default-cover.jpg');
}

}
