<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;

class KategoriController extends Controller
{
    // tampilkan daftar kategori
    public function index()
    {
        $kategori = Auth::user()->kategori()
            ->withCount(['todo' => function ($query) {
                $query->aktif();
            }])
            ->get();

        return view('kategori.index', compact('kategori'));
    }

    // simpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'warna' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'ikon' => 'nullable|max:50',
        ], [
            'nama.required' => 'nama kategori wajib diisi',
            'warna.regex' => 'format warna harus hex (#000000)',
        ]);

        Auth::user()->kategori()->create($validated);

        return response()->json(['success' => true, 'message' => 'kategori berhasil ditambahkan']);
    }

    // update kategori
    public function update(Request $request, Kategori $kategori)
    {
        // pastikan kategori milik user yang login
        if ($kategori->pengguna_id !== Auth::id()) {
            abort(403);
        }

        // tidak bisa edit kategori default
        if ($kategori->adalah_default) {
            return response()->json(['success' => false, 'message' => 'kategori default tidak bisa diedit'], 403);
        }

        $validated = $request->validate([
            'nama' => 'required|max:100',
            'warna' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'ikon' => 'nullable|max:50',
        ]);

        $kategori->update($validated);

        return response()->json(['success' => true, 'message' => 'kategori berhasil diupdate']);
    }

    // hapus kategori
    public function destroy(Kategori $kategori)
    {
        // pastikan kategori milik user yang login
        if ($kategori->pengguna_id !== Auth::id()) {
            abort(403);
        }

        // tidak bisa hapus kategori default
        if ($kategori->adalah_default) {
            return response()->json(['success' => false, 'message' => 'kategori default tidak bisa dihapus'], 403);
        }

        // set todo yang pakai kategori ini jadi null
        $kategori->todo()->update(['kategori_id' => null]);

        $kategori->delete();

        return response()->json(['success' => true, 'message' => 'kategori berhasil dihapus']);
    }
}
