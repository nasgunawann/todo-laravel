@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="mb-4">
        <h2 class="fw-bold">Edit Tugas</h2>
        <p class="text-muted">Perbarui detail tugas</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('todo.update', $todo) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label fw-500">Judul</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" name="judul" value="{{ old('judul', $todo->judul) }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-500">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" 
                              rows="4">{{ old('deskripsi', $todo->deskripsi) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori_id" class="form-label fw-500">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id">
                            <option value="">Tanpa Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" 
                                        {{ old('kategori_id', $todo->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label fw-500">Prioritas</label>
                        <select class="form-select" id="prioritas" name="prioritas" required>
                            <option value="rendah" {{ old('prioritas', $todo->prioritas) === 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ old('prioritas', $todo->prioritas) === 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ old('prioritas', $todo->prioritas) === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-500">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="tertunda" {{ old('status', $todo->status) === 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                            <option value="sedang_dikerjakan" {{ old('status', $todo->status) === 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                            <option value="selesai" {{ old('status', $todo->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="tenggat_waktu" class="form-label fw-500">Tenggat Waktu</label>
                        <input type="datetime-local" class="form-control" 
                               id="tenggat_waktu" name="tenggat_waktu" 
                               value="{{ old('tenggat_waktu', $todo->tenggat_waktu?->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark">
                        <i class="ti ti-check"></i> Perbarui Tugas
                    </button>
                    <a href="{{ route('todo.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
