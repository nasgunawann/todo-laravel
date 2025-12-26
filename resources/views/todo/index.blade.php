@extends('layouts.app')

@section('title', 'Semua Tugas')

@section('content')
<div class="container-fluid" style="max-width: 900px;">
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
                        <option value="">Status</option>
                        <option value="tertunda" {{ request('status') === 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                        <option value="sedang_dikerjakan" {{ request('status') === 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="prioritas" class="form-select">
                        <option value="">Prioritas</option>
                        <option value="tinggi" {{ request('prioritas') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        <option value="sedang" {{ request('prioritas') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="rendah" {{ request('prioritas') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">Kategori</option>
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

    <!-- todos list -->
    <div id="todo-grid">
        @forelse($todos as $todo)
            <div class="todo-row {{ $todo->status === 'selesai' ? 'todo-completed' : '' }}" data-todo-id="{{ $todo->id }}">
                <div class="todo-checkbox">
                    <input type="checkbox" class="form-check-input" 
                           {{ $todo->status === 'selesai' ? 'checked' : '' }}
                           onclick="toggleSelesai({{ $todo->id }})">
                </div>
                
                <div class="todo-content">
                    <h6 class="todo-title">{{ $todo->judul }}</h6>
                    @if($todo->deskripsi)
                        <p class="todo-description">{{ Str::limit($todo->deskripsi, 100) }}</p>
                    @endif
                    @if($todo->tenggat_waktu)
                        <div class="todo-deadline {{ $todo->apakah_terlambat ? 'deadline-overdue' : '' }}">
                            <i class="ti ti-calendar"></i>
                            <span>{{ $todo->tenggat_waktu->format('d M Y, H:i') }}</span>
                            @if($todo->apakah_terlambat && $todo->status !== 'selesai')
                                <span class="badge-overdue">Terlambat</span>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="todo-meta">
                    @if($todo->kategori)
                        <span class="kategori-badge">
                            <i class="ti ti-{{ $todo->kategori->ikon ?? 'tag' }}" style="color: {{ $todo->kategori->warna }}"></i>
                        </span>
                    @endif
                    
                    <span class="priority-badge priority-{{ $todo->prioritas }}">
                        <i class="ti ti-point-filled"></i>
                        <span class="priority-text">{{ ucfirst($todo->prioritas) }}</span>
                    </span>
                    
                    @if($todo->disematkan)
                        <span class="pin-badge">
                            <i class="ti ti-pin-filled"></i>
                        </span>
                    @endif
                </div>
                
                <div class="todo-actions">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="openEditModal({{ $todo->id}});return false;"><i class="ti ti-edit"></i> Edit</a></li>
                            <li><a class="dropdown-item" href="#" onclick="togglePin({{ $todo->id }});return false;"><i class="ti ti-pin"></i> {{ $todo->disematkan ? 'Lepas Pin' : 'Sematkan' }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="hapusTodo({{ $todo->id }});return false;"><i class="ti ti-trash"></i> Hapus</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="ti ti-list-check" style="font-size: 4rem; color: #d4d4d4;"></i>
                <p class="text-muted mt-3">Tidak ada tugas</p>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createTodoModal">
                    <i class="ti ti-plus"></i> Buat tugas pertama
                </button>
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
/* todo row list */
.todo-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e5e5e5;
    transition: background 0.15s;
}

.todo-row:hover {
    background: #f9f9f9;
}

.todo-row.todo-completed {
    background: #f5f5f5;
}

.todo-row.todo-completed .todo-title,
.todo-row.todo-completed .todo-description {
    text-decoration: line-through;
    color: #a3a3a3;
}

.todo-checkbox {
    flex-shrink: 0;
}

.todo-content {
    flex: 1;
    min-width: 0;
}

.todo-title {
    font-size: 0.9375rem;
    font-weight: 500;
    margin: 0;
    color: #000;
}

.todo-description {
    font-size: 0.875rem;
    color: #737373;
    margin: 0.25rem 0 0 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.todo-deadline {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    color: #737373;
    margin-top: 0.375rem;
}

.todo-deadline i {
    font-size: 0.875rem;
}

.todo-deadline.deadline-overdue {
    color: #dc2626;
}

.badge-overdue {
    font-size: 0.75rem;
    padding: 0.125rem 0.375rem;
    background: #fee;
    color: #dc2626;
    border-radius: 0.25rem;
    font-weight: 500;
}

.todo-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.kategori-badge i,
.pin-badge i {
    font-size: 1.125rem;
}

.priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.625rem;
    border-radius: 1rem;
    font-size: 0.8125rem;
    font-weight: 500;
}

.priority-badge i {
    font-size: 0.875rem;
}

.priority-badge.priority-tinggi {
    background: #fee;
    color: #991b1b;
}

.priority-badge.priority-tinggi i {
    color: #dc2626;
}

.priority-badge.priority-sedang {
    background: #fef3c7;
    color: #92400e;
}

.priority-badge.priority-sedang i {
    color: #f59e0b;
}

.priority-badge.priority-rendah {
    background: #dbeafe;
    color: #1e40af;
}

.priority-badge.priority-rendah i {
    color: #3b82f6;
}

.todo-actions {
    flex-shrink: 0;
}

.todo-actions .btn {
    background: transparent;
}

.todo-actions .btn:hover {
    background: #e5e5e5;
}
</style>
@endpush
@endsection
