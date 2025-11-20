<?php

class TimKreatif
{
    private $db;
    private $table = 'tim_kreatif';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getForLandingPage()
    {
        $query = "SELECT id, nama, jabatan, foto FROM {$this->table} ORDER BY id ASC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama, jabatan, keahlian, portofolio_singkat, linkedin, foto, created_at)
              VALUES (:nama, :jabatan, :keahlian, :portofolio_singkat, :linkedin, :foto, :created_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}