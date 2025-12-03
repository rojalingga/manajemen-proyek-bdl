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
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->proyekModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/proyek/' . $row['id_proyek'];
                    $deleteUrl = '/admin/proyek/delete/' . $row['id_proyek'];

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . htmlspecialchars($row['id_proyek']) . '"
                                data-url="' . htmlspecialchars($editUrl) . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . htmlspecialchars($deleteUrl) . '">Hapus</button>
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

                    $tanggalMulai = $row['tanggal_mulai'] ? strtotime($row['tanggal_mulai']) : null;
                    $tanggalSelesai = $row['tanggal_selesai'] ? strtotime($row['tanggal_selesai']) : null;

                    $mulai = $tanggalMulai ? date('j', $tanggalMulai) . ' ' . $bulanIndo[(int)date('n', $tanggalMulai)] . ' ' . date('Y', $tanggalMulai) : '-';
                    $selesai = $tanggalSelesai ? date('j', $tanggalSelesai) . ' ' . $bulanIndo[(int)date('n', $tanggalSelesai)] . ' ' . date('Y', $tanggalSelesai) : '-';

                    $rentangWaktu = "Dimulai: $mulai<br>Selesai: $selesai";

                    $data[] = [
                        'DT_RowIndex'    => $index++,
                        'nama_proyek'    => htmlspecialchars($row['nama_proyek']),
                        'rentang_waktu'  => $rentangWaktu,
                        'status'         => htmlspecialchars($row['nama_status']),
                        'action'         => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $tim = $this->timModel->getAllTim();
        $klien = $this->klienModel->getAll();
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

        $insertData = [
            'nama_proyek'  => $data['nama_proyek'],
            'tanggal_mulai'  => $data['tanggal_mulai'],
            'tanggal_selesai'  => $data['tanggal_selesai'] ?? null,
            'id_status' => $data['id_status'],
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
