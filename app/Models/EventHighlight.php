<?php

class EventHighlight
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM event_highlight ORDER BY id ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getForLandingPage()
    {
        $query = "SELECT id, nama_event, deskripsi, tanggal_event, lokasi, banner FROM event_highlight ORDER BY id ASC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM event_highlight WHERE id = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO event_highlight (nama_event, deskripsi, tanggal_event, lokasi, banner, created_at)
              VALUES (:nama_event, :deskripsi, :tanggal_event, :lokasi, :banner, :created_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE event_highlight SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM event_highlight WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
