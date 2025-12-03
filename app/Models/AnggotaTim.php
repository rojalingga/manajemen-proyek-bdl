<?php

class AnggotaTim
{
    private $db;
    private $table = 'anggota_tim';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (id_pegawai, id_tim) VALUES (:id_pegawai, :id_tim)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function deleteByTim($id_tim)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_tim = :id_tim");
        $stmt->bindValue(':id_tim', $id_tim);
        $stmt->execute();
    }
}