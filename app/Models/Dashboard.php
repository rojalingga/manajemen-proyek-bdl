<?php

class Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Mengambil data dari VIEW
    public function getRekapStatus()
    {
        $query = "SELECT * FROM v_rekap_proyek_status";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil data dari MATERIALIZED VIEW
    public function getStatistikTim()
    {
        $query = "SELECT * FROM mv_statistik_tim";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Refresh Materialized View
    public function refreshStatistik()
    {
        try {
            $query = "REFRESH MATERIALIZED VIEW mv_statistik_tim";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}