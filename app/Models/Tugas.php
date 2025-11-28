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
        $query = "SELECT * FROM {$this->table} ORDER BY id_tugas DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id_tugas)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_tugas = :id_tugas LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_tugas', $id_tugas, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        // Kolom sesuai ERD: nama_tugas, deskripsi
        $query = "INSERT INTO {$this->table} (nama_tugas, deskripsi)
              VALUES (:nama_tugas, :deskripsi)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id_tugas, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query            = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_tugas = :id_tugas";
        $stmt             = $this->db->prepare($query);
        $data['id_tugas'] = $id_tugas;
        $stmt->execute($data);
    }

    public function delete($id_tugas)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_tugas = :id_tugas");
        $stmt->bindValue(':id_tugas', $id_tugas);
        $stmt->execute();
    }
}