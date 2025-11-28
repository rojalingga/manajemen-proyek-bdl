<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Proyek.php';
require_once __DIR__ . '/../Models/Status.php';

class ProyekController extends Controller
{
    private $proyekModel;
    private $statusModel;

    public function __construct()
    {
        $this->proyekModel = new Proyek();
        $this->statusModel = new Status();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json');
            try {
                $data = $this->proyekModel->getAll();
                $result = [];
                $no = 1;
                foreach ($data as $row) {
                    $editUrl = '/admin/proyek/edit/' . $row['id_proyek'];
                    $delUrl  = '/admin/proyek/delete/' . $row['id_proyek'];
                    
                    $action = '<div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button" data-url="'.$editUrl.'">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button" data-url="'.$delUrl.'">Hapus</button>
                    </div>';

                    $result[] = [
                        'DT_RowIndex' => $no++,
                        'nama_proyek' => htmlspecialchars($row['nama_proyek']),
                        'tanggal_mulai' => $row['tanggal_mulai'],
                        'tanggal_selesai' => $row['tanggal_selesai'],
                        'nama_status' => htmlspecialchars($row['nama_status'] ?? '-'), // Dari Join
                        'action' => $action
                    ];
                }
                echo json_encode(['data' => $result]);
            } catch (Throwable $e) { echo json_encode(['error' => $e->getMessage()]); }
            exit;
        }

        // Ambil data status untuk dikirim ke view (Dropdown)
        $listStatus = $this->statusModel->getAll();
        $this->view('admin/proyek/index', ['listStatus' => $listStatus]);
    }

    public function store()
    {
        header('Content-Type: application/json');
        $data = $_POST;
        // Tambahkan validasi sesuai kebutuhan
        if(empty($data['nama_proyek']) || empty($data['id_status'])) {
             http_response_code(422);
             echo json_encode(['errors' => ['msg' => ['Nama Proyek dan Status wajib diisi']]]);
             exit;
        }

        $this->proyekModel->insert([
            'nama_proyek' => $data['nama_proyek'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'id_status' => $data['id_status']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json');
        $data = $this->proyekModel->findById($id);
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        $data = $_POST;
        
        $this->proyekModel->update($id, [
            'nama_proyek' => $data['nama_proyek'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'id_status' => $data['id_status']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->proyekModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}