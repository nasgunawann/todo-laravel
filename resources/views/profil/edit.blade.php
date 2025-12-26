@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="mb-4">
        <h2 class="fw-bold">Pengaturan Profil</h2>
        <p class="text-muted">Kelola informasi akun Anda</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('profil.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label fw-500">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-500">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3">Ganti Password</h5>
                <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengganti password</p>

                <div class="mb-3">
                    <label for="kata_sandi_lama" class="form-label fw-500">Password Lama</label>
                    <input type="password" class="form-control @error('kata_sandi_lama') is-invalid @enderror" 
                           id="kata_sandi_lama" name="kata_sandi_lama">
                    @error('kata_sandi_lama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kata_sandi_baru" class="form-label fw-500">Password Baru</label>
                    <input type="password" class="form-control @error('kata_sandi_baru') is-invalid @enderror" 
                           id="kata_sandi_baru" name="kata_sandi_baru">
                    @error('kata_sandi_baru')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Minimal 6 karakter</small>
                </div>

                <div class="mb-4">
                    <label for="kata_sandi_baru_confirmation" class="form-label fw-500">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" 
                           id="kata_sandi_baru_confirmation" name="kata_sandi_baru_confirmation">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark">
                        <i class="ti ti-check"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-x"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
