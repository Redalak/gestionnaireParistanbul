<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepository.php';

use repository\ProduitRepository;

$id = (int)($_GET['id'] ?? 0);

// retour vers la liste depuis /src/traitement/
$listeUrl = '../../vue/produit.php';

if ($id > 0) {
    $repo = new ProduitRepository();
    $repo->delete($id);
    header('Location: ' . $listeUrl . '?deleted=1');
    exit;
}

header('Location: ' . $listeUrl . '?deleted=0');
exit;