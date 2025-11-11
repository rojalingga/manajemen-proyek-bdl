<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Users.php';

class AuthController extends Controller
{

    public function getLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            header('Location: /admin/dashboard');
            exit;
        }

        $this->view('admin/login');
    }

    public function postLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json; charset=utf-8');

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        $errors = [];

        if (empty($username)) {
            $errors['username'][] = 'Username wajib diisi.';
        }

        if (empty($password)) {
            $errors['password'][] = 'Password wajib diisi.';
        }

        if (! empty($errors)) {
            http_response_code(422);
            echo json_encode(['message' => 'The given data was invalid.', 'errors' => $errors]);
            return;
        }

        $userModel = new Users();
        $user      = $userModel->findByUsername($username);

        if (! $user) {
            http_response_code(422);
            echo json_encode([
                'message' => 'The given data was invalid.',
                'errors'  => [
                    'username' => ['Username belum terdaftar.'],
                ],
            ]);
            return;
        }

        if ($user['is_active'] == 2) {
            http_response_code(422);
            echo json_encode([
                'message' => 'The given data was invalid.',
                'errors'  => [
                    'username' => ['Akun anda telah diblokir.'],
                ],
            ]);
            return;
        }

        if (! password_verify($password, $user['password'])) {
            http_response_code(422);
            echo json_encode([
                'message' => 'The given data was invalid.',
                'errors'  => [
                    'password' => ['Password salah.'],
                ],
            ]);
            return;
        }

        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role_id'  => $user['id_role'],
        ];

        echo json_encode(['status' => 'success']);
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: /admin/login');
        exit;
    }

}
