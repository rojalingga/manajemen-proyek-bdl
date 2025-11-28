<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/AnggotaTim.php';
require_once __DIR__ . '/../Models/Pegawai.php';
require_once __DIR__ . '/../Models/Tim.php';

class AnggotaTimController extends Controller
{
    private $atModel;
    private $pegawaiModel;
    private $timModel;

    public function __construct()
    {
        $this->atModel = new AnggotaTim();
        $this->pegawaiModel = new Pegawai();
        $this->timModel = new Tim();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json');
            try {
                $data = $this->atModel->getAll();
                $result = [];
                $no = 1;
                $baseUrl = ''; 

                foreach ($data as $row) {
                    $editUrl = $baseUrl . '/admin/anggota_tim/edit/' . $row['id_anggota_tim'];
                    $delUrl  = $baseUrl . '/admin/anggota_tim/delete/' . $row['id_anggota_tim'];
                    
                    $action = '<div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button" data-url="'.$editUrl.'">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button" data-url="'.$delUrl.'">Hapus</button>
                    </div>';

                    $result[] = [
                        'DT_RowIndex' => $no++,
                        'nama_pegawai' => htmlspecialchars($row['nama_pegawai'] ?? 'Pegawai dihapus'),
                        'jabatan'      => htmlspecialchars($row['jabatan'] ?? '-'),
                        'nama_tim'     => htmlspecialchars($row['nama_tim'] ?? 'Tim dihapus'),
                        'action'       => $action
                    ];
                }
                echo json_encode(['data' => $result]);
            } catch (Throwable $e) { echo json_encode(['error' => $e->getMessage()]); }
            exit;
        }

        // Ambil data untuk Dropdown
        $listPegawai = $this->pegawaiModel->getAll();
        $listTim     = $this->timModel->getAll();

        $this->view('admin/anggota_tim/index', [
            'listPegawai' => $listPegawai,
            'listTim'     => $listTim
        ]);
    }

    public function store()
    {
        header('Content-Type: application/json');
        $data = $_POST;
        if(empty($data['id_pegawai']) || empty($data['id_tim'])) {
             http_response_code(422);
             echo json_encode(['errors' => ['msg' => ['Pegawai dan Tim wajib dipilih']]]);
             exit;
        }

        $this->atModel->insert([
            'id_pegawai' => $data['id_pegawai'],
            'id_tim'     => $data['id_tim']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json');
        $data = $this->atModel->findById($id);
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        $data = $_POST;
        $this->atModel->update($id, [
            'id_pegawai' => $data['id_pegawai'],
            'id_tim'     => $data['id_tim']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->atModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}