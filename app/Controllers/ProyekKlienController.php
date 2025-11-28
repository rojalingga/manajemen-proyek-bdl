<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/ProyekKlien.php';
require_once __DIR__ . '/../Models/Proyek.php';
require_once __DIR__ . '/../Models/Klien.php';

class ProyekKlienController extends Controller
{
    private $pkModel;
    private $proyekModel;
    private $klienModel;

    public function __construct()
    {
        $this->pkModel = new ProyekKlien();
        $this->proyekModel = new Proyek();
        $this->klienModel = new Klien();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json');
            try {
                $data = $this->pkModel->getAll();
                $result = [];
                $no = 1;
                $baseUrl = ''; 

                foreach ($data as $row) {
                    $editUrl = $baseUrl . '/admin/proyek_klien/edit/' . $row['id_proyek_klien'];
                    $delUrl  = $baseUrl . '/admin/proyek_klien/delete/' . $row['id_proyek_klien'];
                    
                    $action = '<div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button" data-url="'.$editUrl.'">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button" data-url="'.$delUrl.'">Hapus</button>
                    </div>';

                    $result[] = [
                        'DT_RowIndex' => $no++,
                        'nama_proyek' => htmlspecialchars($row['nama_proyek'] ?? 'Proyek dihapus'),
                        'nama_klien'  => htmlspecialchars($row['nama_klien'] ?? 'Klien dihapus'),
                        'action' => $action
                    ];
                }
                echo json_encode(['data' => $result]);
            } catch (Throwable $e) { echo json_encode(['error' => $e->getMessage()]); }
            exit;
        }

        // Ambil data Dropdown
        $listProyek = $this->proyekModel->getAll();
        $listKlien  = $this->klienModel->getAll();

        $this->view('admin/proyek_klien/index', [
            'listProyek' => $listProyek,
            'listKlien'  => $listKlien
        ]);
    }

    public function store()
    {
        header('Content-Type: application/json');
        $data = $_POST;
        if(empty($data['id_proyek']) || empty($data['id_klien'])) {
             http_response_code(422);
             echo json_encode(['errors' => ['msg' => ['Proyek dan Klien wajib dipilih']]]);
             exit;
        }

        $this->pkModel->insert([
            'id_proyek' => $data['id_proyek'],
            'id_klien'  => $data['id_klien']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json');
        $data = $this->pkModel->findById($id);
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        $data = $_POST;
        $this->pkModel->update($id, [
            'id_proyek' => $data['id_proyek'],
            'id_klien'  => $data['id_klien']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->pkModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}