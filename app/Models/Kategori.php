<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'tbl_kategori';

    protected $fillable = [
        'pengguna_id',
        'nama',
        'warna',
        'ikon',
        'adalah_default',
    ];

    protected $casts = [
        'adalah_default' => 'boolean',
    ];

    // relasi ke user dan todo
    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function todo()
    {
        return $this->hasMany(Todo::class, 'kategori_id');
    }
}
