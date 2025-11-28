<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Kelola Klien Proyek</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">Tambah
                    Data</button>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Proyek</th>
                            <th>Nama Klien</th>
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
                <h5 class="modal-title">Form Proyek Klien</h5><button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="mb-3">
                        <label>Pilih Proyek</label>
                        <select class="form-select" id="id_proyek" name="id_proyek" required>
                            <option value="">-- Pilih Proyek --</option>
                            <?php foreach ($listProyek as $p): ?>
                            <option value="<?= $p['id_proyek'] ?>"><?= $p['nama_proyek'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pilih Klien</label>
                        <select class="form-select" id="id_klien" name="id_klien" required>
                            <option value="">-- Pilih Klien --</option>
                            <?php foreach ($listKlien as $k): ?>
                            <option value="<?= $k['id_klien'] ?>"><?= $k['nama_klien'] ?></option>
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
            ajax: '/admin/proyek_klien?ajax=1',
            columns: [{
                data: 'DT_RowIndex'
            }, {
                data: 'nama_proyek'
            }, {
                data: 'nama_klien'
            }, {
                data: 'action'
            }]
        });

        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var id = $('#primary_id').val();
            var url = id ? '/admin/proyek_klien/update/' + id : '/admin/proyek_klien/store';
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
                $('#primary_id').val(res.data.id_proyek_klien);
                $('#id_proyek').val(res.data.id_proyek);
                $('#id_klien').val(res.data.id_klien);
                $('#modalForm').modal('show');
            });
        });

        $(document).on('click', '.delete-button', function () {
            if (confirm('Hapus relasi ini?')) {
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