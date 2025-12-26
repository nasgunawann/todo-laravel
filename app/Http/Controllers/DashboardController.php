<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Models\Kategori;

class DashboardController extends Controller
{
    // tampilkan dashboard dengan statistik
    public function index()
    {
        $user = Auth::user();

        // hitung statistik todo
        $stats = [
            'total' => $user->todo()->aktif()->count(),
            'selesai' => $user->todo()->aktif()->where('status', 'selesai')->count(),
            'sedang_dikerjakan' => $user->todo()->aktif()->where('status', 'sedang_dikerjakan')->count(),
            'tertunda' => $user->todo()->aktif()->where('status', 'tertunda')->count(),
            'terlambat' => $user->todo()->terlambat()->count(),
        ];

        // ambil todo yang disematkan
        $todoPenting = $user->todo()
            ->aktif()
            ->disematkan()
            ->with('kategori')
            ->orderBy('tenggat_waktu', 'asc')
            ->limit(5)
            ->get();

        // ambil todo terbaru
        $todoTerbaru = $user->todo()
            ->aktif()
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ambil kategori dengan jumlah todo
        $kategori = $user->kategori()
            ->withCount(['todo' => function ($query) {
                $query->aktif();
            }])
            ->get();

        return view('dashboard', compact('stats', 'todoPenting', 'todoTerbaru', 'kategori'));
    }
}
