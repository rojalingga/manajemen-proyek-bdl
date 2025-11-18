<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/ImageCompressorController.php';
require_once __DIR__ . '/../models/TimKreatif.php';

class TimKreatifController extends Controller
{
    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $timKreatifModel = new TimKreatif();
                $datatables      = $timKreatifModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/tim-kreatif/' . $row['id'];
                    $deleteUrl = '/admin/tim-kreatif/delete/' . $row['id'];

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
                        'nama'        => htmlspecialchars($row['nama']),
                        'jabatan'     => htmlspecialchars($row['jabatan']),
                        'keahlian'    => htmlspecialchars($row['keahlian']),
                        'action'      => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $this->view('admin/tim_kreatif/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $timKreatifModel = new TimKreatif();
            $data            = $timKreatifModel->findById($id);

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
        if (empty($data['nama'])) {
            $errors['nama'][] = 'Nama wajib diisi.';
        }

        if (empty($data['jabatan'])) {
            $errors['jabatan'][] = 'Jabatan wajib diisi.';
        }

        if (empty($data['keahlian'])) {
            $errors['keahlian'][] = 'Keahlian wajib diisi.';
        }

        if (empty($data['portofolio_singkat'])) {
            $errors['portofolio_singkat'][] = 'Portofolio singkat wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $timKreatifModel = new TimKreatif();

        $filename = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['foto']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['foto' => ['Format foto tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['foto' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $filename  = uniqid('timkreatif_') . '.' . $ext;
            $targetDir = __DIR__ . '/../../public/assets/tim_kreatif/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filePath = $targetDir . $filename;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan foto: ' . $e->getMessage()]);
                return;
            }
        }

        $insertData = [
            'nama'               => $data['nama'],
            'jabatan'            => $data['jabatan'],
            'keahlian'           => $data['keahlian'],
            'portofolio_singkat' => $data['portofolio_singkat'],
            'linkedin'           => $data['linkedin'] ?? '',
            'foto'               => $filename ?? '',
            'created_at'         => date('Y-m-d H:i:s'),
        ];

        try {
            $timKreatifModel->insert($insertData);
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
        $timKreatifModel = new TimKreatif();
        $existing        = $timKreatifModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'User tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama'])) {
            $errors['nama'][] = 'Nama wajib diisi.';
        }

        if (empty($data['jabatan'])) {
            $errors['jabatan'][] = 'Jabatan wajib diisi.';
        }

        if (empty($data['keahlian'])) {
            $errors['keahlian'][] = 'Keahlian wajib diisi.';
        }

        if (empty($data['portofolio_singkat'])) {
            $errors['portofolio_singkat'][] = 'Portofolio singkat wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename = $existing['foto'];

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['foto']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['foto' => ['Format foto tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['foto' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $targetDir = __DIR__ . '/../../public/assets/tim_kreatif/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (! empty($existing['foto']) && file_exists($targetDir . $existing['foto'])) {
                unlink($targetDir . $existing['foto']);
            }

            $filename = uniqid('timkreatif_') . '.' . $ext;
            $filePath = $targetDir . $filename;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan foto: ' . $e->getMessage()]);
                return;
            }
        }

        $updateData = [
            'nama'               => $data['nama'],
            'jabatan'            => $data['jabatan'],
            'keahlian'           => $data['keahlian'],
            'portofolio_singkat' => $data['portofolio_singkat'],
            'linkedin'           => $data['linkedin'] ?? '',
            'foto'               => $filename ?? '',
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        try {
            $timKreatifModel->update($id, $updateData);
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
            $timKreatifModel = new TimKreatif();
            $user            = $timKreatifModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'User tidak ditemukan.']);
                return;
            }

            if (! empty($user['foto'])) {
                $filePath = __DIR__ . '/../../public/assets/tim_kreatif/' . $user['foto'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $timKreatifModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
