<?php

class Proyek
{
    private $db;
    private $table = 'proyek';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT p.*, s.nama_status 
                  FROM {$this->table} p
                  LEFT JOIN status s ON p.id_status = s.id_status
                  ORDER BY p.id_proyek DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id_proyek)
    {
        $query = "
            SELECT
                p.*,
                COALESCE(
                    STRING_AGG(DISTINCT pt.id_tim::text, '||') FILTER (WHERE pt.id_tim IS NOT NULL),
                '') AS tim_ids,
                COALESCE(
                    STRING_AGG(DISTINCT pk.id_klien::text, '||') FILTER (WHERE pk.id_klien IS NOT NULL),
                '') AS klien_ids
            FROM {$this->table} p
            LEFT JOIN proyek_tim pt ON pt.id_proyek = p.id_proyek
            LEFT JOIN proyek_klien pk ON pk.id_proyek = p.id_proyek
            WHERE p.id_proyek = :id_proyek
            GROUP BY p.id_proyek
            LIMIT 1
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
        $query = "INSERT INTO {$this->table} (nama_proyek, tanggal_mulai, tanggal_selesai, id_status)
              VALUES (:nama_proyek, :tanggal_mulai, :tanggal_selesai, :id_status)
              RETURNING id_proyek";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nama_proyek', $data['nama_proyek']);
        $stmt->bindValue(':tanggal_mulai', $data['tanggal_mulai']);

        if (!empty($data['tanggal_selesai'])) {
            $stmt->bindValue(':tanggal_selesai', $data['tanggal_selesai']);
        } else {
            $stmt->bindValue(':tanggal_selesai', null, PDO::PARAM_NULL);
        }

        $stmt->bindValue(':id_status', $data['id_status'], PDO::PARAM_INT);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id_proyek'];
    }


    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} SET 
                  nama_proyek = :nama_proyek, 
                  tanggal_mulai = :tanggal_mulai, 
                  tanggal_selesai = :tanggal_selesai,
                  id_status = :id_status
                  WHERE id_proyek = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_proyek = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
