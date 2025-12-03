<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-content-center justify-content-between">
                    <h3 class="font-weight-bold text-xl">Tugas</h3>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                            <i class="bi bi-plus-lg"></i> Tambah Tugas
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table data-table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th width="50px">No</th>
                                <th>Nama Tugas</th>
                                <th>Proyek</th>
                                <th>Tim</th>
                                <th>Status</th>
                                <th width="100px" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade text-left" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Form Tugas
                </h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formData" enctype="multipart/form-data">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Nama Tugas</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_tugas" name="nama_tugas">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Proyek</label>
                        <div class="col-sm-7">
                            <select class="form-select select-proyek" id="id_proyek" name="id_proyek">
                                <option value=""></option>
                                <?php foreach ($proyek as $data): ?>
                                    <option value="<?php echo $data['id_proyek']; ?>"><?php echo htmlspecialchars($data['nama_proyek']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Tim</label>
                        <div class="col-sm-7">
                            <select class="form-select select-tim" id="id_tim" name="id_tim">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-7">
                            <select class="form-select select-status" id="id_status" name="id_status">
                                <?php foreach ($status as $data): ?>
                                    <option value="<?php echo $data['id_status']; ?>"><?php echo htmlspecialchars($data['nama_status']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Penanggung Jawab</label>
                        <div class="col-sm-7">
                            <select class="form-select select-penanggung-jawab" id="id_penanggung_jawab" name="id_penanggung_jawab">
                                <option value=""></option>
                                <?php foreach ($pegawai as $data): ?>
                                    <option value="<?php echo $data['id_pegawai']; ?>"><?php echo htmlspecialchars($data['nama_pegawai']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <span class="button-text">Batal</span>
                </button>
                <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                    <span class="button-text">Simpan</span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>
    var audio = new Audio("/audio/notification.ogg");

    $(document).ready(function() {
        $('.select-proyek').select2({
            dropdownParent: $('#modalForm'),
            width: '100%',
            placeholder: 'Pilih Proyek',
        });
        $('.select-tim').select2({
            dropdownParent: $('#modalForm'),
            width: '100%',
            placeholder: 'Pilih Tim',
        });
        $('.select-status').select2({
            dropdownParent: $('#modalForm'),
            width: '100%',
            placeholder: 'Pilih Status Pengerjaan',
            minimumResultsForSearch: -1
        });
        $('.select-penanggung-jawab').select2({
            dropdownParent: $('#modalForm'),
            width: '100%',
            placeholder: 'Pilih Penanggung Jawab',
        });

        if ($('body').hasClass('dark')) {
            $('.select2-container').addClass('select2-dark');
        }

        $('#id_proyek').on('change', function() {
            var id_proyek = $(this).val();
            var $timSelect = $('#id_tim');

            $timSelect.html('<option value="">Loading...</option>');

            if (!id_proyek) {
                $timSelect.html('<option value=""></option>');
                return;
            }

            $.getJSON('/admin/tugas/get-tim-by-proyek/' + id_proyek, function(data) {
                var options = '<option value=""></option>';
                $.each(data, function(i, tim) {
                    options += '<option value="' + tim.id_tim + '">' + tim.nama_tim + '</option>';
                });
                $timSelect.html(options);
            });
        });

        $(function() {
            $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: '/admin/tugas?ajax=1',
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_tugas'
                    },
                    {
                        data: 'nama_proyek'
                    },
                    {
                        data: 'nama_tim'
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
        });

        $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#primary_id').val(response.data.id_tugas);
                    $('#nama_tugas').val(response.data.nama_tugas);
                    $('#deskripsi').val(response.data.deskripsi);
                    $('#id_status').val(response.data.id_status).trigger('change');
                    $('#id_penanggung_jawab').val(response.data.id_penanggung_jawab).trigger('change');

                    $('#id_proyek').val(response.data.id_proyek).trigger('change');

                    $.getJSON('/admin/tugas/get-tim-by-proyek/' + response.data.id_proyek, function(timData) {
                        var options = '<option value=""></option>';
                        $.each(timData, function(i, tim) {
                            options += '<option value="' + tim.id_tim + '">' + tim.nama_tim + '</option>';
                        });
                        $('#id_tim').html(options);

                        $('#id_tim').val(response.data.id_tim).trigger('change');

                        $('#modalForm').modal('show');
                    });
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('#id_proyek').val('').trigger('change');
            $('#id_tim').val('').trigger('change');
            $('#id_status').val('').trigger('change');
            $('#id_penanggung_jawab').val('').trigger('change');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.addClass('d-none');
            btnText.text('Simpan');
            submitBtn.prop('disabled', false);
        });

        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = id ? '/admin/tugas/update/' + id : '/admin/tugas/store';
            let method = id ? 'PUT' : 'POST';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);
            formData.append('_method', method);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalForm').modal('hide');
                    audio.play();
                    let msg = id ? "Tugas berhasil diupdate!" : "Tugas berhasil ditambahkan!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        audio.play();
                        toastr.error("Ada inputan yang salah!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            let input = $('#' + key);
                            input.addClass('is-invalid');
                            input.parent().find('.invalid-feedback').remove();
                            input.parent().append(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                val[0] + '</strong></span>'
                            );
                        });
                    }
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Tugas ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Hapus</span>',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: false,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-secondary'
                },
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const confirmBtn = Swal.getConfirmButton();
                        const btnText = confirmBtn.querySelector('.swal-btn-text');

                        btnText.innerHTML =
                            `<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...`;
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            success: function() {
                                audio.play();
                                toastr.success("Tugas telah dihapus!", "BERHASIL", {
                                    progressBar: true,
                                    timeOut: 3500,
                                    positionClass: "toast-bottom-right"
                                });

                                $('.data-table').DataTable().ajax.reload(null, false);
                                Swal.close();
                            },
                            error: function(xhr) {
                                audio.play();
                                toastr.error(
                                    "Gagal menghapus Tugas.",
                                    "GAGAL!", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right"
                                    }
                                );

                                btnText.innerHTML = `Ya, Hapus`;
                                confirmBtn.disabled = false;
                            }
                        });
                    });
                }
            });
        });

    });
</script>