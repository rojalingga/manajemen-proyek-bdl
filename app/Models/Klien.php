<?php

class Klien
{
    private $db;
    private $table = 'klien';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getServerSide($start, $length, $search)
    {
        $base = "FROM {$this->table}";
        $where = "";
        $params = [];

        if (!empty($search)) {
            $where = " WHERE nama_klien ILIKE :s OR email_klien ILIKE :s OR telp_klien ILIKE :s";
            $params[':s'] = "%{$search}%";
        }

        $stmtTotal = $this->db->prepare("SELECT COUNT(*) AS total {$base}");
        $stmtTotal->execute();
        $total = $stmtTotal->fetch()['total'];

        $stmtFiltered = $this->db->prepare("SELECT COUNT(*) AS total {$base} {$where}");
        $stmtFiltered->execute($params);
        $filtered = $stmtFiltered->fetch()['total'];

        $stmtData = $this->db->prepare("SELECT * {$base} {$where} ORDER BY id_klien DESC LIMIT :length OFFSET :start");
        foreach ($params as $key => $val) {
            $stmtData->bindValue($key, $val);
        }
        $stmtData->bindValue(':length', (int)$length, PDO::PARAM_INT);
        $stmtData->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmtData->execute();
        $data = $stmtData->fetchAll();

        return [
            'total'    => $total,
            'filtered' => $filtered,
            'data'     => $data
        ];
    }

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id_klien DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id_klien)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_klien = :id_klien LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id_klien', $id_klien, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} (nama_klien, telp_klien, email_klien)
              VALUES (:nama_klien, :telp_klien, :email_klien)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id_klien, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_klien = :id_klien";
        $stmt       = $this->db->prepare($query);
        $data['id_klien'] = $id_klien;
        $stmt->execute($data);
    }

    public function delete($id_klien)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_klien = :id_klien");
        $stmt->bindValue(':id_klien', $id_klien);
        $stmt->execute();
    }
}
