<?php
require_once __DIR__ . '/../core/Database.php';

class ContactModel {
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS `contacts` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(64) DEFAULT '',
            `email` VARCHAR(255) DEFAULT '',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->pdo->exec($sql);
    }

    public function all()
    {
        $stmt = $this->pdo->query('SELECT * FROM contacts ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM contacts WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO contacts (name, phone, email, created_at, updated_at) VALUES (:name, :phone, :email, :created_at, :updated_at)');
        $now = date('Y-m-d H:i:s');
        $stmt->execute([
            'name' => $data['name'] ?? '',
            'phone' => $data['phone'] ?? '',
            'email' => $data['email'] ?? '',
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
        if (isset($data['phone'])) { $fields[] = 'phone = :phone'; $params['phone'] = $data['phone']; }
        if (isset($data['email'])) { $fields[] = 'email = :email'; $params['email'] = $data['email']; }
        if (empty($fields)) return $this->find($id);

        $sql = 'UPDATE contacts SET ' . implode(', ', $fields) . ', updated_at = :updated_at WHERE id = :id';
        $params['updated_at'] = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->find($id);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM contacts WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
