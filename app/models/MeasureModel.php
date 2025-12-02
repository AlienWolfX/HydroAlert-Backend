<?php
require_once __DIR__ . '/../core/Database.php';

class MeasureModel {
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS `measures` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT DEFAULT '',
            `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->pdo->exec($sql);
    }

    public function all()
    {
        $stmt = $this->pdo->query('SELECT * FROM measures ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM measures WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO measures (title, description, status, created_at, updated_at) VALUES (:title, :description, :status, :created_at, :updated_at)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute([
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            // ensure status defaults to 'active' when not provided
            'status' => (function($d){ $s = $d['status'] ?? 'active'; return in_array($s, ['active','inactive']) ? $s : 'active'; })($data),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        return $this->find($this->pdo->lastInsertId());
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = ['id' => $id];
        if (isset($data['title'])) { $fields[] = 'title = :title'; $params['title'] = $data['title']; }
        if (isset($data['description'])) { $fields[] = 'description = :description'; $params['description'] = $data['description']; }
        if (isset($data['status']) && in_array($data['status'], ['active','inactive'])) { $fields[] = 'status = :status'; $params['status'] = $data['status']; }
        if (empty($fields)) return $this->find($id);

        $sql = 'UPDATE measures SET ' . implode(', ', $fields) . ', updated_at = :updated_at WHERE id = :id';
        $params['updated_at'] = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->find($id);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM measures WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
