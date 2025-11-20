<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $cfg = require __DIR__ . '/../../config/config.php';
        $host = $cfg['db_host'];
        $db = $cfg['db_name'];
        $user = $cfg['db_user'];
        $pass = $cfg['db_pass'];
        $charset = $cfg['db_charset'] ?? 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
            exit;
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
