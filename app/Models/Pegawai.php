<?php

class Pegawai
{
    private $db;
    private $table = 'pegawai';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id_pegawai DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_pegawai = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama_pegawai, telp_pegawai, email_pegawai, jabatan) 
                  VALUES (:nama_pegawai, :telp_pegawai, :email_pegawai, :jabatan)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET 
                  nama_pegawai = :nama_pegawai, 
                  telp_pegawai = :telp_pegawai, 
                  email_pegawai = :email_pegawai,
                  jabatan = :jabatan
                  WHERE id_pegawai = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_pegawai = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}