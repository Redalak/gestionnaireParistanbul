<?php
declare(strict_types=1);

namespace auth;

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../model/User.php';

use repository\UserRepository;
use model\User;

class Auth
{
    private const SESSION_KEY = 'user';

    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            // Secure defaults (adjust for your environment if needed)
            ini_set('session.use_strict_mode', '1');
            session_start();
        }
    }

    public static function login(string $email, string $password): bool
    {
        self::startSession();
        $repo = new UserRepository();
        $user = $repo->getUserByEmail($email);
        if (!$user) return false;

        $stored = (string)$user->getMdp();
        $ok = password_verify($password, $stored);

        // Backward compatibility: if stored is plain and equals password, rehash
        if (!$ok && hash_equals($stored, $password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            // Try to update
            $repo->update((int)$user->getIdUser(), ['mdp' => $hash]);
            $ok = true;
            $stored = $hash;
        }

        if (!$ok) return false;

        // Block login if not approved
        $role = (string)$user->getRole();
        if ($role === 'pending') {
            return false;
        }

        // Rehash if algorithm updated
        if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
            $repo->update((int)$user->getIdUser(), ['mdp' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        $_SESSION[self::SESSION_KEY] = [
            'id_user' => (int)$user->getIdUser(),
            'email' => (string)$user->getEmail(),
            'role' => (string)$user->getRole(),
            'nom' => (string)$user->getNom(),
            'prenom' => (string)$user->getPrenom(),
        ];
        return true;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION[self::SESSION_KEY] = null;
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public static function isLoggedIn(): bool
    {
        self::startSession();
        return isset($_SESSION[self::SESSION_KEY]['id_user']);
    }

    public static function currentUser(): ?array
    {
        self::startSession();
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public static function requireRole(array $roles): void
    {
        self::requireAnyRole($roles);
    }

    public static function requireAnyRole(array $roles): void
    {
        self::startSession();
        $u = $_SESSION[self::SESSION_KEY] ?? null;
        if (!$u || empty($u['role']) || !in_array((string)$u['role'], $roles, true)) {
            // redirect to login keeping return URL
            $login = self::resolveLoginPath();
            $ret = rawurlencode(self::currentUrl());
            header('Location: ' . $login . '?ret=' . $ret);
            http_response_code(302);
            exit;
        }
    }

    private static function resolveLoginPath(): string
    {
        // Attempt to compute a path to vue/login.php relative to current script.
        $script = (string)($_SERVER['SCRIPT_NAME'] ?? '/');
        // If app is served at /gestionnaireParistanbul/, use absolute path
        if (strpos($script, '/gestionnaireParistanbul/') !== false) {
            return '/gestionnaireParistanbul/vue/login.php';
        }
        // Fallbacks based on code location
        $callerFile = self::callerFile();
        if (strpos($callerFile, DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR) !== false) {
            return '../../vue/login.php';
        }
        if (strpos($callerFile, DIRECTORY_SEPARATOR . 'vue' . DIRECTORY_SEPARATOR) !== false) {
            return '../login.php';
        }
        // Worst-case: same dir
        return 'login.php';
    }

    private static function callerFile(): string
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        foreach ($bt as $frame) {
            if (!empty($frame['file'])) return (string)$frame['file'];
        }
        return __FILE__;
    }

    private static function currentUrl(): string
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') === '443';
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return $scheme . '://' . $host . $uri;
    }
}
