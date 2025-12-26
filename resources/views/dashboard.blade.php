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
    <!-- stats grid removed to save space -->

    <div class="row g-4">
        <!-- pinned todos -->
        <div class="col-lg-8">
            <div class="notion-block">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-pin"></i>
                        Tugas Disematkan
                    </h5>
                </div>
                
                <div class="block-content">
                    @forelse($todoPenting as $todo)
                        <div class="todo-row {{ $todo->status === 'selesai' ? 'todo-completed' : '' }}" data-todo-id="{{ $todo->id }}">
                            <div class="todo-checkbox">
                                <input type="checkbox" class="form-check-input" 
                                    {{ $todo->status === 'selesai' ? 'checked' : '' }}
                                    onclick="toggleSelesai({{ $todo->id }})">
                            </div>
                            
                            <div class="todo-content">
                                <h6 class="todo-title">{{ $todo->judul }}</h6>
                                @if($todo->deskripsi)
                                    <p class="todo-description">{{ Str::limit($todo->deskripsi, 80) }}</p>
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
                                <a href="{{ route('todo.index') }}#edit-{{ $todo->id }}" class="btn btn-sm btn-light border-0">
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
                    <a href="{{ route('todo.index') }}" class="btn btn-sm btn-light">Lihat semua</a>
                </div>
                
                <div class="block-content">
                    @forelse($todoTerbaru as $todo)
                        <div class="todo-row {{ $todo->status === 'selesai' ? 'todo-completed' : '' }}" data-todo-id="{{ $todo->id }}">
                            <div class="todo-checkbox">
                                <input type="checkbox" class="form-check-input" 
                                    {{ $todo->status === 'selesai' ? 'checked' : '' }}
                                    onclick="toggleSelesai({{ $todo->id }})">
                            </div>
                            
                            <div class="todo-content">
                                <h6 class="todo-title">{{ $todo->judul }}</h6>
                                @if($todo->deskripsi)
                                    <p class="todo-description">{{ Str::limit($todo->deskripsi, 80) }}</p>
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
                            
                            {{-- <div class="todo-actions">
                                <a href="{{ route('todo.index') }}#edit-{{ $todo->id }}" class="btn btn-sm btn-light border-0">
                                    <i class="ti ti-edit"></i>
                                </a>
                            </div> --}}
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="ti ti-list-check" style="font-size: 3rem; color: #d4d4d4;"></i>
                            </div>
                            <p class="empty-text">Belum ada tugas. Mulai atur pekerjaanmu!</p>
                            <a href="{{ route('todo.index') }}#create" class="btn btn-sm btn-dark">
                                <i class="ti ti-plus"></i> Buat tugas
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- sidebar - categories & quick actions -->
        <!-- sidebar -->
        <div class="col-lg-4">
            <!-- quick actions (top) -->
            <div class="notion-block mb-4">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-bolt"></i>
                        Aksi Cepat
                    </h5>
                </div>
                
                <div class="block-content">
                    <a href="{{ route('todo.index') }}#create" class="quick-action ">
                        <i class="ti ti-plus"></i>
                        <span>Tugas Baru</span>
                    </a>
                        {{-- <a href="{{ route('kategori.index') }}" class="quick-action">
                            <i class="ti ti-category"></i>
                            <span>Kelola Kategori</span>
                        </a>
                        <a href="{{ route('profil.edit') }}" class="quick-action">
                            <i class="ti ti-settings"></i>
                            <span>Pengaturan</span>
                        </a> --}}
                </div>
            </div>

            <!-- stats (middle) -->
            <div class="notion-block mb-4">
                <div class="block-header">
                    <h5 class="block-title">
                        <i class="ti ti-chart-pie"></i>
                        Ringkasan
                    </h5>
                </div>
                <!-- list style stats -->
                <div class="stats-list">
                    <div class="stat-item">
                        <div class="d-flex align-items-center gap-3">
                            <span class="stat-icon-sm bg-light text-dark"><i class="ti ti-list-check"></i></span>
                            <span class="stat-label">Total Tugas</span>
                        </div>
                        <span class="stat-value">{{ $stats['total'] }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="d-flex align-items-center gap-3">
                            <span class="stat-icon-sm bg-success-subtle text-success"><i class="ti ti-circle-check"></i></span>
                            <span class="stat-label">Selesai</span>
                        </div>
                        <span class="stat-value">{{ $stats['selesai'] }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="d-flex align-items-center gap-3">
                            <span class="stat-icon-sm bg-warning-subtle text-warning"><i class="ti ti-clock"></i></span>
                            <span class="stat-label">Sedang Dikerjakan</span>
                        </div>
                        <span class="stat-value">{{ $stats['sedang_dikerjakan'] }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="d-flex align-items-center gap-3">
                            <span class="stat-icon-sm bg-danger-subtle text-danger"><i class="ti ti-alert-triangle"></i></span>
                            <span class="stat-label">Terlambat</span>
                        </div>
                        <span class="stat-value">{{ $stats['terlambat'] }}</span>
                    </div>
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

/* stats list */
.stats-list {
    padding: 0.5rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 6px;
    margin-bottom: 0.25rem;
    transition: background 0.15s;
}

.stat-item:hover {
    background: #f9f9f9;
}

.stat-icon-sm {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    font-size: 1.125rem;
}

.stat-label {
    font-size: 0.9375rem;
    color: #404040;
}

.stat-value {
    font-weight: 600;
    font-size: 0.9375rem;
    color: #171717;
}

/* bg utilities for icons */
.bg-success-subtle { background-color: #dcfce7 !important; }
.bg-warning-subtle { background-color: #fef9c3 !important; }
.bg-danger-subtle { background-color: #fee2e2 !important; }

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

/* todo row list - consistent with todo/index */
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

.todo-actions .btn:hover {
    background: #e5e5e5;
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
@endsection
