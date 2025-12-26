@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
<div class="container" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kategori</h2>
            <p class="text-muted mb-0">Kelola kategori tugas Anda</p>
        </div>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="ti ti-plus"></i> Kategori Baru
        </button>
    </div>

    <div class="row g-3">
        @foreach($kategori as $kat)
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="category-icon-lg">
                                <i class="ti ti-{{ $kat->ikon ?? 'tag' }}" style="color: {{ $kat->warna }}; font-size: 2rem;"></i>
                            </div>
                            @if(!$kat->adalah_default)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="editCategory({{ $kat->id }}, '{{ $kat->nama }}', '{{ $kat->warna }}', '{{ $kat->ikon }}')">
                                                <i class="ti ti-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteCategory({{ $kat->id }})">
                                                <i class="ti ti-trash"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <span class="badge bg-light text-dark border">Default</span>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-2">{{ $kat->nama }}</h5>
                        <p class="text-muted mb-0">{{ $kat->todo_count }} tugas</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-500">Name</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Color</label>
                        <input type="color" class="form-control form-control-color w-100" name="warna" value="#6366f1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Ikon (nama Tabler Icon)</label>
                        <input type="text" class="form-control" name="ikon" placeholder="cth: tag, home, briefcase">
                        <small class="text-muted">Lihat ikon di tabler.io/icons</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-dark" onclick="submitCategory()">Buat</button>
</div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Ubah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_category_id">
                    <div class="mb-3">
                        <label class="form-label fw-500">Name</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Color</label>
                        <input type="color" class="form-control form-control-color w-100" id="edit_warna" name="warna">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500"> ikon</label>
                        <input type="text" class="form-control" id="edit_ikon" name="ikon">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-dark" onclick="updateCategory()">Perbarui</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function submitCategory() {
    $.post('{{ route("kategori.store") }}', $('#addCategoryForm').serialize())
        .done(function() {
            location.reload();
        })
        .fail(function(xhr) {
            Swal.fire('Error', xhr.responseJSON.message || 'Gagal membuat kategori', 'error');
        });
}

function editCategory(id, nama, warna, ikon) {
    $('#edit_category_id').val(id);
    $('#edit_nama').val(nama);
    $('#edit_warna').val(warna);
    $('#edit_ikon').val(ikon);
    $('#editCategoryModal').modal('show');
}

function updateCategory() {
    const id = $('#edit_category_id').val();
    $.ajax({
        url: `/kategori/${id}`,
        method: 'PUT',
        data: $('#editCategoryForm').serialize(),
        success: function() {
            location.reload();
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON.message || 'Gagal update kategori', 'error');
        }
    });
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Hapus kategori?',
        text: 'Todo di kategori ini akan menjadi tanpa kategori',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/kategori/${id}`,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    location.reload();
                }
            });
        }
    });
}
</script>
@endpush
@endsection
