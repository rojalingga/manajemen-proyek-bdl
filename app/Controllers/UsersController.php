<?php

require_once __DIR__ . '/Controller.php';
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
        if (isset($_GET['draw'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            $draw   = intval($_GET['draw']);
            $start  = intval($_GET['start']);
            $length = intval($_GET['length']);
            $search = $_GET['search']['value'] ?? '';

            try {
                $result = $this->usersModel->getServerSide($start, $length, $search);

                $data = [];
                $index = $start + 1;

                foreach ($result['data'] as $row) {
                    $editUrl = '/admin/users/' . $row['id'];
                    $deleteUrl = '/admin/users/delete/' . $row['id'];

                    $action = '
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm mx-1 edit-button"
                            data-id="' . $row['id'] . '"
                            data-url="' . $editUrl . '">Edit</button>
                        <button class="btn btn-danger btn-sm mx-1 delete-button"
                            data-url="' . $deleteUrl . '">Hapus</button>
                    </div>
                ';

                    $data[] = [
                        'DT_RowIndex' => $index++,
                        'nama'        => $row['nama'],
                        'username'    => $row['username'],
                        'role'        => $row['nama_role'] ?? '-',
                        'action'      => $action
                    ];
                }

                echo json_encode([
                    'draw'            => $draw,
                    'recordsTotal'    => $result['total'],
                    'recordsFiltered' => $result['filtered'],
                    'data'            => $data
                ], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $roleModel = new Role();
        $roles = $roleModel->getAll();

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
        if (empty($data['nama'])) {
            $errors['nama'][] = 'Nama wajib diisi.';
        }

        if (empty($data['username'])) {
            $errors['username'][] = 'Username wajib diisi.';
        }

        if (empty($data['password'])) {
            $errors['password'][] = 'Password wajib diisi.';
        }

        if (empty($data['id_role'])) {
            $errors['id_role'][] = 'Role harus dipilih.';
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

        $insertData = [
            'nama'   => $data['nama'],
            'username'   => $data['username'],
            'password'   => password_hash($data['password'], PASSWORD_BCRYPT),
            'id_role'    => $data['id_role'],
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
        if (empty($data['nama'])) {
            $errors['nama'][] = 'Nama wajib diisi.';
        }
        if (empty($data['id_role'])) {
            $errors['id_role'][] = 'Role harus dipilih.';
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

        $updateData = [
            'nama' => $data['nama'],
            'username' => $data['username'],
            'id_role'  => $data['id_role'],
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
                echo json_encode(['message' => 'Data tidak ditemukan.']);
                return;
            }

            $this->usersModel->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
