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
        Schema::create('tbl_todo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')
                ->constrained('tbl_pengguna')
                ->onDelete('cascade'); // hapus todo kalau user dihapus
            $table->foreignId('kategori_id')
                ->nullable()
                ->constrained('tbl_kategori')
                ->onDelete('set null'); // set null kalau kategori dihapus
            $table->string('judul');
            $table->text('deskripsi')->nullable(); // deskripsi detail todo
            $table->dateTime('tenggat_waktu')->nullable(); // deadline
            $table->enum('prioritas', ['tinggi', 'sedang', 'rendah'])->default('sedang');
            $table->enum('status', ['tertunda', 'sedang_dikerjakan', 'selesai'])->default('tertunda');
            $table->boolean('disematkan')->default(false); // pin todo penting
            $table->boolean('diarsipkan')->default(false); // arsip todo lama
            $table->timestamp('diselesaikan_pada')->nullable(); // waktu selesai
            $table->timestamps();

            // index untuk query lebih cepat
            $table->index(['pengguna_id', 'status', 'diarsipkan']);
            $table->index('tenggat_waktu');
        });
    }

    /**
     * rollback migrasi (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_todo');
    }
};
