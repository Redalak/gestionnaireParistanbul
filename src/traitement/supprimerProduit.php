<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepository.php';

$id = (int)($_GET['id'] ?? 0);

// L'URL QUI MARCHE CHEZ TOI EN VRAI :
$listeUrl = 'http://localhost/produits.php';

if ($id > 0) {
    $repo = new ProduitRepository();
    $repo->delete($id);

    header('Location: ' . $listeUrl . '?deleted=1');
    exit;
} else {
    header('Location: ' . $listeUrl . '?deleted=0');
    exit;
}