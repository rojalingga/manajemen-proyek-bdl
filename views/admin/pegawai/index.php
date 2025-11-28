<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Data Pegawai</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">Tambah
                    Pegawai</button>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Telp</th>
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
                <h5 class="modal-title">Form Pegawai</h5><button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">
                    <div class="mb-3"><label>Nama Pegawai</label><input type="text" class="form-control"
                            id="nama_pegawai" name="nama_pegawai" required></div>
                    <div class="mb-3"><label>Jabatan</label><input type="text" class="form-control" id="jabatan"
                            name="jabatan"></div>
                    <div class="mb-3"><label>Telepon</label><input type="text" class="form-control" id="telp_pegawai"
                            name="telp_pegawai"></div>
                    <div class="mb-3"><label>Email</label><input type="email" class="form-control" id="email_pegawai"
                            name="email_pegawai"></div>
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
            ajax: '/admin/pegawai?ajax=1',
            columns: [{
                data: 'DT_RowIndex'
            }, {
                data: 'nama_pegawai'
            }, {
                data: 'jabatan'
            }, {
                data: 'telp_pegawai'
            }, {
                data: 'action'
            }]
        });
        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var id = $('#primary_id').val();
            var url = id ? '/admin/pegawai/update/' + id : '/admin/pegawai/store';
            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function () {
                    $('#modalForm').modal('hide');
                    toastr.success('Berhasil');
                    table.ajax.reload();
                }
            });
        });
        $(document).on('click', '.edit-button', function () {
            $.get($(this).data('url'), function (res) {
                $('#primary_id').val(res.data.id_pegawai);
                $('#nama_pegawai').val(res.data.nama_pegawai);
                $('#jabatan').val(res.data.jabatan);
                $('#telp_pegawai').val(res.data.telp_pegawai);
                $('#email_pegawai').val(res.data.email_pegawai);
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