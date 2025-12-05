<?php

class AnggotaTim
{
    private $db;
    private $table = 'anggota_tim';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Menggunakan Stored Procedure untuk Insert
    public function insert($data)
    {
        $query = "CALL tambah_anggota_tim_aman(:id_pegawai, :id_tim)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameter
        $stmt->bindValue(':id_pegawai', $data['id_pegawai']);
        $stmt->bindValue(':id_tim', $data['id_tim']);
        
        $stmt->execute();
    }

    public function deleteByTim($id_tim)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_tim = :id_tim");
        $stmt->bindValue(':id_tim', $id_tim);
        $stmt->execute();
    }
}