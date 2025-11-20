<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/ImageCompressorController.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Role.php';

class UsersController extends Controller
{
    private $usersModel;

    public function __construct()
    {
        $this->usersModel = new Users();
    }

    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $datatables      = $this->usersModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $status = match ((int) $row['status']) {
                        1       => '<span class="badge bg-success">Aktif</span>',
                        2       => '<span class="badge bg-danger">Blokir</span>',
                        default => '<span class="badge bg-secondary">Unknown</span>',
                    };

                    $editUrl   = '/admin/users/' . $row['id'];
                    $deleteUrl = '/admin/users/delete/' . $row['id'];

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
                        'username'    => htmlspecialchars($row['username']),
                        'role'        => htmlspecialchars($row['nama_role'] ?? '-'),
                        'status'      => $status,
                        'action'      => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $roleModel = new Role();
        $roles     = $roleModel->getAll();

        $this->view('admin/users/index', ['roles' => $roles]);
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data      = $this->usersModel->findById($id);

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
        if (empty($data['username'])) {
            $errors['username'][] = 'Username wajib diisi.';
        }

        if (empty($data['password'])) {
            $errors['password'][] = 'Password wajib diisi.';
        }

        if (empty($data['id_role'])) {
            $errors['id_role'][] = 'Role harus dipilih.';
        }

        if (empty($data['status'])) {
            $errors['status'][] = 'Status harus dipilih.';
        }

        if (! isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $errors['foto'][] = 'Foto harus diupload.';
        }
        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        if ($this->usersModel->findByUsername($data['username'])) {
            http_response_code(422);
            echo json_encode(['errors' => ['username' => ['Username sudah digunakan.']]]);
            return;
        }

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

            $filename  = uniqid('user_') . '.' . $ext;
            $targetDir = __DIR__ . '/../../public/assets/foto_profil/';
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
            'username'   => $data['username'],
            'password'   => password_hash($data['password'], PASSWORD_BCRYPT),
            'id_role'    => $data['id_role'],
            'status'     => $data['status'],
            'foto'       => $filename,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->usersModel->insert($insertData);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $data      = $_POST;
        $existing  = $this->usersModel->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'User tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['username'])) {
            $errors['username'][] = 'Username wajib diisi.';
        }
        if (empty($data['id_role'])) {
            $errors['id_role'][] = 'Role harus dipilih.';
        }
        if (empty($data['status'])) {
            $errors['status'][] = 'Status harus dipilih.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $existingUsername = $this->usersModel->findByUsername($data['username']);
        if ($existingUsername && $existingUsername['id'] != $id) {
            http_response_code(422);
            echo json_encode(['errors' => ['username' => ['Username sudah digunakan.']]]);
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

            $targetDir = __DIR__ . '/../../public/assets/foto_profil/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (! empty($existing['foto']) && file_exists($targetDir . $existing['foto'])) {
                unlink($targetDir . $existing['foto']);
            }

            $filename = uniqid('user_') . '.' . $ext;
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
            'username' => $data['username'],
            'id_role'  => $data['id_role'],
            'status'   => $data['status'],
            'foto'     => $filename,
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        try {
            $this->usersModel->update($id, $updateData);
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
            $user      = $this->usersModel->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'User tidak ditemukan.']);
                return;
            }

            if (! empty($user['foto'])) {
                $filePath = __DIR__ . '/../../public/assets/foto_profil/' . $user['foto'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->usersModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
