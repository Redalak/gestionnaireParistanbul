<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepo.php';
require_once __DIR__ . '/../config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $repo = new ProduitRepo();
    $repo->delete($id);

    // ✅ après suppression -> retour liste avec ?deleted=1
    redirect('/produit.php', ['deleted' => 1]);
} else {
    // ⚠️ id pas valide -> deleted=0
    redirect('/produit.php', ['deleted' => 0]);
}