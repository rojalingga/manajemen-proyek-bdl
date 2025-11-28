<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Tugas.php';
require_once __DIR__ . '/../Models/Proyek.php';
require_once __DIR__ . '/../Models/Tim.php';
require_once __DIR__ . '/../Models/Status.php';
require_once __DIR__ . '/../Models/Pegawai.php';

class TugasController extends Controller
{
    private $tugasModel;
    private $proyekModel;
    private $timModel;
    private $statusModel;
    private $pegawaiModel;

    public function __construct()
    {
        $this->tugasModel = new Tugas();
        $this->proyekModel = new Proyek();
        $this->timModel = new Tim();
        $this->statusModel = new Status();
        $this->pegawaiModel = new Pegawai();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json');
            try {
                $data = $this->tugasModel->getAll();
                $result = [];
                $no = 1;
                $baseUrl = ''; // Sesuaikan jika perlu

                foreach ($data as $row) {
                    $editUrl = $baseUrl . '/admin/tugas/edit/' . $row['id_tugas'];
                    $delUrl  = $baseUrl . '/admin/tugas/delete/' . $row['id_tugas'];
                    
                    $action = '<div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button" data-url="'.$editUrl.'">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button" data-url="'.$delUrl.'">Hapus</button>
                    </div>';

                    $result[] = [
                        'DT_RowIndex' => $no++,
                        'nama_tugas' => htmlspecialchars($row['nama_tugas']),
                        'nama_proyek' => htmlspecialchars($row['nama_proyek'] ?? '-'),
                        'nama_tim' => htmlspecialchars($row['nama_tim'] ?? '-'),
                        'status' => htmlspecialchars($row['nama_status'] ?? '-'),
                        'pj' => htmlspecialchars($row['nama_penanggung_jawab'] ?? '-'),
                        'action' => $action
                    ];
                }
                echo json_encode(['data' => $result]);
            } catch (Throwable $e) { echo json_encode(['error' => $e->getMessage()]); }
            exit;
        }

        // Ambil data untuk Dropdown di View
        $this->view('admin/tugas/index', [
            'listProyek' => $this->proyekModel->getAll(),
            'listTim'    => $this->timModel->getAll(),
            'listStatus' => $this->statusModel->getAll(),
            'listPegawai'=> $this->pegawaiModel->getAll()
        ]);
    }

    public function store()
    {
        header('Content-Type: application/json');
        $data = $_POST;
        
        // Validasi Input Wajib
        if(empty($data['nama_tugas']) || empty($data['id_proyek']) || empty($data['id_tim']) || empty($data['id_status'])) {
             http_response_code(422);
             echo json_encode(['errors' => ['msg' => ['Harap lengkapi semua field wajib!']]]);
             exit;
        }

        try {
            $this->tugasModel->insert([
                'nama_tugas' => $data['nama_tugas'],
                'deskripsi'  => $data['deskripsi'],
                'id_proyek'  => $data['id_proyek'],
                'id_tim'     => $data['id_tim'],
                'id_status'  => $data['id_status'],
                'id_penanggung_jawab' => $data['id_penanggung_jawab']
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
        header('Content-Type: application/json');
        $data = $this->tugasModel->findById($id);
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        $data = $_POST;
        
        try {
            $this->tugasModel->update($id, [
                'nama_tugas' => $data['nama_tugas'],
                'deskripsi'  => $data['deskripsi'],
                'id_proyek'  => $data['id_proyek'],
                'id_tim'     => $data['id_tim'],
                'id_status'  => $data['id_status'],
                'id_penanggung_jawab' => $data['id_penanggung_jawab']
            ]);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->tugasModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}