<?php
require_once __DIR__ . '/../core/Database.php';

class EvacuationCenterModel {
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS `evacuation_centers` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `address` VARCHAR(255) DEFAULT '',
            `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->pdo->exec($sql);
    }

    public function all()
    {
        $stmt = $this->pdo->query('SELECT * FROM evacuation_centers ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM evacuation_centers WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO evacuation_centers (name, address, status, created_at, updated_at) VALUES (:name, :address, :status, :created_at, :updated_at)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute([
            'name' => $data['name'] ?? '',
            'address' => $data['address'] ?? '',
            'status' => in_array(($data['status'] ?? 'active'), ['active','inactive']) ? $data['status'] : 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        return $this->find($this->pdo->lastInsertId());
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];
        if (isset($data['name'])) { $fields[] = 'name = :name'; $params['name'] = $data['name']; }
        if (isset($data['address'])) { $fields[] = 'address = :address'; $params['address'] = $data['address']; }
        if (isset($data['status']) && in_array($data['status'], ['active','inactive'])) { $fields[] = 'status = :status'; $params['status'] = $data['status']; }
        if (empty($fields)) return $this->find($id);

        $sql = 'UPDATE evacuation_centers SET ' . implode(', ', $fields) . ', updated_at = :updated_at WHERE id = :id';
        $params['updated_at'] = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->find($id);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM evacuation_centers WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}