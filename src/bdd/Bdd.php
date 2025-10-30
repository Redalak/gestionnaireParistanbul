<?php
declare(strict_types=1);
namespace bdd;

final class Bdd {
    private static ?\PDO $pdo = null;
    private static array $cfg;

    private static function config(): array {
        if (isset(self::$cfg)) return self::$cfg;

        $isMac = stripos(PHP_OS, 'Darwin') !== false; // MAMP
        $isWin = stripos(PHP_OS, 'WIN') !== false;    // WAMP

        // Valeurs par défaut (marchent direct sur MAMP/WAMP)
        $defaults = [
            'host'    => '127.0.0.1',
            'port'    => $isMac ? 8889 : 3306,
            'name'    => 'stock_paristanbul',
            'user'    => 'root',
            'pass'    => $isMac ? 'root' : '',
            'socket'  => $isMac ? '/Applications/MAMP/tmp/mysql/mysql.sock' : null,
            'charset' => 'utf8mb4',
        ];

        // 1) Fichier local d’override (non versionné)
        $file = __DIR__ . '/bdd.local.php';
        if (is_file($file)) {
            $arr = require $file;               // doit retourner un array
            if (is_array($arr)) $defaults = array_replace($defaults, $arr);
        }

        // 2) Constantes (si tu préfères)
        foreach (['host','port','name','user','pass','socket'] as $k) {
            $const = 'DB_' . strtoupper($k);
            if (defined($const)) {
                $defaults[$k] = constant($const);
            }
        }

        // 3) Variables d’environnement (DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_SOCKET)
        $envMap = [
            'host'   => ['DB_HOST','MYSQL_HOST'],
            'port'   => ['DB_PORT','MYSQL_PORT'],
            'name'   => ['DB_NAME','MYSQL_DATABASE'],
            'user'   => ['DB_USER','MYSQL_USER'],
            'pass'   => ['DB_PASS','MYSQL_PASSWORD'],
            'socket' => ['DB_SOCKET','MYSQL_SOCKET'],
        ];
        foreach ($envMap as $key => $vars) {
            foreach ($vars as $v) {
                $val = getenv($v);
                if ($val !== false && $val !== '') {
                    $defaults[$key] = ($key === 'port') ? (int)$val : $val;
                    break;
                }
            }
        }

        return self::$cfg = $defaults;
    }

    public static function connect(): \PDO {
        if (self::$pdo instanceof \PDO) return self::$pdo;

        $c = self::config();

        $dsn = ($c['socket'] && is_readable((string)$c['socket']))
            ? "mysql:unix_socket={$c['socket']};dbname={$c['name']};charset={$c['charset']}"
            : "mysql:host={$c['host']};port={$c['port']};dbname={$c['name']};charset={$c['charset']}";

        self::$pdo = new \PDO($dsn, $c['user'], $c['pass'], [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
        return self::$pdo;
    }

    // Optionnel: override à chaud si besoin
    public static function configure(array $override): void {
        self::$cfg = array_replace(self::config(), $override);
        self::$pdo = null;
    }
}

/* --- Expose $bdd + helpers rétro-compat --- */
$bdd = Bdd::connect();

if (!function_exists('bdd'))    { function bdd(): \PDO { return Bdd::connect(); } }
if (!function_exists('getBdd')) { function getBdd(): \PDO { return Bdd::connect(); } }

