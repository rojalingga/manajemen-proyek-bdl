<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Pegawai.php';

class PegawaiController extends Controller
{
    private $pegawaiModel;

    public function __construct()
    {
        $this->pegawaiModel = new Pegawai();
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

            try {
                $result = $this->pegawaiModel->getServerSide($start, $length, $search);

                $data  = [];
                $index = $start + 1;

                foreach ($result['data'] as $row) {
                    $editUrl   = '/admin/pegawai/' . $row['id_pegawai'];
                    $deleteUrl = '/admin/pegawai/delete/' . $row['id_pegawai'];

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . $row['id_pegawai'] . '"
                                data-url="' . $editUrl . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . $deleteUrl . '">Hapus</button>
                        </div>
                    ';

                    $data[] = [
                        'DT_RowIndex'   => $index++,
                        'nama_pegawai'  => $row['nama_pegawai'],
                        'telp_pegawai'  => $row['telp_pegawai'],
                        'email_pegawai' => $row['email_pegawai'],
                        'action'        => $action,
                    ];
                }

                echo json_encode([
                    'draw'            => $draw,
                    'recordsTotal'    => $result['total'],
                    'recordsFiltered' => $result['filtered'],
                    'data'            => $data,
                ], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }

        $this->view('admin/pegawai/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->pegawaiModel->findById($id);

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
        if (empty($data['nama_pegawai'])) {
            $errors['nama_pegawai'][] = 'Nama pegawai wajib diisi.';
        }

        if (empty($data['telp_pegawai'])) {
            $errors['telp_pegawai'][] = 'Telepon pegawai wajib diisi.';
        }

        if (empty($data['email_pegawai'])) {
            $errors['email_pegawai'][] = 'Email pegawai wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $insertData = [
            'nama_pegawai'  => $data['nama_pegawai'],
            'telp_pegawai'  => $data['telp_pegawai'],
            'email_pegawai' => $data['email_pegawai'],
        ];

        try {
            $this->pegawaiModel->insert($insertData);
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
        $existing = $this->pegawaiModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama_pegawai'])) {
            $errors['nama_pegawai'][] = 'Nama pegawai wajib diisi.';
        }

        if (empty($data['telp_pegawai'])) {
            $errors['telp_pegawai'][] = 'Telepon pegawai wajib diisi.';
        }

        if (empty($data['email_pegawai'])) {
            $errors['email_pegawai'][] = 'Email pegawai wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $updateData = [
            'nama_pegawai'  => $data['nama_pegawai'],
            'telp_pegawai'  => $data['telp_pegawai'],
            'email_pegawai' => $data['email_pegawai'],
        ];

        try {
            $this->pegawaiModel->update($id, $updateData);
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
            $user = $this->pegawaiModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            $this->pegawaiModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
