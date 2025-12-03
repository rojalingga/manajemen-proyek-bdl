<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Tim.php';
require_once __DIR__ . '/../models/Pegawai.php';
require_once __DIR__ . '/../models/AnggotaTim.php';

class TimController extends Controller
{
    private $timModel;
    private $pegawaiModel;
    private $anggotaTimModel;

    public function __construct()
    {
        $this->timModel = new Tim();
        $this->pegawaiModel = new Pegawai();
        $this->anggotaTimModel = new AnggotaTim();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->timModel->getAll();

                $data  = [];
                $index = 1;

                $timGrouped = [];
                foreach ($datatables as $row) {
                    $idTim = $row['id_tim'];
                    if (!isset($timGrouped[$idTim])) {
                        $timGrouped[$idTim] = [
                            'nama_tim' => $row['nama_tim'],
                            'anggota'  => []
                        ];
                    }
                    if (!empty($row['nama_pegawai'])) {
                        $timGrouped[$idTim]['anggota'][] = $row['nama_pegawai'];
                    }
                }

                foreach ($timGrouped as $idTim => $timData) {
                    $editUrl   = '/admin/tim/' . $idTim;
                    $deleteUrl = '/admin/tim/delete/' . $idTim;

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . htmlspecialchars($idTim) . '"
                                data-url="' . htmlspecialchars($editUrl) . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . htmlspecialchars($deleteUrl) . '">Hapus</button>
                        </div>
                    ';

                    $anggotaList = '';
                    foreach ($timData['anggota'] as $key => $pegawai) {
                        $anggotaList .= ($key + 1) . '. ' . htmlspecialchars($pegawai) . '<br>';
                    }
                    if (empty($anggotaList)) {
                        $anggotaList = '<i>- Tidak ada anggota</i>';
                    }

                    $data[] = [
                        'DT_RowIndex' => $index++,
                        'nama_tim'    => htmlspecialchars($timData['nama_tim']),
                        'anggota_tim' => $anggotaList,
                        'action'      => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $pegawai = $this->pegawaiModel->getAll();

        $this->view('admin/tim/index', [
            'pegawai' => $pegawai
        ]);
    }


    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->timModel->findById($id);

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
        if (empty($data['nama_tim'])) {
            $errors['nama_tim'][] = 'Nama tim wajib diisi.';
        }
        if (empty($data['id_pegawai']) || count($data['id_pegawai']) < 1) {
            $errors['id_pegawai'][] = 'Minimal pilih 1 Anggota.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $insertData = [
            'nama_tim'  => $data['nama_tim'],
        ];

        try {
            $id_tim = $this->timModel->insertGetId($insertData);

            foreach ($data['id_pegawai'] as $idPegawai) {
                $this->anggotaTimModel->insert([
                    'id_pegawai'    => $idPegawai,
                    'id_tim' => $id_tim,
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
        if (empty($data['nama_tim'])) {
            $errors['nama_tim'][] = 'Nama tim wajib diisi.';
        }

        if (empty($data['id_pegawai']) || count($data['id_pegawai']) < 1) {
            $errors['id_pegawai'][] = 'Minimal pilih 1 Anggota.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $updateData = [
            'nama_tim'  => $data['nama_tim'],
        ];

        try {
            $this->timModel->update($id, $updateData);
            $this->anggotaTimModel->deleteByTim($id);

            foreach ($data['id_pegawai'] as $idPegawai) {
                $this->anggotaTimModel->insert([
                    'id_pegawai'    => $idPegawai,
                    'id_tim' => $id,
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
            $user = $this->timModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            $this->timModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
