<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Daftar Tugas</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">Tambah Tugas</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table data-table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tugas</th>
                                <th>Proyek</th>
                                <th>Tim</th>
                                <th>Status</th>
                                <th>PJ</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title">Form Data Tugas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="formData">
                    <input type="hidden" id="primary_id" name="primary_id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_tugas" name="nama_tugas" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_status" name="id_status" required>
                                <option value="">-- Pilih Status --</option>
                                <?php foreach($listStatus as $s): ?>
                                    <option value="<?= $s['id_status'] ?>"><?= $s['nama_status'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Proyek <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_proyek" name="id_proyek" required>
                                <option value="">-- Pilih Proyek --</option>
                                <?php foreach($listProyek as $p): ?>
                                    <option value="<?= $p['id_proyek'] ?>"><?= $p['nama_proyek'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tim <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_tim" name="id_tim" required>
                                <option value="">-- Pilih Tim --</option>
                                <?php foreach($listTim as $t): ?>
                                    <option value="<?= $t['id_tim'] ?>"><?= $t['nama_tim'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Penanggung Jawab</label>
                        <select class="form-select" id="id_penanggung_jawab" name="id_penanggung_jawab">
                            <option value="">-- Pilih Pegawai --</option>
                            <?php foreach($listPegawai as $pg): ?>
                                <option value="<?= $pg['id_pegawai'] ?>"><?= $pg['nama_pegawai'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
        ajax: '/admin/tugas?ajax=1',
        columns: [
            {data:'DT_RowIndex'}, 
            {data:'nama_tugas'}, 
            {data:'nama_proyek'}, 
            {data:'nama_tim'}, 
            {data:'status'}, 
            {data:'pj'},
            {data:'action'}
        ]
    });

    $('#formData').on('submit', function(e){
        e.preventDefault();
        var id = $('#primary_id').val();
        var url = id ? '/admin/tugas/update/'+id : '/admin/tugas/store';
        $.ajax({
            url: url, type: 'POST', data: $(this).serialize(),
            success: function(){ $('#modalForm').modal('hide'); toastr.success('Berhasil'); table.ajax.reload(); },
            error: function(xhr){ 
                var msg = xhr.responseJSON?.errors?.msg ? xhr.responseJSON.errors.msg[0] : 'Gagal menyimpan data';
                toastr.error(msg); 
            }
        });
    });

    $(document).on('click', '.edit-button', function(){
        $.get($(this).data('url'), function(res){
            $('#primary_id').val(res.data.id_tugas);
            $('#nama_tugas').val(res.data.nama_tugas);
            $('#deskripsi').val(res.data.deskripsi);
            // Set Dropdown Values
            $('#id_proyek').val(res.data.id_proyek);
            $('#id_tim').val(res.data.id_tim);
            $('#id_status').val(res.data.id_status);
            $('#id_penanggung_jawab').val(res.data.id_penanggung_jawab);
            
            $('#modalForm').modal('show');
        });
    });

    $(document).on('click', '.delete-button', function() {
        if(confirm('Hapus?')) {
            $.ajax({ url: $(this).data('url'), type: 'DELETE', success: function(){ toastr.success('Dihapus'); table.ajax.reload(); } });
        }
    });

    $('#modalForm').on('hidden.bs.modal', function() { $('#formData')[0].reset(); $('#primary_id').val(''); });
});
</script>