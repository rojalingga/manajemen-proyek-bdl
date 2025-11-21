<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Klien.php';

class KlienController extends Controller
{
    private $klienModel;

    public function __construct()
    {
        $this->klienModel = new Klien();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->klienModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/klien/' . $row['id_klien'];
                    $deleteUrl = '/admin/klien/delete/' . $row['id_klien'];

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . htmlspecialchars($row['id_klien']) . '"
                                data-url="' . htmlspecialchars($editUrl) . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . htmlspecialchars($deleteUrl) . '">Hapus</button>
                        </div>
                    ';

                    $data[] = [
                        'DT_RowIndex' => $index++,
                        'nama_klien'  => htmlspecialchars($row['nama_klien']),
                        'telp_klien'  => htmlspecialchars($row['telp_klien']),
                        'email_klien' => htmlspecialchars($row['email_klien']),
                        'action'      => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $this->view('admin/klien/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->klienModel->findById($id);

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
        if (empty($data['nama_klien'])) {
            $errors['nama_klien'][] = 'Nama Klien wajib diisi.';
        }

        if (empty($data['telp_klien'])) {
            $errors['telp_klien'][] = 'Telepon Klien wajib diisi.';
        }

        if (empty($data['email_klien'])) {
            $errors['email_klien'][] = 'Email Klien wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $insertData = [
            'nama_klien'  => $data['nama_klien'],
            'telp_klien'  => $data['telp_klien'],
            'email_klien' => $data['email_klien'],
        ];

        try {
            $this->klienModel->insert($insertData);
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
        $existing = $this->klienModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama_klien'])) {
            $errors['nama_klien'][] = 'Nama Klien wajib diisi.';
        }

        if (empty($data['telp_klien'])) {
            $errors['telp_klien'][] = 'Telepon Klien wajib diisi.';
        }

        if (empty($data['email_klien'])) {
            $errors['email_klien'][] = 'Email Klien wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $updateData = [
            'nama_klien'  => $data['nama_klien'],
            'telp_klien'  => $data['telp_klien'],
            'email_klien' => $data['email_klien'],
        ];

        try {
            $this->klienModel->update($id, $updateData);
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
            $user = $this->klienModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            $this->klienModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
