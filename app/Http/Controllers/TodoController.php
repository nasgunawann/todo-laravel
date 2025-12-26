<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Models\Kategori;

class TodoController extends Controller
{
    // tampilkan daftar todo
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->todo()->aktif()->with('kategori');

        // filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // filter berdasarkan prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        // pencarian
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->q . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->q . '%');
            });
        }

        // sorting
        $query->orderBy('disematkan', 'desc')
            ->orderBy('tenggat_waktu', 'asc');

        $todos = $query->paginate(12);
        $kategori = $user->kategori()->get();

        return view('todo.index', compact('todos', 'kategori'));
    }

    // tampilkan form buat todo baru
    public function create()
    {
        $kategori = Auth::user()->kategori()->get();
        return view('todo.create', compact('kategori'));
    }

    // simpan todo baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'nullable',
            'kategori_id' => 'nullable|exists:tbl_kategori,id',
            'tenggat_waktu' => 'nullable|date',
            'prioritas' => 'required|in:tinggi,sedang,rendah',
        ], [
            'judul.required' => 'judul wajib diisi',
            'kategori_id.exists' => 'kategori tidak valid',
            'tenggat_waktu.date' => 'format tanggal tidak valid',
            'prioritas.in' => 'prioritas harus tinggi, sedang, atau rendah',
        ]);

        Auth::user()->todo()->create($validated);

        return redirect()->route('todo.index')->with('success', 'todo berhasil ditambahkan');
    }

    // tampilkan form edit todo
    public function edit(Todo $todo)
    {
        // pastikan todo milik user yang login
        if ($todo->pengguna_id !== Auth::id()) {
            abort(403);
        }

        $kategori = Auth::user()->kategori()->get();
        return view('todo.edit', compact('todo', 'kategori'));
    }

    // update todo
    public function update(Request $request, Todo $todo)
    {
        // pastikan todo milik user yang login
        if ($todo->pengguna_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'nullable',
            'kategori_id' => 'nullable|exists:tbl_kategori,id',
            'tenggat_waktu' => 'nullable|date',
            'prioritas' => 'required|in:tinggi,sedang,rendah',
            'status' => 'required|in:tertunda,sedang_dikerjakan,selesai',
        ]);

        $todo->update($validated);

        return redirect()->route('todo.index')->with('success', 'todo berhasil diupdate');
    }

    // hapus todo
    public function destroy(Todo $todo)
    {
        // pastikan todo milik user yang login
        if ($todo->pengguna_id !== Auth::id()) {
            abort(403);
        }

        $todo->delete();

        return response()->json(['success' => true, 'message' => 'todo berhasil dihapus']);
    }

    // toggle status selesai
    public function toggleSelesai(Todo $todo)
    {
        if ($todo->pengguna_id !== Auth::id()) {
            abort(403);
        }

        if ($todo->status === 'selesai') {
            $todo->update([
                'status' => 'tertunda',
                'diselesaikan_pada' => null,
            ]);
        } else {
            $todo->update([
                'status' => 'selesai',
                'diselesaikan_pada' => now(),
            ]);
        }

        return response()->json(['success' => true, 'status' => $todo->status]);
    }

    // toggle pin/sematkan
    public function toggleSematkan(Todo $todo)
    {
        if ($todo->pengguna_id !== Auth::id()) {
            abort(403);
        }

        $todo->update(['disematkan' => !$todo->disematkan]);

        return response()->json(['success' => true, 'disematkan' => $todo->disematkan]);
    }

    // arsipkan todo
    public function arsipkan(Todo $todo)
    {
        if ($todo->pengguna_id !== Auth::id()) {
            abort(403);
        }

        $todo->update(['diarsipkan' => true]);

        return response()->json(['success' => true, 'message' => 'todo berhasil diarsipkan']);
    }

    // arsipkan multiple todo
    public function arsipkanMassal(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tbl_todo,id',
        ]);

        Todo::whereIn('id', $validated['ids'])
            ->where('pengguna_id', Auth::id())
            ->update(['diarsipkan' => true]);

        return response()->json(['success' => true, 'message' => 'todo berhasil diarsipkan']);
    }
}
