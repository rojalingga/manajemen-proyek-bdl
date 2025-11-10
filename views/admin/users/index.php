<?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-xl">Pengguna</h3>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm">
                                <i class="bi bi-plus-lg"></i> Tambah Pengguna
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table data-table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50px">No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th class="text-center">Status</th>
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
                    <h5 class="modal-title white" id="myModalLabel160">Form Data Pengguna
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formData">
                        <input type="hidden" id="primary_id" name="primary_id">

                        <div class="row mb-3 align-items-center">
                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-3">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-5">
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="status" class="col-sm-3 col-form-label">Status Pengguna</label>
                            <div class="col-sm-5">
                                <select class="form-select select-status" id="status" name="status">
                                    <option value=""></option>
                                    <option value="1">AKTIF</option>
                                    <option value="2">BLOKIR</option>
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
<script>
    $(document).ready(function() {
            $('.select-status').select2({
                dropdownParent: $('#modalForm'),
                width: '100%',
                placeholder: 'Pilih Status',
                allowClear: true,
                minimumResultsForSearch: Infinity,
            });

            if ($('body').hasClass('dark')) {
                $('.select2-container').addClass('select2-dark');
            }
        });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>