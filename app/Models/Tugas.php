<?php

class Tugas
{
    private $db;
    private $table = 'tugas';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    //Function 'hitung_sisa_hari'
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
                s.nama_status,
                hitung_sisa_hari(t.deadline) as sisa_hari 
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

    //STORED PROCEDURE 'tambah_tugas_baru'
    public function insert($data)
    {
        $query = "CALL tambah_tugas_baru(
            :nama_tugas,
            :deskripsi,
            :id_proyek,
            :id_tim,
            :id_penanggung_jawab,
            :deadline
        )";

        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':nama_tugas', $data['nama_tugas']);
        $stmt->bindValue(':deskripsi', $data['deskripsi']);
        $stmt->bindValue(':id_proyek', $data['id_proyek']);
        $stmt->bindValue(':id_tim', $data['id_tim']);
        $stmt->bindValue(':id_penanggung_jawab', $data['id_penanggung_jawab']);
        $stmt->bindValue(':deadline', $data['deadline']);

        $stmt->execute();
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

    public function getTugasWithDeadline()
    {
        $sql = "SELECT
                    t.*,
                    p.nama_proyek,
                    tm.nama_tim,
                    s.nama_status,
                    hitung_sisa_hari(t.deadline) as sisa_hari
                FROM tugas t
                JOIN proyek p ON t.id_proyek = p.id_proyek
                JOIN tim tm ON t.id_tim = tm.id_tim
                JOIN status s ON t.id_status = s.id_status";

        return $this->db->query($sql)->fetchAll();
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }
}