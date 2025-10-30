<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepository.php';

$nom       = trim($_POST['nom'] ?? '');
$categorie = trim($_POST['categorie'] ?? '');
$quantite  = (int)($_POST['quantite'] ?? 0);
$prix      = (float)($_POST['prix_unitaire'] ?? 0);

if ($nom && $categorie && $quantite >= 0 && $prix >= 0) {

    $repo = new ProduitRepository();
    $repo->create($nom, $categorie, $quantite, $prix);

    header('Location: ../../ajouter.php?success=1');
    exit;
} else {
    header('Location: ../../ajouter.php?error=1');
    exit;
}