<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // tampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    // update profil
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_pengguna,email,' . $user->id,
            'kata_sandi_lama' => 'nullable|required_with:kata_sandi_baru',
            'kata_sandi_baru' => 'nullable|min:6|confirmed',
        ], [
            'nama.required' => 'nama wajib diisi',
            'email.required' => 'email wajib diisi',
            'email.unique' => 'email sudah digunakan',
            'kata_sandi_lama.required_with' => 'password lama wajib diisi jika ingin ganti password',
            'kata_sandi_baru.min' => 'password baru minimal 6 karakter',
            'kata_sandi_baru.confirmed' => 'konfirmasi password tidak cocok',
        ]);

        // update nama dan email
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];

        // update password jika diisi
        if ($request->filled('kata_sandi_baru')) {
            // cek password lama
            if (!Hash::check($request->kata_sandi_lama, $user->kata_sandi)) {
                return back()->withErrors(['kata_sandi_lama' => 'password lama salah']);
            }

            $user->kata_sandi = Hash::make($validated['kata_sandi_baru']);
        }

        $user->save();

        return redirect()->route('profil.edit')->with('success', 'profil berhasil diupdate');
    }
}
