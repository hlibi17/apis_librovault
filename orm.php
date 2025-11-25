<?php
class Model {
    protected $table;
    protected $primaryKey = "id";
    protected $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=localhost;dbname=librovault;charset=utf8", "root", "");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // ==== GET ALL ====
    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==== COUNT ALL ====
    public function count() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$r['total'];
    }

    // ==== PAGINATE ====
    // $orderBy e.g. "id DESC"
    public function paginate($limit, $offset, $orderBy = null) {
        $order = $orderBy ? "ORDER BY $orderBy" : "";
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} $order LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==== FIND BY ID ====
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==== INSERT ====
    public function create($data) {
        $keys = implode(",", array_keys($data));
        $values = implode(",", array_fill(0, count($data), "?"));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");
        return $stmt->execute(array_values($data));
    }

    // ==== UPDATE ====
    public function update($id, $data) {
        $set = implode("=?, ", array_keys($data)) . "=?";
        $values = array_values($data);
        $values[] = $id;

        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?");
        return $stmt->execute($values);
    }

    // ==== DELETE ====
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
}
