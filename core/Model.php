<?php

namespace core;

use PDO;
use PDOException;

class Model
{
    protected $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/config.php';
        try {
            $this->db = new PDO(
                "mysql:host={$config['db_host']};dbname={$config['db_name']};charset={$config['db_charset']}",
                $config['db_user'],
                $config['db_pass'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
        }
    }
}
