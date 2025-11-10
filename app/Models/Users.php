<?php

require_once __DIR__ . '/Model.php';

class Gallery extends Model
{
    protected $table = 'users';

    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
