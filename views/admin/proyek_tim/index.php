<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-content-center justify-content-between">
                    <h3 class="font-weight-bold text-xl">Kelola Tim Proyek</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                        <i class="bi bi-plus-lg"></i> Tambah Proyek Tim
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table data-table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Proyek</th>
                                <th>Nama Tim</th>
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
                <h5 class="modal-title">Form Proyek Tim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="mb-3">
                        <label class="form-label">Pilih Proyek</label>
                        <select class="form-select" id="id_proyek" name="id_proyek">
                            <option value="">-- Pilih Proyek --</option>
                            <?php foreach ($listProyek as $p): ?>
                            <option value="<?= $p['id_proyek'] ?>"><?= $p['nama_proyek'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Tim</label>
                        <select class="form-select" id="id_tim" name="id_tim">
                            <option value="">-- Pilih Tim --</option>
                            <?php foreach ($listTim as $t): ?>
                            <option value="<?= $t['id_tim'] ?>"><?= $t['nama_tim'] ?></option>
                            <?php endforeach; ?>
                        </select>
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

<?php include __DIR__ . '../layout/footer.php'; ?>

<script>
    $(document).ready(function () {
        // 1. DataTables
        var table = $('.data-table').DataTable({
            processing: false,
            serverSide: false,
            ajax: '/admin/proyek_tim?ajax=1',
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'text-center'
                },
                {
                    data: 'nama_proyek'
                },
                {
                    data: 'nama_tim'
                },
                {
                    data: 'action',
                    className: 'text-center'
                }
            ]
        });

        // 2. Edit Button
        $(document).on('click', '.edit-button', function () {
            var url = $(this).data('url');
            $.get(url, function (res) {
                if (res.status === 'success') {
                    $('#primary_id').val(res.data.id_proyek_tim);
                    // Set nilai select option
                    $('#id_proyek').val(res.data.id_proyek);
                    $('#id_tim').val(res.data.id_tim);

                    $('#modalForm').modal('show');
                }
            });
        });

        // 3. Reset Modal
        $('#modalForm').on('hidden.bs.modal', function () {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('.is-invalid').removeClass('is-invalid');
        });

        // 4. Submit
        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var id = $('#primary_id').val();
            var url = id ? '/admin/proyek_tim/update/' + id : '/admin/proyek_tim/store';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#modalForm').modal('hide');
                    toastr.success('Data berhasil disimpan');
                    table.ajax.reload();
                },
                error: function (xhr) {
                    toastr.error('Gagal menyimpan data (Pastikan semua field terisi)');
                }
            });
        });

        // 5. Delete
        $(document).on('click', '.delete-button', function () {
            var url = $(this).data('url');
            Swal.fire({
                title: 'Hapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
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