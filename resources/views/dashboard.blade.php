@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="notion-dashboard">
    <!-- header dengan sapaan -->
    <div class="dashboard-header mb-5">
        <h1 class="fw-bold mb-2">Selamat {{ now()->format('A') === 'AM' ? 'Pagi' : 'Siang' }}, {{ Auth::user()->nama }}!</h1>
        <p class="text-muted">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>

    <!-- quick stats - notion style blocks -->
    <div class="stats-grid mb-5">
        <div class="stat-block">
            <div class="stat-icon">
                <i class="ti ti-list-check"></i>
            </div>
            <div class="stat-content">
                <h2 class="stat-number">{{ $stats['total'] }}</h2>
                <p class="stat-label">Total Tugas</p>
            </div>
        </div>
        
        <div class="stat-block">
            <div class="stat-icon">
                <i class="ti ti-circle-check"></i>
            </div>
            <div class="stat-content">
                <h2 class="stat-number">{{ $stats['selesai'] }}</h2>
                <p class="stat-label">Selesai</p>
            </div>
        </div>
        
        <div class="stat-block">
            <div class="stat-icon">
                <i class="ti ti-clock"></i>
            </div>
            <div class="stat-content">
                <h2 class="stat-number">{{ $stats['sedang_dikerjakan'] }}</h2>
                <p class="stat-label">Sedang Dikerjakan</p>
            </div>
        </div>
        
        <div class="stat-block">
            <div class="stat-icon text-danger">
                <i class="ti ti-alert-triangle"></i>
            </div>
            <div class="stat-content">
                <h2 class="stat-number text-danger">{{ $stats['terlambat'] }}</h2>
                <p class="stat-label">Terlambat</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- pinned todos -->
        <div class="col-lg-8">
            <div class="notion-block">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-pin"></i>
                        Tugas Disematkan
                    </h5>
                    <a href="{{ route('todo.index') }}" class="btn btn-sm btn-light">Lihat semua</a>
                </div>
                
                <div class="block-content">
                    @forelse($todoPenting as $todo)
                        <div class="todo-row">
                            <div class="todo-checkbox">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $todo->status === 'selesai' ? 'checked' : '' }}
                                       onclick="toggleSelesai({{ $todo->id }})">
                            </div>
                            <div class="todo-content">
                                <div class="todo-title {{ $todo->status === 'selesai' ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $todo->judul }}
                                </div>
                                <div class="todo-meta">
                                    @if($todo->kategori)
                                        <span class="meta-badge">
                                            <i class="ti ti-{{ $todo->kategori->ikon ?? 'tag' }}" style="color: {{ $todo->kategori->warna }}"></i>
                                            {{ $todo->kategori->nama }}
                                        </span>
                                    @endif
                                    <span class="meta-badge priority-{{ $todo->prioritas }}">
                                        {{ $todo->prioritas }}
                                    </span>
                                    @if($todo->tenggat_waktu)
                                        <span class="meta-badge">
                                            <i class="ti ti-calendar"></i>
                                            {{ $todo->tenggat_waktu->format('M d') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="todo-actions">
                                <a href="{{ route('todo.edit', $todo) }}" class="action-btn">
                                    <i class="ti ti-edit"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="ti ti-pin" style="font-size: 3rem; color: #d4d4d4;"></i>
                            </div>
                            <p class="empty-text">Belum ada tugas yang disematkan</p>
                            <a href="{{ route('todo.create') }}" class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i> Buat tugas pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- recent todos -->
            <div class="notion-block mt-4">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-clock"></i>
                        Tugas Terbaru
                    </h5>
                    <a href="{{ route('todo.create') }}" class="btn btn-sm btn-dark">
                        <i class="ti ti-plus"></i> Tugas Baru
                    </a>
                </div>
                
                <div class="block-content">
                    @forelse($todoTerbaru as $todo)
                        <div class="todo-row">
                            <div class="todo-checkbox">
                                <input type="checkbox" class="form-check-input" 
                                       {{ $todo->status === 'selesai' ? 'checked' : '' }}
                                       onclick="toggleSelesai({{ $todo->id }})">
                            </div>
                            <div class="todo-content">
                                <div class="todo-title {{ $todo->status === 'selesai' ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $todo->judul }}
                                </div>
                                <div class="todo-meta">
                                    @if($todo->kategori)
                                        <span class="meta-badge">
                                            <i class="ti ti-{{ $todo->kategori->ikon ?? 'tag' }}" style="color: {{ $todo->kategori->warna}}"></i>
                                            {{ $todo->kategori->nama }}
                                        </span>
                                    @endif
                                    <span class="meta-badge status-{{ $todo->status }}">
                                        {{ str_replace('_', ' ', $todo->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="todo-actions">
                                <a href="{{ route('todo.edit', $todo) }}" class="action-btn">
                                    <i class="ti ti-edit"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="ti ti-list-check" style="font-size: 3rem; color: #d4d4d4;"></i>
                            </div>
                            <p class="empty-text">Belum ada tugas. Mulai atur pekerjaanmu!</p>
                            <a href="{{ route('todo.create') }}" class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i> Buat tugas
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- sidebar - categories & quick actions -->
        <div class="col-lg-4">
            <!-- categories -->
            <div class="notion-block">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-folder"></i>
                        Kategori
                    </h5>
                    <a href="{{ route('kategori.index') }}" class="btn btn-sm btn-light">Kelola</a>
                </div>
                
                <div class="block-content">
                    @foreach($kategori as $kat)
                        <a href="{{ route('todo.index', ['kategori_id' => $kat->id]) }}" class="category-item">
                            <div class="category-icon">
                                <i class="ti ti-{{ $kat->ikon ?? 'tag' }}" style="color: {{ $kat->warna }}"></i>
                            </div>
                            <div class="category-content">
                                <div class="category-name">{{ $kat->nama }}</div>
                                <div class="category-count">{{ $kat->todo_count }} tugas</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- quick actions -->
            <div class="notion-block mt-4">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-bolt"></i>
                        Aksi Cepat
                    </h5>
                </div>
                
                <div class="block-content">
                    <a href="{{ route('todo.create') }}" class="quick-action">
                        <i class="ti ti-plus"></i>
                        <span>Tugas Baru</span>
                    </a>
                    <a href="{{ route('kategori.index') }}" class="quick-action">
                        <i class="ti ti-category"></i>
                        <span>Kelola Kategori</span>
                    </a>
                    <a href="{{ route('profil.edit') }}" class="quick-action">
                        <i class="ti ti-settings"></i>
                        <span>Pengaturan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* notion-inspired dashboard styles */
.notion-dashboard {
    max-width: 1200px;
    margin: 0 auto;
}

.dashboard-header h1 {
    font-size: 1.875rem;
    color: #000;
}

/* stats grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-block {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    transition: all 0.2s;
}

.stat-block:hover {
    border-color: #d4d4d4;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.stat-icon {
    font-size: 2rem;
    color: #000;
}

.stat-icon i {
    font-size: 2rem;
}

.stat-number {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
    color: #000;
}

.stat-label {
    font-size: 0.875rem;
    color: #737373;
    margin: 0;
}

/* notion blocks */
.notion-block {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    overflow: hidden;
}

.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f5f5f5;
}

.block-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #000;
}

.block-title i {
    font-size: 1.25rem;
}

.block-content {
    padding: 0.5rem;
}

/* todo rows */
.todo-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    transition: background 0.15s;
}

.todo-row:hover {
    background: #f5f5f5;
}

.todo-checkbox {
    flex-shrink: 0;
}

.todo-content {
    flex-grow: 1;
    min-width: 0;
}

.todo-title {
    font-size: 0.9375rem;
    color: #000;
    margin-bottom: 0.25rem;
}

.todo-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #737373;
    padding: 0.125rem 0.5rem;
    background: #f5f5f5;
    border-radius: 4px;
}

.meta-badge i {
    font-size: 0.875rem;
}

.meta-badge.priority-tinggi {
    background: #fee;
    color: #c00;
}

.meta-badge.priority-sedang {
    background: #fef3e0;
    color: #c70;
}

.meta-badge.priority-rendah {
    background: #e0f2fe;
    color: #0369a1;
}

.todo-actions {
    flex-shrink: 0;
    opacity: 0;
    transition: opacity 0.15s;
}

.todo-row:hover .todo-actions {
    opacity: 1;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 4px;
    color: #737373;
    transition: all 0.15s;
}

.action-btn:hover {
    background: #e5e5e5;
    color: #000;
}

/* empty state */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    margin-bottom: 1rem;
}

.empty-text {
    color: #737373;
    margin-bottom: 1rem;
}

/* category items */
.category-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    color: inherit;
    text-decoration: none;
    transition: background 0.15s;
}

.category-item:hover {
    background: #f5f5f5;
    color: inherit;
}

.category-icon i {
    font-size: 1.25rem;
}

.category-name {
    font-size: 0.9375rem;
    color: #000;
    font-weight: 500;
}

.category-count {
    font-size: 0.8125rem;
    color: #737373;
}

/* quick actions */
.quick-action {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    color: #000;
    text-decoration: none;
    transition: background 0.15s;
}

.quick-action:hover {
    background: #f5f5f5;
    color: #000;
}

.quick-action i {
    font-size: 1.125rem;
    color: #737373;
}
</style>
@endpush

@push('scripts')
<script>
function toggleSelesai(todoId) {
    $.post(`/todo/${todoId}/toggle-selesai`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function() {
        location.reload();
    })
    .fail(function() {
        Swal.fire('Error', 'Gagal mengupdate todo', 'error');
    });
}
</script>
@endpush
@endsection
