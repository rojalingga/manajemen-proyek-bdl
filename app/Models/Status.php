<?php

class Status
{
    private $db;
    private $table = 'status';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id_status DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id_status)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_status = :id_status LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_status', $id_status, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama_status) VALUES (:nama_status)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id_status, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $query = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_status = :id_status";
        $data['id_status'] = $id_status;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id_status)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_status = :id_status");
        $stmt->bindValue(':id_status', $id_status);
        $stmt->execute();
    }
}