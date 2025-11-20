<?php

class ArtikelBerita
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM artikel_berita ORDER BY id ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getForLandingPage()
    {
        $query = "SELECT id, judul, penulis, thumbnail, file, tanggal_publish, deskripsi FROM artikel_berita ORDER BY id ASC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM artikel_berita WHERE id = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO artikel_berita (judul, penulis, thumbnail, file, tanggal_publish, deskripsi, created_at)
              VALUES (:judul, :penulis, :thumbnail, :file, :tanggal_publish, :deskripsi, :created_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE artikel_berita SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM artikel_berita WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
