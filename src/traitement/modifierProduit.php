<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepo.php';
require_once __DIR__ . '/../config.php';

$id        = (int)($_POST['id'] ?? 0);
$nom       = trim($_POST['nom'] ?? '');
$categorie = trim($_POST['categorie'] ?? '');
$quantite  = (int)($_POST['quantite'] ?? 0);
$prix      = (float)($_POST['prix_unitaire'] ?? 0);

if ($id > 0 && $nom && $categorie && $quantite >= 0 && $prix >= 0) {
    $repo = new ProduitRepo();
    $repo->update($id, $nom, $categorie, $quantite, $prix);

    // ✅ revient sur la même page d'édition avec bandeau vert
    redirect('/modifier.php', [
        'id'      => $id,
        'success' => 1,
    ]);
} else {
    // ⚠️ erreur de saisie -> bandeau rouge
    redirect('/modifier.php', [
        'id'    => $id,
        'error' => 1,
    ]);
}