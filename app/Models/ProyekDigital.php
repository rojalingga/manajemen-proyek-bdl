<?php

class ProyekDigital
{
    private $db;
    private $table = 'proyek_digital';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getForLandingPage()
    {
        $query = "SELECT id, judul, deskripsi, foto_proyek, tahun FROM {$this->table} ORDER BY id ASC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT 
                    p.*, 
                    pk.nama_partner, 
                    kp.nama_kategori, 
                    t.nama_teknologi
                  FROM {$this->table} p
                  LEFT JOIN partner_kolaborator pk ON p.id_partner = pk.id
                  LEFT JOIN kategori_proyek kp ON p.id_kategori_proyek = kp.id
                  LEFT JOIN teknologi t ON p.id_teknologi = t.id
                  WHERE p.id = :id 
                  LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKomentarByProyek($id)
    {
        $query = "SELECT * FROM komentar 
                  WHERE id_proyek_digital = :id 
                  ORDER BY created_at DESC"; 
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRataRataRating($id)
    {
        $query = "SELECT AVG(rating) as total_rating FROM rating 
                  WHERE id_proyek_digital = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        
        return $result['total_rating'] ? round($result['total_rating'], 1) : 0;
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (judul, deskripsi, id_kategori_proyek, id_teknologi, foto_proyek, id_partner, tahun, created_at)
              VALUES (:judul, :deskripsi, :id_kategori_proyek, :id_teknologi, :foto_proyek, :id_partner, :tahun, :created_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}