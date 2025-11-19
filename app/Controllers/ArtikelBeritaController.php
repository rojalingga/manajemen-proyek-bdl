<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/ImageCompressorController.php';
require_once __DIR__ . '/../models/ArtikelBerita.php';

class ArtikelBeritaController extends Controller
{
    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $timArtikelBerita = new ArtikelBerita();
                $datatables      = $timArtikelBerita->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/artikel-berita/' . $row['id'];
                    $deleteUrl = '/admin/artikel-berita/delete/' . $row['id'];

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
                        'DT_RowIndex' => $index++,
                        'judul'        => htmlspecialchars($row['judul']),
                        'tanggal_publish'     => htmlspecialchars($row['tanggal_publish']),
                        'deskripsi'    => htmlspecialchars($row['deskripsi']),
                        'action'      => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $this->view('admin/artikel_berita/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $timArtikelBerita = new ArtikelBerita();
            $data            = $timArtikelBerita->findById($id);

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

        if (empty($data['tanggal_publish'])) {
            $errors['tanggal_publish'][] = 'Tanggal Publish wajib diisi.';
        }

        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }

        if (! isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
            $errors['thumbnail'][] = 'Thumbnail harus diupload.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $timArtikelBerita = new ArtikelBerita();

        $filename = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['thumbnail']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['thumbnail' => ['Format thumbnail tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['thumbnail' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $filename  = uniqid('artikelberita_') . '.' . $ext;
            $targetDir = __DIR__ . '/../../public/assets/artikel_berita/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filePath = $targetDir . $filename;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan thumbnail: ' . $e->getMessage()]);
                return;
            }
        }

        $insertData = [
            'judul'               => $data['judul'],
            'tanggal_publish'            => $data['tanggal_publish'],
            'deskripsi'           => $data['deskripsi'],
            'thumbnail'               => $filename ?? '',
            'created_at'         => date('Y-m-d H:i:s'),
        ];

        try {
            $timArtikelBerita->insert($insertData);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $data            = $_POST;
        $timArtikelBerita = new ArtikelBerita();
        $existing        = $timArtikelBerita->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'User tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['judul'])) {
            $errors['judul'][] = 'Judul wajib diisi.';
        }

        if (empty($data['tanggal_publish'])) {
            $errors['tanggal_publish'][] = 'Tanggal Publish wajib diisi.';
        }

        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }

        if (! isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
            $errors['thumbnail'][] = 'Thumbnail harus diupload.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename = $existing['thumbnail'];

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['thumbnail']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['thumbnail' => ['Format thumbnail tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['thumbnail' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $targetDir = __DIR__ . '/../../public/assets/artikel_berita/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (! empty($existing['thumbnail']) && file_exists($targetDir . $existing['thumbnail'])) {
                unlink($targetDir . $existing['thumbnail']);
            }

            $filename = uniqid('artikelberita_') . '.' . $ext;
            $filePath = $targetDir . $filename;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan thumbnail: ' . $e->getMessage()]);
                return;
            }
        }

        $updateData = [
            'judul'               => $data['judul'],
            'tanggal_publish'            => $data['tanggal_publish'],
            'deskripsi'           => $data['deskripsi'],
            'thumbnail'               => $filename ?? '',
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        try {
            $timArtikelBerita->update($id, $updateData);
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
            $timArtikelBerita = new ArtikelBerita();
            $user            = $timArtikelBerita->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'User tidak ditemukan.']);
                return;
            }

            if (! empty($user['thumbnail'])) {
                $filePath = __DIR__ . '/../../public/assets/artikel_berita/' . $user['thumbnail'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $timArtikelBerita->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
