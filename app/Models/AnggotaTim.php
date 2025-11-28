<?php

class AnggotaTim
{
    private $db;
    private $table = 'anggota_tim';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT at.*, p.nama_pegawai, p.jabatan, t.nama_tim 
                  FROM {$this->table} at
                  LEFT JOIN pegawai p ON at.id_pegawai = p.id_pegawai
                  LEFT JOIN tim t ON at.id_tim = t.id_tim
                  ORDER BY at.id_anggota_tim DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_anggota_tim = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (id_pegawai, id_tim) VALUES (:id_pegawai, :id_tim)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET id_pegawai = :id_pegawai, id_tim = :id_tim WHERE id_anggota_tim = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_anggota_tim = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}