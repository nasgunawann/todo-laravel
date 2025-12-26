<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'tbl_todo';

    protected $fillable = [
        'pengguna_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'tenggat_waktu',
        'prioritas',
        'status',
        'disematkan',
        'diarsipkan',
        'diselesaikan_pada',
    ];

    protected $casts = [
        'tenggat_waktu' => 'datetime',
        'diselesaikan_pada' => 'datetime',
        'disematkan' => 'boolean',
        'diarsipkan' => 'boolean',
    ];

    // query helper untuk filter data
    public function scopeAktif($query)
    {
        return $query->where('diarsipkan', false);
    }

    public function scopeDisematkan($query)
    {
        return $query->where('disematkan', true);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('tenggat_waktu', '<', now())
            ->where('status', '!=', 'selesai');
    }

    // relasi ke user dan kategori
    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // cek apakah todo sudah terlambat
    public function getApakahTerlambatAttribute()
    {
        return $this->tenggat_waktu && $this->tenggat_waktu->isPast()
            && $this->status !== 'selesai';
    }
}
