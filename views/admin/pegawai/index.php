<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="font-weight-bold">Data Pegawai</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                    <i class="bi bi-plus-lg"></i> Tambah Pegawai
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table data-table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Telp</th>
                                <th>Email</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Form Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Pegawai <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" required placeholder="Nama Lengkap">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="telp_pegawai" name="telp_pegawai" placeholder="08...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_pegawai" name="email_pegawai" placeholder="email@contoh.com">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
$(document).ready(function() {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: '/admin/pegawai?ajax=1',
        columns: [
            { data: 'DT_RowIndex', className: 'text-center' },
            { data: 'nama_pegawai' },
            // { data: 'jabatan' },  <-- DIHAPUS
            { data: 'telp_pegawai' },
            { data: 'email_pegawai', defaultContent: '-' },
            { data: 'action', className: 'text-center' }
        ]
    });

    $(document).on('click', '.edit-button', function() {
        var url = $(this).data('url');
        
        $('#formData')[0].reset();
        
        $.get(url, function(res) {
            if(res.status === 'success') {
                $('#primary_id').val(res.data.id_pegawai);
                $('#nama_pegawai').val(res.data.nama_pegawai);
                // $('#jabatan').val(res.data.jabatan); <-- DIHAPUS
                $('#telp_pegawai').val(res.data.telp_pegawai);
                $('#email_pegawai').val(res.data.email_pegawai);
                
                $('#modalTitle').text('Edit Pegawai');
                $('#modalForm').modal('show');
            } else {
                toastr.error('Gagal mengambil data');
            }
        }).fail(function() {
            toastr.error('Terjadi kesalahan koneksi');
        });
    });

    $('#modalForm').on('hidden.bs.modal', function() {
        $('#formData')[0].reset();
        $('#primary_id').val('');
        $('#modalTitle').text('Tambah Pegawai');
    });

    $('#formData').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#primary_id').val();
        var url = id ? '/admin/pegawai/update/' + id : '/admin/pegawai/store';
        var btn = $('#submitBtn');

        btn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#modalForm').modal('hide');
                toastr.success('Data berhasil disimpan');
                table.ajax.reload();
            },
            error: function(xhr) {
                var msg = 'Gagal menyimpan data';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    if(xhr.responseJSON.errors.msg) {
                        msg = xhr.responseJSON.errors.msg[0];
                    }
                }
                toastr.error(msg);
            },
            complete: function() {
                btn.prop('disabled', false).text('Simpan');
            }
        });
    });

    $(document).on('click', '.delete-button', function() {
        var url = $(this).data('url');
        
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function() {
                        toastr.success('Data berhasil dihapus');
                        table.ajax.reload();
                    },
                    error: function() {
                        toastr.error('Gagal menghapus data');
                    }
                });
            }
        })
    });
});
</script>