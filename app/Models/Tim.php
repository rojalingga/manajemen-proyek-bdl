<?php

class Tim
{
    private $db;
    private $table = 'tim';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getServerSide($start, $length, $search)
    {
        $where  = "";
        $params = [];

        if (! empty($search)) {
            $where        = "WHERE t.nama_tim ILIKE :s";
            $params[':s'] = "%{$search}%";
        }

        $stmtTotal = $this->db->prepare("SELECT COUNT(*) AS total FROM {$this->table}");
        $stmtTotal->execute();
        $total = $stmtTotal->fetch()['total'];

        $stmtFiltered = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM {$this->table} t
            {$where}
        ");
        $stmtFiltered->execute($params);
        $filtered = $stmtFiltered->fetch()['total'];

        $sql = "
            SELECT t.id_tim, t.nama_tim
            FROM {$this->table} t
            {$where}
            ORDER BY t.id_tim DESC
            LIMIT :length OFFSET :start
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':length', (int) $length, PDO::PARAM_INT);
        $stmt->bindValue(':start', (int) $start, PDO::PARAM_INT);
        $stmt->execute();

        $listTim = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! $listTim) {
            return [
                'total'    => $total,
                'filtered' => $filtered,
                'data'     => [],
            ];
        }

        $ids = array_column($listTim, 'id_tim');
        $in  = implode(',', array_fill(0, count($ids), '?'));

        $stmtAnggota = $this->db->prepare("
            SELECT a.id_tim, p.nama_pegawai
            FROM anggota_tim a
            LEFT JOIN pegawai p ON p.id_pegawai = a.id_pegawai
            WHERE a.id_tim IN ($in)
            ORDER BY p.nama_pegawai
        ");
        $stmtAnggota->execute($ids);

        $anggotaRaw = $stmtAnggota->fetchAll(PDO::FETCH_ASSOC);

        $grupAnggota = [];
        foreach ($ids as $id) {
            $grupAnggota[$id] = [];
        }

        foreach ($anggotaRaw as $row) {
            if (! empty($row['nama_pegawai'])) {
                $grupAnggota[$row['id_tim']][] = $row['nama_pegawai'];
            }
        }

        $final = [];
        foreach ($listTim as $tim) {
            $final[] = [
                'id_tim'   => $tim['id_tim'],
                'nama_tim' => $tim['nama_tim'],
                'anggota'  => $grupAnggota[$tim['id_tim']],
            ];
        }

        return [
            'total'    => $total,
            'filtered' => $filtered,
            'data'     => $final,
        ];
    }

    public function getAll()
    {
        $query = "SELECT id_tim, nama_tim FROM {$this->table} ORDER BY id_tim DESC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id_tim)
    {
        $query = "
            SELECT t.*, COALESCE(STRING_AGG(a.id_pegawai::text, '||') FILTER (WHERE a.id_pegawai IS NOT NULL),'') AS pegawai_ids
            FROM {$this->table} t LEFT JOIN anggota_tim a ON a.id_tim = t.id_tim
            WHERE t.id_tim = :id_tim GROUP BY t.id_tim LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_tim', $id_tim, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $data['pegawai_ids'] = $data['pegawai_ids'] ? explode('||', $data['pegawai_ids']) : [];
        }

        return $data;
    }

    public function insertGetId($data)
    {
        $query = "INSERT INTO {$this->table} (nama_tim) VALUES (:nama_tim) RETURNING id_tim";

        $stmt = $this->db->prepare($query);
        $stmt->execute($data);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id_tim'];
    }

    public function update($id_tim, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $query          = "UPDATE {$this->table} SET " . implode(',', $fields) . " WHERE id_tim = :id_tim";
        $stmt           = $this->db->prepare($query);
        $data['id_tim'] = $id_tim;
        $stmt->execute($data);
    }

    public function delete($id_tim)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_tim = :id_tim");
        $stmt->bindValue(':id_tim', $id_tim);
        $stmt->execute();
    }

    public function getTimByIds(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $query = "SELECT id_tim, nama_tim FROM {$this->table} WHERE id_tim IN ($placeholders) ORDER BY nama_tim";
        $stmt  = $this->db->prepare($query);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
