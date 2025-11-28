<?php

class Proyek
{
    private $db;
    private $table = 'proyek';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        // JOIN KE TABEL STATUS AGAR NAMA STATUS MUNCUL
        $query = "SELECT p.*, s.nama_status 
                  FROM {$this->table} p
                  LEFT JOIN status s ON p.id_status = s.id_status
                  ORDER BY p.id_proyek DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_proyek = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama_proyek, tanggal_mulai, tanggal_selesai, id_status) 
                  VALUES (:nama_proyek, :tanggal_mulai, :tanggal_selesai, :id_status)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET 
                  nama_proyek = :nama_proyek, 
                  tanggal_mulai = :tanggal_mulai, 
                  tanggal_selesai = :tanggal_selesai,
                  id_status = :id_status
                  WHERE id_proyek = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}