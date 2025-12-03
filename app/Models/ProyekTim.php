<?php

class ProyekTim
{
    private $db;
    private $table = 'proyek_tim';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (id_proyek, id_tim) VALUES (:id_proyek, :id_tim)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function deleteByProyek($id_proyek)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek = :id_proyek");
        $stmt->bindValue(':id_proyek', $id_proyek);
        $stmt->execute();
    }

    public function getTimIdsByProyek($id_proyek)
    {
        $query = "SELECT id_tim FROM {$this->table} WHERE id_proyek = :id_proyek";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_proyek', $id_proyek, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
