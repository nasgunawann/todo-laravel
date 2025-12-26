@extends('layouts.app')

@section('title', 'Semua Tugas')

@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Semua Tugas</h2>
        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createTodoModal">
            <i class="ti ti-plus"></i> Tugas Baru
        </button>
    </div>

    <!-- filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('todo.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control" placeholder="Cari..." value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="tertunda" {{ request('status') === 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                        <option value="sedang_dikerjakan" {{ request('status') === 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="prioritas" class="form-select">
                        <option value="">Semua Prioritas</option>
                        <option value="tinggi" {{ request('prioritas') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ request('prioritas') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ request('prioritas') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-dark w-100">
                        <i class="ti ti-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- todos grid -->
    <div class="row g-3" id="todo-grid">
        @forelse($todos as $todo)
            <div class="col-md-6 col-lg-4" data-todo-id="{{ $todo->id }}">
                <div class="card border h-100 todo-card-item">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $todo->status === 'selesai' ? 'checked' : '' }}
                                       onclick="toggleSelesai({{ $todo->id }})">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="openEditModal({{ $todo->id }})">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="togglePin({{ $todo->id }})">
                                            <i class="ti ti-pin"></i> {{ $todo->disematkan ? 'Lepas Pin' : 'Sematkan' }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="hapusTodo({{ $todo->id }})">
                                            <i class="ti ti-trash"></i> Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h6 class="mb-2 {{ $todo->status === 'selesai' ? 'text-decoration-line-through text-muted' : '' }}">
                            @if($todo->disematkan)
                                <i class="ti ti-pin text-dark"></i>
                            @endif
                            {{ $todo->judul }}
                        </h6>

                        @if($todo->deskripsi)
                            <p class="text-muted small mb-3">{{ Str::limit($todo->deskripsi, 80) }}</p>
                        @endif

                        <div class="d-flex flex-wrap gap-1 mb-2">
                            @if($todo->kategori)
                                <span class="badge bg-light text-dark border">
                                    <i class="ti ti-{{ $todo->kategori->ikon ?? 'tag' }}" style="color: {{ $todo->kategori->warna }}"></i>
                                    {{ $todo->kategori->nama }}
                                </span>
                            @endif
                            <span class="badge priority-{{ $todo->prioritas }}">{{ $todo->prioritas }}</span>
                            <span class="badge status-{{ $todo->status }}">{{ str_replace('_', ' ', $todo->status) }}</span>
                        </div>

                        @if($todo->tenggat_waktu)
                            <div class="text-muted small">
                                <i class="ti ti-calendar"></i> {{ $todo->tenggat_waktu->format('d M Y, H:i') }}
                                @if($todo->apakah_terlambat)
                                    <span class="text-danger">(Terlambat)</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ti ti-list-check" style="font-size: 4rem; color: #d4d4d4;"></i>
                    <p class="text-muted mt-3">Tidak ada tugas</p>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createTodoModal">
                        <i class="ti ti-plus"></i> Buat tugas pertama
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- pagination -->
    <div class="mt-4">
        {{ $todos->links() }}
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createTodoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createTodoForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select">
                                <option value="">Pilih kategori</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select name="prioritas" class="form-select" required>
                                    <option value="rendah">Rendah</option>
                                    <option value="sedang" selected>Sedang</option>
                                    <option value="tinggi">Tinggi</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tenggat Waktu</label>
                                <input type="datetime-local" name="tenggat_waktu" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-dark" onclick="submitCreateTodo()">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editTodoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTodoForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_todo_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" id="edit_judul" name="judul" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select id="edit_kategori_id" name="kategori_id" class="form-select">
                                <option value="">Pilih kategori</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select id="edit_prioritas" name="prioritas" class="form-select" required>
                                    <option value="rendah">Rendah</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="tinggi">Tinggi</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select id="edit_status" name="status" class="form-select" required>
                                    <option value="tertunda">Tertunda</option>
                                    <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tenggat Waktu</label>
                            <input type="datetime-local" id="edit_tenggat_waktu" name="tenggat_waktu" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-dark" onclick="submitEditTodo()">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.todo-card-item {
    transition: all 0.2s;
}

.todo-card-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.priority-tinggi {
    background: #fee;
    color: #c00;
}

.priority-sedang {
    background: #fef3e0;
    color: #c70;
}

.priority-rendah {
    background: #e0f2fe;
    color: #0369a1;
}

.status-tertunda {
    background: #f5f5f5;
    color: #737373;
}

.status-sedang_dikerjakan {
    background: #e0f2fe;
    color: #0369a1;
}

.status-selesai {
    background: #000;
    color: #fff;
}
</style>
@endpush
@endsection
