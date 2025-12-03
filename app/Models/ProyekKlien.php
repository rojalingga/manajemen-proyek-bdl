<?php

class ProyekKlien
{
    private $db;
    private $table = 'proyek_klien';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (id_proyek, id_klien) VALUES (:id_proyek, :id_klien)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function deleteByProyek($id_proyek)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek = :id_proyek");
        $stmt->bindValue(':id_proyek', $id_proyek);
        $stmt->execute();
    }
}
