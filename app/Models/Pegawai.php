<?php

class Pegawai
{
    private $db;
    private $table = 'pegawai';

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
            $where = " WHERE nama_pegawai ILIKE :s OR email_pegawai ILIKE :s OR telp_pegawai ILIKE :s";
            $params[':s'] = "%{$search}%";
        }

        $stmtTotal = $this->db->prepare("SELECT COUNT(*) AS total {$base}");
        $stmtTotal->execute();
        $total = $stmtTotal->fetch()['total'];

        $stmtFiltered = $this->db->prepare("SELECT COUNT(*) AS total {$base} {$where}");
        $stmtFiltered->execute($params);
        $filtered = $stmtFiltered->fetch()['total'];

        $stmtData = $this->db->prepare("SELECT * {$base} {$where} ORDER BY id_pegawai DESC LIMIT :length OFFSET :start");
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
