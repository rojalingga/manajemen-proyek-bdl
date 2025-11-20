<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/ImageCompressorController.php';
require_once __DIR__ . '/../models/ProfilWeb.php';

class ProfilWebController extends Controller
{
    private $profilWebModel;

    public function __construct()
    {
        $this->profilWebModel = new ProfilWeb();
    }

    public function index()
    {
        $data = $this->profilWebModel->getData();

        $this->view('admin/profil_web/index', ['data' => $data]);
    }

    public function update()
    {
        header('Content-Type: application/json; charset=utf-8');

        $data     = $_POST;
        $existing = $this->profilWebModel->getData();

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'Data tidak ditemukan.']);
            return;
        }

        $errors = [];

        if (empty($data['nama'])) {
            $errors['nama'][] = 'Nama wajib diisi.';
        }
        if (empty($data['sejarah'])) {
            $errors['sejarah'][] = 'Sejarah wajib diisi.';
        }
        if (empty($data['visi'])) {
            $errors['visi'][] = 'Visi wajib diisi.';
        }
        if (empty($data['misi'])) {
            $errors['misi'][] = 'Misi wajib diisi.';
        }
        if (empty($data['nilai_inti_lab'])) {
            $errors['nilai_inti_lab'][] = 'Nilai inti lab wajib diisi.';
        }

        if (empty($data['email'])) {
            $errors['email'][] = 'Email wajib diisi.';
        } elseif (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Format email tidak valid.';
        }

        if (empty($data['no_telp'])) {
            $errors['no_telp'][] = 'Nomor telepon wajib diisi.';
        }

        if (empty($data['alamat'])) {
            $errors['alamat'][] = 'Alamat wajib diisi.';
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

            $targetDir = __DIR__ . '/../../public/assets/logo_web/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (! empty($existing['logo'])) {
                $oldFile = $targetDir . $existing['logo'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $filename = uniqid('logoweb_') . '.' . $ext;
            $filePath = $targetDir . $filename;

            move_uploaded_file($tmpFile, $filePath);
        }

        $updateData = [
            'nama'           => $data['nama'],
            'sejarah'        => $data['sejarah'],
            'visi'           => $data['visi'],
            'misi'           => $data['misi'],
            'nilai_inti_lab' => $data['nilai_inti_lab'],
            'email'          => $data['email'],
            'no_telp'        => $data['no_telp'],
            'alamat'         => $data['alamat'],
            'logo'           => $filename ?? $existing['logo'],
        ];

        try {
            $this->profilWebModel->update(1, $updateData);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}