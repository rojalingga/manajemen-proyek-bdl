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

    public function findById($id_pegawai)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_pegawai = :id_pegawai LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_pegawai', $id_pegawai, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama_pegawai, telp_pegawai, email_pegawai)
              VALUES (:nama_pegawai, :telp_pegawai, :email_pegawai)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id_pegawai, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_pegawai = :id_pegawai";
        $stmt       = $this->db->prepare($query);
        $data['id_pegawai'] = $id_pegawai;
        $stmt->execute($data);
    }

    public function delete($id_pegawai)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_pegawai = :id_pegawai");
        $stmt->bindValue(':id_pegawai', $id_pegawai);
        $stmt->execute();
    }
}
