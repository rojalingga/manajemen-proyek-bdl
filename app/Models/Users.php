<?php

class Users
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findWithRole($username)
    {
        $query = "
            SELECT u.*, r.nama_role
            FROM {$this->table} u
            INNER JOIN role r ON r.id = u.id_role
            WHERE u.username = :username
            LIMIT 1
        ";

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
                u.nama,
                u.username,
                r.nama_role
            FROM {$this->table} u
            INNER JOIN role r ON u.id_role = r.id
            ORDER BY u.id DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $query = "
            INSERT INTO {$this->table} (nama, username, password, id_role, status, foto, created_at)
            VALUES ( :nama, :username, :password, :id_role)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query = "
            UPDATE {$this->table}
            SET " . implode(',', $fields) . "
            WHERE id = :id
        ";

        $stmt       = $this->db->prepare($query);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function findByUsername($username)
    {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
