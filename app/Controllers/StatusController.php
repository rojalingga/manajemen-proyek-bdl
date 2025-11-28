<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Status.php';

class StatusController extends Controller
{
    private $statusModel;

    public function __construct()
    {
        $this->statusModel = new Status();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->statusModel->getAll();
                $data  = [];
                $index = 1;
                
                // Cek apakah kamu pakai subfolder url manual atau virtual host
                // Jika pakai Virtual Host (.test), $baseUrl kosongkan saja.
                // Jika pakai localhost/folder, isi: $baseUrl = '/manajemen-proyek-bdl';
                $baseUrl = ''; 

                foreach ($datatables as $row) {
                    $editUrl   = $baseUrl . '/admin/status/edit/' . $row['id_status'];
                    $deleteUrl = $baseUrl . '/admin/status/delete/' . $row['id_status'];

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . htmlspecialchars($row['id_status']) . '"
                                data-url="' . htmlspecialchars($editUrl) . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . htmlspecialchars($deleteUrl) . '">Hapus</button>
                        </div>
                    ';

                    $data[] = [
                        'DT_RowIndex' => $index++,
                        'nama_status' => htmlspecialchars($row['nama_status']),
                        'action'      => $action,
                    ];
                }
                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }

        $this->view('admin/status/index');
    }

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $_POST;
        $errors = [];

        if (empty($data['nama_status'])) {
            $errors['nama_status'][] = 'Nama Status wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        try {
            $this->statusModel->insert([
                'nama_status' => $data['nama_status']
            ]);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->statusModel->findById($id);
        
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data not found']);
        }
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $_POST;
        
        if (empty($data['nama_status'])) {
            http_response_code(422);
            echo json_encode(['errors' => ['nama_status' => ['Nama Status wajib diisi.']]]);
            return;
        }

        try {
            $this->statusModel->update($id, [
                'nama_status' => $data['nama_status']
            ]);
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
            $this->statusModel->delete($id);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}