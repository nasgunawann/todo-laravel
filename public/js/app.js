/**
 * todo app - ajax helpers
 * modal-based crud dengan dynamic content update
 */

// =====================================
// helper: toast notification
// =====================================
function showToast(icon, title, timer = 3000) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true
    });
}

// =====================================
// helper: validation errors di modal
// =====================================
function showValidationErrors(xhr, form) {
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        
        // clear previous errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');
        
        // show new errors
        $.each(errors, function(field, messages) {
            form.find(`[name="${field}"]`)
                .addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text(messages[0]);
        });
        
        // scroll to first error
        form.find('.is-invalid').first().focus();
    } else {
        // server error
        showToast('error', 'Terjadi kesalahan server', 5000);
    }
}

// =====================================
// helper: build todo card html
// =====================================
function buildTodoCard(todo) {
    let completed = todo.status === 'selesai' ? 'todo-completed' : '';
    let checked = todo.status === 'selesai' ? 'checked' : '';
    let kategoriIcon = todo.kategori ? `<span class="kategori-badge"><i class="ti ti-${todo.kategori.ikon || 'tag'}" style="color: ${todo.kategori.warna}"></i></span>` : '';
    let pinIcon = todo.disematkan ? '<span class="pin-badge"><i class="ti ti-pin-filled"></i></span>' : '';
    let priorityColor = {'tinggi': '#dc2626', 'sedang': '#ea580c', 'rendah': '#0284c7'}[todo.prioritas];
    
    return `
        <div class="todo-row ${completed}" data-todo-id="${todo.id}">
            <div class="todo-checkbox">
                <input type="checkbox" class="form-check-input" ${checked}
                       onclick="toggleSelesai(${todo.id})">
            </div>
            
            <div class="todo-content">
                <h6 class="todo-title">${todo.judul}</h6>
                ${todo.deskripsi ? `<p class="todo-description">${todo.deskripsi.substring(0, 100)}</p>` : ''}
                ${todo.tenggat_waktu ? `
                    <div class="todo-deadline ${todo.apakah_terlambat ? 'deadline-overdue' : ''}">
                        <i class="ti ti-calendar"></i>
                        <span>${new Date(todo.tenggat_waktu).toLocaleString('id-ID', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'})}</span>
                        ${todo.apakah_terlambat && todo.status !== 'selesai' ? '<span class="badge-overdue">Terlambat</span>' : ''}
                    </div>
                ` : ''}
            </div>
            
            <div class="todo-meta">
                ${kategoriIcon}
                <span class="priority-badge priority-${todo.prioritas}">
                    <i class="ti ti-point-filled"></i>
                    <span class="priority-text">${todo.prioritas.charAt(0).toUpperCase() + todo.prioritas.slice(1)}</span>
                </span>
                ${pinIcon}
            </div>
            
            <div class="todo-actions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="openEditModal(${todo.id});return false;"><i class="ti ti-edit"></i> Edit</a></li>
                        <li><a class="dropdown-item" href="#" onclick="togglePin(${todo.id});return false;"><i class="ti ti-pin"></i> ${todo.disematkan ? 'Lepas Pin' : 'Sematkan'}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="hapusTodo(${todo.id});return false;"><i class="ti ti-trash"></i> Hapus</a></li>
                    </ul>
                </div>
            </div>
        </div>
    `;
}

// =====================================
// todo: toggle selesai
// =====================================
function toggleSelesai(todoId) {
    $.post(`/todo/${todoId}/toggle-selesai`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        const todo = response.todo;
        
        // update todo index card (if exists)
        if ($('#todo-grid').length) {
            updateTodoCard(todo);
        }
        
        // update dashboard todo row (if exists)
        const dashboardRow = $(`.todo-row[data-todo-id="${todoId}"]`);
        if (dashboardRow.length && !$('#todo-grid').length) {
            const checkbox = dashboardRow.find('input[type="checkbox"]');
            
            if (todo.status === 'selesai') {
                checkbox.prop('checked', true);
                dashboardRow.addClass('todo-completed');
            } else {
                checkbox.prop('checked', false);
                dashboardRow.removeClass('todo-completed');
            }
        }
        
        showToast('success', 'Status berhasil diupdate');
    })
    .fail(function() {
        showToast('error', 'Gagal mengupdate status');
    });
}

// =====================================
// todo: toggle pin
// =====================================
function togglePin(todoId) {
    $.post(`/todo/${todoId}/toggle-sematkan`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        updateTodoCard(response.todo);
        showToast('success', response.message);
    });
}

// =====================================
// todo: delete dengan confirmation
// =====================================
function hapusTodo(todoId) {
    Swal.fire({
        title: 'Hapus todo?',
        text: 'Tindakan ini tidak dapat dibatalkan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#000',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/todo/${todoId}`,
                type: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    // remove card from DOM
                    $(`[data-todo-id="${todoId}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    showToast('success', 'Todo berhasil dihapus');
                }
            });
        }
    });
}

// =====================================
// todo: create modal submit
// =====================================
function submitCreateTodo() {
    let form = $('#createTodoForm');
    
    $.post('/todo', form.serialize())
        .done(function(response) {
            // close modal
            $('#createTodoModal').modal('hide');
            form[0].reset();
            
            // prepend new card (no reload!)
            $('#todo-grid').prepend(buildTodoCard(response.todo));
            
            // success toast
            showToast('success', response.message);
        })
        .fail(function(xhr) {
            showValidationErrors(xhr, form);
        });
}

// =====================================
// todo: open edit modal
// =====================================
function openEditModal(todoId) {
    // fetch todo data
    $.get(`/todo/${todoId}/edit`)
        .done(function(todo) {
            // populate modal fields
            $('#edit_todo_id').val(todo.id);
            $('#edit_judul').val(todo.judul);
            $('#edit_deskripsi').val(todo.deskripsi);
            $('#edit_kategori_id').val(todo.kategori_id);
            $('#edit_prioritas').val(todo.prioritas);
            $('#edit_status').val(todo.status);
            $('#edit_tenggat_waktu').val(todo.tenggat_waktu ? todo.tenggat_waktu.substring(0, 16) : '');
            
            // show modal
            $('#editTodoModal').modal('show');
        });
}

// =====================================
// todo: edit modal submit
// =====================================
function submitEditTodo() {
    let todoId = $('#edit_todo_id').val();
    let form = $('#editTodoForm');
    
    $.ajax({
        url: `/todo/${todoId}`,
        method: 'PUT',
        data: form.serialize(),
        success: function(response) {
            // close modal
            $('#editTodoModal').modal('hide');
            
            // update card in DOM (no reload!)
            updateTodoCard(response.todo);
            
            // success toast
            showToast('success', response.message);
        },
        error: function(xhr) {
            showValidationErrors(xhr, form);
        }
    });
}

// =====================================
// helper: update todo card
// =====================================
function updateTodoCard(todo) {
    let card = $(`[data-todo-id="${todo.id}"]`);
    if (card.length) {
        card.replaceWith(buildTodoCard(todo));
    }
}

// =====================================
// kategori: submit create
// =====================================
function submitCategory() {
    let form = $('#addCategoryForm');
    
    $.post('/kategori', form.serialize())
        .done(function(response) {
            $('#addCategoryModal').modal('hide');
            showToast('success', response.message);
            location.reload(); // reload untuk update kategori list
        })
        .fail(function(xhr) {
            showValidationErrors(xhr, form);
        });
}

// =====================================
// kategori: open edit modal
// =====================================
function editCategory(id, nama, warna, ikon) {
    $('#edit_category_id').val(id);
    $('#edit_nama').val(nama);
    $('#edit_warna').val(warna);
    $('#edit_ikon').val(ikon);
    $('#editCategoryModal').modal('show');
}

// =====================================
// kategori: submit update
// =====================================
function updateCategory() {
    let id = $('#edit_category_id').val();
    let form = $('#editCategoryForm');
    
    $.ajax({
        url: `/kategori/${id}`,
        method: 'PUT',
        data: form.serialize(),
        success: function(response) {
            $('#editCategoryModal').modal('hide');
            showToast('success', response.message);
            location.reload();
        },
        error: function(xhr) {
            showValidationErrors(xhr, form);
        }
    });
}

// =====================================
// kategori: delete
// =====================================
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
                success: function(response) {
                    showToast('success', response.message);
                    location.reload();
                }
            });
        }
    });
}

// =====================================
// init: on document ready
// =====================================
$(document).ready(function() {
    // auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
    
    // clear validation errors on modal hide
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0]?.reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
    });
    
    // auto-open modal based on URL hash
    const hash = window.location.hash;
    
    if (hash === '#create') {
        $('#createTodoModal').modal('show');
        // remove hash from URL after opening modal
        history.replaceState(null, null, ' ');
    } else if (hash.startsWith('#edit-')) {
        // extract todo ID from hash
        const todoId = hash.replace('#edit-', '');
        if (todoId) {
            openEditModal(parseInt(todoId));
            // remove hash from URL after opening modal
            history.replaceState(null, null, ' ');
        }
    }
});
