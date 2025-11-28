<?php

class ProyekTim
{
    private $db;
    private $table = 'proyek_tim';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT pt.*, p.nama_proyek, t.nama_tim 
                  FROM {$this->table} pt 
                  LEFT JOIN proyek p ON pt.id_proyek = p.id_proyek 
                  LEFT JOIN tim t ON pt.id_tim = t.id_tim 
                  ORDER BY pt.id_proyek_tim DESC";
                  
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_proyek_tim = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (id_proyek, id_tim) VALUES (:id_proyek, :id_tim)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET id_proyek = :id_proyek, id_tim = :id_tim WHERE id_proyek_tim = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek_tim = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}