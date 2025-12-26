<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_pengguna';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'avatar',
    ];

    protected $hidden = [
        'kata_sandi',
        'token_ingat_saya',
    ];

    protected $casts = [
        'email_terverifikasi_pada' => 'datetime',
    ];

    // override untuk ambil password
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function getRememberTokenName()
    {
        return 'token_ingat_saya';
    }

    // relasi ke tabel kategori dan todo
    public function kategori()
    {
        return $this->hasMany(Kategori::class, 'pengguna_id');
    }

    public function todo()
    {
        return $this->hasMany(Todo::class, 'pengguna_id');
    }
}
