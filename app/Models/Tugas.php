<?php

class Tugas
{
    private $db;
    private $table = 'tugas';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT t.*, 
                         p.nama_proyek, 
                         tm.nama_tim, 
                         s.nama_status, 
                         pg.nama_pegawai as nama_penanggung_jawab
                  FROM {$this->table} t
                  LEFT JOIN proyek p ON t.id_proyek = p.id_proyek
                  LEFT JOIN tim tm ON t.id_tim = tm.id_tim
                  LEFT JOIN status s ON t.id_status = s.id_status
                  LEFT JOIN pegawai pg ON t.id_penanggung_jawab = pg.id_pegawai
                  ORDER BY t.id_tugas DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_tugas = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        // Masukkan semua kolom sesuai struktur tabel
        $query = "INSERT INTO {$this->table} 
                  (nama_tugas, deskripsi, id_proyek, id_tim, id_status, id_penanggung_jawab) 
                  VALUES 
                  (:nama_tugas, :deskripsi, :id_proyek, :id_tim, :id_status, :id_penanggung_jawab)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET 
                  nama_tugas = :nama_tugas, 
                  deskripsi = :deskripsi,
                  id_proyek = :id_proyek,
                  id_tim = :id_tim,
                  id_status = :id_status,
                  id_penanggung_jawab = :id_penanggung_jawab
                  WHERE id_tugas = :id";
                  
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_tugas = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}