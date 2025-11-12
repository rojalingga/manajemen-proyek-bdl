<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Role.php';

class UsersController extends Controller
{
    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $userModel = new Users();
                $users     = $userModel->getAll();

                $data  = [];
                $index = 1;

                foreach ($users as $row) {
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
            $userModel = new Users();
            $data      = $userModel->findById($id);

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

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $userModel = new Users();

        if ($userModel->findByUsername($data['username'])) {
            http_response_code(422);
            echo json_encode([
                'errors' => ['username' => ['Username sudah digunakan.']],
            ]);
            return;
        }

        $insertData = [
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'id_role'  => $data['id_role'],
            'status'   => $data['status'],
        ];

        $userModel->create($insertData);

        echo json_encode(['status' => 'success']);
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $method = $_SERVER['REQUEST_METHOD'];
        $data   = $method === 'PUT' ? $_POST : $_POST;

        $userModel = new Users();
        $existing  = $userModel->findById($id);

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

        $existingUsername = $userModel->findByUsername($data['username']);
        if ($existingUsername && $existingUsername['id'] != $id) {
            http_response_code(422);
            echo json_encode([
                'errors' => ['username' => ['Username sudah digunakan.']],
            ]);
            return;
        }

        $updateData = [
            'username' => $data['username'],
            'id_role'  => $data['id_role'],
            'status'   => $data['status'],
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $userModel->updateData($id, $updateData);

        echo json_encode(['status' => 'success']);
    }

    public function destroy($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $userModel = new Users();

            $user = $userModel->findById($id);
            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'User tidak ditemukan.']);
                return;
            }

            $userModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
