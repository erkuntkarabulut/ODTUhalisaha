<?php
namespace core;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // config/config.php dosyanızın içeriğini alıyoruz
        $config = require __DIR__ . '/../config/config.php';
        $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset={$config['db_charset']}";
        try {
            $this->pdo = new \PDO($dsn, $config['db_user'], $config['db_pass']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

