<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/ProyekTim.php';
require_once __DIR__ . '/../Models/Proyek.php';
require_once __DIR__ . '/../Models/Tim.php';

class ProyekTimController extends Controller
{
    private $proyekTimModel;
    private $proyekModel;
    private $timModel;

    public function __construct()
    {
        $this->proyekTimModel = new ProyekTim();
        $this->proyekModel = new Proyek();
        $this->timModel = new Tim();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->proyekTimModel->getAll();
                $data  = [];
                $index = 1;
                $baseUrl = '';

                foreach ($datatables as $row) {
                    $editUrl   = $baseUrl . '/admin/proyek_tim/edit/' . $row['id_proyek_tim'];
                    $deleteUrl = $baseUrl . '/admin/proyek_tim/delete/' . $row['id_proyek_tim'];

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . htmlspecialchars($row['id_proyek_tim']) . '"
                                data-url="' . htmlspecialchars($editUrl) . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . htmlspecialchars($deleteUrl) . '">Hapus</button>
                        </div>
                    ';

                    $data[] = [
                        'DT_RowIndex' => $index++,
                        'nama_proyek' => htmlspecialchars($row['nama_proyek'] ?? 'Proyek Dihapus'),
                        'nama_tim'    => htmlspecialchars($row['nama_tim'] ?? 'Tim Dihapus'),
                        'action'      => $action,
                    ];
                }
                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            exit;
        }

        // Ambil data untuk dropdown select option
        $dataProyek = $this->proyekModel->getAll();
        $dataTim    = $this->timModel->getAll();

        $this->view('admin/proyek_tim/index', [
            'listProyek' => $dataProyek,
            'listTim'    => $dataTim
        ]);
    }

    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        $data = $_POST;
        $errors = [];

        if (empty($data['id_proyek'])) $errors['id_proyek'][] = 'Proyek wajib dipilih.';
        if (empty($data['id_tim'])) $errors['id_tim'][] = 'Tim wajib dipilih.';

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        try {
            $this->proyekTimModel->insert([
                'id_proyek' => $data['id_proyek'],
                'id_tim'    => $data['id_tim']
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
        $data = $this->proyekTimModel->findById($id);
        
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
        
        if (empty($data['id_proyek']) || empty($data['id_tim'])) {
             http_response_code(422);
             echo json_encode(['errors' => ['msg' => ['Proyek dan Tim wajib dipilih']]]);
             return;
        }

        try {
            $this->proyekTimModel->update($id, [
                'id_proyek' => $data['id_proyek'],
                'id_tim'    => $data['id_tim']
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
            $this->proyekTimModel->delete($id);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}