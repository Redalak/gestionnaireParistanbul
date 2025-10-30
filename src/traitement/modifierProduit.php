<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepo.php';

$id        = (int)($_POST['id'] ?? 0);
$nom       = trim($_POST['nom'] ?? '');
$categorie = trim($_POST['categorie'] ?? '');
$quantite  = (int)($_POST['quantite'] ?? 0);
$prix      = (float)($_POST['prix_unitaire'] ?? 0);

// même principe : ton vrai host c'est localhost:8888
$modifierBase = 'http://localhost:8888/modifier.php';

if ($id > 0 && $nom && $categorie && $quantite >= 0 && $prix >= 0) {

    $repo = new ProduitRepo();
    $repo->update($id, $nom, $categorie, $quantite, $prix);

    header('Location: ' . $modifierBase . '?id=' . $id . '&success=1');
    exit;

} else {

    header('Location: ' . $modifierBase . '?id=' . $id . '&error=1');
    exit;
}