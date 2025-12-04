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
        $query = "SELECT * FROM {$this->table} ORDER BY id_status ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
