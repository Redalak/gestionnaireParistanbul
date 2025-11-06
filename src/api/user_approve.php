<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../auth/Auth.php';
require_once __DIR__ . '/../repository/UserRepository.php';

use repository\UserRepository;

try {
    \auth\Auth::startSession();
    \auth\Auth::requireAnyRole(['admin']);

    // Accept JSON or form
    $input = $_POST;
    if (empty($input)) {
        $raw = file_get_contents('php://input');
        if ($raw) {
            $asJson = json_decode($raw, true);
            if (is_array($asJson)) $input = $asJson;
        }
    }

    $id = (int)($input['id_user'] ?? 0);
    $action = (string)($input['action'] ?? ''); // 'approve' or 'reject'

    if ($id <= 0 || !in_array($action, ['approve','reject'], true)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Paramètres invalides']);
        exit;
    }

    $repo = new UserRepository();
    $user = $repo->getUserById($id);
    if (!$user) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Utilisateur introuvable']);
        exit;
    }

    if ($action === 'reject') {
        $ok = $repo->delete($id);
        echo json_encode(['ok' => (bool)$ok]);
        exit;
    }

    // approve: set role to requested role stored in poste (fallback magasinier), clear poste
    $requested = (string)($user->getPoste() ?? '');
    if (!in_array($requested, ['magasinier','gestionnaire','admin'], true)) {
        $requested = 'magasinier';
    }

    $ok = $repo->update($id, [
        'role' => $requested,
        'poste' => null,
    ]);

    echo json_encode(['ok' => (bool)$ok, 'role' => $requested]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
