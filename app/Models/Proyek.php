<?php

class Proyek
{
    private $db;
    private $table = 'proyek';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getServerSide($start, $length, $search, $filterStatus, $filterDeadline)
    {
        $base = "FROM {$this->table} p LEFT JOIN status s ON p.id_status = s.id_status";
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "p.nama_proyek ILIKE :s";
            $params[':s'] = "%{$search}%";
        }

        if (!empty($filterStatus)) {
            $where[] = "p.id_status = :st";
            $params[':st'] = $filterStatus;
        }

        $orderDeadline = "";
        if (!empty($filterDeadline)) {
            if ($filterDeadline == "1") {
                $orderDeadline = "ORDER BY p.tanggal_selesai ASC";
            } else {
                $orderDeadline = "ORDER BY p.tanggal_selesai DESC";
            }
        }

        $whereSQL = "";
        if (!empty($where)) {
            $whereSQL = " WHERE " . implode(" AND ", $where);
        }

        $stmtTotal = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
        $stmtTotal->execute();
        $total = $stmtTotal->fetch()['total'];

        $stmtFiltered = $this->db->prepare("SELECT COUNT(*) AS total {$base} {$whereSQL}");
        $stmtFiltered->execute($params);
        $filtered = $stmtFiltered->fetch()['total'];

        // Menambahkan hitung_progress_proyek()
        $sql = "SELECT
                    p.*,
                    s.nama_status,
                    hitung_progress_proyek(p.id_proyek) as progress 
                {$base} {$whereSQL} "
             . ($orderDeadline ?: "ORDER BY p.id_proyek DESC")
             . " LIMIT :length OFFSET :start";

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

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id_proyek DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id_proyek)
    {
        $query = "
            SELECT p.*, COALESCE(STRING_AGG(DISTINCT pt.id_tim::text, '||') FILTER (WHERE pt.id_tim IS NOT NULL),'') AS tim_ids,
                        COALESCE(STRING_AGG(DISTINCT pk.id_klien::text, '||') FILTER (WHERE pk.id_klien IS NOT NULL),'') AS klien_ids
            FROM {$this->table} p
            LEFT JOIN proyek_tim pt ON pt.id_proyek = p.id_proyek
            LEFT JOIN proyek_klien pk ON pk.id_proyek = p.id_proyek
            WHERE p.id_proyek = :id_proyek
            GROUP BY p.id_proyek LIMIT 1
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_proyek', $id_proyek, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $data['tim_ids']   = $data['tim_ids'] ? explode('||', $data['tim_ids']) : [];
            $data['klien_ids'] = $data['klien_ids'] ? explode('||', $data['klien_ids']) : [];
        }
        return $data;
    }

    public function insertGetId($data)
    {
        $query = "INSERT INTO {$this->table} (nama_proyek, tanggal_mulai, tanggal_selesai, id_status, budget)
              VALUES (:nama_proyek, :tanggal_mulai, :tanggal_selesai, :id_status, :budget) RETURNING id_proyek";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_proyek'];
    }

    public function update($id_proyek, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $query      = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_proyek = :id_proyek";
        $stmt       = $this->db->prepare($query);
        $data['id_proyek'] = $id_proyek;
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}