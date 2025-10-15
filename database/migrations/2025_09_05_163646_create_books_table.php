<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('penulis');
        $table->string('kategori');
        $table->string('isbn')->nullable();
        $table->integer('tahun_terbit')->nullable();
        $table->string('lokasi_rak')->nullable();
        $table->text('deskripsi')->nullable();
        $table->string('cover_image')->nullable(); // <-- Sudah ada di sini
        $table->enum('status', ['Tersedia', 'Dipinjam'])->default('Tersedia');
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
