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
        Schema::create('tbl_pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_terverifikasi_pada')->nullable();
            $table->string('kata_sandi');
            $table->string('avatar')->nullable();
            $table->rememberToken(); // token ingat saya
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * rollback migrasi (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pengguna');
    }
};
