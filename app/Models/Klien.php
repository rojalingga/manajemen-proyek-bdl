<?php

class Klien
{
    private $db;
    private $table = 'klien';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id_klien DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id_klien)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_klien = :id_klien LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_klien', $id_klien, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama_klien, telp_klien, email_klien)
              VALUES (:nama_klien, :telp_klien, :email_klien)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id_klien, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_klien = :id_klien";
        $stmt       = $this->db->prepare($query);
        $data['id_klien'] = $id_klien;
        $stmt->execute($data);
    }

    public function delete($id_klien)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_klien = :id_klien");
        $stmt->bindValue(':id_klien', $id_klien);
        $stmt->execute();
    }
}