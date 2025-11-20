<?php

class ProfilWeb
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getData()
    {
        $query = "SELECT * FROM profil_web LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getForLandingPage()
    {
        $query = "SELECT * FROM profil_web LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE profil_web SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }
}
