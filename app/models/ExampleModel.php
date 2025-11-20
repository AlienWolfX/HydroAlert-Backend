<?php
class ExampleModel extends Model
{
    public function getDatabaseName()
    {
        try {
            $stmt = $this->db->query('SELECT DATABASE() AS db');
            $row = $stmt->fetch();
            return $row['db'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
