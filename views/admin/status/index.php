<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-content-center justify-content-between">
                    <h3 class="font-weight-bold text-xl">Data Status</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-lg"></i> Tambah Status
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table data-table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Status</th>
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

<div class="modal fade" id="modalForm" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Form Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="mb-3">
                        <label class="form-label">Nama Status</label>
                        <input type="text" class="form-control" id="nama_status" name="nama_status"
                            placeholder="Contoh: Selesai, Pending, Dibatalkan">
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

<?php include __DIR__ . '/../../layout/footer.php'; ?>

<script>
    $(document).ready(function () {
        // 1. Setup DataTable (Sesuaikan URL jika pakai base url manual)
        var table = $('.data-table').DataTable({
            processing: false,
            serverSide: false,
            ajax: '/admin/status?ajax=1',
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'text-center'
                },
                {
                    data: 'nama_status'
                },
                {
                    data: 'action',
                    className: 'text-center'
                }
            ]
        });

        // 2. Tombol Edit Click
        $(document).on('click', '.edit-button', function () {
            var url = $(this).data('url');
            $.get(url, function (res) {
                if (res.status === 'success') {
                    $('#primary_id').val(res.data.id_status);
                    $('#nama_status').val(res.data.nama_status);
                    $('#modalForm').modal('show');
                }
            });
        });

        // 3. Reset Modal saat close
        $('#modalForm').on('hidden.bs.modal', function () {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('.is-invalid').removeClass('is-invalid'); // Hapus pesan error jika ada
        });

        // 4. Submit Form
        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var id = $('#primary_id').val();

            // Sesuaikan URL jika pakai $baseUrl di routes
            var url = id ? '/admin/status/update/' + id : '/admin/status/store';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#modalForm').modal('hide');
                    toastr.success('Data status berhasil disimpan');
                    table.ajax.reload();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        toastr.error('Ada inputan yang salah');
                        // Logika menampilkan error merah di input (opsional, sudah ada di contoh KlienController sebelumnya)
                    } else {
                        toastr.error('Gagal menyimpan data');
                    }
                }
            });
        });

        // 5. Delete
        $(document).on('click', '.delete-button', function () {
            var url = $(this).data('url');

            // Gunakan SweetAlert jika mau, atau confirm biasa
            Swal.fire({
                title: 'Yakin hapus status ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function () {
                            toastr.success('Data dihapus');
                            table.ajax.reload();
                        }
                    });
                }
            });
        });
    });
</script>