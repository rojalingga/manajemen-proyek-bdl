<?php

class ProyekKlien
{
    private $db;
    private $table = 'proyek_klien';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT pk.*, p.nama_proyek, k.nama_klien 
                  FROM {$this->table} pk
                  LEFT JOIN proyek p ON pk.id_proyek = p.id_proyek
                  LEFT JOIN klien k ON pk.id_klien = k.id_klien
                  ORDER BY pk.id_proyek_klien DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_proyek_klien = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (id_proyek, id_klien) VALUES (:id_proyek, :id_klien)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET id_proyek = :id_proyek, id_klien = :id_klien WHERE id_proyek_klien = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek_klien = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}