<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/ImageCompressorController.php';
require_once __DIR__ . '/../models/EventHighlight.php';

class EventHighlightController extends Controller
{
    public function index()
    {
        if (isset($_GET['ajax'])) {
            ob_clean();
            header('Content-Type: application/json; charset=utf-8');

            try {
                $timEventHighlight = new EventHighlight();
                $datatables       = $timEventHighlight->getAll();

                $data  = [];
                $index = 1;

                foreach ($datatables as $row) {
                    $editUrl   = '/admin/event-highlight/' . $row['id'];
                    $deleteUrl = '/admin/event-highlight/delete/' . $row['id'];

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
                        'nama_event'      => htmlspecialchars($row['nama_event']),
                        'deskripsi'       => htmlspecialchars($row['deskripsi']),
                        'tanggal_event'   => htmlspecialchars($row['tanggal_event']),
                        'lokasi'          => htmlspecialchars($row['lokasi']),
                        'action'          => $action,
                    ];
                }

                echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }

            exit;
        }

        $this->view('admin/event_highlight/index');
    }

    public function edit($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            $timEventHighlight = new EventHighlight();
            $data             = $timEventHighlight->findById($id);

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
        if (empty($data['nama_event'])) {
            $errors['nama_event'][] = 'Nama Event wajib diisi.';
        }

        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }

        if (empty($data['tanggal_event'])) {
            $errors['tanggal_event'][] = 'Tanggal Event wajib diisi.';
        }

        if (empty($data['lokasi'])) {
            $errors['lokasi'][] = 'Lokasi wajib diisi.';
        }

        if (! isset($_FILES['banner']) || $_FILES['banner']['error'] !== UPLOAD_ERR_OK) {
            $errors['banner'][] = 'Banner harus diupload.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $timEventHighlight = new EventHighlight();

        $filename_banner = '';
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['banner']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['banner' => ['Format banner tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['banner' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $filename_banner  = uniqid('eventhighlight_') . '.' . $ext;
            $targetDir = __DIR__ . '/../../public/assets/event_highlight/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filePath = $targetDir . $filename_banner;

            try {
                ImageCompressorController::compress($tmpFile, $filePath, $ext);
            } catch (Throwable $e) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan banner: ' . $e->getMessage()]);
                return;
            }
        }

        $insertData = [
            'nama_event'      => $data['nama_event'],
            'deskripsi'         => $data['deskripsi'],
            'tanggal_event' => $data['tanggal_event'],
            'lokasi'       => $data['lokasi'],
            'banner'       => $filename_banner ?? '',
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        try {
            $timEventHighlight->insert($insertData);
            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');

        $data             = $_POST;
        $timEventHighlight = new EventHighlight();
        $existing         = $timEventHighlight->findById($id);

        if (! $existing) {
            http_response_code(404);
            echo json_encode(['message' => 'User tidak ditemukan.']);
            return;
        }

        $errors = [];
        if (empty($data['nama_event'])) {
            $errors['nama_event'][] = 'Nama Event wajib diisi.';
        }
 
        if (empty($data['deskripsi'])) {
            $errors['deskripsi'][] = 'Deskripsi wajib diisi.';
        }

        if (empty($data['tanggal_event'])) {
            $errors['tanggal_event'][] = 'Tanggal Event wajib diisi.';
        }

        if (empty($data['lokasi'])) {
            $errors['lokasi'][] = 'Lokasi wajib diisi.';
        }

        if (! isset($_FILES['banner']) || $_FILES['banner']['error'] !== UPLOAD_ERR_OK) {
            $errors['banner'][] = 'Banner harus diupload.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $filename = $existing['banner'];

        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $tmpFile = $_FILES['banner']['tmp_name'];
            $ext     = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                http_response_code(422);
                echo json_encode(['errors' => ['banner' => ['Format banner tidak valid.']]]);
                return;
            }

            if (! file_exists($tmpFile)) {
                http_response_code(400);
                echo json_encode(['errors' => ['banner' => ['File upload tidak ditemukan.']]]);
                return;
            }

            $targetDir = __DIR__ . '/../../public/assets/event_highlight/';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if (! empty($existing['banner']) && file_exists($targetDir . $existing['banner'])) {
                unlink($targetDir . $existing['banner']);
            }

            $filename = uniqid('eventhighlight_') . '.' . $ext;
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
            'nama_event'      => $data['nama_event'],
            'deskripsi'         => $data['deskripsi'],
            'tanggal_event' => $data['tanggal_event'],
            'lokasi'       => $data['lokasi'],
            'banner'          => $filename ?? '',
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        try {
            $timEventHighlight->update($id, $updateData);
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
            $timEventHighlight = new EventHighlight();
            $user             = $timEventHighlight->findById($id);

            if (! $user) {
                http_response_code(404);
                echo json_encode(['message' => 'User tidak ditemukan.']);
                return;
            }

            if (! empty($user['banner'])) {
                $filePath = __DIR__ . '/../../public/assets/event_highlight/' . $user['banner'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $timEventHighlight->delete($id);

            echo json_encode(['status' => 'success']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}