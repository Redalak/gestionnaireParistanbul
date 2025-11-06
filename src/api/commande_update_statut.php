<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);

require_once __DIR__ . '/../repository/CommandeRepository.php';
require_once __DIR__ . '/../model/Commande.php';
require_once __DIR__ . '/../bdd/Bdd.php';

use repository\CommandeRepository;
use model\Commande;

try {
    // Accept both JSON and form-encoded
    $input = $_POST;
    if (empty($input)) {
        $raw = file_get_contents('php://input');
        if ($raw) {
            $asJson = json_decode($raw, true);
            if (is_array($asJson)) $input = $asJson;
        }
    }

    $id  = isset($input['id_commande']) ? (int)$input['id_commande'] : (int)($input['id'] ?? 0);
    $etat = isset($input['etat']) ? trim((string)$input['etat']) : '';

    $allowed = ['en attente','préparée','expédiée','livrée','annulée'];
    if ($id <= 0 || $etat === '' || !in_array($etat, $allowed, true)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Paramètres invalides']);
        exit;
    }

    $repo = new CommandeRepository();
    $cmd = new Commande(['id_commande' => $id, 'etat' => $etat]);
    $ok = $repo->updateCommande($cmd);

    echo json_encode(['ok' => (bool)$ok]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
