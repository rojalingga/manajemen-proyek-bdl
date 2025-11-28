<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Pegawai.php';

class PegawaiController extends Controller
{
    private $pegawaiModel;

    public function __construct()
    {
        $this->pegawaiModel = new Pegawai();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json');
            try {
                $data = $this->pegawaiModel->getAll();
                $result = [];
                $no = 1;
                $baseUrl = ''; 

                foreach ($data as $row) {
                    $editUrl = $baseUrl . '/admin/pegawai/edit/' . $row['id_pegawai'];
                    $delUrl  = $baseUrl . '/admin/pegawai/delete/' . $row['id_pegawai'];
                    
                    $action = '<div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button" data-url="'.$editUrl.'">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button" data-url="'.$delUrl.'">Hapus</button>
                    </div>';

                    $result[] = [
                        'DT_RowIndex' => $no++,
                        'nama_pegawai' => htmlspecialchars($row['nama_pegawai']),
                        'jabatan' => htmlspecialchars($row['jabatan']),
                        'telp_pegawai' => htmlspecialchars($row['telp_pegawai']),
                        'action' => $action
                    ];
                }
                echo json_encode(['data' => $result]);
            } catch (Throwable $e) { echo json_encode(['error' => $e->getMessage()]); }
            exit;
        }
        $this->view('admin/pegawai/index');
    }

    public function store()
    {
        header('Content-Type: application/json');
        $data = $_POST;
        if(empty($data['nama_pegawai'])) {
             http_response_code(422);
             echo json_encode(['errors' => ['msg' => ['Nama Pegawai wajib diisi']]]);
             exit;
        }

        $this->pegawaiModel->insert([
            'nama_pegawai' => $data['nama_pegawai'],
            'telp_pegawai' => $data['telp_pegawai'],
            'email_pegawai' => $data['email_pegawai'],
            'jabatan' => $data['jabatan']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json');
        $data = $this->pegawaiModel->findById($id);
        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    public function update($id)
    {
        header('Content-Type: application/json');
        $data = $_POST;
        $this->pegawaiModel->update($id, [
            'nama_pegawai' => $data['nama_pegawai'],
            'telp_pegawai' => $data['telp_pegawai'],
            'email_pegawai' => $data['email_pegawai'],
            'jabatan' => $data['jabatan']
        ]);
        echo json_encode(['status' => 'success']);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');
        $this->pegawaiModel->delete($id);
        echo json_encode(['status' => 'success']);
    }
}