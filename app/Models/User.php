<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_pengguna';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'avatar',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
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
        return 'remember_token';
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
