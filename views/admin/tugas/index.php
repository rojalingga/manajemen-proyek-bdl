<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-content-center justify-content-between">
                    <h3 class="font-weight-bold text-xl">Daftar Tugas</h3>
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
                                <th>Deskripsi</th>
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
    aria-hidden="true" data-backdrop="static" data-keyboard="false" data-focus="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="modalTitle">Form Data Tugas</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formData" enctype="multipart/form-data">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Nama Tugas</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_tugas" name="nama_tugas"
                                placeholder="Masukkan nama tugas">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                placeholder="Deskripsi tugas..."></textarea>
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

    $(document).ready(function () {
        var table = $('.data-table').DataTable({
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
                    data: 'deskripsi'
                },
                {
                    data: 'action',
                    className: 'text-center'
                }
            ]
        });

        // Open Modal Edit
        $(document).on('click', '.edit-button', function () {
            var url = $(this).data('url');
            $.get(url, function (response) {
                if (response.status === 'success') {
                    $('#primary_id').val(response.data
                        .id_tugas); // Sesuai nama kolom primary key
                    $('#nama_tugas').val(response.data.nama_tugas);
                    $('#deskripsi').val(response.data.deskripsi);

                    $('#modalForm').modal('show');
                }
            });
        });

        // Reset Modal saat ditutup
        $('#modalForm').on('hidden.bs.modal', function () {
            $('#formData')[0].reset();
            $('#primary_id').val('');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let submitBtn = $('#submitBtn');
            submitBtn.find('.spinner-border').addClass('d-none');
            submitBtn.find('.button-text').text('Simpan');
            submitBtn.prop('disabled', false);
        });

        // Submit Form (Simpan/Update)
        $('#formData').on('submit', function (e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let id = $('#primary_id').val();
            let url = id ? '/admin/tugas/update/' + id : '/admin/tugas/store';
            let method = id ? 'PUT' : 'POST'; // Sesuai dengan controller method

            // Membersihkan pesan error lama
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let formData = new FormData(this);
            // Tambahkan _method untuk menghandle PUT di backend jika diperlukan, 
            // tapi disini kita kirim POST ke ajax dengan parameter _method
            formData.append('_method', method);

            $.ajax({
                url: url,
                method: 'POST', // Selalu POST untuk FormData, method asli dihandle via _method atau logika controller
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#modalForm').modal('hide');
                    // audio.play(); // Uncomment jika file audio ada

                    let msg = id ? "Tugas berhasil diupdate!" :
                        "Tugas berhasil ditambahkan!";
                    toastr.success(msg, "BERHASIL", {
                        progressBar: true,
                        timeOut: 3500,
                        positionClass: "toast-bottom-right",
                    });
                    $('.data-table').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        // audio.play();
                        toastr.error("Ada inputan yang salah!", "GAGAL!", {
                            progressBar: true,
                            timeOut: 3500,
                            positionClass: "toast-bottom-right",
                        });

                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, val) {
                            let input = $('#' + key);
                            input.addClass('is-invalid');
                            input.parent().append(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                val[0] + '</strong></span>'
                            );
                        });
                    } else {
                        toastr.error("Terjadi kesalahan sistem.", "ERROR");
                    }
                },
                complete: function () {
                    spinner.addClass('d-none');
                    btnText.text('Simpan');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // Delete Action
        $(document).on('click', '.delete-button', function (e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data tugas ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span class="swal-btn-text">Ya, Hapus</span>',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-secondary'
                },
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const confirmBtn = Swal.getConfirmButton();
                        confirmBtn.querySelector('.swal-btn-text').innerHTML =
                            `<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Menghapus...`;
                        confirmBtn.disabled = true;

                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            success: function () {
                                toastr.success("Tugas telah dihapus!",
                                    "BERHASIL", {
                                        progressBar: true,
                                        timeOut: 3500,
                                        positionClass: "toast-bottom-right"
                                    });
                                $('.data-table').DataTable().ajax
                                    .reload(null, false);
                                Swal.close();
                            },
                            error: function () {
                                toastr.error("Gagal menghapus tugas.",
                                    "GAGAL!");
                                confirmBtn.querySelector(
                                        '.swal-btn-text').innerHTML =
                                    `Ya, Hapus`;
                                confirmBtn.disabled = false;
                                Swal.close();
                            }
                        });
                    });
                }
            });
        });
    });
</script>