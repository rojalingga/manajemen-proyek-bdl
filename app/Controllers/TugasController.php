<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Tugas.php';
require_once __DIR__ . '/../models/Proyek.php';
require_once __DIR__ . '/../models/Tim.php';
require_once __DIR__ . '/../models/Status.php';
require_once __DIR__ . '/../models/Pegawai.php';
require_once __DIR__ . '/../models/ProyekTim.php';

class TugasController extends Controller
{
    private $tugasModel;
    private $proyekModel;
    private $timModel;
    private $statusModel;
    private $pegawaiModel;
    private $proyekTimModel;

    public function __construct()
    {
        $this->tugasModel     = new Tugas();
        $this->proyekModel    = new Proyek();
        $this->timModel       = new Tim();
        $this->statusModel    = new Status();
        $this->pegawaiModel   = new Pegawai();
        $this->proyekTimModel = new ProyekTim();
    }

    public function index()
    {
        if (isset($_GET['draw'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                // Menyalakan Stopwatch
                $startTime = microtime(true);

                $draw   = intval($_GET['draw']);
                $start  = intval($_GET['start']);
                $length = intval($_GET['length']);
                $search = $_GET['search']['value'] ?? '';

                $filterProyek   = $_GET['filter_proyek'] ?? '';
                $filterStatus   = $_GET['filter_status'] ?? '';
                $filterDeadline = $_GET['filter_deadline'] ?? '';

                //logika "Performance Optimization"
                $result = $this->tugasModel->getServerSide(
                    $start,
                    $length,
                    $search,
                    $filterProyek,
                    $filterStatus,
                    $filterDeadline
                );

                // Mematikan Stopwatch & Hitung Selisih
                $endTime = microtime(true);
                $executionTime = round($endTime - $startTime, 5);

                $data  = [];
                $index = $start + 1;

                foreach ($result['data'] as $row) {
                    $editUrl   = '/admin/tugas/' . $row['id_tugas'];
                    $deleteUrl = '/admin/tugas/delete/' . $row['id_tugas'];

                    $action = '
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button"
                            data-id="' . $row['id_tugas'] . '"
                            data-url="' . $editUrl . '">Edit</button>

                        <button class="btn btn-danger btn-sm mx-1 delete-button"
                            data-url="' . $deleteUrl . '">Hapus</button>
                    </div>
                    ';

                    $sisaHari = intval($row['sisa_hari']); 
                    $deadlineText = '-';

                    if ($row['deadline']) {
                        if ($sisaHari < 0) {
                            $telat = abs($sisaHari);
                            $deadlineText = "<span class='text-danger fw-bold'>Terlambat {$telat} hari</span>";
                        } elseif ($sisaHari == 0) {
                            $deadlineText = "<span class='text-warning fw-bold'>Hari ini</span>";
                        } else {
                            $deadlineText = "<span class='text-primary fw-bold'>{$sisaHari} hari lagi</span>";
                        }
                    }

                    if ($row['nama_status'] === 'Belum Mulai') {
                        $badgeStatus = '<span class="badge bg-warning text-white">Belum Mulai</span>';
                    } elseif ($row['nama_status'] === 'Selesai') {
                        $badgeStatus = '<span class="badge bg-success">Selesai</span>';
                    } else {
                        $badgeStatus = '<span class="badge bg-info">' . htmlspecialchars($row['nama_status']) . '</span>';
                    }

                    $proyekTim = '
                        <div>
                            <div>' . htmlspecialchars($row['nama_proyek']) . '</div>
                            <div><span class="badge bg-dark mt-2">' . htmlspecialchars($row['nama_tim']) . '</span></div>
                        </div>
                    ';

                    $data[] = [
                        'DT_RowIndex' => $index++,
                        'nama_tugas'  => $row['nama_tugas'],
                        'proyek_tim'  => $proyekTim,
                        'deadline'    => $deadlineText,
                        'nama_status' => $badgeStatus,
                        'action'      => $action,
                    ];
                }

                echo json_encode([
                    'draw'            => $draw,
                    'recordsTotal'    => $result['total'],
                    'recordsFiltered' => $result['filtered'],
                    'data'            => $data,
                    'executionTime'   => $executionTime . ' detik' 
                ], JSON_UNESCAPED_UNICODE);

            } catch (Throwable $e) {
                echo json_encode([
                    'error' => 'Error: ' . $e->getMessage()
                ]);
            }
            exit;
        }

        $proyek  = $this->proyekModel->getAll();
        $status  = $this->statusModel->getAll();
        $pegawai = $this->pegawaiModel->getAll();

        $this->view('admin/tugas/index', [
            'proyek'  => $proyek,
            'status'  => $status,
            'pegawai' => $pegawai,
        ]);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->tugasModel->findById($id);

            if ($data) {
                echo json_encode([
                    'status' => 'success',
                    'data'   => $data,
                ]);
            } else {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        } catch (Throwable $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }

        exit;
    }

    public function getTimByProyek($id_proyek)
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $timByProyek = $this->proyekTimModel->getTimIdsByProyek($id_proyek);
            $idTimList = array_column($timByProyek, 'id_tim');

            if (! $idTimList) {
                echo json_encode([]);
                return;
            }

            $timData = $this->timModel->getTimByIds($idTimList);
            echo json_encode($timData, JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $_POST;

        $errors = [];
        if (empty($data['nama_tugas'])) $errors['nama_tugas'][] = 'Nama tugas wajib diisi.';
        if (empty($data['deskripsi'])) $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        if (empty($data['id_proyek'])) $errors['id_proyek'][] = 'Proyek wajib diisi.';
        if (empty($data['id_tim'])) $errors['id_tim'][] = 'Tim wajib diisi.';
        if (empty($data['id_penanggung_jawab'])) $errors['id_penanggung_jawab'][] = 'Penanggung Jawab wajib diisi.';
        if (empty($data['deadline'])) $errors['deadline'][] = 'Deadline wajib diisi.';

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $insertData = [
            'nama_tugas'          => $data['nama_tugas'],
            'deskripsi'           => $data['deskripsi'],
            'id_proyek'           => $data['id_proyek'],
            'id_tim'              => $data['id_tim'],
            'id_penanggung_jawab' => $data['id_penanggung_jawab'],
            'deadline'            => $data['deadline'],
        ];

        try {
            $this->tugasModel->beginTransaction();
            $this->tugasModel->insert($insertData);
            $this->proyekModel->update($data['id_proyek'], ['id_status' => 4]);
            $this->tugasModel->commit();
            echo json_encode(['status' => 'success']);

        } catch (Throwable $e) {
            $this->tugasModel->rollBack();

            http_response_code(500);
            echo json_encode(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $data     = $_POST;
        $existing = $this->tugasModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama_tugas'])) {
            $errors['nama_tugas'][] = 'Nama tugas wajib diisi.';
        }
        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }
        if (empty($data['id_proyek'])) {
            $errors['id_proyek'][] = 'Proyek wajib diisi.';
        }
        if (empty($data['id_tim'])) {
            $errors['id_tim'][] = 'Tim wajib diisi.';
        }
        if (empty($data['id_status'])) {
            $errors['id_status'][] = 'Status wajib diisi.';
        }
        if (empty($data['id_penanggung_jawab'])) {
            $errors['id_penanggung_jawab'][] = 'Penanggung Jawab wajib diisi.';
        }
        if (empty($data['deadline'])) {
            $errors['deadline'][] = 'Deadline wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $updateData = [
            'nama_tugas'          => $data['nama_tugas'],
            'deskripsi'           => $data['deskripsi'],
            'id_proyek'           => $data['id_proyek'],
            'id_tim'              => $data['id_tim'],
            'id_status'           => $data['id_status'],
            'id_penanggung_jawab' => $data['id_penanggung_jawab'],
            'deadline'            => $data['deadline'],
        ];

        try {
            $this->tugasModel->update($id, $updateData);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $user = $this->tugasModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            $this->tugasModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}