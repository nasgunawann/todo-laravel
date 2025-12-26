@extends('layouts.app')

@section('title', 'Buat Tugas')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="mb-4">
        <h2 class="fw-bold">Buat Tugas Baru</h2>
        <p class="text-muted">Isi detail untuk membuat tugas baru</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('todo.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label fw-500">Judul</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" name="judul" value="{{ old('judul') }}" required autofocus>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-500">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" 
                              rows="4">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori_id" class="form-label fw-500">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id">
                            <option value="">Tanpa Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label fw-500">Prioritas</label>
                        <select class="form-select @error('prioritas') is-invalid @enderror" 
                                id="prioritas" name="prioritas" required>
                            <option value="rendah" {{ old('prioritas') === 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ old('prioritas', 'sedang') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ old('prioritas') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                        @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tenggat_waktu" class="form-label fw-500">Tenggat Waktu (Opsional)</label>
                    <input type="datetime-local" class="form-control" 
                           id="tenggat_waktu" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark">
                        <i class="ti ti-check"></i> Buat Tugas
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
