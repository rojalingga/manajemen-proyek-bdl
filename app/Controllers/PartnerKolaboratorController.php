<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/ImageCompressorController.php';
require_once __DIR__ . '/../models/PartnerKolaborator.php';

class PartnerKolaboratorController extends Controller
{
    private $partnerKolaboratorModel;

    public function __construct()
    {
        $this->partnerKolaboratorModel = new PartnerKolaborator();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables = $this->partnerKolaboratorModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/partner-kolaborator/' . $row['id'];
                    $deleteUrl = '/admin/partner-kolaborator/delete/' . $row['id'];

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
                        'DT_RowIndex'  => $index++,
                        'nama_partner' => htmlspecialchars($row['nama_partner']),
                        'deskripsi'    => htmlspecialchars($row['deskripsi']),
                        'action'       => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $this->view('admin/partner_kolaborator/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = $this->partnerKolaboratorModel->findById($id);

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
        if (empty($data['nama_partner'])) {
            $errors['nama_partner'][] = 'Nama partner wajib diisi.';
        }

        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }

        if (! isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $errors['logo'][] = 'Logo harus diupload.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['logo']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['logo' => ['Format logo tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['logo' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $filename  = uniqid('partnerkolaborator_') . '.' . $ext;
            $targetDir = __DIR__ . '/../../public/assets/partner_kolaborator/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filePath = $targetDir . $filename;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan logo: ' . $e->getMessage()]);
                return;
            }
        }

        $insertData = [
            'nama_partner' => $data['nama_partner'],
            'deskripsi'    => $data['deskripsi'],
            'logo'         => $filename ?? '',
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        try {
            $this->partnerKolaboratorModel->insert($insertData);
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
        $existing = $this->partnerKolaboratorModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama_partner'])) {
            $errors['nama_partner'][] = 'Nama partner wajib diisi.';
        }

        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename = $existing['logo'];

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['logo']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['logo' => ['Format logo tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['logo' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $targetDir = __DIR__ . '/../../public/assets/partner_kolaborator/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (! empty($existing['logo']) && file_exists($targetDir . $existing['logo'])) {
                unlink($targetDir . $existing['logo']);
            }

            $filename = uniqid('partnerkolaborator_') . '.' . $ext;
            $filePath = $targetDir . $filename;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan logo: ' . $e->getMessage()]);
                return;
            }
        }

        $updateData = [
            'nama_partner' => $data['nama_partner'],
            'deskripsi'    => $data['deskripsi'],
            'logo'         => $filename ?? '',
        ];

        try {
            $this->partnerKolaboratorModel->update($id, $updateData);
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
            $user = $this->partnerKolaboratorModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'User tidak ditemukan.']);
                return;
            }

            if (! empty($user['logo'])) {
                $filePath = __DIR__ . '/../../public/assets/partner_kolaborator/' . $user['logo'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->partnerKolaboratorModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}