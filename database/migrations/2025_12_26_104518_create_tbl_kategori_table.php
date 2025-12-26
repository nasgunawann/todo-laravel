<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * jalankan migrasi untuk buat tabel
     */
    public function up(): void
    {
        Schema::create('tbl_kategori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')
                ->constrained('tbl_pengguna')
                ->onDelete('cascade'); // hapus kategori kalau user dihapus
            $table->string('nama', 100);
            $table->string('warna', 7)->default('#6366f1'); // warna hex untuk badge
            $table->string('ikon', 50)->nullable(); // nama icon dari tabler
            $table->boolean('adalah_default')->default(false); // kategori bawaan sistem
            $table->timestamps();

            $table->index('pengguna_id'); // index untuk query lebih cepat
        });
    }

    /**
     * rollback migrasi (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kategori');
    }
};
