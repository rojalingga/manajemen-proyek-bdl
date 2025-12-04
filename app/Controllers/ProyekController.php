<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Tim.php';
require_once __DIR__ . '/../models/Proyek.php';
require_once __DIR__ . '/../models/Klien.php';
require_once __DIR__ . '/../models/Status.php';
require_once __DIR__ . '/../models/ProyekTim.php';
require_once __DIR__ . '/../models/ProyekKlien.php';

class ProyekController extends Controller
{
    private $timModel;
    private $proyekModel;
    private $klienModel;
    private $statusModel;
    private $proyekTimModel;
    private $proyekKlienModel;

    public function __construct()
    {
        $this->timModel = new Tim();
        $this->proyekModel = new Proyek();
        $this->klienModel = new Klien();
        $this->statusModel = new Status();
        $this->proyekTimModel = new ProyekTim();
        $this->proyekKlienModel = new ProyekKlien();
    }

    public function index()
    {
        if (isset($_GET['draw'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            $draw   = intval($_GET['draw']);
            $start  = intval($_GET['start']);
            $length = intval($_GET['length']);
            $search = $_GET['search']['value'] ?? '';
            $filterStatus   = $_GET['filter_status'] ?? '';
            $filterDeadline = $_GET['filter_deadline'] ?? '';

            try {
                $result = $this->proyekModel->getServerSide($start, $length, $search, $filterStatus, $filterDeadline);

                $data = [];
                $index = $start + 1;

                foreach ($result['data'] as $row) {

                    $editUrl   = '/admin/proyek/' . $row['id_proyek'];
                    $deleteUrl = '/admin/proyek/delete/' . $row['id_proyek'];

                    $action = '
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button"
                            data-id="' . $row['id_proyek'] . '"
                            data-url="' . $editUrl . '">Edit</button>

                        <button class="btn btn-danger btn-sm delete-button"
                            data-url="' . $deleteUrl . '">Hapus</button>
                    </div>
                ';

                    $bulanIndo = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember'
                    ];

                    $tanggalMulai   = $row['tanggal_mulai'] ? strtotime($row['tanggal_mulai']) : null;
                    $tanggalSelesai = $row['tanggal_selesai'] ? strtotime($row['tanggal_selesai']) : null;

                    $mulai = $tanggalMulai
                        ? date('j', $tanggalMulai) . ' ' . $bulanIndo[(int)date('n', $tanggalMulai)] . ' ' . date('Y', $tanggalMulai)
                        : '-';

                    $selesai = $tanggalSelesai
                        ? date('j', $tanggalSelesai) . ' ' . $bulanIndo[(int)date('n', $tanggalSelesai)] . ' ' . date('Y', $tanggalSelesai)
                        : '-';

                    $rentangWaktu = "Dimulai : $mulai<br>Selesai : $selesai";

                    if ($row['nama_status'] === 'Belum Mulai') {
                        $badge = '<span class="badge bg-warning text-white">Belum Mulai</span>';
                    } elseif ($row['nama_status'] === 'Selesai') {
                        $badge = '<span class="badge bg-primary">Selesai</span>';
                    } else {
                        $badge = '<span class="badge bg-danger">' . htmlspecialchars($row['nama_status']) . '</span>';
                    }

                    $now = new DateTime();
                    $deadline = $row['tanggal_selesai'] ? new DateTime($row['tanggal_selesai']) : null;
                    $deadlineText = '-';

                    if ($deadline) {
                        if ($now > $deadline) {
                            $diff = $now->diff($deadline);

                            $parts = [];
                            if ($diff->y > 0) $parts[] = $diff->y . " tahun";
                            if ($diff->m > 0) $parts[] = $diff->m . " bulan";
                            if ($diff->d > 0) $parts[] = $diff->d . " hari";
                            if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0) {
                                $parts[] = $diff->h . " jam";
                            }

                            $deadlineText = "<span class='text-danger fw-bold'>
                            Terlambat " . implode(' ', $parts) . "
                        </span>";
                        } else {
                            $diff = $deadline->diff($now);

                            $parts = [];
                            if ($diff->y > 0) $parts[] = $diff->y . " tahun";
                            if ($diff->m > 0) $parts[] = $diff->m . " bulan";
                            if ($diff->d > 0) $parts[] = $diff->d . " hari";
                            if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0) {
                                $parts[] = $diff->h . " jam";
                            }

                            $deadlineText = "<span class='text-primary fw-bold'>
                            " . implode(' ', $parts) . "
                        </span>";
                        }
                    }

                    $data[] = [
                        'DT_RowIndex'   => $index++,
                        'nama_proyek'   => $row['nama_proyek'],
                        'timeline'      => $rentangWaktu,
                        'deadline'      => $deadlineText,
                        'status'        => $badge,
                        'action'        => $action
                    ];
                }

                echo json_encode([
                    'draw'            => $draw,
                    'recordsTotal'    => $result['total'],
                    'recordsFiltered' => $result['filtered'],
                    'data'            => $data
                ], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $tim    = $this->timModel->getAll();
        $klien  = $this->klienModel->getAll();
        $status = $this->statusModel->getAll();

        $this->view('admin/proyek/index', [
            'tim' => $tim,
            'klien' => $klien,
            'status' => $status
        ]);
    }


    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->proyekModel->findById($id);

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

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $_POST;

        $errors = [];
        if (empty($data['nama_proyek'])) {
            $errors['nama_proyek'][] = 'Nama Proyek wajib diisi.';
        }
        if (empty($data['budget'])) {
            $errors['budget'][] = 'Budget wajib diisi.';
        }
        if (empty($data['tanggal_mulai'])) {
            $errors['tanggal_mulai'][] = 'Tanggal Mulai wajib diisi.';
        }
        if (empty($data['tanggal_selesai'])) {
            $errors['tanggal_selesai'][] = 'Tanggal Selesai wajib diisi.';
        }
        if (empty($data['id_status'])) {
            $errors['id_status'][] = 'Status wajib diisi.';
        }
        if (empty($data['id_tim']) || count($data['id_tim']) < 1) {
            $errors['id_tim'][] = 'Minimal pilih 1 Tim.';
        }
        if (empty($data['id_klien']) || count($data['id_klien']) < 1) {
            $errors['id_klien'][] = 'Minimal pilih 1 Klien.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $insertData = [
            'nama_proyek'  => $data['nama_proyek'],
            'tanggal_mulai'  => $data['tanggal_mulai'],
            'tanggal_selesai'  => $data['tanggal_selesai'] ?? null,
            'id_status' => $data['id_status'],
            'budget' => str_replace('.', '', $data['budget']),
        ];

        try {
            $id_proyek = $this->proyekModel->insertGetId($insertData);

            foreach ($data['id_tim'] as $idTim) {
                $this->proyekTimModel->insert([
                    'id_proyek' => $id_proyek,
                    'id_tim'    => $idTim,
                ]);
            }
            foreach ($data['id_klien'] as $idKlien) {
                $this->proyekKlienModel->insert([
                    'id_proyek' => $id_proyek,
                    'id_klien'    => $idKlien,
                ]);
            }

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $data     = $_POST;
        $existing = $this->timModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama_proyek'])) {
            $errors['nama_proyek'][] = 'Nama Proyek wajib diisi.';
        }
        if (empty($data['budget'])) {
            $errors['budget'][] = 'Budget wajib diisi.';
        }
        if (empty($data['tanggal_mulai'])) {
            $errors['tanggal_mulai'][] = 'Tanggal Mulai wajib diisi.';
        }
        if (empty($data['id_status'])) {
            $errors['id_status'][] = 'Status wajib diisi.';
        }
        if (empty($data['id_tim']) || count($data['id_tim']) < 1) {
            $errors['id_tim'][] = 'Minimal pilih 1 Tim.';
        }
        if (empty($data['id_klien']) || count($data['id_klien']) < 1) {
            $errors['id_klien'][] = 'Minimal pilih 1 Klien.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $updateData = [
            'nama_proyek'  => $data['nama_proyek'],
            'tanggal_mulai'  => $data['tanggal_mulai'],
            'tanggal_selesai'  => $data['tanggal_selesai'] ?? null,
            'id_status' => $data['id_status'],
            'budget' => str_replace('.', '', $data['budget']),
        ];

        try {
            $this->proyekModel->update($id, $updateData);
            $this->proyekTimModel->deleteByProyek($id);

            foreach ($data['id_tim'] as $idTim) {
                $this->proyekTimModel->insert([
                    'id_proyek' => $id,
                    'id_tim'    => $idTim,
                ]);
            }

            $this->proyekKlienModel->deleteByProyek($id);

            foreach ($data['id_klien'] as $idKlien) {
                $this->proyekKlienModel->insert([
                    'id_proyek' => $id,
                    'id_klien'    => $idKlien,
                ]);
            }

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
            $user = $this->proyekModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            $this->proyekModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
