<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-content-center justify-content-between">
                    <h3 class="font-weight-bold text-xl text-primary">Klien</h3>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                            <i class="bi bi-plus-lg"></i> Tambah Klien
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
                                <th>Nama Klien</th>
                                <th>Telp Klien</th>
                                <th>Email Klien</th>
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
                <h5 class="modal-title white" id="myModalLabel160">Form Klien
                </h5>
                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formData" enctype="multipart/form-data">
                    <input type="hidden" id="primary_id" name="primary_id">

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Nama Klien</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_klien" name="nama_klien">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Telp Klien</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="telp_klien" name="telp_klien">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-3 col-form-label">Email Klien</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="email_klien" name="email_klien">
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
        $(function() {
            $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                responsive: true,
                lengthChange: false,
                pageLength: 10,
                ajax: {
                    url: '/admin/klien',
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_klien'
                    },
                    {
                        data: 'telp_klien'
                    },
                    {
                        data: 'email_klien'
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
                    $('#primary_id').val(response.data.id_klien);
                    $('#nama_klien').val(response.data.nama_klien);
                    $('#telp_klien').val(response.data.telp_klien);
                    $('#email_klien').val(response.data.email_klien);

                    $('#modalForm').modal('show');
                }
            });
        });

        $('#modalForm').on('hidden.bs.modal', function() {
            $('#formData')[0].reset();
            $('#primary_id').val('');

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
            let url = id ? '/admin/klien/update/' + id : '/admin/klien/store';
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
                    let msg = id ? "Klien berhasil diupdate!" : "Klien berhasil ditambahkan!";
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
                text: 'Klien ini akan dihapus secara permanen!',
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
                                toastr.success("Klien telah dihapus!", "BERHASIL", {
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
                                    "Gagal menghapus Klien.",
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