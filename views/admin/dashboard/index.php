<?php include __DIR__ . '/../layout/header.php'; ?>
<div class="page-content">
    <section class="section">

        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3">Ringkasan Proyek (Real-time View)</h4>
            </div>
            <?php foreach ($rekapStatus as $row): ?>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon blue">
                                    <i class="bi bi-layers-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">
                                    <?php echo htmlspecialchars($row['nama_status']); ?></h6>
                                <h6 class="font-extrabold mb-0"><?php echo $row['total_proyek']; ?> Proyek</h6>
                                <small class="text-success">Rp
                                    <?php echo number_format($row['total_budget'], 0, ',', '.'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Statistik Performa Tim (Materialized View)</h4>

                <form action="/admin/dashboard/refresh" method="POST">
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-clockwise"></i> Refresh Data
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="alert alert-light-secondary color-secondary">
                    <i class="bi bi-info-circle"></i> Data di tabel ini adalah <b>Snapshot</b>. Klik tombol refresh
                    untuk memperbarui data terkini dari database.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="tableTim">
                        <thead>
                            <tr>
                                <th>Nama Tim</th>
                                <th class="text-center">Total Tugas</th>
                                <th class="text-center">Tugas Selesai</th>
                                <th class="text-center">Tugas Terlambat</th>
                                <th class="text-center">Performa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistikTim as $stat): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($stat['nama_tim']); ?></td>
                                <td class="text-center"><?php echo $stat['total_tugas']; ?></td>
                                <td class="text-center text-success fw-bold"><?php echo $stat['tugas_selesai']; ?></td>
                                <td class="text-center text-danger fw-bold"><?php echo $stat['tugas_terlambat']; ?></td>
                                <td class="text-center">
                                    <?php 
                                            $total = $stat['total_tugas'] > 0 ? $stat['total_tugas'] : 1;
                                            $persen = round(($stat['tugas_selesai'] / $total) * 100);
                                        ?>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: <?php echo $persen; ?>%"></div>
                                    </div>
                                    <small><?php echo $persen; ?>%</small>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>