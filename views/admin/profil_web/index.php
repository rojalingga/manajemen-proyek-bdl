<?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Profil Web</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formData" enctype="multipart/form-data">
                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label">Nama Lab</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data['nama']?>">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                             <label class="col-sm-2 col-form-label">Nilai Inti Lab</label>
                            <div class="col-sm-4">
                                <textarea name="nilai_inti_lab" id="nilai_inti_lab" class="form-control" rows="4"><?php echo $data['nilai_inti_lab'] ?? ''?></textarea>
                            </div>
                            <label class="col-sm-1 col-form-label">Sejarah</label>
                            <div class="col-sm-5">
                                <textarea name="sejarah" id="sejarah" class="form-control" rows="4"><?php echo $data['sejarah'] ?? ''?></textarea>
                            </div>
                            
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label">Visi</label>
                            <div class="col-sm-4">
                                <textarea name="visi" id="visi" class="form-control" rows="4"><?php echo $data['visi'] ?? ''?></textarea>
                            </div>
                             <label class="col-sm-1 col-form-label">Misi</label>
                            <div class="col-sm-5">
                                <textarea name="misi" id="misi" class="form-control" rows="4"><?php echo $data['misi'] ?? ''?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                             <label class="col-sm-2 col-form-label">No. Telp</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $data['no_telp'] ?? ''?>">
                            </div>
                            <label class="col-sm-1 col-form-label">Email</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $data['email'] ?? ''?>">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-5">
                                <textarea name="alamat" id="alamat" class="form-control" rows="4"><?php echo $data['alamat'] ?? ''?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-2 col-form-label">Logo</label>
                            <div class="col-sm-5">
                                <input type="file" class="form-control" id="logo" name="logo" accept=".svg">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="img-thumbnail mb-2 d-flex align-items-center justify-content-center"
                                    id="previewLogo"
                                    style="max-width: 140px; height: 150px; background-color: #f8f9fa; border: 1px solid #dee2e6; overflow: hidden;">

                                    <?php if (! empty($data['logo'])): ?>
                                        <img src="/assets/logo_web/<?php echo $data['logo']?>" style="max-width: 100%; max-height: 100%;">
                                    <?php else: ?>
                                        <span style="color: #6c757d;">Tidak ada logo</span>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary ms-1" id="submitBtn">
                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                                <span class="button-text">Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

<?php include __DIR__ . '/../layout/footer.php'; ?>

<script>

    var audio = new Audio("/audio/notification.ogg");

    $(document).ready(function() {
        const successMsg = sessionStorage.getItem('success');
        if (successMsg) {
            audio.play();
            toastr.success(successMsg, "BERHASIL", {
                progressBar: true,
                timeOut: 3500,
                positionClass: "toast-bottom-right",
            });
            sessionStorage.removeItem('success');
        }
    });

    $('#logo').on('change', function() {
        const file = this.files[0];
        const previewDiv = $('#previewLogo');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.html(
                    `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.html('<span style="color: #6c757d;">Tidak ada logo</span>');
        }
    });

    $(document).ready(function() {
        $('#formData').on('submit', function(e) {
            e.preventDefault();

            let submitBtn = $('#submitBtn');
            let spinner = submitBtn.find('.spinner-border');
            let btnText = submitBtn.find('.button-text');

            spinner.removeClass('d-none');
            btnText.text('Menyimpan...');
            submitBtn.prop('disabled', true);

            let url = '/admin/profil-web/update';
            let method = 'POST';

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
                    sessionStorage.setItem('success', 'Profil Web berhasil diupdate!');
                    location.reload();
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
    });

</script>
