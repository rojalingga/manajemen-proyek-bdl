<?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Event Highlight</h3>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                                <i class="bi bi-plus-lg"></i> Tambah Event
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
                                    <th>Nama Event</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Event</th>
                                    <th>Lokasi</th>
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

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="myModalLabel160">Form Data Event Highlight
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formData" enctype="multipart/form-data">
                        <input type="hidden" id="primary_id" name="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Nama Event</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_event" name="nama_event">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Tanggal Event</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tanggal_event" name="tanggal_event">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Lokasi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="lokasi" name="lokasi">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Banner</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="banner" name="banner" accept=".jpg, .jpeg, .png, .webp">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewBanner"
                                    style="max-width: 140px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada banner</span>
                                </div>
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

    $('#banner').on('change', function() {
        const file = this.files[0];
        const previewDiv = $('#previewBanner');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.html(
                    `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.html('<span style="color: #6c757d;">Tidak ada banner</span>');
        }
    });

    $(document).ready(function() {
        $(function() {
            $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: '/admin/event-highlight?ajax=1',
                columns: [
                    { data: 'DT_RowIndex', className: 'text-center' },
                    { data: 'nama_event' },
                    { data: 'deskripsi' },
                    { data: 'tanggal_event' },
                    { data: 'lokasi' },
                    { data: 'action', className: 'text-center' }
                ]
            });
        });

         $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#primary_id').val(response.data.id);
                    $('#nama_event').val(response.data.nama_event);
                    $('#deskripsi').val(response.data.deskripsi);
                    $('#tanggal_event').val(response.data.tanggal_event);
                    $('#lokasi').val(response.data.lokasi);

                    let banner = response.data.banner;
                    let preview = $('#previewBanner');
                    if (banner) {
                        let imageUrl = '/assets/event_highlight/' + banner;
                        preview.html(
                            `<img src="${imageUrl}" alt="Foto" style="max-height: 100%; max-width: 100%;">`
                        );
                    } else {
                        preview.html(`<span style="color: #6c757d;">Tidak ada banner</span>`);
                    }

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

            $('#previewBanner').html('<span style="color: #6c757d;">Tidak ada banner</span>');

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
            let url = id ? '/admin/event-highlight/update/' + id : '/admin/event-highlight/store';
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
                    let msg = id ? "Event highlight berhasil diupdate!" : "Event highlight berhasil ditambahkan!";
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
                text: 'Event highlight ini akan dihapus secara permanen!',
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
                                toastr.success("Event highlight telah dihapus!", "BERHASIL", {
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
                                    "Gagal menghapus Event highlight.",
                                    "GAGAL!",
                                    {
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
