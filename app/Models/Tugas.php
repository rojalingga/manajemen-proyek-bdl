<?php

class Tugas
{
    private $db;
    private $table = 'tugas';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getServerSide($start, $length, $search, $filterProyek, $filterStatus, $filterDeadline)
    {
        $base = "
            FROM {$this->table} t
            LEFT JOIN proyek p ON t.id_proyek = p.id_proyek
            LEFT JOIN tim tm ON t.id_tim = tm.id_tim
            LEFT JOIN status s ON t.id_status = s.id_status
        ";

        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "t.nama_tugas ILIKE :s";
            $params[':s'] = "%{$search}%";
        }

        if (!empty($filterProyek)) {
            $where[] = "t.id_proyek = :p";
            $params[':p'] = $filterProyek;
        }

        if (!empty($filterStatus)) {
            $where[] = "t.id_status = :st";
            $params[':st'] = $filterStatus;
        }

        $orderDeadline = "";

        if (!empty($filterDeadline)) {
            if ($filterDeadline == "1") {
                $orderDeadline = "ORDER BY t.deadline ASC";
            } else {
                $orderDeadline = "ORDER BY t.deadline DESC";
            }
        }

        $whereSQL = "";
        if (!empty($where)) {
            $whereSQL = " WHERE " . implode(" AND ", $where);
        }

        $stmtTotal = $this->db->prepare("SELECT COUNT(*) AS total {$base}");
        $stmtTotal->execute();
        $total = $stmtTotal->fetch()['total'];

        $stmtFiltered = $this->db->prepare("SELECT COUNT(*) AS total {$base} {$whereSQL}");
        $stmtFiltered->execute($params);
        $filtered = $stmtFiltered->fetch()['total'];

        $sql = "
            SELECT 
                t.*, 
                p.nama_proyek, 
                tm.nama_tim, 
                s.nama_status
            {$base}
            {$whereSQL}
            " . ($orderDeadline ?: "ORDER BY t.id_tugas DESC") . "
            LIMIT :length OFFSET :start
        ";

        $stmtData = $this->db->prepare($sql);

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


    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_tugas = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO {$this->table} 
                  (nama_tugas, deskripsi, id_proyek, id_tim, id_status, id_penanggung_jawab, deadline) 
                  VALUES (:nama_tugas, :deskripsi, :id_proyek, :id_tim, :id_status, :id_penanggung_jawab, :deadline)";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id_tugas, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_tugas = :id_tugas";
        $stmt       = $this->db->prepare($query);
        $data['id_tugas'] = $id_tugas;
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_tugas = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
