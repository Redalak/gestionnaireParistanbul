<?php
declare(strict_types=1);

// Debug temporaire si besoin
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../repository/ProduitRepository.php';
use repository\ProduitRepository;

$redirectBase = '../../vue/updateProduit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $id = (int)($_GET['id'] ?? 0);
    header('Location: ' . $redirectBase . '?id=' . $id . '&error=1');
    exit;
}

$id           = (int)($_POST['id'] ?? 0);
$nom          = trim((string)($_POST['nom'] ?? ''));
$refCategorie = (int)($_POST['categorie'] ?? 0);
$nbUnites     = (int)($_POST['quantite'] ?? 0);
$prix         = (float)($_POST['prix_unitaire'] ?? 0);

if ($id <= 0 || $nom === '' || $refCategorie <= 0 || $nbUnites < 0 || $prix < 0) {
    header('Location: ' . $redirectBase . '?id=' . $id . '&error=1');
    exit;
}

$repo = new ProduitRepository();
$ok   = $repo->update($id, $nom, $refCategorie, $nbUnites, $prix);

header('Location: ' . $redirectBase . '?id=' . $id . '&success=' . ($ok ? '1' : '0'));
exit;