<?php
declare(strict_types=1);

class Bdd {
    private static ?PDO $pdo = null;

    public static function connect(): PDO {
        if (self::$pdo === null) {

            // ✅ Détection automatique de l’environnement
            $isMac = stripos(PHP_OS, 'Darwin') !== false; // macOS = MAMP
            $isWin = stripos(PHP_OS, 'WIN') !== false;    // Windows = WAMP

            // 🔧 Connexion adaptée
            if ($isMac) {
                $host = 'localhost';
                $dbname = 'stock_paristanbul';
                $user = 'root';
                $pass = 'root'; // MAMP
            } elseif ($isWin) {
                $host = 'localhost';
                $dbname = 'stock_paristanbul';
                $user = 'root';
                $pass = ''; // WAMP
            } else {
                // Par sécurité : autre environnement (Linux, etc.)
                $host = 'localhost';
                $dbname = 'stock_paristanbul';
                $user = 'root';
                $pass = '';
            }

            try {
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die('❌ Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}