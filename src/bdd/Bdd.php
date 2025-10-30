<?php
declare(strict_types=1);

namespace bdd;

use PDO;
use PDOException;

class Bdd {
    private ?PDO $pdo = null;
    public const DB_NAME = 'stock_paristanbul';

    public function getBdd(): PDO {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // 1) MAMP (TCP 8889)
        try {
            $this->pdo = new PDO(
                'mysql:host=127.0.0.1;port=8889;dbname=' . self::DB_NAME . ';charset=utf8mb4',
                'root',
                'root',
                $options
            );
            return $this->pdo;
        } catch (PDOException $e) {
            // 1bis) MAMP (socket)
            $sock = '/Applications/MAMP/tmp/mysql/mysql.sock';
            if (is_readable($sock)) {
                try {
                    $this->pdo = new PDO(
                        'mysql:unix_socket=' . $sock . ';dbname=' . self::DB_NAME . ';charset=utf8mb4',
                        'root',
                        'root',
                        $options
                    );
                    return $this->pdo;
                } catch (PDOException $e2) {
                    // continue vers WAMP
                }
            }
        }

        // 2) WAMP (TCP 3306)
        $this->pdo = new PDO(
            'mysql:host=127.0.0.1;port=3306;dbname=' . self::DB_NAME . ';charset=utf8mb4',
            'root',
            '',
            $options
        );
        return $this->pdo;
    }
}