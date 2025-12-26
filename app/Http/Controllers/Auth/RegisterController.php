<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // tampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // proses registrasi
    public function register(Request $request)
    {
        // validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_pengguna,email',
            'kata_sandi' => 'required|min:6|confirmed',
        ], [
            'nama.required' => 'nama wajib diisi',
            'email.required' => 'email wajib diisi',
            'email.email' => 'format email tidak valid',
            'email.unique' => 'email sudah terdaftar',
            'kata_sandi.required' => 'password wajib diisi',
            'kata_sandi.min' => 'password minimal 6 karakter',
            'kata_sandi.confirmed' => 'konfirmasi password tidak cocok',
        ]);

        // buat user baru
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
        ]);

        // buat kategori default
        $kategoriDefault = [
            ['nama' => 'Pribadi', 'warna' => '#6366f1', 'ikon' => 'user', 'adalah_default' => true],
            ['nama' => 'Pekerjaan', 'warna' => '#0ea5e9', 'ikon' => 'briefcase', 'adalah_default' => true],
            ['nama' => 'Belanja', 'warna' => '#f59e0b', 'ikon' => 'shopping-cart', 'adalah_default' => true],
            ['nama' => 'Kesehatan', 'warna' => '#10b981', 'ikon' => 'heart-plus', 'adalah_default' => true],
        ];

        foreach ($kategoriDefault as $kategori) {
            Kategori::create([
                'pengguna_id' => $user->id,
                'nama' => $kategori['nama'],
                'warna' => $kategori['warna'],
                'ikon' => $kategori['ikon'],
                'adalah_default' => $kategori['adalah_default'],
            ]);
        }

        // auto login setelah register
        Auth::login($user);

        return redirect('/')->with('success', 'registrasi berhasil! selamat datang');
    }
}
