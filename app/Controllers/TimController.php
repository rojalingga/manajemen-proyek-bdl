<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Tim.php';

class TimController extends Controller
{
    private $timModel;

    public function __construct()
    {
        $this->timModel = new Tim();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');
            try {
                $data = $this->timModel->getAll();
                $result = [];
                $no = 1;
                foreach ($data as $row) {
                    $editUrl   = '/admin/tim/edit/' . $row['id_tim'];
                    $deleteUrl = '/admin/tim/delete/' . $row['id_tim'];

                    $action = '<div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button" data-url="'.$editUrl.'">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button" data-url="'.$deleteUrl.'">Hapus</button>
                    </div>';

                    $result[] = [
                        'DT_RowIndex' => $no++,
                        'nama_tim' => htmlspecialchars($row['nama_tim']),
                        'action' => $action
                    ];
                }
                echo json_encode(['data' => $result]);
            } catch (Throwable $e) { echo json_encode(['error' => $e->getMessage()]); }
            exit;
        }
        $this->view('admin/tim/index');
    }

    public function store()
    {
        header('Content-Type: application/json');
        if (empty($_POST['nama_tim'])) {
            http_response_code(422);
            echo json_encode(['errors' => ['nama_tim' => ['Nama Tim wajib diisi']]]);
            exit;
        }
        $this->timModel->insert(['nama_tim' => $_POST['nama_tim']]);
        echo json_encode(['status' => 'success']);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json');
        $data = $this->timModel->findById($id);
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        if (empty($_POST['nama_tim'])) {
            http_response_code(422);
            echo json_encode(['errors' => ['nama_tim' => ['Nama Tim wajib diisi']]]);
            exit;
        }
        $this->timModel->update($id, ['nama_tim' => $_POST['nama_tim']]);
        echo json_encode(['status' => 'success']);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->timModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}