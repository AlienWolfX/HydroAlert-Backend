<?php
class UserModel extends Model
{
    /**
     * Find a user by username. Returns associative array with keys `id`, `username`, `password_hash` or false.
     */
    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
}
