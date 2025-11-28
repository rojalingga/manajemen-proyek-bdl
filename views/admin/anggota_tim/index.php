<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Kelola Anggota Tim</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">Tambah Anggota</button>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Masuk di Tim</th>
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
            <div class="modal-header bg-primary text-white"><h5 class="modal-title">Form Anggota Tim</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">
                    
                    <div class="mb-3">
                        <label>Pilih Pegawai</label>
                        <select class="form-select" id="id_pegawai" name="id_pegawai" required>
                            <option value="">-- Pilih Pegawai --</option>
                            <?php foreach ($listPegawai as $p): ?>
                                <option value="<?= $p['id_pegawai'] ?>"><?= $p['nama_pegawai'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pilih Tim</label>
                        <select class="form-select" id="id_tim" name="id_tim" required>
                            <option value="">-- Pilih Tim --</option>
                            <?php foreach ($listTim as $t): ?>
                                <option value="<?= $t['id_tim'] ?>"><?= $t['nama_tim'] ?></option>
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
$(document).ready(function() {
    var table = $('.data-table').DataTable({
        ajax: '/admin/anggota_tim?ajax=1',
        columns: [
            {data:'DT_RowIndex'}, 
            {data:'nama_pegawai'}, 
            {data:'nama_tim'}, 
            {data:'action'}
        ]
    });

    $('#formData').on('submit', function(e){
        e.preventDefault();
        var id = $('#primary_id').val();
        var url = id ? '/admin/anggota_tim/update/'+id : '/admin/anggota_tim/store';
        $.ajax({
            url: url, type: 'POST', data: $(this).serialize(),
            success: function(){ $('#modalForm').modal('hide'); toastr.success('Berhasil'); table.ajax.reload(); },
            error: function(){ toastr.error('Gagal simpan'); }
        });
    });

    $(document).on('click', '.edit-button', function(){
        $.get($(this).data('url'), function(res){
            $('#primary_id').val(res.data.id_anggota_tim);
            $('#id_pegawai').val(res.data.id_pegawai);
            $('#id_tim').val(res.data.id_tim);
            $('#modalForm').modal('show');
        });
    });
    
    $(document).on('click', '.delete-button', function() {
        if(confirm('Hapus anggota dari tim ini?')) {
            $.ajax({ url: $(this).data('url'), type: 'DELETE', success: function(){ toastr.success('Dihapus'); table.ajax.reload(); } });
        }
    });
    $('#modalForm').on('hidden.bs.modal', function() { $('#formData')[0].reset(); $('#primary_id').val(''); });
});
</script>