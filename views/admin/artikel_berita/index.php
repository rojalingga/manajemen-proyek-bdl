<?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Artikel & Berita</h3>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                                <i class="bi bi-plus-lg"></i> Tambah Artikel Berita
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
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tanggal Publish</th>
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

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="myModalLabel160">Form Data Artikel berita
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formData" enctype="multipart/form-data">
                        <input type="hidden" id="primary_id" name="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Judul</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="judul" name="judul">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Penulis</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="penulis" name="penulis">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Tanggal Publish</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tanggal_publish" name="tanggal_publish">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">File</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="file" name="file" accept=".docx, .pdf, .txt">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 col-form-label">Thumbnail</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept=".jpg, .jpeg, .png">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewthumbnail"
                                    style="max-width: 140px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">
                                    <span style="color: #6c757d;">Tidak ada thumbnail</span>
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

    $('#thumbnail').on('change', function() {
        const file = this.files[0];
        const previewDiv = $('#previewthumbnail');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.html(
                    `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.html('<span style="color: #6c757d;">Tidak ada thumbnail</span>');
        }
    });

    $(document).ready(function() {
        $(function() {
            $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                ordering: false,
                responsive: true,
                ajax: '/admin/artikel-berita?ajax=1',
                columns: [
                    { data: 'DT_RowIndex', className: 'text-center' },
                    { data: 'judul' },
                    { data: 'penulis' },
                    { data: 'tanggal_publish' ,
                        render:  function(data) {
                            if(!data) return "-";
                            let d = new Date(data);
                            return d.toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });
                        }
                    },
                    { data: 'deskripsi' },
                    { data: 'action', className: 'text-center' }
                ]
            });
        });

         $(document).on('click', '.edit-button', function() {
            var url = $(this).data('url');
            $.get(url, function(response) {
                if (response.status === 'success') {
                    $('#primary_id').val(response.data.id);
                    $('#judul').val(response.data.judul);
                    $('#penulis').val(response.data.penulis);
                    $('#tanggal_publish').val(response.data.tanggal_publish);
                    $('#deskripsi').val(response.data.deskripsi);

                    let thumbnail = response.data.thumbnail;
                    let previewthumbnail = $('#previewthumbnail');
                    if (thumbnail) {
                        let imageUrl = '/assets/artikel_berita/' + thumbnail;
                        previewthumbnail.html(
                            `<img src="${imageUrl}" alt="Thumbnail" style="max-height: 100%; max-width: 100%;">`
                        );
                    } else {
                        preview.html(`<span style="color: #6c757d;">Tidak ada thumbnail</span>`);
                    }

                    let file = response.data.file;
                    let previewfile = $('#previewfile');
                    if (file) {
                        let fileUrl = '/assets/artikel_berita/' + file;
                        previewfile.html(
                            `<img src="${fileUrl}" alt="Thumbnail" style="max-height: 100%; max-width: 100%;">`
                        );
                    } else {
                        preview.html(`<span style="color: #6c757d;">Tidak ada thumbnail</span>`);
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

            $('#previewthumbnail').html('<span style="color: #6c757d;">Tidak ada thumbnail</span>');

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
            let url = id ? '/admin/artikel-berita/update/' + id : '/admin/artikel-berita/store';
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
                    let msg = id ? "Artikel berita berhasil diupdate!" : "Artikel berita berhasil ditambahkan!";
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
                text: 'Artikel berita ini akan dihapus secara permanen!',
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
                                toastr.success("Artikel berita telah dihapus!", "BERHASIL", {
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
                                    "Gagal menghapus Artikel berita.",
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
