<?php

class Users
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findWithRole($username)
    {
        $query = "SELECT u.*, r.nama_role FROM users u
                INNER JOIN roles r ON r.id = u.id_role
                WHERE u.username = :username
                LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAll()
    {
        $query = "
            SELECT
                u.id,
                u.username, 
                u.status,
                r.nama_role
            FROM users u
            INNER JOIN roles r ON u.id_role = r.id
            ORDER BY u.id DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "INSERT INTO users (username, password, id_role, status, foto, created_at)
              VALUES (:username, :password, :id_role, :status, :foto, :created_at)";
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query      = "UPDATE users SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function findByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
