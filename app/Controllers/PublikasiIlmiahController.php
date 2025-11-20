<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/PublikasiIlmiah.php';

class PublikasiIlmiahController extends Controller
{
    private $publikasiIlmiahModel;

    public function __construct()
    {
        $this->publikasiIlmiahModel = new PublikasiIlmiah();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->publikasiIlmiahModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/publikasi-ilmiah/' . $row['id'];
                    $deleteUrl = '/admin/publikasi-ilmiah/delete/' . $row['id'];

                    $action = '
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary btn-sm mx-1 edit-button"
                                data-id="' . htmlspecialchars($row['id']) . '"
                                data-url="' . htmlspecialchars($editUrl) . '">Edit</button>
                            <button class="btn btn-danger btn-sm mx-1 delete-button"
                                data-url="' . htmlspecialchars($deleteUrl) . '">Hapus</button>
                        </div>
                    ';


                    $data[] = [
                        'DT_RowIndex'     => $index++,
                        'judul'           => htmlspecialchars($row['judul']),
                        'peneliti'        => htmlspecialchars($row['peneliti']),
                        'tanggal_publish' => htmlspecialchars($row['tanggal_publish']),
                        'link_doi'        => htmlspecialchars($row['link_doi']),
                        'action'          => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $this->view('admin/publikasi_ilmiah/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->publikasiIlmiahModel->findById($id);

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
        if (empty($data['judul'])) {
            $errors['judul'][] = 'Judul wajib diisi.';
        }

        if (empty($data['peneliti'])) {
            $errors['peneliti'][] = 'Peneliti wajib diisi.';
        }

        if (empty($data['tanggal_publish'])) {
            $errors['tanggal_publish'][] = 'Tanggal Publish wajib diisi.';
        }

        if (empty($data['link_doi'])) {
            $errors['link_doi'][] = 'Link DOI wajib diisi.';
        }

        
        if (! isset($_FILES['file_pdf']) || $_FILES['file_pdf']['error'] !== UPLOAD_ERR_OK) {
            $errors['file_pdf'][] = 'File PDF harus diupload.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename_pdf = '';
        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['file_pdf']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));

            
            if (! in_array($ext, ['pdf'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['file_pdf' => ['Format file harus PDF.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['file_pdf' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $filename_pdf = uniqid('publikasi_') . '.' . $ext;
            $targetDir = __DIR__ . '/../../public/assets/publikasi_ilmiah/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filePath = $targetDir . $filename_pdf;

            
            if (! move_uploaded_file($tmpFile, $filePath)) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan file PDF.']);
                return;
            }
        }

        $insertData = [
            'judul'           => $data['judul'],
            'peneliti'        => $data['peneliti'],
            'tanggal_publish' => $data['tanggal_publish'],
            'link_doi'        => $data['link_doi'],
            'file_pdf'        => $filename_pdf ?? '',
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        try {
            $this->publikasiIlmiahModel->insert($insertData);
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
        $existing = $this->publikasiIlmiahModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['judul'])) {
            $errors['judul'][] = 'Judul wajib diisi.';
        }
 
        if (empty($data['peneliti'])) {
            $errors['peneliti'][] = 'Peneliti wajib diisi.';
        }

        if (empty($data['tanggal_publish'])) {
            $errors['tanggal_publish'][] = 'Tanggal Publish wajib diisi.';
        }

        if (empty($data['link_doi'])) {
            $errors['link_doi'][] = 'Link DOI wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename_pdf = $existing['file_pdf'];

        if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['file_pdf']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['pdf'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['file_pdf' => ['Format file harus PDF.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['file_pdf' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $targetDir = __DIR__ . '/../../public/assets/publikasi_ilmiah/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Hapus file lama jika ada
            if (! empty($existing['file_pdf']) && file_exists($targetDir . $existing['file_pdf'])) {
                unlink($targetDir . $existing['file_pdf']);
            }

            $filename_pdf = uniqid('publikasi_') . '.' . $ext;
            $filePath = $targetDir . $filename_pdf;

            // Simpan file PDF baru
            if (! move_uploaded_file($tmpFile, $filePath)) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan file PDF.']);
                return;
            }
        }

        $updateData = [
            'judul'           => $data['judul'],
            'peneliti'        => $data['peneliti'],
            'tanggal_publish' => $data['tanggal_publish'],
            'link_doi'        => $data['link_doi'],
            'file_pdf'        => $filename_pdf ?? '',
        ];

        try {
            $this->publikasiIlmiahModel->update($id, $updateData);
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
            $data = $this->publikasiIlmiahModel->findById($id);

            if (! $data) {
                http_response_code(404);
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            if (! empty($data['file_pdf'])) {
                $filePath = __DIR__ . '/../../public/assets/publikasi_ilmiah/' . $data['file_pdf'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->publikasiIlmiahModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}