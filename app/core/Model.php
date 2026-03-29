<?php
/**
 * Model base - todos os models herdam daqui
 * Fornece métodos CRUD básicos
 */
abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(string $orderBy = 'id ASC'): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findWhere(array $conditions, string $orderBy = 'id ASC'): array
    {
        $where = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$whereStr} ORDER BY {$orderBy}");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findOneWhere(array $conditions): ?array
    {
        $results = $this->findWhere($conditions);
        return $results[0] ?? null;
    }

    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "{$key} = :{$key}";
        }
        $setStr = implode(', ', $set);
        $data['id'] = $id;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$setStr} WHERE {$this->primaryKey} = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count(array $conditions = []): int
    {
        if (empty($conditions)) {
            $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        } else {
            $where = [];
            $params = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = :{$key}";
                $params[$key] = $value;
            }
            $whereStr = implode(' AND ', $where);
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE {$whereStr}");
            $stmt->execute($params);
        }
        return (int) $stmt->fetchColumn();
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
