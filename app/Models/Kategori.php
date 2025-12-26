<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'tbl_kategori';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

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

    // relasi ke pengguna dan todo
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function todo()
    {
        return $this->hasMany(Todo::class, 'kategori_id');
    }
}
