<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Data Proyek</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">Tambah
                    Proyek</button>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Proyek</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Form Proyek</h5><button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="mb-3">
                        <label>Nama Proyek</label>
                        <input type="text" class="form-control" id="nama_proyek" name="nama_proyek" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-select" id="id_status" name="id_status" required>
                            <option value="">-- Pilih Status --</option>
                            <?php foreach($listStatus as $st): ?>
                            <option value="<?= $st['id_status'] ?>"><?= $st['nama_status'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
<script>
    $(document).ready(function () {
        var table = $('.data-table').DataTable({
            ajax: '/admin/proyek?ajax=1',
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'nama_proyek'
                },
                {
                    data: 'tanggal_mulai'
                },
                {
                    data: 'tanggal_selesai'
                },
                {
                    data: 'nama_status'
                },
                {
                    data: 'action'
                }
            ]
        });

        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var id = $('#primary_id').val();
            var url = id ? '/admin/proyek/update/' + id : '/admin/proyek/store';
            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function () {
                    $('#modalForm').modal('hide');
                    toastr.success('Berhasil');
                    table.ajax.reload();
                },
                error: function () {
                    toastr.error('Gagal simpan');
                }
            });
        });

        $(document).on('click', '.edit-button', function () {
            $.get($(this).data('url'), function (res) {
                $('#primary_id').val(res.data.id_proyek);
                $('#nama_proyek').val(res.data.nama_proyek);
                $('#tanggal_mulai').val(res.data.tanggal_mulai);
                $('#tanggal_selesai').val(res.data.tanggal_selesai);
                $('#id_status').val(res.data.id_status); // Set dropdown value
                $('#modalForm').modal('show');
            });
        });

        $(document).on('click', '.delete-button', function () {
            if (confirm('Hapus?')) {
                $.ajax({
                    url: $(this).data('url'),
                    type: 'DELETE',
                    success: function () {
                        toastr.success('Dihapus');
                        table.ajax.reload();
                    }
                });
            }
        });

        $('#modalForm').on('hidden.bs.modal', function () {
            $('#formData')[0].reset();
            $('#primary_id').val('');
        });
    });
</script>